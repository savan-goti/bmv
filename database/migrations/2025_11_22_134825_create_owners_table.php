<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            
            // Profile
            $table->string('profile_image')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            
            // Business Information
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
            $table->text('business_description')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('registration_number')->nullable();
            
            // Status & Security
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            
            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Login Email Verification
            $table->boolean('login_email_verification_enabled')->default(false);
            $table->string('login_verification_code', 10)->nullable();
            $table->timestamp('login_verification_code_expires_at')->nullable();
            
            // Email Verification & Remember Token
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
