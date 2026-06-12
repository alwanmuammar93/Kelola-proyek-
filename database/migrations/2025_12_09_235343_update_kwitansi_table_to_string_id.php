<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Backup data lama
        $oldData = DB::table('kwitansi')->get();
        
        // 2. Drop tabel lama
        Schema::dropIfExists('kwitansi');
        
        // 3. Buat tabel baru dengan struktur baru
        Schema::create('kwitansi', function (Blueprint $table) {
            // ✅ Primary key STRING
            $table->string('Id_Kwitansi', 50)->primary();
            
            $table->unsignedBigInteger('Id_Sumber')->nullable();
            $table->enum('Sumber_Tabel', ['rabs', 'penjualan'])->nullable();
            $table->string('Sales', 100)->nullable();
            $table->date('Tanggal_Kwitansi')->nullable();
            $table->double('Total', 15, 2)->default(0);
            $table->double('Total_Pembayaran', 15, 2)->default(0);
            $table->enum('Metode_Pembayaran', ['Cash', 'QRIS', 'Transfer'])->nullable();
            $table->text('Untuk_Pembayaran')->nullable();
            
            // ✅ Status VARCHAR untuk dinamis
            $table->string('Status', 20)->default('DP 0%');

            $table->timestamps();

            // Index
            $table->index('Id_Sumber');
            $table->index('Sumber_Tabel');
            $table->index(['Id_Sumber', 'Sumber_Tabel']);
            $table->index('Status');
            $table->index('Tanggal_Kwitansi');
        });

        // 4. Restore data dengan ID baru
        foreach ($oldData as $row) {
            $tanggal = \Carbon\Carbon::parse($row->Tanggal_Kwitansi)->format('Ymd');
            $random = strtoupper(\Illuminate\Support\Str::random(6));
            $newId = "KWT-{$tanggal}-{$random}";
            
            // Convert status lama ke format baru
            $newStatus = $row->Status;
            if ($row->Status === 'DP 50%' || $row->Status === 'Belum Lunas') {
                if ($row->Total > 0) {
                    $persentase = round(($row->Total_Pembayaran / $row->Total) * 100);
                    $persentase = max(0, min(99, $persentase));
                    $newStatus = "DP {$persentase}%";
                } else {
                    $newStatus = 'DP 0%';
                }
            }
            
            DB::table('kwitansi')->insert([
                'Id_Kwitansi' => $newId,
                'Id_Sumber' => $row->Id_Sumber,
                'Sumber_Tabel' => $row->Sumber_Tabel,
                'Sales' => $row->Sales,
                'Tanggal_Kwitansi' => $row->Tanggal_Kwitansi,
                'Total' => $row->Total,
                'Total_Pembayaran' => $row->Total_Pembayaran,
                'Metode_Pembayaran' => $row->Metode_Pembayaran,
                'Untuk_Pembayaran' => $row->Untuk_Pembayaran,
                'Status' => $newStatus,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
        
        // Rollback ke struktur lama
        Schema::create('kwitansi', function (Blueprint $table) {
            $table->id('Id_Kwitansi');
            $table->unsignedBigInteger('Id_Sumber')->nullable();
            $table->enum('Sumber_Tabel', ['rabs', 'penjualan'])->nullable();
            $table->string('Sales', 100)->nullable();
            $table->date('Tanggal_Kwitansi')->nullable();
            $table->double('Total', 15, 2)->default(0);
            $table->double('Total_Pembayaran', 15, 2)->default(0);
            $table->enum('Metode_Pembayaran', ['Cash', 'QRIS', 'Transfer'])->nullable();
            $table->text('Untuk_Pembayaran')->nullable();
            $table->enum('Status', ['Lunas', 'DP 50%'])->default('DP 50%');
            $table->timestamps();

            $table->index('Id_Sumber');
            $table->index('Sumber_Tabel');
            $table->index(['Id_Sumber', 'Sumber_Tabel']);
        });
    }
};