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
        // Drop the existing type column and recreate with new enum values
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->enum('type', ['company', 'branches'])->default('branches')->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to old enum values
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->enum('type', ['product', 'service'])->default('product')->after('code');
        });
    }
};
