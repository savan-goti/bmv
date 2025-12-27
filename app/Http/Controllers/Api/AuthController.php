<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Traits\ResponseTrait;


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
            'phone' => 'nullable|string|min:10|max:20|unique:customers,phone',
            'country_code' => 'nullable|string|max:5',
            'username' => 'nullable|string|min:3|max:50|unique:customers,username|regex:/^[a-zA-Z0-9_]+$/',
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
            'username.unique' => 'This username is already taken',
            'username.regex' => 'Username can only contain letters, numbers, and underscores',
            'username.min' => 'Username must be at least 3 characters',
            'dob.before' => 'Date of birth must be in the past',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            // Generate username if not provided
            $username = $request->username;
            if (!$username) {
                $username = $this->generateUniqueUsername($request->name);
            }

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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return $this->sendError('Invalid credentials', 401);
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
