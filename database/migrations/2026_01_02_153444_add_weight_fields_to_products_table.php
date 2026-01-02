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
            // Add weight fields in Variations section
            $table->decimal('product_weight', 8, 2)->nullable()->after('has_variation');
            $table->decimal('shipping_weight', 8, 2)->nullable()->after('product_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_weight', 'shipping_weight']);
        });
    }
};
