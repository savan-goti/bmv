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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            
            // Profile Information
            $table->string('profile_image')->nullable();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('password');
            
            // Role & Position
            $table->string('role')->default('staff');
            $table->json('permissions')->nullable();
            $table->string('education')->nullable();
            $table->foreignId('position')->nullable()->constrained('job_positions')->onDelete('set null');
            $table->text('address')->nullable();
            
            // Status & Security
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
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
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            
            // Resignation
            $table->date('resignation_date')->nullable();
            $table->text('purpose')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
