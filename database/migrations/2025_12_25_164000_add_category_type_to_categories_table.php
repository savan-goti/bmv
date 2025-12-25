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
        Schema::table('categories', function (Blueprint $table) {
            // Add category_type column after id
            if (!Schema::hasColumn('categories', 'category_type')) {
                $table->enum('category_type', ['product', 'service', 'digital', 'mixed'])->default('product')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop category_type column
            if (Schema::hasColumn('categories', 'category_type')) {
                $table->dropColumn('category_type');
            }
        });
    }
};
