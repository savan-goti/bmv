<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\SettingsController;
use App\Http\Controllers\Seller\ForgotPasswordController;
    
Route::middleware(['guest:seller'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password.update');
});

Route::middleware(['auth:seller', 'session.guard:seller'])->group(function () {
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
        
        // Two-Factor Authentication routes
        Route::post('/settings/two-factor/enable', 'enableTwoFactor')->name('settings.two-factor.enable');
        Route::post('/settings/two-factor/confirm', 'confirmTwoFactor')->name('settings.two-factor.confirm');
        Route::post('/settings/two-factor/disable', 'disableTwoFactor')->name('settings.two-factor.disable');
        Route::post('/settings/two-factor/recovery-codes', 'regenerateRecoveryCodes')->name('settings.two-factor.recovery-codes');
        
        // Session management routes
        Route::get('/settings/sessions', 'getSessions')->name('settings.sessions');
        Route::post('/settings/sessions/logout', 'logoutSession')->name('settings.sessions.logout');
        Route::post('/settings/sessions/logout-others', 'logoutOtherSessions')->name('settings.sessions.logout-others');
    });
    
});
