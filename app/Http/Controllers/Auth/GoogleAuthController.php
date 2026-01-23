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
     * @param string $action The action type (login or register)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle($guard, $action = 'login')
    {
        // Validate guard type
        if (!in_array($guard, ['owner', 'admin', 'staff', 'seller'])) {
            abort(404);
        }

        // Validate action type
        if (!in_array($action, ['login', 'register'])) {
            $action = 'login';
        }

        // Store the action in session to use in callback
        session(['google_auth_action' => $action]);

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
            // Get the action from session (default to login)
            $action = session('google_auth_action', 'login');
            session()->forget('google_auth_action');

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
                    $user->google_token = $googleUser->token;
                    $user->google_refresh_token = $googleUser->refreshToken;
                    $user->save();
                } else {
                    // Handle registration flow
                    if ($action === 'register' && $guard === 'seller') {
                        // Create new seller account via Google
                        $user = $this->createSellerFromGoogle($googleUser);
                        
                        if (!$user) {
                            return redirect()
                                ->route("{$guard}.register")
                                ->with('error', 'Failed to create account. Please try again.');
                        }
                        
                        // Redirect to login with success message
                        return redirect()
                            ->route("{$guard}.login")
                            ->with('success', 'Registration successful! Your account is pending approval. You will be notified once approved.');
                    } else {
                        // For login or other guards, don't auto-create accounts
                        Log::warning("Google login attempted for non-existent {$guard}: " . $googleUser->getEmail());
                        return redirect()
                            ->route("{$guard}.login")
                            ->with('error', 'No account found with this email. Please contact your administrator.');
                    }
                }
            }

            // Check if user account is active (if status field exists)
            if (isset($user->status) && $user->status !== 'active') {
                // For sellers, check if pending approval
                if ($guard === 'seller' && $user->status === 'pending') {
                    Log::warning("Google login attempted for pending {$guard}: " . $user->email);
                    return redirect()
                        ->route("{$guard}.login")
                        ->with('error', 'Your account is pending approval. Please wait for administrator approval.');
                }
                
                Log::warning("Google login attempted for inactive {$guard}: " . $user->email);
                return redirect()
                    ->route("{$guard}.login")
                    ->with('error', 'Your account is not active. Please contact your administrator.');
            }
            
            // Log in the user with the appropriate guard
            Auth::guard($guard)->login($user, true);
            
            // Update last login info
            if (method_exists($user, 'update')) {
                $user->update([
                    'last_login_at' => now(),
                    'last_login_ip' => request()->ip(),
                ]);
            }
            
            // Log the successful login
            Log::info("Successful Google login for {$guard}: " . $user->email);
            
            // Redirect to appropriate dashboard
            return redirect()->route("{$guard}.dashboard");
            
        } catch (\Exception $e) {
            Log::error("Google OAuth Error for {$guard}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            $route = $action === 'register' ? "{$guard}.register" : "{$guard}.login";
            
            return redirect()
                ->route($route)
                ->with('error', 'Failed to authenticate with Google. Please try again.');
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

    /**
     * Create a new seller from Google OAuth data
     * 
     * @param \Laravel\Socialite\Contracts\User $googleUser
     * @return \App\Models\Seller|null
     */
    private function createSellerFromGoogle($googleUser)
    {
        try {
            // Extract name from Google user
            $fullName = $googleUser->getName();
            $email = $googleUser->getEmail();
            
            // Generate business name from email or name
            $businessName = $fullName ?: explode('@', $email)[0];
            
            // Generate unique username from business name
            $username = \Str::slug($businessName);
            $originalUsername = $username;
            $counter = 1;
            
            while (Seller::where('username', $username)->exists()) {
                $username = $originalUsername . '-' . $counter;
                $counter++;
            }

            // Generate unique store link
            $storeLink = url('/seller/store/' . $username);

            // Create seller account
            $seller = Seller::create([
                'business_name' => $businessName,
                'owner_name' => $fullName,
                'username' => $username,
                'store_link' => $storeLink,
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'phone' => '', // Will need to be updated later
                'password' => \Hash::make(\Str::random(32)), // Random password for Google users
                'status' => 'pending',
                'is_approved' => false,
                'email_verified_at' => now(), // Google email is already verified
            ]);

            Log::info("New seller created via Google: " . $seller->email);
            
            return $seller;
        } catch (\Exception $e) {
            Log::error("Failed to create seller from Google: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return null;
        }
    }
}
