<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: membuat tabel penjualans (HEADER)
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            // 🔹 PRIMARY KEY
            $table->id('id_penjualan');

            // 🔹 Kolom untuk nama sales
            $table->string('nama_sales');

            // 🔹 Tanggal penjualan
            $table->date('tanggal')->default(now());

            // 🔹 Total keseluruhan (opsional, bisa dihitung dari details)
            $table->decimal('total', 15, 2)->default(0);

            // 🔹 Timestamps
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};