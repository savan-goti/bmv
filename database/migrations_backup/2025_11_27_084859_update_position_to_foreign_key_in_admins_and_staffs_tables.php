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
        // Update admins table
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('education')->constrained('job_positions')->nullOnDelete();
        });

        // Update staffs table
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn('position');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->foreignId('position_id')->nullable()->after('education')->constrained('job_positions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse admins table changes
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('position')->nullable()->after('education');
        });

        // Reverse staffs table changes
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->string('position')->nullable()->after('education');
        });
    }
};
