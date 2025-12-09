<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminEmailVerificationMail;
use Exception;

class SettingsController extends Controller
{
    use ResponseTrait;

    /**
     * Show the admin settings page.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Update the admin settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $admin = Auth::guard('admin')->user();

            $validator = Validator::make($request->all(), [
                'email_verified' => 'nullable|boolean',
                'login_auth_method' => 'nullable|in:email_verification,two_factor',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $saveData = [];

            // Handle email verification
            if ($request->has('email_verified')) {
                if ($request->email_verified) {
                    $saveData['email_verified_at'] = now();
                } else {
                    $saveData['email_verified_at'] = null;
                }
            }

            // Handle login authentication method preference
            if ($request->has('login_auth_method')) {
                $saveData['login_auth_method'] = $request->login_auth_method;
            }

            $admin->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $admin);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Send email verification link to admin.
     */
    public function sendVerificationEmail()
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Check if email is already verified
            if ($admin->email_verified_at) {
                return $this->sendError('Email is already verified.');
            }

            // Generate a signed URL that expires in 60 minutes
            $verificationUrl = URL::temporarySignedRoute(
                'admin.email.verify',
                now()->addMinutes(60),
                ['id' => $admin->id, 'hash' => sha1($admin->email)]
            );

            // Send the verification email
            Mail::to($admin->email)->send(new AdminEmailVerificationMail($admin, $verificationUrl));

            return $this->sendSuccess('Verification email sent successfully. Please check your inbox.');
        } catch (Exception $e) {
            return $this->sendError('Failed to send verification email: ' . $e->getMessage());
        }
    }

    /**
     * Verify admin email address.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        try {
            // Verify the signature
            if (!$request->hasValidSignature()) {
                return redirect()->route('admin.settings')
                    ->with('error', 'Invalid or expired verification link.');
            }

            // Find the admin
            $admin = \App\Models\Admin::findOrFail($id);

            // Verify the hash matches
            if (!hash_equals($hash, sha1($admin->email))) {
                return redirect()->route('admin.settings')
                    ->with('error', 'Invalid verification link.');
            }

            // Check if already verified
            if ($admin->email_verified_at) {
                return redirect()->route('admin.settings')
                    ->with('info', 'Email is already verified.');
            }

            // Mark email as verified
            $admin->update([
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.settings')
                ->with('success', 'Email verified successfully!');
        } catch (Exception $e) {
            return redirect()->route('admin.settings')
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Enable two-factor authentication and generate QR code.
     */
    public function enableTwoFactor()
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            // Generate a new secret key
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = $google2fa->generateSecretKey();
            
            // Store the secret temporarily (not confirmed yet)
            $admin->update([
                'two_factor_secret' => encrypt($secret),
                'two_factor_enabled' => false, // Not enabled until confirmed
                'two_factor_confirmed_at' => null,
            ]);
            
            // Generate QR code URL
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $admin->email,
                $secret
            );
            
            // Generate QR code inline using the QRCode package
            $qrcode = new \PragmaRX\Google2FAQRCode\Google2FA();
            $qrCodeInline = $qrcode->getQRCodeInline(
                config('app.name'),
                $admin->email,
                $secret,
                200
            );
            
            return $this->sendResponse('Two-factor authentication setup initiated', [
                'secret' => $secret,
                'qr_code' => base64_encode($qrCodeInline),
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Confirm two-factor authentication with OTP verification.
     */
    public function confirmTwoFactor(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|size:6',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $admin = Auth::guard('admin')->user();
            
            if (!$admin->two_factor_secret) {
                return $this->sendError('Two-factor authentication is not set up.');
            }

            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($admin->two_factor_secret);
            
            $valid = $google2fa->verifyKey($secret, $request->code);
            
            if (!$valid) {
                return $this->sendError('Invalid verification code. Please try again.');
            }

            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();
            
            // Enable 2FA
            $admin->update([
                'two_factor_enabled' => true,
                'two_factor_confirmed_at' => now(),
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);

            return $this->sendResponse('Two-factor authentication enabled successfully', [
                'recovery_codes' => $recoveryCodes,
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactor(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $admin = Auth::guard('admin')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $admin->password)) {
                return $this->sendError('Invalid password.');
            }

            // Disable 2FA
            $admin->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]);

            return $this->sendSuccess('Two-factor authentication disabled successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Regenerate recovery codes.
     */
    public function regenerateRecoveryCodes()
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin->two_factor_enabled) {
                return $this->sendError('Two-factor authentication is not enabled.');
            }

            $recoveryCodes = $this->generateRecoveryCodes();
            
            $admin->update([
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);

            return $this->sendResponse('Recovery codes regenerated successfully', [
                'recovery_codes' => $recoveryCodes,
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Generate recovery codes.
     */
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(6))), 0, 10));
        }
        return $codes;
    }

    /**
     * Get all active sessions for the admin.
     */
    public function getSessions()
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            $sessions = Session::forUser($admin->id, 'admin')
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => $session->last_activity,
                        'is_current' => $session->isCurrentSession(),
                    ];
                });

            return $this->sendResponse('Sessions retrieved successfully', $sessions);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Logout from a specific session.
     */
    public function logoutSession(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $admin = Auth::guard('admin')->user();
            $sessionId = $request->session_id;

            // Check if trying to logout current session
            if ($sessionId === session()->getId()) {
                return $this->sendError('Cannot logout from current session. Use logout button instead.');
            }

            // Verify the session belongs to this admin
            $session = Session::forUser($admin->id, 'admin')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return $this->sendError('Session not found or does not belong to you.');
            }

            // Delete the session
            Session::logoutSession($admin->id, 'admin', $sessionId);

            return $this->sendSuccess('Session logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Logout from all other sessions except current.
     */
    public function logoutOtherSessions()
    {
        try {
            $admin = Auth::guard('admin')->user();
            $currentSessionId = session()->getId();

            // Delete all sessions except current
            Session::logoutOtherSessions($admin->id, 'admin', $currentSessionId);

            return $this->sendSuccess('All other sessions logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Permanently delete the admin account.
     */
    public function deleteAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
                'confirmation' => 'required|string|in:DELETE',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $admin = Auth::guard('admin')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $admin->password)) {
                return $this->sendError('Invalid password.');
            }

            DB::beginTransaction();

            // Delete all sessions for this admin
            Session::where('user_id', $admin->id)
                ->where('user_type', 'admin')
                ->delete();

            // You may want to handle related data here
            // For example, reassign or delete staff and sellers created by this admin
            // This depends on your business logic
            
            // Logout the admin
            Auth::guard('admin')->logout();
            
            // Delete the admin account
            $admin->delete();

            DB::commit();

            return $this->sendSuccess('Your account has been permanently deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
