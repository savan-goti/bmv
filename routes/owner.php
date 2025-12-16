<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\AuthController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\ProfileController;
use App\Http\Controllers\Owner\SettingController;
use App\Http\Controllers\Owner\OwnerSettingsController;
use App\Http\Controllers\Owner\AdminController;
use App\Http\Controllers\Owner\SellerController;
use App\Http\Controllers\Owner\StaffController;
use App\Http\Controllers\Owner\CustomerController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\SubCategoryController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\JobPositionController;
use App\Http\Controllers\Owner\BranchController;
use App\Http\Controllers\Owner\BranchPositionController;
use App\Http\Controllers\Owner\SupportTeamController;
use App\Http\Controllers\Owner\ForgotPasswordController;
    

Route::middleware(['guest:owner'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

// Email Verification Route (accessible without authentication via signed URL)
Route::get('/email/verify/{id}/{hash}', [OwnerSettingsController::class, 'verifyEmail'])->name('email.verify');

Route::middleware(['auth:owner', 'session.guard:owner'])->group(function () {
    Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/profile/password', 'updatePassword')->name('change.password');
        Route::get('/profile/login-history', 'loginHistory')->name('profile.login-history');
    });

    // Application Settings routes
    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings');
        Route::post('/settings/update', 'update')->name('settings.update');
    });

    // Owner Settings routes (personal settings)
    Route::controller(OwnerSettingsController::class)->group(function () {
        Route::get('/owner-settings', 'index')->name('owner-settings');
        Route::post('/owner-settings', 'update')->name('owner-settings.update');
        
        // Email Verification routes
        Route::post('/owner-settings/email/send-verification', 'sendVerificationEmail')->name('owner-settings.email.send-verification');
        
        // Two-Factor Authentication routes
        Route::post('/owner-settings/two-factor/enable', 'enableTwoFactor')->name('owner-settings.two-factor.enable');
        Route::post('/owner-settings/two-factor/confirm', 'confirmTwoFactor')->name('owner-settings.two-factor.confirm');
        Route::post('/owner-settings/two-factor/disable', 'disableTwoFactor')->name('owner-settings.two-factor.disable');
        Route::post('/owner-settings/two-factor/recovery-codes', 'regenerateRecoveryCodes')->name('owner-settings.two-factor.recovery-codes');
        
        // Session management routes
        Route::get('/owner-settings/sessions', 'getSessions')->name('owner-settings.sessions');
        Route::post('/owner-settings/sessions/logout', 'logoutSession')->name('owner-settings.sessions.logout');
        Route::post('/owner-settings/sessions/logout-others', 'logoutOtherSessions')->name('owner-settings.sessions.logout-others');
        
        // Account deletion route
        Route::post('/owner-settings/delete-account', 'deleteAccount')->name('owner-settings.delete-account');
    });

    // Admin Management
    Route::controller(AdminController::class)->group(function () {
        Route::get('admins/ajax-data', 'ajaxData')->name('admins.ajaxData');
        Route::post('admins/{admin}/status', 'status')->name('admins.status');
    });
    Route::resource('admins', AdminController::class);
    

    // Seller Management
    Route::controller(SellerController::class)->group(function () {
        Route::get('sellers/ajax-data', 'ajaxData')->name('sellers.ajaxData');
        Route::post('sellers/{seller}/status', 'status')->name('sellers.status');
        Route::post('sellers/{seller}/approve', 'approve')->name('sellers.approve');
    });
    Route::resource('sellers', SellerController::class);
    

    // Staff Management
    Route::controller(StaffController::class)->group(function () {
        Route::get('staffs/ajax-data', 'ajaxData')->name('staffs.ajaxData');
        Route::post('staffs/{staff}/status', 'status')->name('staffs.status');
    });
    Route::resource('staffs', StaffController::class);
    

    // Customer Management
    Route::controller(CustomerController::class)->group(function () {
        Route::get('customers/ajax-data', 'ajaxData')->name('customers.ajaxData');
        Route::post('customers/{customer}/status', 'status')->name('customers.status');
    });
    Route::resource('customers', CustomerController::class);

    // Category Management
    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories/ajax-data', 'ajaxData')->name('categories.ajaxData');
        Route::post('categories/{category}/status', 'status')->name('categories.status');
    });
    Route::resource('categories', CategoryController::class);

    // SubCategory Management
    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('sub-categories/ajax-data', 'ajaxData')->name('sub-categories.ajaxData');
        Route::post('sub-categories/{subCategory}/status', 'status')->name('sub-categories.status');
        Route::get('sub-categories/get-by-category', 'getByCategory')->name('sub-categories.get-by-category');
    });
    Route::resource('sub-categories', SubCategoryController::class);

    // Child Category Management
    Route::controller(\App\Http\Controllers\Owner\ChildCategoryController::class)->group(function () {
        Route::get('child-categories/ajax-data', 'ajaxData')->name('child-categories.ajaxData');
        Route::post('child-categories/{childCategory}/status', 'status')->name('child-categories.status');
        Route::get('child-categories/get-by-sub-category', 'getBySubCategory')->name('child-categories.get-by-sub-category');
    });
    Route::resource('child-categories', \App\Http\Controllers\Owner\ChildCategoryController::class);

    // Brand Management
    Route::controller(\App\Http\Controllers\Owner\BrandController::class)->group(function () {
        Route::get('brands/ajax-data', 'ajaxData')->name('brands.ajaxData');
        Route::post('brands/{brand}/status', 'status')->name('brands.status');
    });
    Route::resource('brands', \App\Http\Controllers\Owner\BrandController::class);

    // Collection Management
    Route::controller(\App\Http\Controllers\Owner\CollectionController::class)->group(function () {
        Route::get('collections/ajax-data', 'ajaxData')->name('collections.ajaxData');
        Route::post('collections/{collection}/status', 'status')->name('collections.status');
    });
    Route::resource('collections', \App\Http\Controllers\Owner\CollectionController::class);

    // Product Management
    Route::controller(ProductController::class)->group(function () {
        Route::get('products/ajax-data', 'ajaxData')->name('products.ajaxData');
        Route::post('products/{product}/status', 'status')->name('products.status');
        Route::delete('products/image/{productImage}', 'deleteImage')->name('products.image.delete');
    });
    Route::resource('products', ProductController::class);

    // Job Position Management
    Route::controller(JobPositionController::class)->group(function () {
        Route::get('job-positions/ajax-data', 'ajaxData')->name('job-positions.ajaxData');
        Route::post('job-positions/{jobPosition}/status', 'status')->name('job-positions.status');
    });
    Route::resource('job-positions', JobPositionController::class);

    // Branch Management
    Route::controller(BranchController::class)->group(function () {
        Route::get('branches/ajax-data', 'ajaxData')->name('branches.ajaxData');
        Route::post('branches/{branch}/status', 'status')->name('branches.status');
    });
    Route::resource('branches', BranchController::class);

    // Branch Position Management
    Route::controller(BranchPositionController::class)->group(function () {
        Route::get('branch-positions/ajax-data', 'ajaxData')->name('branch-positions.ajaxData');
        Route::post('branch-positions/{branchPosition}/status', 'status')->name('branch-positions.status');
    });
    Route::resource('branch-positions', BranchPositionController::class);

    // Support Team Management
    Route::controller(SupportTeamController::class)->group(function () {
        Route::get('support-team/ajax-data', 'ajaxData')->name('support-team.ajaxData');
        Route::post('support-team/{support_team}/status', 'status')->name('support-team.status');
    });
    Route::resource('support-team', SupportTeamController::class)->parameters([
        'support-team' => 'supportTeamMember'
    ]);
    
});

