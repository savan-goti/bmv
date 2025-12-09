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
            'login_verification_code' => 'nullable|string|max:10',
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

        // Check user's preferred authentication method
        $authMethod = $admin->login_auth_method ?? 'email_verification';

        // Apply Email Verification if selected and email is verified
        if ($authMethod === 'email_verification' && $admin->email_verified_at) {
            // If no verification code provided, generate and send one
            if (!$request->filled('login_verification_code')) {
                // Generate a 6-digit verification code
                $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                
                // Store the code and expiration time (10 minutes)
                $admin->update([
                    'login_verification_code' => $verificationCode,
                    'login_verification_code_expires_at' => now()->addMinutes(10),
                ]);

                // Send the verification code via email
                try {
                    \Mail::to($admin->email)->send(new \App\Mail\AdminLoginVerificationMail($admin, $verificationCode));
                } catch (\Exception $e) {
                    return $this->sendError('Failed to send verification code. Please try again.');
                }

                return $this->sendResponse('Verification code sent to your email', [
                    'requires_login_verification' => true,
                    'auth_method' => 'email_verification',
                ], 200);
            }

            // Verify the login verification code
            if ($admin->login_verification_code !== $request->login_verification_code) {
                return $this->sendError('Invalid verification code');
            }

            // Check if the code has expired
            if ($admin->login_verification_code_expires_at < now()) {
                return $this->sendError('Verification code has expired. Please request a new one.');
            }

            // Clear the verification code after successful verification
            $admin->update([
                'login_verification_code' => null,
                'login_verification_code_expires_at' => null,
            ]);
        }

        // Check if 2FA is enabled and selected as auth method
        if (
            $authMethod === 'two_factor' &&
            (int) $admin->two_factor_enabled === 1 &&     // explicitly enabled
            !empty($admin->two_factor_secret) &&          // secret exists
            !is_null($admin->two_factor_confirmed_at)     // confirmed
        ) {
            // 2FA is enabled, verify the code
            if (!$request->filled('two_factor_code')) {
                return $this->sendResponse('Two-factor authentication required', [
                    'requires_2fa' => true,
                    'auth_method' => 'two_factor',
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
