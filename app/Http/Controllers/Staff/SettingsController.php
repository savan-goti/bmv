<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffEmailVerificationMail;
use Exception;

class SettingsController extends Controller
{
    use ResponseTrait;

    /**
     * Show the staff settings page.
     */
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        
        return view('staff.settings.index', compact('staff'));
    }

    /**
     * Update the staff settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $staff = Auth::guard('staff')->user();

            $validator = Validator::make($request->all(), [
                'email_verified' => 'nullable|boolean',
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

            $staff->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $staff);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Send email verification link to staff.
     */
    public function sendVerificationEmail()
    {
        try {
            $staff = Auth::guard('staff')->user();

            // Check if email is already verified
            if ($staff->email_verified_at) {
                return $this->sendError('Email is already verified.');
            }

            // Generate a signed URL that expires in 60 minutes
            $verificationUrl = URL::temporarySignedRoute(
                'staff.email.verify',
                now()->addMinutes(60),
                ['id' => $staff->id, 'hash' => sha1($staff->email)]
            );

            // Send the verification email
            Mail::to($staff->email)->send(new StaffEmailVerificationMail($staff, $verificationUrl));

            return $this->sendSuccess('Verification email sent successfully. Please check your inbox.');
        } catch (Exception $e) {
            return $this->sendError('Failed to send verification email: ' . $e->getMessage());
        }
    }

    /**
     * Verify staff email address.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        try {
            // Verify the signature
            if (!$request->hasValidSignature()) {
                return redirect()->route('staff.settings')
                    ->with('error', 'Invalid or expired verification link.');
            }

            // Find the staff
            $staff = \App\Models\Staff::findOrFail($id);

            // Verify the hash matches
            if (!hash_equals($hash, sha1($staff->email))) {
                return redirect()->route('staff.settings')
                    ->with('error', 'Invalid verification link.');
            }

            // Check if already verified
            if ($staff->email_verified_at) {
                return redirect()->route('staff.settings')
                    ->with('info', 'Email is already verified.');
            }

            // Mark email as verified
            $staff->update([
                'email_verified_at' => now(),
            ]);

            return redirect()->route('staff.settings')
                ->with('success', 'Email verified successfully!');
        } catch (Exception $e) {
            return redirect()->route('staff.settings')
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Enable two-factor authentication and generate QR code.
     */
    public function enableTwoFactor()
    {
        try {
            $staff = Auth::guard('staff')->user();
            
            // Generate a new secret key
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = $google2fa->generateSecretKey();
            
            // Store the secret temporarily (not confirmed yet)
            $staff->update([
                'two_factor_secret' => encrypt($secret),
                'two_factor_enabled' => false, // Not enabled until confirmed
                'two_factor_confirmed_at' => null,
            ]);
            
            // Generate QR code URL
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $staff->email,
                $secret
            );
            
            // Generate QR code inline using the QRCode package
            $qrcode = new \PragmaRX\Google2FAQRCode\Google2FA();
            $qrCodeInline = $qrcode->getQRCodeInline(
                config('app.name'),
                $staff->email,
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

            $staff = Auth::guard('staff')->user();
            
            if (!$staff->two_factor_secret) {
                return $this->sendError('Two-factor authentication is not set up.');
            }

            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($staff->two_factor_secret);
            
            $valid = $google2fa->verifyKey($secret, $request->code);
            
            if (!$valid) {
                return $this->sendError('Invalid verification code. Please try again.');
            }

            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();
            
            // Enable 2FA
            $staff->update([
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

            $staff = Auth::guard('staff')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $staff->password)) {
                return $this->sendError('Invalid password.');
            }

            // Disable 2FA
            $staff->update([
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
            $staff = Auth::guard('staff')->user();
            
            if (!$staff->two_factor_enabled) {
                return $this->sendError('Two-factor authentication is not enabled.');
            }

            $recoveryCodes = $this->generateRecoveryCodes();
            
            $staff->update([
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
     * Get all active sessions for the staff.
     */
    public function getSessions()
    {
        try {
            $staff = Auth::guard('staff')->user();
            
            $sessions = Session::forUser($staff->id, 'staff')
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

            $staff = Auth::guard('staff')->user();
            $sessionId = $request->session_id;

            // Check if trying to logout current session
            if ($sessionId === session()->getId()) {
                return $this->sendError('Cannot logout from current session. Use logout button instead.');
            }

            // Verify the session belongs to this admin
            $session = Session::forUser($staff->id, 'staff')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return $this->sendError('Session not found or does not belong to you.');
            }

            // Delete the session
            Session::logoutSession($staff->id, 'staff', $sessionId);

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
            $staff = Auth::guard('staff')->user();
            $currentSessionId = session()->getId();

            // Delete all sessions except current
            Session::logoutOtherSessions($staff->id, 'staff', $currentSessionId);

            return $this->sendSuccess('All other sessions logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Permanently delete the staff account.
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

            $staff = Auth::guard('staff')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $staff->password)) {
                return $this->sendError('Invalid password.');
            }

            DB::beginTransaction();

            // Delete all sessions for this staff
            Session::where('user_id', $staff->id)
                ->where('user_type', 'staff')
                ->delete();
            
            // Logout the staff
            Auth::guard('staff')->logout();
            
            // Delete the staff account
            $staff->delete();

            DB::commit();

            return $this->sendSuccess('Your account has been permanently deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}

