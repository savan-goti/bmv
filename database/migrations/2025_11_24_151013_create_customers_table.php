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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // Profile Information
            $table->string('profile_image')->nullable();
            $table->string('canonical')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone_otp')->nullable();
            $table->dateTime('otp_expired_at')->nullable();
            $table->boolean('phone_validate')->default(false);
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            
            // Address & Location
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('pincode')->nullable();
            $table->json('social_links')->nullable();
            
            // Status & Security
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->string('password');
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->dateTime('email_verified_at')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
