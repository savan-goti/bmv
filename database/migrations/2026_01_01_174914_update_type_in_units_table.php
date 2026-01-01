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
        // 1. Expand the enum to include new values
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('product', 'service', 'unit', 'weight', 'dimension') DEFAULT 'unit'");

        // 2. Update existing data to new values
        DB::table('units')->whereNotIn('type', ['unit', 'weight', 'dimension'])->update(['type' => 'unit']);

        // 3. Restrict enum to only the new values
        DB::statement("ALTER TABLE units MODIFY COLUMN type ENUM('unit', 'weight', 'dimension') DEFAULT 'unit'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->enum('type', ['product', 'service'])->default('product')->change();
        });
    }
};
