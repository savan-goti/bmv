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
        Schema::table('owners', function (Blueprint $table) {
            $table->string('login_auth_method', 50)->default('email_verification')->after('two_factor_confirmed_at')->comment('email_verification or two_factor');
            $table->boolean('login_email_verification_enabled')->default(false)->after('login_auth_method');
            $table->string('login_verification_code', 10)->nullable()->after('login_email_verification_enabled');
            $table->timestamp('login_verification_code_expires_at')->nullable()->after('login_verification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn([
                'login_auth_method',
                'login_email_verification_enabled',
                'login_verification_code',
                'login_verification_code_expires_at',
            ]);
        });
    }
};
