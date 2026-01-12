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
        Schema::table('branches', function (Blueprint $table) {
            $table->enum('type', ['product', 'service'])->default('product')->after('code');
            $table->json('social_media')->nullable()->after('manager_phone');
            $table->string('username')->unique()->nullable()->after('code');
            $table->string('branch_link')->unique()->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn(['type', 'social_media', 'username', 'branch_link']);
        });
    }
};
