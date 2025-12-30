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
        Schema::table('hsn_sacs', function (Blueprint $table) {
            // First convert to string to allow any value
            DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type VARCHAR(20)");
            
            // Update existing values
            DB::table('hsn_sacs')->where('type', 'product')->update(['type' => 'hsn']);
            DB::table('hsn_sacs')->where('type', 'service')->update(['type' => 'sac']);
            
            // Finally set back to the new enum
            DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type ENUM('hsn', 'sac') NOT NULL DEFAULT 'hsn'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsn_sacs', function (Blueprint $table) {
            DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type VARCHAR(20)");
            
            DB::table('hsn_sacs')->where('type', 'hsn')->update(['type' => 'product']);
            DB::table('hsn_sacs')->where('type', 'sac')->update(['type' => 'service']);
            
            DB::statement("ALTER TABLE hsn_sacs MODIFY COLUMN type ENUM('product', 'service') NOT NULL DEFAULT 'product'");
        });
    }
};
