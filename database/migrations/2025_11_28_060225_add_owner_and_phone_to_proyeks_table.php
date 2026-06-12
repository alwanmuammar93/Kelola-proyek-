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
        Schema::table('proyeks', function (Blueprint $table) {
            $table->string('nama_owner')->nullable()->after('nama_proyek');
            $table->string('nomor_hp')->nullable()->after('nama_owner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyeks', function (Blueprint $table) {
            $table->dropColumn(['nama_owner', 'nomor_hp']);
        });
    }
};  // ← PERHATIKAN: ADA TITIK KOMA DI SINI!