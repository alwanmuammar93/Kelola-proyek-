<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * CATATAN: Kolom 'name' dan 'profile_photo' SUDAH ADA di database,
     * jadi TIDAK ditambahkan lagi untuk menghindari error duplicate column.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Contact Information
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            
            // Settings & Preferences
            $table->string('theme_preference', 10)->default('light')->after('role');
            $table->boolean('notification_email')->default(true)->after('theme_preference');
            $table->boolean('notification_system')->default(true)->after('notification_email');
            
            // Two-Factor Authentication
            $table->boolean('two_factor_enabled')->default(false)->after('notification_system');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            
            // Login Tracking
            $table->timestamp('last_login_at')->nullable()->after('two_factor_secret');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'theme_preference',
                'notification_email',
                'notification_system',
                'two_factor_enabled',
                'two_factor_secret',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};