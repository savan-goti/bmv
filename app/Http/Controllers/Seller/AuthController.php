<?php

namespace App\Http\Controllers\Seller;

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
        return view('seller.auth.login', compact('setting'));
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'two_factor_code' => 'nullable|string|size:6',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Find the seller by email
        $seller = \App\Models\Seller::where('email', $request->email)->first();

        if (!$seller) {
            return $this->sendError('Invalid email or password');
        }

        // Check if password is correct
        if (!\Hash::check($request->password, $seller->password)) {
            return $this->sendError('Invalid email or password');
        }

        // Check if 2FA is enabled
        if ($seller->two_factor_enabled && $seller->two_factor_confirmed_at) {
            // 2FA is enabled, verify the code
            if (!$request->has('two_factor_code')) {
                return $this->sendResponse('Two-factor authentication required', [
                    'requires_2fa' => true,
                ], 200);
            }

            // Verify the 2FA code
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($seller->two_factor_secret);
            
            $valid = $google2fa->verifyKey($secret, $request->two_factor_code);
            
            // If code is invalid, check recovery codes
            if (!$valid) {
                $valid = $this->verifyRecoveryCode($seller, $request->two_factor_code);
            }

            if (!$valid) {
                return $this->sendError('Invalid two-factor authentication code');
            }
        }

        // Attempt login
        if (Auth::guard('seller')->loginUsingId($seller->id, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login info
            $seller->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Set guard in session
            Session::setGuard($request->session()->getId(), 'seller');

            return $this->sendSuccess('Login successful', 201);
        }

        return $this->sendError('Invalid email or password');
    }

    /**
     * Verify recovery code and mark it as used
     */
    private function verifyRecoveryCode($seller, $code)
    {
        if (!$seller->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = json_decode(decrypt($seller->two_factor_recovery_codes), true);
        
        $key = array_search(strtoupper($code), $recoveryCodes);
        
        if ($key !== false) {
            // Remove the used recovery code
            unset($recoveryCodes[$key]);
            
            // Update the seller's recovery codes
            $seller->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ]);
            
            return true;
        }
        
        return false;
    }

    public function logout(Request $request)
    {
        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('seller.login');
    }
}
