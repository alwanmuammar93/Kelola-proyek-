<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kwitansi', function (Blueprint $table) {
            $table->id('Id_Kwitansi');
            
            // 🔗 Id_Sumber bisa berasal dari tabel RAB, Penjualan, atau Proyek
            $table->unsignedBigInteger('Id_Sumber')->nullable();

            // Tambahkan 'proyek' agar fleksibel
            $table->enum('Sumber_Tabel', ['rabs', 'penjualan', 'proyek'])->nullable();

            $table->string('Sales', 100)->nullable();
            $table->date('Tanggal_Kwitansi')->nullable();
            $table->double('Total', 15, 2)->default(0);
            $table->string('Metode_Pembayaran', 100)->nullable();
            $table->enum('Status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');

            $table->timestamps();

            /**
             * ⚙️ Catatan:
             * Karena Id_Sumber bisa merujuk ke lebih dari satu tabel,
             * kita TIDAK menambahkan foreign key constraint langsung ke satu tabel (seperti rabs saja),
             * karena akan memunculkan error saat referensi ke penjualan/proyek.
             * Sebagai gantinya, relasi ditangani lewat Model Eloquent.
             */

            // Jika tetap ingin FK ke RAB secara opsional, bisa tambahkan index untuk performa
            $table->index('Id_Sumber');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }
};
