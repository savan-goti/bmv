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
        // Modify the category enum to include 'both'
        DB::statement("ALTER TABLE units MODIFY COLUMN category ENUM('product', 'service', 'both') DEFAULT 'product'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'both' values to 'product' to avoid data loss
        DB::table('units')->where('category', 'both')->update(['category' => 'product']);
        
        // Revert the category enum to only product and service
        DB::statement("ALTER TABLE units MODIFY COLUMN category ENUM('product', 'service') DEFAULT 'product'");
    }
};
