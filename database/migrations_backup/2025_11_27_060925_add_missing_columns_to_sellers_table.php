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
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('business_logo')->nullable()->after('id');
            $table->string('business_type')->nullable()->after('business_name');
            $table->unsignedBigInteger('category_id')->nullable()->after('business_type');
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
            $table->string('username')->unique()->nullable()->after('owner_name');
            $table->string('bar_code')->nullable()->after('username');
            $table->string('store_link')->unique()->nullable()->after('bar_code');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('country_code')->nullable()->after('gender');
            $table->string('phone_otp')->nullable()->after('phone');
            $table->boolean('otp_validate')->default(false)->after('phone_otp');
            $table->string('aadhar_number')->nullable()->after('otp_validate');
            $table->string('aadhaar_front')->nullable()->after('aadhar_number');
            $table->string('aadhaar_back')->nullable()->after('aadhaar_front');
            $table->boolean('aadhaar_verified')->default(false)->after('aadhaar_back');
            $table->string('pancard_number')->nullable()->after('aadhaar_verified');
            $table->string('pancard_image')->nullable()->after('pancard_number');
            $table->boolean('pancard_verified')->default(false)->after('pancard_image');
            $table->string('gst_number')->nullable()->after('pancard_verified');
            $table->string('gst_certificate_image')->nullable()->after('gst_number');
            $table->boolean('gst_verified')->default(false)->after('gst_certificate_image');
            $table->string('account_holder_name')->nullable()->after('gst_verified');
            $table->string('ifsc_code')->nullable()->after('account_holder_name');
            $table->string('bank_account_number')->nullable()->after('ifsc_code');
            $table->string('cancel_check_image')->nullable()->after('bank_account_number');
            $table->string('kyc_status')->default('pending')->after('cancel_check_image');
            $table->string('kyc_document')->nullable()->after('kyc_status');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_document');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->nullable()->after('state');
            $table->string('pincode')->nullable()->after('country');
            $table->json('social_links')->nullable()->after('pincode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'business_logo',
                'business_type',
                'category_id',
                'sub_category_id',
                'username',
                'bar_code',
                'store_link',
                'date_of_birth',
                'gender',
                'country_code',
                'phone_otp',
                'otp_validate',
                'aadhar_number',
                'aadhaar_front',
                'aadhaar_back',
                'aadhaar_verified',
                'pancard_number',
                'pancard_image',
                'pancard_verified',
                'gst_number',
                'gst_certificate_image',
                'gst_verified',
                'account_holder_name',
                'ifsc_code',
                'bank_account_number',
                'cancel_check_image',
                'kyc_status',
                'kyc_document',
                'kyc_verified_at',
                'city',
                'state',
                'country',
                'pincode',
                'social_links',
            ]);
        });
    }
};
