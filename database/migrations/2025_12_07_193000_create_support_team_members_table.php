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
        Schema::create('support_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('created_by_type')->nullable(); // Admin, Owner, Staff
            $table->string('profile_image')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'staff', 'seller', 'customer'])->default('staff');
            $table->json('departments')->nullable(); // Array of department IDs or names
            $table->json('default_queues')->nullable(); // Array of queue IDs or names
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->enum('notification_method', ['email', 'in_app', 'both'])->default('both');
            
            // Statistics columns (can be updated periodically)
            $table->integer('tickets_assigned')->default(0);
            $table->integer('open_tickets')->default(0);
            $table->decimal('avg_response_time', 8, 2)->nullable(); // in minutes
            
            // Additional fields
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('role');
            $table->index('status');
            $table->index(['created_by_id', 'created_by_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_team_members');
    }
};
