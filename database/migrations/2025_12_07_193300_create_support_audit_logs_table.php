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
        Schema::create('support_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_team_member_id')->nullable()->constrained('support_team_members')->nullOnDelete();
            $table->foreignId('performed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('performed_by_type')->nullable(); // Admin, Owner, Staff
            $table->string('action'); // created, updated, deleted, status_changed, etc.
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('support_team_member_id');
            $table->index(['performed_by_id', 'performed_by_type']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_audit_logs');
    }
};
