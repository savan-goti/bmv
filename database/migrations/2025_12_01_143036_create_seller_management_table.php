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
        Schema::create('seller_management', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->string('created_by_type'); // Polymorphic type (Admin, Owner, Staff, Seller)
            $table->unsignedBigInteger('created_by_id'); // Polymorphic ID
            $table->enum('action', ['created', 'approved', 'rejected', 'suspended'])->default('created');
            $table->text('notes')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            
            // Index for polymorphic relationship
            $table->index(['created_by_type', 'created_by_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_management');
    }
};
