<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration: membuat tabel penjualan_details (DETAIL)
     */
    public function up(): void
    {
        Schema::create('penjualan_details', function (Blueprint $table) {
            // 🔹 PRIMARY KEY
            $table->id();

            // 🔹 FOREIGN KEY ke tabel penjualans
            $table->unsignedBigInteger('penjualan_id');
            $table->foreign('penjualan_id')
                  ->references('id_penjualan')
                  ->on('penjualans')
                  ->onDelete('cascade'); // Jika penjualan dihapus, detail ikut terhapus

            // 🔹 Kolom untuk rincian detail
            $table->string('rincian');              // Nama barang/rincian
            $table->integer('jumlah');              // Jumlah barang
            $table->decimal('harga_satuan', 15, 2); // Harga per unit
            $table->decimal('subtotal', 15, 2);     // Jumlah x Harga Satuan

            // 🔹 Timestamps
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (hapus tabel)
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_details');
    }
};