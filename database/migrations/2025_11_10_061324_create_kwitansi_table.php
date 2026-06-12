<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kwitansi', function (Blueprint $table) {
            // ✅ UPDATED: Primary key jadi STRING untuk format profesional
            // Format: KWT-20251210-A7X9K2
            $table->string('Id_Kwitansi', 50)->primary();
            
            // 🔗 Id_Sumber bisa berasal dari tabel RAB atau Penjualan
            $table->unsignedBigInteger('Id_Sumber')->nullable();
            
            // Sumber hanya RAB atau Penjualan sesuai requirements
            $table->enum('Sumber_Tabel', ['rabs', 'penjualan'])->nullable();

            $table->string('Sales', 100)->nullable();
            $table->date('Tanggal_Kwitansi')->nullable();
            
            // Total dari data sumber (RAB/Penjualan) - auto-fill
            $table->double('Total', 15, 2)->default(0);
            
            // Total yang dibayarkan oleh customer
            $table->double('Total_Pembayaran', 15, 2)->default(0);
            
            // Metode pembayaran: Cash, QRIS, Transfer
            $table->enum('Metode_Pembayaran', ['Cash', 'QRIS', 'Transfer'])->nullable();
            
            // Untuk Pembayaran (text field untuk keterangan)
            $table->text('Untuk_Pembayaran')->nullable();
            
            // ✅ UPDATED: Status sekarang VARCHAR untuk support DP dinamis
            // Format: "Lunas", "DP 0%", "DP 10%", "DP 66%", dst
            $table->string('Status', 20)->default('DP 0%');

            $table->timestamps();

            // Index untuk performa query
            $table->index('Id_Sumber');
            $table->index('Sumber_Tabel');
            $table->index(['Id_Sumber', 'Sumber_Tabel']);
            $table->index('Status'); // ✅ TAMBAHAN: Index untuk sorting by status
            $table->index('Tanggal_Kwitansi'); // ✅ TAMBAHAN: Index untuk sorting by tanggal
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }
};