<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
use App\Models\Session;

class AuthController extends Controller
{
    use ResponseTrait;
    
    public function login()
    {
        $setting = Setting::first();
        return view('admin.auth.login', compact('setting'));
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'two_factor_code' => 'nullable|string|min:6|max:10',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Find the admin by email
        $admin = \App\Models\Admin::where('email', $request->email)->first();

        if (!$admin) {
            return $this->sendError('Invalid email or password');
        }

        // Check if password is correct
        if (!\Hash::check($request->password, $admin->password)) {
            return $this->sendError('Invalid email or password');
        }

        // Check if 2FA is enabled
        if (
            (int) $admin->two_factor_enabled === 1 &&     // explicitly enabled
            !empty($admin->two_factor_secret) &&          // secret exists
            !is_null($admin->two_factor_confirmed_at)     // confirmed
        ) {
            // 2FA is enabled, verify the code
            if (!$request->filled('two_factor_code')) {
                return $this->sendResponse('Two-factor authentication required', [
                    'requires_2fa' => true,
                ], 200);
            }

            // Verify the 2FA code
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($admin->two_factor_secret);

            $valid = $google2fa->verifyKey($secret, $request->two_factor_code);

            // If code is invalid, check recovery codes
            if (!$valid) {
                $valid = $this->verifyRecoveryCode($admin, $request->two_factor_code);
            }

            if (!$valid) {
                return $this->sendError('Invalid two-factor authentication code');
            }
        }

        // Attempt login
        if (Auth::guard('admin')->loginUsingId($admin->id, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login info
            $admin->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Set guard in session
            Session::setGuard($request->session()->getId(), 'admin');

            return $this->sendSuccess('Login successful', 201);
        }

        return $this->sendError('Invalid email or password');
    }

    /**
     * Verify recovery code and mark it as used
     */
    private function verifyRecoveryCode($admin, $code)
    {
        if (!$admin->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = json_decode(decrypt($admin->two_factor_recovery_codes), true);
        
        if (!is_array($recoveryCodes)) {
            return false;
        }
        
        // Convert input code to uppercase for comparison
        $inputCode = strtoupper(trim($code));
        
        // Find the recovery code (case-insensitive)
        $key = array_search($inputCode, array_map('strtoupper', $recoveryCodes));
        
        if ($key !== false) {
            // Remove the used recovery code
            unset($recoveryCodes[$key]);
            
            // Update the admin's recovery codes
            $admin->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ]);
            
            return true;
        }
        
        return false;
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
