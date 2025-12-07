<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerEmailVerificationMail;
use Exception;

class SettingsController extends Controller
{
    use ResponseTrait;

    /**
     * Show the seller settings page.
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        
        return view('seller.settings.index', compact('seller'));
    }

    /**
     * Update the seller settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $seller = Auth::guard('seller')->user();

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

            $seller->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $seller);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Send email verification link to seller.
     */
    public function sendVerificationEmail()
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Check if email is already verified
            if ($seller->email_verified_at) {
                return $this->sendError('Email is already verified.');
            }

            // Generate a signed URL that expires in 60 minutes
            $verificationUrl = URL::temporarySignedRoute(
                'seller.email.verify',
                now()->addMinutes(60),
                ['id' => $seller->id, 'hash' => sha1($seller->email)]
            );

            // Send the verification email
            Mail::to($seller->email)->send(new SellerEmailVerificationMail($seller, $verificationUrl));

            return $this->sendSuccess('Verification email sent successfully. Please check your inbox.');
        } catch (Exception $e) {
            return $this->sendError('Failed to send verification email: ' . $e->getMessage());
        }
    }

    /**
     * Verify seller email address.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        try {
            // Verify the signature
            if (!$request->hasValidSignature()) {
                return redirect()->route('seller.settings')
                    ->with('error', 'Invalid or expired verification link.');
            }

            // Find the seller
            $seller = \App\Models\Seller::findOrFail($id);

            // Verify the hash matches
            if (!hash_equals($hash, sha1($seller->email))) {
                return redirect()->route('seller.settings')
                    ->with('error', 'Invalid verification link.');
            }

            // Check if already verified
            if ($seller->email_verified_at) {
                return redirect()->route('seller.settings')
                    ->with('info', 'Email is already verified.');
            }

            // Mark email as verified
            $seller->update([
                'email_verified_at' => now(),
            ]);

            return redirect()->route('seller.settings')
                ->with('success', 'Email verified successfully!');
        } catch (Exception $e) {
            return redirect()->route('seller.settings')
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Enable two-factor authentication and generate QR code.
     */
    public function enableTwoFactor()
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            // Generate a new secret key
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = $google2fa->generateSecretKey();
            
            // Store the secret temporarily (not confirmed yet)
            $seller->update([
                'two_factor_secret' => encrypt($secret),
                'two_factor_enabled' => false, // Not enabled until confirmed
                'two_factor_confirmed_at' => null,
            ]);
            
            // Generate QR code URL
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name'),
                $seller->email,
                $secret
            );
            
            // Generate QR code inline using the QRCode package
            $qrcode = new \PragmaRX\Google2FAQRCode\Google2FA();
            $qrCodeInline = $qrcode->getQRCodeInline(
                config('app.name'),
                $seller->email,
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

            $seller = Auth::guard('seller')->user();
            
            if (!$seller->two_factor_secret) {
                return $this->sendError('Two-factor authentication is not set up.');
            }

            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = decrypt($seller->two_factor_secret);
            
            $valid = $google2fa->verifyKey($secret, $request->code);
            
            if (!$valid) {
                return $this->sendError('Invalid verification code. Please try again.');
            }

            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();
            
            // Enable 2FA
            $seller->update([
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

            $seller = Auth::guard('seller')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $seller->password)) {
                return $this->sendError('Invalid password.');
            }

            // Disable 2FA
            $seller->update([
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
            $seller = Auth::guard('seller')->user();
            
            if (!$seller->two_factor_enabled) {
                return $this->sendError('Two-factor authentication is not enabled.');
            }

            $recoveryCodes = $this->generateRecoveryCodes();
            
            $seller->update([
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
     * Get all active sessions for the seller.
     */
    public function getSessions()
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            $sessions = Session::forUser($seller->id, 'seller')
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

            $seller = Auth::guard('seller')->user();
            $sessionId = $request->session_id;

            // Check if trying to logout current session
            if ($sessionId === session()->getId()) {
                return $this->sendError('Cannot logout from current session. Use logout button instead.');
            }

            // Verify the session belongs to this seller
            $session = Session::forUser($seller->id, 'seller')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return $this->sendError('Session not found or does not belong to you.');
            }

            // Delete the session
            Session::logoutSession($seller->id, 'seller', $sessionId);

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
            $seller = Auth::guard('seller')->user();
            $currentSessionId = session()->getId();

            // Delete all sessions except current
            Session::logoutOtherSessions($seller->id, 'seller', $currentSessionId);

            return $this->sendSuccess('All other sessions logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Permanently delete the seller account.
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

            $seller = Auth::guard('seller')->user();
            
            // Verify password
            if (!\Hash::check($request->password, $seller->password)) {
                return $this->sendError('Invalid password.');
            }

            DB::beginTransaction();

            // Delete all sessions for this seller
            Session::where('user_id', $seller->id)
                ->where('user_type', 'seller')
                ->delete();
            
            // Logout the seller
            Auth::guard('seller')->logout();
            
            // Delete the seller account
            $seller->delete();

            DB::commit();

            return $this->sendSuccess('Your account has been permanently deleted.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
