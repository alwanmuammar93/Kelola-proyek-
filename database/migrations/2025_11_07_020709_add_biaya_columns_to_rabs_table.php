<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rabs', function (Blueprint $table) {
            // Tambahkan kolom biaya_material jika belum ada
            if (!Schema::hasColumn('rabs', 'biaya_material')) {
                $table->decimal('biaya_material', 15, 2)->default(0)->after('satuan');
            }

            // Tambahkan kolom biaya_pekerja jika belum ada
            if (!Schema::hasColumn('rabs', 'biaya_pekerja')) {
                $table->decimal('biaya_pekerja', 15, 2)->default(0)->after('biaya_material');
            }

            // Tambahkan kolom total jika belum ada
            if (!Schema::hasColumn('rabs', 'total')) {
                $table->decimal('total', 15, 2)->default(0)->after('biaya_pekerja');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rabs', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan jika ada
            if (Schema::hasColumn('rabs', 'biaya_material')) {
                $table->dropColumn('biaya_material');
            }
            if (Schema::hasColumn('rabs', 'biaya_pekerja')) {
                $table->dropColumn('biaya_pekerja');
            }
            if (Schema::hasColumn('rabs', 'total')) {
                $table->dropColumn('total');
            }
        });
    }
};
