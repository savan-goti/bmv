<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        
        // OTP Verification Routes
        Route::post('send-otp', [AuthController::class, 'sendOTP']);
        Route::post('verify-otp', [AuthController::class, 'verifyOTP']);
        Route::post('resend-otp', [AuthController::class, 'resendOTP']);
        
        // Protected routes (require JWT token)
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('profile', [AuthController::class, 'profile']);
        });
    });

    // Protected API routes
    Route::middleware('auth:api')->group(function () {
        // Customer Info Routes
        Route::prefix('customer')->group(function () {
            // Profile Management
            Route::get('profile', [CustomerController::class, 'getProfile']);
            Route::put('profile', [CustomerController::class, 'updateProfile']);
            Route::post('profile', [CustomerController::class, 'updateProfile']); // For multipart/form-data
            
            // Password Management
            Route::put('password', [CustomerController::class, 'updatePassword']);
            Route::post('password', [CustomerController::class, 'updatePassword']);
            
            // Profile Image Management
            Route::post('profile-image', [CustomerController::class, 'updateProfileImage']);
            Route::delete('profile-image', [CustomerController::class, 'deleteProfileImage']);
            
            // Location Management
            Route::put('location', [CustomerController::class, 'updateLocation']);
            Route::post('location', [CustomerController::class, 'updateLocation']);
            
            // Social Links Management
            Route::put('social-links', [CustomerController::class, 'updateSocialLinks']);
            Route::post('social-links', [CustomerController::class, 'updateSocialLinks']);
            
            // Account Deletion
            Route::delete('account', [CustomerController::class, 'deleteAccount']);
            Route::post('account/delete', [CustomerController::class, 'deleteAccount']);
        });
        
        // Public Customer Profile Routes (requires authentication to view)
        Route::get('customers/{id}', [CustomerController::class, 'show']);
        Route::get('customers/username/{username}', [CustomerController::class, 'showByUsername']);
        
    });

    // Category Routes
    Route::get('category-types', [CategoryController::class, 'getCategoryTypes']);
    Route::get('categories', [CategoryController::class, 'getCategories']);
    
    // Sub-Category Routes
    Route::get('subcategories', [CategoryController::class, 'getSubCategories']);
    
    // Child Category Routes
    Route::get('child-categories', [CategoryController::class, 'getChildCategories']);
});

// Health check endpoint
Route::get('health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toDateTimeString()
    ]);
});
