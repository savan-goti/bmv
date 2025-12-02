<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Owner;
use App\Models\OwnerPasswordResetToken;
use App\Models\Setting;
use App\Mail\OwnerPasswordResetMail;
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
        return view('owner.auth.forgot-password', compact('setting'));
    }

    /**
     * Send password reset link to owner email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:owners,email'
        ], [
            'email.exists' => 'We could not find an owner account with that email address.'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $owner = Owner::where('email', $request->email)->first();

        // Delete any existing tokens for this email
        OwnerPasswordResetToken::where('email', $request->email)->delete();

        // Create new token
        $token = Str::random(64);

        OwnerPasswordResetToken::create([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new OwnerPasswordResetMail($token, $owner));
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
        $email = $this->getOwnerEmailFromToken($token);
        return view('owner.auth.reset-password', compact('token', 'setting', 'email'));
    }

    /**
     * Reset the owner password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:owners,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Check if token exists and is valid
        $resetRecord = OwnerPasswordResetToken::where('email', $request->email)->first();

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
        $owner = Owner::where('email', $request->email)->first();
        $owner->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the token
        $resetRecord->delete();

        return $this->sendSuccess('Your password has been reset successfully. You can now login with your new password.', 200);
    }

    /**
     * Get owner email from token.
     *
     * @param string $token
     * @return string|null
     */
    private function getOwnerEmailFromToken($token)
    {
        $resetTokens = OwnerPasswordResetToken::all();

        foreach ($resetTokens as $resetToken) {
            if (Hash::check($token, $resetToken->token)) {
                return $resetToken->email;
            }
        }

        return null;
    }
}
