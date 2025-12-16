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
        Schema::table('products', function (Blueprint $table) {
            // Basic Info (rename and add columns)
            $table->enum('product_type', ['simple', 'variable', 'digital', 'service'])->default('simple')->after('id');
            $table->renameColumn('name', 'product_name');
            $table->string('sku', 100)->unique()->nullable()->after('slug');
            $table->string('barcode', 100)->nullable()->after('sku');
            $table->text('short_description')->nullable()->after('barcode');
            $table->longText('full_description')->nullable()->after('short_description');

            // Ownership & Audit
            $table->foreignId('owner_id')->nullable()->after('full_description')->constrained('owners')->onDelete('cascade');
            $table->foreignId('seller_id')->nullable()->after('owner_id')->constrained('sellers')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->after('seller_id')->constrained('branches')->onDelete('set null');
            $table->enum('added_by_role', ['owner', 'admin', 'staff', 'seller'])->nullable()->after('branch_id');
            $table->renameColumn('added_by_id', 'added_by_user_id');
            $table->foreignId('approved_by_admin_id')->nullable()->after('added_by_type')->constrained('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('approved_by_admin_id');

            // Category & Brand (collection_id will be handled via pivot table)
            // category_id, sub_category_id, child_category_id, brand_id already exist

            // Pricing (rename and add columns)
            $table->decimal('purchase_price', 10, 2)->nullable()->after('brand_id');
            $table->decimal('original_price', 10, 2)->nullable()->after('purchase_price');
            $table->renameColumn('price', 'sell_price');
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat')->after('sell_price');
            $table->renameColumn('discount', 'discount_value');
            $table->decimal('gst_rate', 5, 2)->default(0)->after('discount_value');
            $table->boolean('tax_included')->default(false)->after('gst_rate');
            $table->enum('commission_type', ['flat', 'percentage'])->default('percentage')->after('tax_included');
            $table->decimal('commission_value', 10, 2)->default(0)->after('commission_type');

            // Inventory (rename and add columns)
            $table->enum('stock_type', ['limited', 'unlimited'])->default('limited')->after('commission_value');
            $table->renameColumn('quantity', 'total_stock');
            $table->integer('reserved_stock')->default(0)->after('total_stock');
            $table->integer('available_stock')->default(0)->after('reserved_stock');
            $table->integer('low_stock_alert')->default(10)->after('available_stock');
            $table->string('warehouse_location', 100)->nullable()->after('low_stock_alert');

            // Variations
            $table->boolean('has_variation')->default(false)->after('warehouse_location');

            // Media (rename and add columns)
            $table->renameColumn('image', 'thumbnail_image');
            $table->string('video_url')->nullable()->after('thumbnail_image');
            $table->string('image_alt_text')->nullable()->after('video_url');

            // Shipping
            $table->decimal('weight', 8, 2)->nullable()->after('image_alt_text');
            $table->decimal('length', 8, 2)->nullable()->after('weight');
            $table->decimal('width', 8, 2)->nullable()->after('length');
            $table->decimal('height', 8, 2)->nullable()->after('width');
            $table->enum('shipping_class', ['normal', 'heavy'])->default('normal')->after('height');
            $table->boolean('free_shipping')->default(false)->after('shipping_class');
            $table->boolean('cod_available')->default(true)->after('free_shipping');

            // Status & Workflow (rename and add columns)
            $table->enum('product_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->after('cod_available');
            $table->renameColumn('status', 'is_active');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_returnable')->default(true)->after('is_featured');
            $table->integer('return_days')->default(7)->after('is_returnable');

            // SEO
            $table->string('meta_title')->nullable()->after('return_days');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->text('search_tags')->nullable()->after('meta_keywords');
            $table->json('schema_markup')->nullable()->after('search_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop new columns in reverse order
            $table->dropColumn([
                'schema_markup',
                'search_tags',
                'meta_keywords',
                'meta_description',
                'meta_title',
                'return_days',
                'is_returnable',
                'is_featured',
                'product_status',
                'cod_available',
                'free_shipping',
                'shipping_class',
                'height',
                'width',
                'length',
                'weight',
                'image_alt_text',
                'video_url',
                'has_variation',
                'warehouse_location',
                'low_stock_alert',
                'available_stock',
                'reserved_stock',
                'stock_type',
                'commission_value',
                'commission_type',
                'tax_included',
                'gst_rate',
                'discount_type',
                'original_price',
                'purchase_price',
                'approved_at',
                'approved_by_admin_id',
                'added_by_role',
                'branch_id',
                'seller_id',
                'owner_id',
                'full_description',
                'short_description',
                'barcode',
                'sku',
                'product_type',
            ]);

            // Rename columns back
            $table->renameColumn('product_name', 'name');
            $table->renameColumn('sell_price', 'price');
            $table->renameColumn('discount_value', 'discount');
            $table->renameColumn('total_stock', 'quantity');
            $table->renameColumn('thumbnail_image', 'image');
            $table->renameColumn('is_active', 'status');
            $table->renameColumn('added_by_user_id', 'added_by_id');
        });
    }
};
