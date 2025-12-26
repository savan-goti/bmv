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
        Schema::table('sellers', function (Blueprint $table) {
            // Add polymorphic columns for tracking who approved the seller
            $table->string('approved_by_type')->nullable()->after('approved_at');
            $table->unsignedBigInteger('approved_by_id')->nullable()->after('approved_by_type');
            
            // Add index for polymorphic relationship
            $table->index(['approved_by_type', 'approved_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropIndex(['approved_by_type', 'approved_by_id']);
            $table->dropColumn(['approved_by_type', 'approved_by_id']);
        });
    }
};
