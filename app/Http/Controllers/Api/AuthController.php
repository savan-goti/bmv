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
     * Customer Registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|min:10|max:20|unique:customers,phone',
            'country_code' => 'required|string|max:5',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date|before:today',
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 2 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'phone.unique' => 'This phone number is already registered',
            'phone.min' => 'Phone number must be at least 10 digits',
            'dob.before' => 'Date of birth must be in the past',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // Generate username if not provided
            $username = $this->generateUniqueUsername($request->name);

            // Generate canonical (unique identifier)
            $canonical = $this->generateCanonical($request->email);

            // Create customer
            $customer = Customer::create([
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'username' => $username,
                'canonical' => $canonical,
                'password' => $request->password, // Will be hashed by model cast
                'phone' => $request->phone,
                'country_code' => $request->country_code ?? '+91',
                'gender' => $request->gender,
                'dob' => $request->dob,
                'status' => 1, // Active by default
                'phone_validate' => false,
            ]);

            // Generate JWT token
            $token = JWTAuth::fromUser($customer);

            // Prepare response data (hide sensitive fields)
            $customerData = $customer->fresh();
            $customerData->makeHidden(['password', 'phone_otp', 'remember_token']);

            return $this->sendResponse('Registration successful! Welcome to BMV.', [
                'customer' => $customerData,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'refresh_expires_in' => config('jwt.refresh_ttl', 20160) * 60
            ], 201);

        } catch (\Exception $e) {
            return $this->sendError('Registration failed. Please try again.'. $e->getMessage(), 500);
        }
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
     * Customer Login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate type field
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
            'identifier' => 'required|string',
            'password' => 'required|string|min:8',
        ], [
            'type.required' => 'Login type is required',
            'type.in' => 'Login type must be either email or phone',
            'identifier.required' => 'Email or phone number is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Additional validation based on type
        $type = $request->type;
        $identifier = $request->identifier;

        if ($type === 'email') {
            // Validate email format
            if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                return $this->sendError('Please provide a valid email address', 422);
            }
            $credentials = [
                'email' => strtolower(trim($identifier)),
                'password' => $request->password
            ];
        } else {
            // Phone login
            // Remove any spaces or special characters from phone
            $phone = preg_replace('/[^0-9+]/', '', $identifier);
            $credentials = [
                'phone' => $phone,
                'password' => $request->password
            ];
        }

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return $this->sendError('Invalid credentials. Please check your ' . $type . ' and password.', 401);
            }

            // Check if customer is active
            $customer = auth('api')->user();
            if ($customer->status != 'active') {
                auth('api')->logout();
                return $this->sendError('Your account is inactive. Please contact support.', 403);
            }

        } catch (JWTException $e) {
            return $this->sendError('Could not create token', 500);
        }

        return $this->respondWithToken($token);
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
     * Send OTP to customer's phone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:customers,phone',
            'country_code' => 'required|string|max:5',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.exists' => 'Phone number not registered',
            'country_code.required' => 'Country code is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // Find customer
            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return $this->sendError('Customer not found', 404);
            }

            // Check if phone is already verified
            if ($customer->phone_validate) {
                return $this->sendError('Phone number is already verified', 400);
            }

            // Generate OTP
            $otp = TwilioService::generateOTP(6);
            $expirationMinutes = (int) TwilioService::getOTPExpirationMinutes();
            
            // Save OTP to database
            $customer->phone_otp = $otp;
            $customer->otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            $customer->save();
            
            // Send OTP via Twilio
            // $twilioService = new TwilioService();
            // Fix phone number format - country_code should already have +
            $phoneNumber = '+' . $request->country_code . $request->phone;
            $sent = TwilioService::sendOTP($phoneNumber, $otp);
            dd($sent);
            if (!$sent) {
                // Log the error but still return success for development
                Log::warning('OTP not sent via SMS, but saved to database', [
                    'phone' => $phoneNumber,
                    'otp' => $otp // Remove this in production
                ]);
                
                // For development: return success with OTP in response
                if (config('app.debug')) {
                    return $this->sendResponse('OTP generated (SMS failed, check logs)', [
                        'phone' => $request->phone,
                        'expires_in_minutes' => $expirationMinutes,
                        'otp_for_testing' => $otp // Only in debug mode
                    ]);
                }
                
                return $this->sendError('Failed to send OTP. Please try again.', 500);
            }

            return $this->sendResponse('OTP sent successfully to your phone', [
                'phone' => $request->phone,
                'expires_in_minutes' => $expirationMinutes
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to send OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Verify OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:customers,phone',
            'otp' => 'required|string|size:6',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.exists' => 'Phone number not registered',
            'otp.required' => 'OTP is required',
            'otp.size' => 'OTP must be 6 digits',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // Find customer
            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return $this->sendError('Customer not found', 404);
            }

            // Check if phone is already verified
            if ($customer->phone_validate) {
                return $this->sendError('Phone number is already verified', 400);
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

            // Mark phone as verified
            $customer->phone_validate = true;
            $customer->phone_otp = null;
            $customer->otp_expired_at = null;
            $customer->save();

            // Generate JWT token
            $token = JWTAuth::fromUser($customer);

            return $this->sendResponse('Phone verified successfully', [
                'customer' => $customer->makeHidden(['password', 'phone_otp', 'remember_token']),
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to verify OTP: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Resend OTP
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resendOTP(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:customers,phone',
            'country_code' => 'required|string|max:5',
        ], [
            'phone.required' => 'Phone number is required',
            'phone.exists' => 'Phone number not registered',
            'country_code.required' => 'Country code is required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // Find customer
            $customer = Customer::where('phone', $request->phone)->first();

            if (!$customer) {
                return $this->sendError('Customer not found', 404);
            }

            // Check if phone is already verified
            if ($customer->phone_validate) {
                return $this->sendError('Phone number is already verified', 400);
            }

            // Check rate limiting (prevent spam)
            if ($customer->otp_expired_at && Carbon::now()->isBefore($customer->otp_expired_at->subMinutes(9))) {
                $waitTime = Carbon::now()->diffInSeconds($customer->otp_expired_at->subMinutes(9));
                return $this->sendError("Please wait {$waitTime} seconds before requesting a new OTP", 429);
            }

            // Generate new OTP
            $otp = \App\Services\TwilioService::generateOTP(6);
            $expirationMinutes = \App\Services\TwilioService::getOTPExpirationMinutes();

            // Save OTP to database
            $customer->phone_otp = $otp;
            $customer->otp_expired_at = Carbon::now()->addMinutes($expirationMinutes);
            $customer->save();

            // Send OTP via Twilio
            $twilioService = new \App\Services\TwilioService();
            $phoneNumber = $request->country_code . $request->phone;
            $sent = $twilioService->sendOTP($phoneNumber, $otp);

            if (!$sent) {
                return $this->sendError('Failed to send OTP. Please try again.', 500);
            }

            return $this->sendResponse('OTP resent successfully to your phone', [
                'phone' => $request->phone,
                'expires_in_minutes' => $expirationMinutes
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to resend OTP: ' . $e->getMessage(), 500);
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
