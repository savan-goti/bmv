<?php

namespace App\Http\Controllers\Staff;

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
        return view('staff.auth.login', compact('setting'));
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

        // Find the staff by email
        $staff = \App\Models\Staff::where('email', $request->email)->first();

        if (!$staff) {
            return $this->sendError('Invalid email or password');
        }

        // Check if password is correct
        if (!\Hash::check($request->password, $staff->password)) {
            return $this->sendError('Invalid email or password');
        }

        // Check if both authentication methods are available
        $hasEmailVerification = $staff->email_verified_at !== null;
        $has2FA = (int) $staff->two_factor_enabled === 1 && 
                  !empty($staff->two_factor_secret) && 
                  !is_null($staff->two_factor_confirmed_at);
        $hasBothMethods = $hasEmailVerification && $has2FA;

        // Determine which verification is required
        $requiresVerification = $hasEmailVerification || $has2FA;

        if ($requiresVerification) {
            // If no verification code provided, send the appropriate one based on user's preference
            if (!$request->filled('login_verification_code') && !$request->filled('two_factor_code')) {
                $authMethod = $staff->login_auth_method ?? 'email_verification';
                
                // If user prefers email verification and it's available
                if ($authMethod === 'email_verification' && $hasEmailVerification) {
                    // Generate a 6-digit verification code
                    $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    
                    // Store the code and expiration time (10 minutes)
                    $staff->update([
                        'login_verification_code' => $verificationCode,
                        'login_verification_code_expires_at' => now()->addMinutes(10),
                    ]);

                    // Send the verification code via email
                    try {
                        \Mail::to($staff->email)->send(new \App\Mail\StaffLoginVerificationMail($staff, $verificationCode));
                    } catch (\Exception $e) {
                        return $this->sendError('Failed to send verification code. Please try again.');
                    }

                    return $this->sendResponse('Verification code sent to your email', [
                        'requires_login_verification' => true,
                        'auth_method' => 'email_verification',
                        'has_both_methods' => $hasBothMethods,
                    ], 200);
                }
                // If user prefers 2FA or email verification is not available
                elseif ($has2FA) {
                    return $this->sendResponse('Two-factor authentication required', [
                        'requires_2fa' => true,
                        'auth_method' => 'two_factor',
                        'has_both_methods' => $hasBothMethods,
                    ], 200);
                }
            }

            // Verify the provided code (email verification or 2FA)
            $verified = false;

            // Check email verification code if provided
            if ($request->filled('login_verification_code') && $hasEmailVerification) {
                if ($staff->login_verification_code === $request->login_verification_code) {
                    // Check if the code has expired
                    if ($staff->login_verification_code_expires_at >= now()) {
                        $verified = true;
                        // Clear the verification code after successful verification
                        $staff->update([
                            'login_verification_code' => null,
                            'login_verification_code_expires_at' => null,
                        ]);
                    } else {
                        return $this->sendError('Verification code has expired. Please request a new one.');
                    }
                } else {
                    return $this->sendError('Invalid verification code');
                }
            }

            // Check 2FA code if provided and not yet verified
            if (!$verified && $request->filled('two_factor_code') && $has2FA) {
                // Verify the 2FA code
                $google2fa = new \PragmaRX\Google2FA\Google2FA();
                $secret = decrypt($staff->two_factor_secret);

                $valid = $google2fa->verifyKey($secret, $request->two_factor_code);

                // If code is invalid, check recovery codes
                if (!$valid) {
                    $valid = $this->verifyRecoveryCode($staff, $request->two_factor_code);
                }

                if ($valid) {
                    $verified = true;
                } else {
                    return $this->sendError('Invalid two-factor authentication code');
                }
            }

            // If verification is required but not verified, return error
            if (!$verified) {
                return $this->sendError('Verification required. Please provide a valid code.');
            }
        }

        // Attempt login
        if (Auth::guard('staff')->loginUsingId($staff->id, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login info
            $staff->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Set guard in session
            Session::setGuard($request->session()->getId(), 'staff');

            return $this->sendSuccess('Login successful', 201);
        }

        return $this->sendError('Invalid email or password');
    }

    /**
     * Verify recovery code and mark it as used
     */
    private function verifyRecoveryCode($staff, $code)
    {
        if (!$staff->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = json_decode(decrypt($staff->two_factor_recovery_codes), true);
        
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
            
            // Update the staff's recovery codes
            $staff->update([
                'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes))),
            ]);
            
            return true;
        }
        
        return false;
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}

