<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ForgotPasswordController;
    
Route::middleware(['guest:admin'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

Route::middleware(['auth:admin'])->group(function () {
    Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::post('/profile', 'update')->name('profile.update');
        Route::post('/profile/password', 'updatePassword')->name('change.password');
        Route::get('/profile/login-history', 'loginHistory')->name('profile.login-history');
    });

    // Settings routes
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings');
        Route::post('/settings', 'update')->name('settings.update');
    });

    // Staff Management
    Route::controller(StaffController::class)->group(function () {
        Route::get('staffs/ajax-data', 'ajaxData')->name('staffs.ajaxData');
        Route::post('staffs/{staff}/status', 'status')->name('staffs.status');
    });
    Route::resource('staffs', StaffController::class);

    // Seller Management
    Route::controller(SellerController::class)->group(function () {
        Route::get('sellers/ajax-data', 'ajaxData')->name('sellers.ajaxData');
        Route::post('sellers/{seller}/status', 'status')->name('sellers.status');
        Route::post('sellers/{seller}/approve', 'approve')->name('sellers.approve');
    });
    Route::resource('sellers', SellerController::class);
    
});
