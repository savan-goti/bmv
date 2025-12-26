<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class VerifyCompressedMigrationsSeeder extends Seeder
{
    /**
     * Verify that the current database schema matches the compressed migrations.
     */
    public function run(): void
    {
        $this->info('Starting Database Schema Verification...');
        $this->info('==========================================');
        
        $tables = [
            'admins' => [
                'id', 'owner_id', 'profile_image', 'name', 'father_name', 'email', 
                'google_id', 'google_token', 'google_refresh_token', 'avatar', 
                'date_of_birth', 'gender', 'phone', 'password', 'role', 'education', 
                'position', 'address', 'status', 'last_login_at', 'last_login_ip', 
                'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes', 
                'two_factor_confirmed_at', 'login_email_verification_enabled', 
                'login_verification_code', 'login_verification_code_expires_at', 
                'email_verified_at', 'remember_token', 'resignation_date', 'purpose', 
                'created_at', 'updated_at', 'deleted_at'
            ],
            'owners' => [
                'id', 'name', 'email', 'google_id', 'google_token', 'google_refresh_token', 
                'avatar', 'phone', 'password', 'profile_image', 'address', 'city', 'state', 
                'country', 'postal_code', 'business_name', 'business_type', 
                'business_description', 'tax_id', 'registration_number', 'status', 
                'last_login_at', 'last_login_ip', 'two_factor_enabled', 'two_factor_secret', 
                'two_factor_recovery_codes', 'two_factor_confirmed_at', 
                'login_email_verification_enabled', 'login_verification_code', 
                'login_verification_code_expires_at', 'email_verified_at', 'remember_token', 
                'created_at', 'updated_at', 'deleted_at'
            ],
            'staffs' => [
                'id', 'admin_id', 'profile_image', 'name', 'father_name', 'email', 
                'google_id', 'google_token', 'google_refresh_token', 'avatar', 'phone', 
                'date_of_birth', 'gender', 'password', 'role', 'permissions', 'education', 
                'position', 'address', 'status', 'last_login_at', 'last_login_ip', 
                'two_factor_enabled', 'two_factor_secret', 'two_factor_recovery_codes', 
                'two_factor_confirmed_at', 'login_email_verification_enabled', 
                'login_verification_code', 'login_verification_code_expires_at', 
                'email_verified_at', 'remember_token', 'resignation_date', 'purpose', 
                'created_at', 'updated_at', 'deleted_at'
            ],
            'sellers' => [
                'id', 'business_logo', 'avatar', 'business_name', 'business_type', 
                'category_id', 'sub_category_id', 'owner_name', 'username', 'bar_code', 
                'store_link', 'email', 'google_id', 'google_token', 'google_refresh_token', 
                'date_of_birth', 'gender', 'country_code', 'phone', 'phone_otp', 
                'otp_validate', 'aadhar_number', 'aadhaar_front', 'aadhaar_back', 
                'aadhaar_verified', 'pancard_number', 'pancard_image', 'pancard_verified', 
                'gst_number', 'gst_certificate_image', 'gst_verified', 'gst_vat', 
                'account_holder_name', 'ifsc_code', 'bank_account_number', 
                'cancel_check_image', 'kyc_status', 'kyc_document', 'kyc_verified_at', 
                'address', 'city', 'state', 'country', 'pincode', 'social_links', 
                'password', 'status', 'is_approved', 'approved_at', 'approved_by_type', 
                'approved_by_id', 'last_login_at', 'last_login_ip', 'two_factor_enabled', 
                'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 
                'login_auth_method', 'login_email_verification_enabled', 
                'login_verification_code', 'login_verification_code_expires_at', 
                'email_verified_at', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
            ],
            'customers' => [
                'id', 'profile_image', 'canonical', 'username', 'name', 'email', 'phone', 
                'country_code', 'phone_otp', 'otp_expired_at', 'phone_validate', 'gender', 
                'dob', 'address', 'latitude', 'longitude', 'city', 'state', 'country', 
                'pincode', 'social_links', 'status', 'password', 'last_login_at', 
                'last_login_ip', 'two_factor_enabled', 'email_verified_at', 'remember_token', 
                'created_at', 'updated_at', 'deleted_at'
            ],
            'categories' => [
                'id', 'category_type', 'name', 'slug', 'image', 'status', 'deleted_at', 
                'created_at', 'updated_at'
            ],
            'products' => [
                'id', 'product_type', 'category_id', 'sub_category_id', 'child_category_id', 
                'brand_id', 'collection_id', 'product_name', 'slug', 'sku', 'barcode', 
                'short_description', 'full_description', 'owner_id', 'seller_id', 'branch_id', 
                'added_by_role', 'added_by_user_id', 'approved_by_admin_id', 'approved_at', 
                'purchase_price', 'original_price', 'sell_price', 'discount_type', 
                'discount_value', 'gst_rate', 'tax_included', 'commission_type', 
                'commission_value', 'stock_type', 'total_stock', 'reserved_stock', 
                'available_stock', 'low_stock_alert', 'warehouse_location', 'has_variation', 
                'thumbnail_image', 'video_url', 'image_alt_text', 'weight', 'length', 
                'width', 'height', 'shipping_class', 'free_shipping', 'cod_available', 
                'product_status', 'is_active', 'is_featured', 'is_returnable', 'return_days', 
                'meta_title', 'meta_description', 'meta_keywords', 'search_tags', 
                'schema_markup', 'deleted_at', 'created_at', 'updated_at'
            ],
        ];

        $allPassed = true;

        foreach ($tables as $tableName => $expectedColumns) {
            $this->info("\nChecking table: {$tableName}");
            
            if (!Schema::hasTable($tableName)) {
                $this->error("  ✗ Table does not exist!");
                $allPassed = false;
                continue;
            }

            $actualColumns = Schema::getColumnListing($tableName);
            $missing = array_diff($expectedColumns, $actualColumns);
            $extra = array_diff($actualColumns, $expectedColumns);

            if (empty($missing) && empty($extra)) {
                $this->info("  ✓ All columns match! (" . count($expectedColumns) . " columns)");
            } else {
                $allPassed = false;
                
                if (!empty($missing)) {
                    $this->error("  ✗ Missing columns: " . implode(', ', $missing));
                }
                
                if (!empty($extra)) {
                    $this->warn("  ⚠ Extra columns: " . implode(', ', $extra));
                }
            }
        }

        $this->info("\n==========================================");
        if ($allPassed) {
            $this->info("✓ ALL TABLES VERIFIED SUCCESSFULLY!");
            $this->info("Your database schema matches the compressed migrations.");
        } else {
            $this->warn("⚠ Some discrepancies found. Review the output above.");
        }
    }

    private function info($message)
    {
        echo "\033[32m{$message}\033[0m\n";
    }

    private function error($message)
    {
        echo "\033[31m{$message}\033[0m\n";
    }

    private function warn($message)
    {
        echo "\033[33m{$message}\033[0m\n";
    }
}
