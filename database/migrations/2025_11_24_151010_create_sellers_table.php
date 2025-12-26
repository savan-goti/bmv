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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            
            // Business Information
            $table->string('business_logo')->nullable();
            $table->string('avatar')->nullable();
            $table->string('business_name');
            $table->string('business_type')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            
            // Owner Information
            $table->string('owner_name');
            $table->string('username')->unique()->nullable();
            $table->string('bar_code')->nullable();
            $table->string('store_link')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();
            $table->text('google_refresh_token')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->unique();
            $table->string('phone_otp')->nullable();
            $table->boolean('otp_validate')->default(false);
            
            // KYC Documents - Aadhaar
            $table->string('aadhar_number')->nullable();
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();
            $table->boolean('aadhaar_verified')->default(false);
            
            // KYC Documents - PAN Card
            $table->string('pancard_number')->nullable();
            $table->string('pancard_image')->nullable();
            $table->boolean('pancard_verified')->default(false);
            
            // GST Information
            $table->string('gst_number')->nullable();
            $table->string('gst_certificate_image')->nullable();
            $table->boolean('gst_verified')->default(false);
            $table->string('gst_vat')->nullable();
            
            // Bank Account Information
            $table->string('account_holder_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('cancel_check_image')->nullable();
            
            // KYC Status
            $table->string('kyc_status')->default('pending');
            $table->string('kyc_document')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();
            
            // Address Information
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->json('social_links')->nullable();
            
            // Authentication
            $table->string('password');
            
            // Status & Approval
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by_type')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            
            // Security
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            
            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // Login Email Verification
            $table->string('login_auth_method', 50)->default('email_verification')->comment('email_verification or two_factor');
            $table->boolean('login_email_verification_enabled')->default(false);
            $table->string('login_verification_code', 10)->nullable();
            $table->timestamp('login_verification_code_expires_at')->nullable();
            
            // Email Verification & Remember Token
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['approved_by_type', 'approved_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
