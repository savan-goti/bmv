<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Owner;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\Seller;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     * 
     * @param string $guard The guard type (owner, admin, staff, seller)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle($guard)
    {
        // Validate guard type
        if (!in_array($guard, ['owner', 'admin', 'staff', 'seller'])) {
            abort(404);
        }

        // Set the callback URL dynamically based on guard
        $redirectUri = url("/{$guard}/auth/google/callback");

        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     * 
     * @param string $guard The guard type (owner, admin, staff, seller)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback($guard)
    {
        // Validate guard type
        if (!in_array($guard, ['owner', 'admin', 'staff', 'seller'])) {
            abort(404);
        }

        try {
            // Set the callback URL for Socialite
            $redirectUri = url("/{$guard}/auth/google/callback");
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();
            
            // Get the appropriate model based on guard
            $model = $this->getModelForGuard($guard);
            
            // Check if user exists with this Google ID
            $user = $model::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                // Check if user exists with this email
                $user = $model::where('email', $googleUser->getEmail())->first();
                
                if ($user) {
                    // Link Google account to existing user
                    $user->google_id = $googleUser->getId();
                    $user->save();
                } else {
                    // For security, don't auto-create accounts via Google login
                    // User must be created by admin/owner first
                    Log::warning("Google login attempted for non-existent {$guard}: " . $googleUser->getEmail());
                    return redirect()
                        ->route("{$guard}.login")
                        ->with('error', 'No account found with this email. Please contact your administrator.');
                }
            }

            // Check if user account is active (if status field exists)
            if (isset($user->status) && $user->status !== 'active') {
                Log::warning("Google login attempted for inactive {$guard}: " . $user->email);
                return redirect()
                    ->route("{$guard}.login")
                    ->with('error', 'Your account is not active. Please contact your administrator.');
            }
            
            // Log in the user with the appropriate guard
            Auth::guard($guard)->login($user, true);
            
            // Log the successful login
            Log::info("Successful Google login for {$guard}: " . $user->email);
            
            // Redirect to appropriate dashboard
            return redirect()->route("{$guard}.dashboard");
            
        } catch (\Exception $e) {
            Log::error("Google OAuth Error for {$guard}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()
                ->route("{$guard}.login")
                ->with('error', 'Failed to login with Google. Please try again.');
        }
    }

    /**
     * Get the model class for the given guard
     * 
     * @param string $guard
     * @return string
     */
    private function getModelForGuard($guard)
    {
        $models = [
            'owner' => Owner::class,
            'admin' => Admin::class,
            'staff' => Staff::class,
            'seller' => Seller::class,
        ];

        return $models[$guard];
    }
}
