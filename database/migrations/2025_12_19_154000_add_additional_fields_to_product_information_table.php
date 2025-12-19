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
        Schema::table('product_information', function (Blueprint $table) {
            // Product Type & Identification
            $table->string('product_type')->default('simple')->after('product_id');
            $table->string('sku')->nullable()->after('product_type');
            $table->string('barcode')->nullable()->after('sku');
            
            // Pricing
            $table->decimal('purchase_price', 10, 2)->default(0)->after('barcode');
            $table->decimal('original_price', 10, 2)->default(0)->after('purchase_price');
            $table->string('discount_type')->default('flat')->after('original_price');
            $table->decimal('gst_rate', 5, 2)->default(0)->after('discount_type');
            $table->boolean('tax_included')->default(false)->after('gst_rate');
            
            // Commission
            $table->string('commission_type')->default('percentage')->after('tax_included');
            $table->decimal('commission_value', 10, 2)->default(0)->after('commission_type');
            
            // Inventory
            $table->string('stock_type')->default('limited')->after('commission_value');
            $table->integer('low_stock_alert')->default(10)->after('stock_type');
            $table->string('warehouse_location')->nullable()->after('low_stock_alert');
            $table->boolean('has_variation')->default(false)->after('warehouse_location');
            
            // Media
            $table->string('image_alt_text')->nullable()->after('has_variation');
            $table->string('video_url')->nullable()->after('image_alt_text');
            
            // Shipping
            $table->decimal('weight', 10, 2)->default(0)->after('video_url');
            $table->string('shipping_class')->default('normal')->after('weight');
            $table->decimal('length', 10, 2)->default(0)->after('shipping_class');
            $table->decimal('width', 10, 2)->default(0)->after('length');
            $table->decimal('height', 10, 2)->default(0)->after('width');
            $table->boolean('free_shipping')->default(false)->after('height');
            $table->boolean('cod_available')->default(true)->after('free_shipping');
            
            // SEO
            $table->string('search_tags')->nullable()->after('meta_keywords');
            
            // Product Status
            $table->string('product_status')->default('draft')->after('search_tags');
            $table->boolean('is_featured')->default(false)->after('product_status');
            $table->boolean('is_returnable')->default(true)->after('is_featured');
            $table->integer('return_days')->default(7)->after('is_returnable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_information', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'sku',
                'barcode',
                'purchase_price',
                'original_price',
                'discount_type',
                'gst_rate',
                'tax_included',
                'commission_type',
                'commission_value',
                'stock_type',
                'low_stock_alert',
                'warehouse_location',
                'has_variation',
                'image_alt_text',
                'video_url',
                'weight',
                'shipping_class',
                'length',
                'width',
                'height',
                'free_shipping',
                'cod_available',
                'search_tags',
                'product_status',
                'is_featured',
                'is_returnable',
                'return_days',
            ]);
        });
    }
};
