<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;

class CustomerController extends Controller
{
    use ResponseTrait;

    /**
     * Get authenticated customer profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            return $this->sendResponse('Customer profile retrieved successfully', $customer);
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve profile: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update authenticated customer profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('customers')->ignore($customer->id)],
                'name' => 'required|string|max:255',
                'phone' => ['required', 'string', Rule::unique('customers')->ignore($customer->id)],
                'country_code' => 'nullable|string|max:10',
                'gender' => 'nullable|in:male,female,other',
                'dob' => 'nullable|date|before:today',
                'address' => 'nullable|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'pincode' => 'nullable|string|max:20',
                // Social Links
                'whatsapp' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'telegram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
            ], [
                'username.regex' => 'Username can only contain letters, numbers, and underscores',
                'username.unique' => 'This username is already taken',
                'email.unique' => 'This email is already registered',
                'phone.unique' => 'This phone number is already registered',
                'dob.before' => 'Date of birth must be in the past',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($customer->profile_image && file_exists(public_path($customer->profile_image))) {
                    @unlink(public_path($customer->profile_image));
                }
                
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/customers'), $imageName);
                $validatedData['profile_image'] = 'uploads/customers/' . $imageName;
            }

            // Generate canonical URL if username is provided
            if (!empty($validatedData['username'])) {
                $validatedData['canonical'] = url('/customer/' . $validatedData['username']);
            }

            // Handle social links as JSON
            $socialLinks = $customer->social_links ?? [];
            $socialFields = ['whatsapp', 'website', 'facebook', 'instagram', 'linkedin', 'youtube', 'telegram', 'twitter'];
            foreach ($socialFields as $field) {
                if (isset($validatedData[$field])) {
                    if (!empty($validatedData[$field])) {
                        $socialLinks[$field] = $validatedData[$field];
                    } else {
                        unset($socialLinks[$field]);
                    }
                    unset($validatedData[$field]);
                }
            }
            if (!empty($socialLinks)) {
                $validatedData['social_links'] = $socialLinks;
            }

            $customer->update($validatedData);

            return $this->sendResponse('Profile updated successfully', $customer->fresh());

        } catch (\Exception $e) {
            return $this->sendError('Failed to update profile: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update customer password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'current_password.required' => 'Current password is required',
                'new_password.required' => 'New password is required',
                'new_password.min' => 'New password must be at least 8 characters',
                'new_password.confirmed' => 'Password confirmation does not match',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            // Verify current password
            if (!Hash::check($request->current_password, $customer->password)) {
                return $this->sendError('Current password is incorrect', 400);
            }

            // Update password
            $customer->update([
                'password' => Hash::make($request->new_password)
            ]);

            return $this->sendResponse('Password updated successfully', null);

        } catch (\Exception $e) {
            return $this->sendError('Failed to update password: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update customer profile image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfileImage(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'profile_image.required' => 'Profile image is required',
                'profile_image.image' => 'File must be an image',
                'profile_image.mimes' => 'Image must be jpeg, png, jpg, or gif',
                'profile_image.max' => 'Image size must not exceed 2MB',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            // Delete old image if exists
            if ($customer->profile_image && file_exists(public_path($customer->profile_image))) {
                @unlink(public_path($customer->profile_image));
            }
            
            $image = $request->file('profile_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/customers'), $imageName);
            
            $customer->update([
                'profile_image' => 'uploads/customers/' . $imageName
            ]);

            return $this->sendResponse('Profile image updated successfully', [
                'profile_image' => $customer->profile_image,
                'profile_image_url' => asset($customer->profile_image)
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to update profile image: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete customer profile image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProfileImage()
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            // Delete image file if exists
            if ($customer->profile_image && file_exists(public_path($customer->profile_image))) {
                @unlink(public_path($customer->profile_image));
            }
            
            $customer->update([
                'profile_image' => null
            ]);

            return $this->sendResponse('Profile image deleted successfully', null);

        } catch (\Exception $e) {
            return $this->sendError('Failed to delete profile image: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update customer location
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'pincode' => 'nullable|string|max:20',
            ], [
                'latitude.required' => 'Latitude is required',
                'latitude.between' => 'Latitude must be between -90 and 90',
                'longitude.required' => 'Longitude is required',
                'longitude.between' => 'Longitude must be between -180 and 180',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $customer->update($validator->validated());

            return $this->sendResponse('Location updated successfully', [
                'latitude' => $customer->latitude,
                'longitude' => $customer->longitude,
                'address' => $customer->address,
                'city' => $customer->city,
                'state' => $customer->state,
                'country' => $customer->country,
                'pincode' => $customer->pincode,
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to update location: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update customer social links
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSocialLinks(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'whatsapp' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'facebook' => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'linkedin' => 'nullable|url|max:255',
                'youtube' => 'nullable|url|max:255',
                'telegram' => 'nullable|url|max:255',
                'twitter' => 'nullable|url|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $validatedData = $validator->validated();
            $socialLinks = $customer->social_links ?? [];
            
            $socialFields = ['whatsapp', 'website', 'facebook', 'instagram', 'linkedin', 'youtube', 'telegram', 'twitter'];
            foreach ($socialFields as $field) {
                if (isset($validatedData[$field])) {
                    if (!empty($validatedData[$field])) {
                        $socialLinks[$field] = $validatedData[$field];
                    } else {
                        unset($socialLinks[$field]);
                    }
                }
            }

            $customer->update([
                'social_links' => $socialLinks
            ]);

            return $this->sendResponse('Social links updated successfully', [
                'social_links' => $customer->social_links
            ]);

        } catch (\Exception $e) {
            return $this->sendError('Failed to update social links: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete customer account (soft delete)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount(Request $request)
    {
        try {
            $customer = auth('api')->user();
            
            if (!$customer) {
                return $this->sendError('Unauthorized', 401);
            }

            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
            ], [
                'password.required' => 'Password is required to delete account',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            // Verify password
            if (!Hash::check($request->password, $customer->password)) {
                return $this->sendError('Password is incorrect', 400);
            }

            // Soft delete the customer
            $customer->delete();

            // Logout
            auth('api')->logout();

            return $this->sendResponse('Account deleted successfully', null);

        } catch (\Exception $e) {
            return $this->sendError('Failed to delete account: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get customer by ID (public profile)
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $customer = Customer::find($id);
            
            if (!$customer) {
                return $this->sendError('Customer not found', 404);
            }

            // Return only public information
            $publicData = [
                'id' => $customer->id,
                'username' => $customer->username,
                'name' => $customer->name,
                'profile_image' => $customer->profile_image,
                'profile_image_url' => $customer->profile_image ? asset($customer->profile_image) : null,
                'canonical' => $customer->canonical,
                'social_links' => $customer->social_links,
                'created_at' => $customer->created_at,
            ];

            return $this->sendResponse('Customer profile retrieved successfully', $publicData);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve customer: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get customer by username (public profile)
     *
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByUsername($username)
    {
        try {
            $customer = Customer::where('username', $username)->first();
            
            if (!$customer) {
                return $this->sendError('Customer not found', 404);
            }

            // Return only public information
            $publicData = [
                'id' => $customer->id,
                'username' => $customer->username,
                'name' => $customer->name,
                'profile_image' => $customer->profile_image,
                'profile_image_url' => $customer->profile_image ? asset($customer->profile_image) : null,
                'canonical' => $customer->canonical,
                'social_links' => $customer->social_links,
                'created_at' => $customer->created_at,
            ];

            return $this->sendResponse('Customer profile retrieved successfully', $publicData);

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve customer: ' . $e->getMessage(), 500);
        }
    }
}
