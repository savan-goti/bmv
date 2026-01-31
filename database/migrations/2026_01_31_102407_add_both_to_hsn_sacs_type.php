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
        // Modify the type enum to include 'both'
        DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type ENUM('hsn', 'sac', 'both') DEFAULT 'hsn'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update any 'both' values to 'hsn' to avoid data loss
        DB::table('hsn_sacs')->where('type', 'both')->update(['type' => 'hsn']);
        
        // Revert the type enum to only hsn and sac
        DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type ENUM('hsn', 'sac') DEFAULT 'hsn'");
    }
};
