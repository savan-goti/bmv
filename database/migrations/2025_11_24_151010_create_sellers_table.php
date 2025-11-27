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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('gst_vat')->nullable();
            $table->text('address')->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('sellers');
    }
};
