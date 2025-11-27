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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('password'); // Staff might need login
            $table->string('assigned_role'); // Role name or ID
            $table->json('permissions')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->dateTime('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
