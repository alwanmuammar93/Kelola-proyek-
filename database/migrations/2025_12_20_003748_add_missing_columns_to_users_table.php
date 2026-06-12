<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration ini menambahkan HANYA kolom yang BENAR-BENAR tidak ada di database.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            
            // Kolom name (untuk nama lengkap)
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('username');
            }
            
            // Kolom profile photo
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('role');
            }
            
            // Kolom two-factor secret (two_factor_enabled sudah ada)
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            }
            
            // Kolom last login tracking
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable();
            }
            
            // Remember token (untuk "Remember Me" di login)
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            
            // Email verification
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop hanya kolom yang ditambahkan (cek dulu apakah ada)
            $columnsToDrop = [];
            
            if (Schema::hasColumn('users', 'name')) {
                $columnsToDrop[] = 'name';
            }
            if (Schema::hasColumn('users', 'profile_photo')) {
                $columnsToDrop[] = 'profile_photo';
            }
            if (Schema::hasColumn('users', 'two_factor_secret')) {
                $columnsToDrop[] = 'two_factor_secret';
            }
            if (Schema::hasColumn('users', 'last_login_at')) {
                $columnsToDrop[] = 'last_login_at';
            }
            if (Schema::hasColumn('users', 'last_login_ip')) {
                $columnsToDrop[] = 'last_login_ip';
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $columnsToDrop[] = 'remember_token';
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $columnsToDrop[] = 'email_verified_at';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};