<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\SellerController;
use App\Http\Controllers\Staff\SettingsController;
use App\Http\Controllers\Staff\ForgotPasswordController;
    
Route::middleware(['guest:staff'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

// Email Verification Route (accessible without authentication via signed URL)
Route::get('/email/verify/{id}/{hash}', [SettingsController::class, 'verifyEmail'])->name('email.verify');

Route::middleware(['auth:staff', 'session.guard:staff'])->group(function () {
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
        
        // Email Verification routes
        Route::post('/settings/email/send-verification', 'sendVerificationEmail')->name('settings.email.send-verification');
        
        // Two-Factor Authentication routes
        Route::post('/settings/two-factor/enable', 'enableTwoFactor')->name('settings.two-factor.enable');
        Route::post('/settings/two-factor/confirm', 'confirmTwoFactor')->name('settings.two-factor.confirm');
        Route::post('/settings/two-factor/disable', 'disableTwoFactor')->name('settings.two-factor.disable');
        Route::post('/settings/two-factor/recovery-codes', 'regenerateRecoveryCodes')->name('settings.two-factor.recovery-codes');
        
        // Session management routes
        Route::get('/settings/sessions', 'getSessions')->name('settings.sessions');
        Route::post('/settings/sessions/logout', 'logoutSession')->name('settings.sessions.logout');
        Route::post('/settings/sessions/logout-others', 'logoutOtherSessions')->name('settings.sessions.logout-others');
        
        // Account deletion route
        Route::post('/settings/delete-account', 'deleteAccount')->name('settings.delete-account');
    });

    // Seller Management
    Route::controller(SellerController::class)->group(function () {
        Route::get('sellers/ajax-data', 'ajaxData')->name('sellers.ajaxData');
        Route::post('sellers/{seller}/status', 'status')->name('sellers.status');
        Route::post('sellers/{seller}/approve', 'approve')->name('sellers.approve');
    });
    Route::resource('sellers', SellerController::class);
    
});
