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
        // Units Master Table
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->enum('type', ['product', 'service'])->default('product');
            $table->string('status')->default(\App\Enums\Status::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });

        // HSN / SAC Master Table
        Schema::create('hsn_sacs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'service'])->default('product'); // product -> HSN, service -> SAC
            $table->string('status')->default(\App\Enums\Status::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });

        // Colors Master Table
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color_code')->nullable();
            $table->string('status')->default(\App\Enums\Status::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });

        // Sizes Master Table
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default(\App\Enums\Status::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });

        // Suppliers Master Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default(\App\Enums\Status::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('hsn_sacs');
        Schema::dropIfExists('units');
    }
};
