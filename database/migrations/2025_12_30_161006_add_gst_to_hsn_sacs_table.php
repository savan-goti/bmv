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
        Schema::table('hsn_sacs', function (Blueprint $table) {
            $table->decimal('gst', 5, 2)->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsn_sacs', function (Blueprint $table) {
            $table->dropColumn('gst');
        });
    }
};
