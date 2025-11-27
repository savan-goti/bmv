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
        Schema::create('owners', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY

            $table->string('session_id', 100)->nullable();
            $table->string('bar_code', 100)->unique()->nullable();
            $table->string('token', 255)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->string('full_name', 150);
            $table->string('username', 100)->unique();
            $table->string('email', 150)->unique();
            $table->string('phone_code', 10)->default('+91');
            $table->string('phone', 15)->unique()->nullable();
            $table->string('otp', 6)->nullable();
            $table->string('password', 255);

            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();

            $table->string('language_preference', 50)->default('en');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])
                  ->default('single');

            $table->enum('status', [
                'active','inactive','blocked','unblocked',
                'pending','approved','rejected','suspended'
            ])->default('pending');

            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip', 50)->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->dateTime('email_verified_at')->nullable();

            $table->unsignedBigInteger('created_by')->nullable()->comment('self or admin id');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('auth admin id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
