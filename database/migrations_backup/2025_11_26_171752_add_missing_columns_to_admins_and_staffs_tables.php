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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('owner_id');
            $table->string('father_name')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->string('education')->nullable()->after('role');
            $table->string('position')->nullable()->after('education');
            $table->text('address')->nullable()->after('position');
            $table->date('resignation_date')->nullable()->after('updated_at');
            $table->text('purpose')->nullable()->after('resignation_date');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('admin_id');
            $table->string('father_name')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->string('education')->nullable()->after('permissions');
            $table->string('position')->nullable()->after('education');
            $table->text('address')->nullable()->after('position');
            $table->date('resignation_date')->nullable()->after('updated_at');
            $table->text('purpose')->nullable()->after('resignation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'father_name',
                'date_of_birth',
                'gender',
                'education',
                'position',
                'address',
                'resignation_date',
                'purpose',
            ]);
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn([
                'profile_image',
                'father_name',
                'date_of_birth',
                'gender',
                'education',
                'position',
                'address',
                'resignation_date',
                'purpose',
            ]);
        });
    }
};
