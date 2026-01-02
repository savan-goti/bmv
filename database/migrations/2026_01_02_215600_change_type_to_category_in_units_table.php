<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop the old 'type' column
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // 2. Add new 'category' enum column for Product/Service
        Schema::table('units', function (Blueprint $table) {
            $table->enum('category', ['product', 'service'])->default('product')->after('short_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the category column
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        // Restore the old type column
        Schema::table('units', function (Blueprint $table) {
            $table->enum('type', ['unit', 'weight', 'dimension'])->default('unit')->after('short_name');
        });
    }
};
