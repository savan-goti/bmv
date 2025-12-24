<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SupportTeamController;
use App\Http\Controllers\Admin\ForgotPasswordController;
    
Route::middleware(['guest:admin'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    
    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});


// Email Verification Route (accessible without authentication via signed URL)
Route::get('/email/verify/{id}/{hash}', [SettingsController::class, 'verifyEmail'])->name('email.verify');

Route::middleware(['auth:admin', 'session.guard:admin'])->group(function () {
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

    // Support Team Management
    Route::controller(SupportTeamController::class)->group(function () {
        Route::get('support-team/ajax-data', 'ajaxData')->name('support-team.ajaxData');
        Route::post('support-team/{support_team}/status', 'status')->name('support-team.status');
    });
    Route::resource('support-team', SupportTeamController::class)->parameters([
        'support-team' => 'supportTeamMember'
    ]);
    
});
