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
        // Note: MySQL doesn't support partial indexes like PostgreSQL
        // The validation is handled at the application level in the controller
        // This migration is kept for documentation purposes
        
        // If you need database-level enforcement, consider:
        // 1. Using a trigger
        // 2. Using a generated column with unique constraint
        // 3. Application-level validation (current approach)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to revert
    }
};
