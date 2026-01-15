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
        Schema::table('branch_positions', function (Blueprint $table) {
            // Make positionable columns nullable since they're no longer required
            $table->string('positionable_type')->nullable()->change();
            $table->unsignedBigInteger('positionable_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branch_positions', function (Blueprint $table) {
            // Revert back to non-nullable
            $table->string('positionable_type')->nullable(false)->change();
            $table->unsignedBigInteger('positionable_id')->nullable(false)->change();
        });
    }
};
