<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Traits\ResponseTrait;
use App\Services\TwilioService;


class AuthController extends Controller
{
    use ResponseTrait;
    /**
     * Send OTP for Registration (Step 1)
     * Customer enters mobile number and receives OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request, TwilioService $twilio)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:10|max:20',
            'country_code' => 'required|string|max:5',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.min' => 'Phone number must be at least 10 digits',
            'country_code.required' => 'Country code is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $phone = preg_replace('/[^0-9+]/', '', $request->phone);
            
            // Check if phone is already registered
            $existingCustomer = Customer::where('phone', $phone)->first();
            if ($existingCustomer) {
                return $this->sendError('This phone number is already registered. Please login instead.', 422);
            }

            // Generate OTP
            $otp = TwilioService::generateOTP(6);
            $expirationMinutes = (int) TwilioService::getOTPExpirationMinutes();
            
            // Create a temporary customer record with OTP
            $customer = Customer::create([
                'phone' => $phone,
                'country_code' => $request->country_code,
                'phone_otp' => $otp,
                'otp_expired_at' => Carbon::now()->addMinutes($expirationMinutes),
                'phone_validate' => false,
                'status' => 'inactive', // Will be activated after OTP verification
                'password' => Hash::make(uniqid()), // Temporary password
            ]);
            
            // Send OTP via SMS
            $countryCode = ltrim($request->country_code, '+');
            $phoneNumber = '+' . $countryCode . $request->phone;
            $sent = $twilio->sendOTP($phoneNumber, "Your BMV registration OTP is {$otp}. Valid for {$expirationMinutes} minutes.");
            
            if (!$sent) {
                // Log the error but still return success for development
                Log::warning('OTP not sent via SMS, but saved to database', [
                    'phone' => $phoneNumber,
                    'otp' => $otp // Remove this in production
                ]);
                
                // For development: return success with OTP in response
                if (config('app.debug')) {
                    return $this->sendResponse('Registration initiated (SMS failed, check logs)', [
                        'phone' => $request->phone,
                        'expires_in_minutes' => $expirationMinutes,
                        'otp_for_testing' => $otp, // Only in debug mode
                        'message' => 'Please verify OTP to complete registration'
                    ]);
                }
                
                // Clean up the temporary customer record
                $customer->delete();
                return $this->sendError('Failed to send OTP. Please try again.', 500);
            }

            return $this->sendResponse('OTP sent successfully to your phone', [
                'phone' => $request->phone,
                'expires_in_minutes' => $expirationMinutes,
                'message' => 'Please verify OTP to complete registration'
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Verify OTP and Complete Registration (Step 2)
     * Verifies OTP and creates customer account with auto-generated username
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyRegistrationOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
        ], [
            'phone.required' => 'Phone number is required',
            'otp.required' => 'OTP is required',
            'otp.size' => 'OTP must be 6 digits',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $phone = preg_replace('/[^0-9+]/', '', $request->phone);
            
            // Find customer by phone
            $customer = Customer::where('phone', $phone)->first();

            if (!$customer) {
                return $this->sendError('Phone number not found. Please register first.', 404);
            }

            // Check if already verified
            if ($customer->phone_validate && $customer->status === 'active') {
                return $this->sendError('This phone number is already registered. Please login instead.', 422);
            }

            // Check if OTP exists
            if (!$customer->phone_otp) {
                return $this->sendError('No OTP found. Please request a new OTP.', 400);
            }

            // Check if OTP is expired
            if (Carbon::now()->isAfter($customer->otp_expired_at)) {
                return $this->sendError('OTP has expired. Please request a new OTP.', 400);
            }

            // Verify OTP
            if ($customer->phone_otp !== $request->otp) {
                return $this->sendError('Invalid OTP. Please try again.', 400);
            }

            // Generate unique username from phone number
            $username = $this->generateUniqueUsernameFromPhone($customer->phone);
            
            // Generate canonical identifier
            $canonical = $this->generateCanonicalFromPhone($customer->phone);

            // Update customer record - complete registration
            $customer->username = $username;
            $customer->canonical = $canonical;
            $customer->name = $username; // Use username as default name
            $customer->phone_validate = true;
            $customer->status = 'active';
            $customer->phone_otp = null;
            $customer->otp_expired_at = null;
            $customer->save();

            // Generate JWT token for auto-login
            $token = JWTAuth::fromUser($customer);

            return $this->sendResponse('Registration successful! Welcome to BMV.', [
                'customer' => $customer->makeHidden(['password', 'phone_otp', 'email_otp', 'remember_token']),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ], 201);

        } catch (\Exception $e) {
            return $this->sendError('Failed to verify OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Resend OTP for Registration
     * Resends OTP to customer's phone during registration process
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendRegistrationOTP(Request $request, TwilioService $twilio)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'country_code' => 'required|string|max:5',
        ], [
            'phone.required' => 'Phone number is required',
            'country_code.required' => 'Country code is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $phone = preg_replace('/[^0-9+]/', '', $request->phone);
            
            // Find customer by phone
            $customer = Customer::where('phone', $phone)->first();

            if (!$customer) {
                return $this->sendError('Phone number not found. Please register first.', 404);
            }

            // Check if already verified
            if ($customer->phone_validate && $customer->status === 'active') {
                return $this->sendError('This phone number is already registered. Please login instead.', 422);
            }

            // Check rate limiting (prevent spam) - 1 minute cooldown
            if ($customer->otp_expired_at && Carbon::now()->isBefore($customer->otp_expired_at->subMinutes(9))) {
                $waitTime = Carbon::now()->diffInSeconds($customer->otp_expired_at->subMinutes(9));
                return $this->sendError("Please wait {$waitTime} seconds before requesting a new OTP", 429);
            }

            // Generate new OTP
            $otp = TwilioService::generateOTP(6);
            $expirationMinutes = (int) TwilioService::getOTPExpirationMinutes();

            // Update OTP in database
            $customer->phone_otp = $otp;
            $customer->otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            $customer->save();

            // Send OTP via SMS
            $countryCode = ltrim($request->country_code, '+');
            $phoneNumber = '+' . $countryCode . $request->phone;
            $sent = $twilio->sendOTP($phoneNumber, "Your BMV registration OTP is {$otp}. Valid for {$expirationMinutes} minutes.");

            if (!$sent) {
                // Log the error but still return success for development
                Log::warning('OTP not sent via SMS, but saved to database', [
                    'phone' => $phoneNumber,
                    'otp' => $otp // Remove this in production
                ]);
                
                // For development: return success with OTP in response
                if (config('app.debug')) {
                    return $this->sendResponse('OTP resent (SMS failed, check logs)', [
                        'phone' => $request->phone,
                        'expires_in_minutes' => $expirationMinutes,
                        'otp_for_testing' => $otp, // Only in debug mode
                        'message' => 'Please verify OTP to complete registration'
                    ]);
                }
                
                return $this->sendError('Failed to send OTP. Please try again.', 500);
            }

            return $this->sendResponse('OTP resent successfully to your phone', [
                'phone' => $request->phone,
                'expires_in_minutes' => $expirationMinutes,
                'message' => 'Please verify OTP to complete registration'
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to resend OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Generate unique username from phone number
     *
     * @param string $phone
     * @return string
     */
    private function generateUniqueUsernameFromPhone($phone)
    {
        // Clean phone number (remove non-numeric characters)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Create base username from phone (last 8 digits)
        $baseUsername = 'user_' . substr($cleanPhone, -8);
        
        // Check if username exists
        $username = $baseUsername;
        $counter = 1;

        while (Customer::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate canonical identifier from phone number
     *
     * @param string $phone
     * @return string
     */
    private function generateCanonicalFromPhone($phone)
    {
        // Clean phone number
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Create canonical with phone and random suffix
        $canonical = 'ph_' . substr($cleanPhone, -10) . '_' . substr(md5(uniqid()), 0, 6);
        
        return $canonical;
    }

    /**
     * Generate unique username from name
     *
     * @param string $name
     * @return string
     */
    private function generateUniqueUsername($name)
    {
        // Clean and format name
        $username = strtolower(trim($name));
        $username = preg_replace('/[^a-z0-9_]/', '_', $username);
        $username = preg_replace('/_+/', '_', $username); // Remove multiple underscores
        $username = trim($username, '_');

        // Ensure minimum length
        if (strlen($username) < 3) {
            $username = 'user_' . $username;
        }

        // Check if username exists
        $originalUsername = $username;
        $counter = 1;

        while (Customer::where('username', $username)->exists()) {
            $username = $originalUsername . '_' . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate canonical identifier from email
     *
     * @param string $email
     * @return string
     */
    private function generateCanonical($email)
    {
        $emailPart = explode('@', $email)[0];
        $canonical = strtolower(preg_replace('/[^a-z0-9]/', '', $emailPart));
        
        // Add random suffix to ensure uniqueness
        $canonical .= '_' . substr(md5(uniqid()), 0, 8);
        
        return $canonical;
    }


    /**
     * Customer Login - Send OTP (Step 1)
     * Sends OTP to customer's email or phone for login verification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, TwilioService $twilio)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'identifier' => 'required|string',
        ], [
            'type.required' => 'Login type is required',
            'type.in' => 'Login type must be either email or phone',
            'identifier.required' => 'Email or phone number is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $type = $request->type;
            $identifier = $request->identifier;

            // Find customer based on type
            if ($type === 'email') {
                // Validate email format
                if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                    return $this->sendError('Please provide a valid email address', 422);
                }
                $customer = Customer::where('email', strtolower(trim($identifier)))->first();
            } else {
                // Phone - validate country code is provided
                $validator = Validator::make($request->all(), [
                    'country_code' => 'required|string|max:5',
                ], [
                    'country_code.required' => 'Country code is required for phone login',
                ]);

                if ($validator->fails()) {
                    return $this->sendValidationError($validator->errors());
                }

                $phone = preg_replace('/[^0-9+]/', '', $identifier);
                $customer = Customer::where('phone', $phone)->first();
            }

            if (!$customer) {
                return $this->sendError(ucfirst($type) . ' not registered. Please register first.', 404);
            }

            // Check if customer is active
            if ($customer->status !== 'active') {
                return $this->sendError('Your account is inactive. Please contact support.', 403);
            }

            // Generate OTP
            $otp = TwilioService::generateOTP(6);
            $expirationMinutes = (int) TwilioService::getOTPExpirationMinutes();
            
            // Save OTP to database based on type
            if ($type === 'email') {
                $customer->email_otp = $otp;
                $customer->email_otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            } else {
                $customer->phone_otp = $otp;
                $customer->otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            }
            $customer->save();
            
            // Send OTP based on type
            if ($type === 'email') {
                // Send OTP via Email
                try {
                    \Mail::to($customer->email)->send(new \App\Mail\CustomerOTPMail($customer, $otp, $expirationMinutes));
                    
                    return $this->sendResponse('OTP sent successfully to your email', [
                        'email' => $customer->email,
                        'expires_in_minutes' => $expirationMinutes,
                        'message' => 'Please verify OTP to login'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send login OTP email', [
                        'email' => $customer->email,
                        'error' => $e->getMessage()
                    ]);
                    
                    // For development: return success with OTP in response
                    if (config('app.debug')) {
                        return $this->sendResponse('OTP generated (Email failed, check logs)', [
                            'email' => $customer->email,
                            'expires_in_minutes' => $expirationMinutes,
                            'otp_for_testing' => $otp, // Only in debug mode
                            'message' => 'Please verify OTP to login'
                        ]);
                    }
                    
                    return $this->sendError('Failed to send OTP email. Please try again.', 500);
                }
            } else {
                // Send OTP via SMS (Twilio)
                $countryCode = ltrim($request->country_code, '+');
                $phoneNumber = '+' . $countryCode . $request->identifier;
                $sent = $twilio->sendOTP($phoneNumber, "Your BMV login OTP is {$otp}. Valid for {$expirationMinutes} minutes.");
                
                if (!$sent) {
                    // Log the error but still return success for development
                    Log::warning('Login OTP not sent via SMS, but saved to database', [
                        'phone' => $phoneNumber,
                        'otp' => $otp // Remove this in production
                    ]);
                    
                    // For development: return success with OTP in response
                    if (config('app.debug')) {
                        return $this->sendResponse('OTP generated (SMS failed, check logs)', [
                            'phone' => $request->identifier,
                            'expires_in_minutes' => $expirationMinutes,
                            'otp_for_testing' => $otp, // Only in debug mode
                            'message' => 'Please verify OTP to login'
                        ]);
                    }
                    
                    return $this->sendError('Failed to send OTP. Please try again.', 500);
                }

                return $this->sendResponse('OTP sent successfully to your phone', [
                    'phone' => $request->identifier,
                    'expires_in_minutes' => $expirationMinutes,
                    'message' => 'Please verify OTP to login'
                ]);
            }

        } catch (\Exception $e) {
            return $this->sendError('Failed to send login OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated Customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        try {
            $customer = auth('api')->user();
            
            return $this->sendResponse('User profile', $customer);
        } catch (\Exception $e) {
            return $this->sendError('User not found', 404);
        }
    }

    /**
     * Log the customer out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth('api')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, please try again'
            ], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = auth('api')->refresh();
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not refresh token'
            ], 500);
        }
    }

    /**
     * Verify Login OTP (Step 2)
     * Verifies OTP and logs in the customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyLoginOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6',
        ], [
            'type.required' => 'Type is required',
            'type.in' => 'Type must be either email or phone',
            'identifier.required' => 'Email or phone number is required',
            'otp.required' => 'OTP is required',
            'otp.size' => 'OTP must be 6 digits',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $type = $request->type;
            $identifier = $request->identifier;

            // Find customer based on type
            if ($type === 'email') {
                if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                    return $this->sendError('Please provide a valid email address', 422);
                }
                $customer = Customer::where('email', strtolower(trim($identifier)))->first();
            } else {
                $phone = preg_replace('/[^0-9+]/', '', $identifier);
                $customer = Customer::where('phone', $phone)->first();
            }

            if (!$customer) {
                return $this->sendError(ucfirst($type) . ' not registered', 404);
            }

            // Check if customer is active
            if ($customer->status !== 'active') {
                return $this->sendError('Your account is inactive. Please contact support.', 403);
            }

            // Check OTP based on type
            if ($type === 'email') {
                // Check if OTP exists
                if (!$customer->email_otp) {
                    return $this->sendError('No OTP found. Please request a new OTP.', 400);
                }

                // Check if OTP is expired
                if (Carbon::now()->isAfter($customer->email_otp_expired_at)) {
                    return $this->sendError('OTP has expired. Please request a new OTP.', 400);
                }

                // Verify OTP
                if ($customer->email_otp !== $request->otp) {
                    return $this->sendError('Invalid OTP. Please try again.', 400);
                }

                // Clear email OTP
                $customer->email_otp = null;
                $customer->email_otp_expired_at = null;
            } else {
                // Check if OTP exists
                if (!$customer->phone_otp) {
                    return $this->sendError('No OTP found. Please request a new OTP.', 400);
                }

                // Check if OTP is expired
                if (Carbon::now()->isAfter($customer->otp_expired_at)) {
                    return $this->sendError('OTP has expired. Please request a new OTP.', 400);
                }

                // Verify OTP
                if ($customer->phone_otp !== $request->otp) {
                    return $this->sendError('Invalid OTP. Please try again.', 400);
                }

                // Clear phone OTP
                $customer->phone_otp = null;
                $customer->otp_expired_at = null;
            }

            $customer->save();

            // Generate JWT token for login
            $token = JWTAuth::fromUser($customer);

            return $this->sendResponse('Login successful! Welcome back.', [
                'customer' => $customer->makeHidden(['password', 'phone_otp', 'email_otp', 'remember_token']),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to verify OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Resend Login OTP
     * Resends OTP to customer's email or phone for login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendLoginOTP(Request $request, TwilioService $twilio)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'identifier' => 'required|string',
        ], [
            'type.required' => 'Type is required',
            'type.in' => 'Type must be either email or phone',
            'identifier.required' => 'Email or phone number is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $type = $request->type;
            $identifier = $request->identifier;

            // Find customer based on type
            if ($type === 'email') {
                if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                    return $this->sendError('Please provide a valid email address', 422);
                }
                $customer = Customer::where('email', strtolower(trim($identifier)))->first();
            } else {
                // Phone - validate country code is provided
                $validator = Validator::make($request->all(), [
                    'country_code' => 'required|string|max:5',
                ], [
                    'country_code.required' => 'Country code is required for phone OTP',
                ]);

                if ($validator->fails()) {
                    return $this->sendValidationError($validator->errors());
                }

                $phone = preg_replace('/[^0-9+]/', '', $identifier);
                $customer = Customer::where('phone', $phone)->first();
            }

            if (!$customer) {
                return $this->sendError(ucfirst($type) . ' not registered', 404);
            }

            // Check if customer is active
            if ($customer->status !== 'active') {
                return $this->sendError('Your account is inactive. Please contact support.', 403);
            }

            // Check rate limiting (prevent spam) based on type
            if ($type === 'email') {
                if ($customer->email_otp_expired_at && Carbon::now()->isBefore($customer->email_otp_expired_at->subMinutes(9))) {
                    $waitTime = Carbon::now()->diffInSeconds($customer->email_otp_expired_at->subMinutes(9));
                    return $this->sendError("Please wait {$waitTime} seconds before requesting a new OTP", 429);
                }
            } else {
                if ($customer->otp_expired_at && Carbon::now()->isBefore($customer->otp_expired_at->subMinutes(9))) {
                    $waitTime = Carbon::now()->diffInSeconds($customer->otp_expired_at->subMinutes(9));
                    return $this->sendError("Please wait {$waitTime} seconds before requesting a new OTP", 429);
                }
            }

            // Generate new OTP
            $otp = TwilioService::generateOTP(6);
            $expirationMinutes = (int) TwilioService::getOTPExpirationMinutes();

            // Save OTP to database based on type
            if ($type === 'email') {
                $customer->email_otp = $otp;
                $customer->email_otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            } else {
                $customer->phone_otp = $otp;
                $customer->otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            }
            $customer->save();

            // Send OTP based on type
            if ($type === 'email') {
                // Send OTP via Email
                try {
                    \Mail::to($customer->email)->send(new \App\Mail\CustomerOTPMail($customer, $otp, $expirationMinutes));
                    
                    return $this->sendResponse('OTP resent successfully to your email', [
                        'email' => $customer->email,
                        'expires_in_minutes' => $expirationMinutes,
                        'message' => 'Please verify OTP to login'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to resend login OTP email', [
                        'email' => $customer->email,
                        'error' => $e->getMessage()
                    ]);
                    
                    // For development: return success with OTP in response
                    if (config('app.debug')) {
                        return $this->sendResponse('OTP resent (Email failed, check logs)', [
                            'email' => $customer->email,
                            'expires_in_minutes' => $expirationMinutes,
                            'otp_for_testing' => $otp, // Only in debug mode
                            'message' => 'Please verify OTP to login'
                        ]);
                    }
                    
                    return $this->sendError('Failed to resend OTP email. Please try again.', 500);
                }
            } else {
                // Send OTP via SMS
                $countryCode = ltrim($request->country_code, '+');
                $phoneNumber = '+' . $countryCode . $request->identifier;
                $sent = $twilio->sendOTP($phoneNumber, "Your BMV login OTP is {$otp}. Valid for {$expirationMinutes} minutes.");

                if (!$sent) {
                    // Log the error but still return success for development
                    Log::warning('Login OTP not sent via SMS, but saved to database', [
                        'phone' => $phoneNumber,
                        'otp' => $otp // Remove this in production
                    ]);
                    
                    // For development: return success with OTP in response
                    if (config('app.debug')) {
                        return $this->sendResponse('OTP resent (SMS failed, check logs)', [
                            'phone' => $request->identifier,
                            'expires_in_minutes' => $expirationMinutes,
                            'otp_for_testing' => $otp, // Only in debug mode
                            'message' => 'Please verify OTP to login'
                        ]);
                    }
                    
                    return $this->sendError('Failed to send OTP. Please try again.', 500);
                }

                return $this->sendResponse('OTP resent successfully to your phone', [
                    'phone' => $request->identifier,
                    'expires_in_minutes' => $expirationMinutes,
                    'message' => 'Please verify OTP to login'
                ]);
            }

        } catch (\Exception $e) {
            return $this->sendError('Failed to resend login OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'customer' => auth('api')->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);
    }
}
