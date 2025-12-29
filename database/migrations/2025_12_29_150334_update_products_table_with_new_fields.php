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
            // Basic Info updates
            $table->string('warranty_value')->nullable()->after('barcode');
            $table->enum('warranty_unit', ['months', 'years'])->nullable()->after('warranty_value');

            // Tax & Units
            $table->foreignId('hsn_sac_id')->nullable()->after('commission_value')->constrained('hsn_sacs')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->after('hsn_sac_id')->constrained('units')->onDelete('set null');

            // Variations
            $table->foreignId('color_id')->nullable()->after('has_variation')->constrained('colors')->onDelete('set null');
            $table->foreignId('size_id')->nullable()->after('color_id')->constrained('sizes')->onDelete('set null');

            // Other Details
            $table->foreignId('supplier_id')->nullable()->after('size_id')->constrained('suppliers')->onDelete('set null');
            $table->string('packer_name')->nullable()->after('supplier_id');
            $table->text('packer_address')->nullable()->after('packer_name');
            $table->string('packer_gst')->nullable()->after('packer_address');
            
            // Media
            $table->json('product_videos')->nullable()->after('video_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['hsn_sac_id']);
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'warranty_value',
                'warranty_unit',
                'hsn_sac_id',
                'unit_id',
                'color_id',
                'size_id',
                'supplier_id',
                'packer_name',
                'packer_address',
                'packer_gst',
                'product_videos'
            ]);
        });
    }
};
