<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyeks', function (Blueprint $table) {
            // 🔧 ubah dari $table->id(); ke custom primary key 'id_proyek'
            $table->bigIncrements('id_proyek'); // ✅ primary key sesuai model

            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['Belum_Dimulai', 'Sedang Berjalan', 'Selesai'])->default('Belum_Dimulai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
