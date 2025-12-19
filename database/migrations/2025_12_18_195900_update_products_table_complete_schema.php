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
        Schema::table('products', function (Blueprint $table) {
            // Add collection_id if it doesn't exist
            if (!Schema::hasColumn('products', 'collection_id')) {
                $table->foreignId('collection_id')->nullable()->after('brand_id')->constrained('collections')->onDelete('set null');
            }
            
            // Drop published_at if it exists (legacy field)
            if (Schema::hasColumn('products', 'published_at')) {
                $table->dropColumn('published_at');
            }
            
            // Drop added_by_type if it exists (we'll use added_by_role instead)
            if (Schema::hasColumn('products', 'added_by_type')) {
                $table->dropColumn('added_by_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Restore published_at
            $table->timestamp('published_at')->nullable();
            
            // Restore added_by_type
            $table->string('added_by_type')->nullable();
            
            // Drop collection_id
            if (Schema::hasColumn('products', 'collection_id')) {
                $table->dropForeign(['collection_id']);
                $table->dropColumn('collection_id');
            }
        });
    }
};
