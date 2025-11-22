<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: membuat tabel penjualans
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {

            // 🔹 DISAMAKAN DENGAN MODEL (primary key = id_barang)
            $table->id('id_barang');

            // Struktur kolom lain tetap seperti yang sudah kamu tulis
            $table->string('nama_barang');    // Nama barang
            $table->integer('jumlah');        // Jumlah barang
            $table->double('harga_satuan');   // Harga per unit
            $table->double('total');          // Total harga
            $table->timestamps();             // created_at & updated_at
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
