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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Product Type
            $table->enum('product_type', ['simple', 'variable', 'digital', 'service'])->default('simple');
            
            // Category Relations
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->onDelete('set null');
            $table->foreignId('child_category_id')->nullable()->constrained('child_categories')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('collection_id')->nullable()->constrained('collections')->onDelete('set null');
            
            // Basic Information
            $table->string('product_name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->nullable();
            $table->string('barcode', 100)->nullable();
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            
            // Ownership & Audit
            $table->foreignId('owner_id')->nullable()->constrained('owners')->onDelete('cascade');
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained('branches')->onDelete('set null');
            $table->enum('added_by_role', ['owner', 'admin', 'staff', 'seller'])->nullable();
            $table->integer('added_by_user_id');
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Pricing
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('original_price', 10, 2)->nullable();
            $table->decimal('sell_price', 10, 2);
            $table->enum('discount_type', ['flat', 'percentage'])->default('flat');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->boolean('tax_included')->default(false);
            $table->enum('commission_type', ['flat', 'percentage'])->default('percentage');
            $table->decimal('commission_value', 10, 2)->default(0);
            
            // Inventory
            $table->enum('stock_type', ['limited', 'unlimited'])->default('limited');
            $table->integer('total_stock');
            $table->integer('reserved_stock')->default(0);
            $table->integer('available_stock')->default(0);
            $table->integer('low_stock_alert')->default(10);
            $table->string('warehouse_location', 100)->nullable();
            
            // Variations
            $table->boolean('has_variation')->default(false);
            
            // Media
            $table->string('thumbnail_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('image_alt_text')->nullable();
            
            // Shipping
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->enum('shipping_class', ['normal', 'heavy'])->default('normal');
            $table->boolean('free_shipping')->default(false);
            $table->boolean('cod_available')->default(true);
            
            // Status & Workflow
            $table->enum('product_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->string('is_active')->default(\App\Enums\Status::Active->value);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_returnable')->default(true);
            $table->integer('return_days')->default(7);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('search_tags')->nullable();
            $table->json('schema_markup')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
