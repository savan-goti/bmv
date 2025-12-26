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
        Schema::table('customers', function (Blueprint $table) {
            // Profile and Identity
            $table->string('profile_image')->nullable()->after('id');
            $table->string('canonical')->nullable()->after('profile_image');
            $table->string('username')->unique()->nullable()->after('canonical');
            
            // Phone OTP and Validation
            $table->string('country_code')->nullable()->after('phone');
            $table->string('phone_otp')->nullable()->after('country_code');
            $table->dateTime('otp_expired_at')->nullable()->after('phone_otp');
            $table->boolean('phone_validate')->default(false)->after('otp_expired_at');
            
            // Location Data
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('city')->nullable()->after('longitude');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('pincode')->nullable()->after('country');
            
            // Social Links (JSON)
            $table->json('social_links')->nullable()->after('pincode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'canonical',
                'username',
                'country_code',
                'phone_otp',
                'otp_expired_at',
                'phone_validate',
                'latitude',
                'longitude',
                'city',
                'state',
                'country',
                'pincode',
                'social_links'
            ]);
        });
    }
};
