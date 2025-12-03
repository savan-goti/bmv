<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Seller;
use App\Models\SellerPasswordResetToken;
use App\Models\Setting;
use App\Mail\SellerPasswordResetMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    use ResponseTrait;

    /**
     * Display the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        $setting = Setting::first();
        return view('seller.auth.forgot-password', compact('setting'));
    }

    /**
     * Send password reset link to seller email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sellers,email'
        ], [
            'email.exists' => 'We could not find a seller account with that email address.'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $seller = Seller::where('email', $request->email)->first();

        // Delete any existing tokens for this email
        SellerPasswordResetToken::where('email', $request->email)->delete();

        // Create new token
        $token = Str::random(64);

        SellerPasswordResetToken::create([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new SellerPasswordResetMail($token, $seller));
            return $this->sendSuccess('Password reset link has been sent to your email address.', 200);
        } catch (\Exception $e) {
            return $this->sendError('Failed to send email. Please try again later.');
        }
    }

    /**
     * Display the reset password form.
     */
    public function showResetPasswordForm($token)
    {
        $setting = Setting::first();
        $email = $this->getSellerEmailFromToken($token);
        return view('seller.auth.reset-password', compact('token', 'setting', 'email'));
    }

    /**
     * Reset the seller password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:sellers,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Check if token exists and is valid
        $resetRecord = SellerPasswordResetToken::where('email', $request->email)->first();

        if (!$resetRecord) {
            return $this->sendError('Invalid password reset token.');
        }

        // Check if token matches
        if (!Hash::check($request->token, $resetRecord->token)) {
            return $this->sendError('Invalid password reset token.');
        }

        // Check if token is expired (60 minutes)
        if ($resetRecord->isExpired()) {
            $resetRecord->delete();
            return $this->sendError('Password reset token has expired. Please request a new one.');
        }

        // Update password
        $seller = Seller::where('email', $request->email)->first();
        $seller->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the token
        $resetRecord->delete();

        return $this->sendSuccess('Your password has been reset successfully. You can now login with your new password.', 200);
    }

    /**
     * Get seller email from token.
     *
     * @param string $token
     * @return string|null
     */
    private function getSellerEmailFromToken($token)
    {
        $resetTokens = SellerPasswordResetToken::all();

        foreach ($resetTokens as $resetToken) {
            if (Hash::check($token, $resetToken->token)) {
                return $resetToken->email;
            }
        }

        return null;
    }
}
