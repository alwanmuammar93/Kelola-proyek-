<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyeks', function (Blueprint $table) {
            // 🔧 custom primary key 'id_proyek'
            $table->bigIncrements('id_proyek'); // ✅ primary key sesuai model

            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            
            // ❌ DIHAPUS: tanggal_mulai dan tanggal_selesai
            // $table->date('tanggal_mulai')->nullable();
            // $table->date('tanggal_selesai')->nullable();
            
            // ✅ DIUBAH: Status baru sesuai alur RAB dan proyek
            $table->enum('status', [
                'RAB Belum Dibuat', 
                'RAB Telah Dibuat', 
                'Proyek Dikerjakan', 
                'Proyek Selesai Dikerjakan'
            ])->default('RAB Belum Dibuat');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};