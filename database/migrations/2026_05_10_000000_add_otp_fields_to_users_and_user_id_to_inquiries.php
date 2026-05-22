<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable()->unique()->after('email');
            $table->string('mobile_otp_code')->nullable()->after('email_verified_at');
            $table->timestamp('mobile_otp_expires_at')->nullable()->after('mobile_otp_code');
            $table->timestamp('mobile_otp_sent_at')->nullable()->after('mobile_otp_expires_at');
            $table->timestamp('mobile_verified_at')->nullable()->after('mobile_otp_sent_at');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'mobile',
                'mobile_otp_code',
                'mobile_otp_expires_at',
                'mobile_otp_sent_at',
                'mobile_verified_at',
            ]);
        });
    }
};
