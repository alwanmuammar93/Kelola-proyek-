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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Tipe notifikasi: 'rab', 'kwitansi', 'laporan', dll
            $table->string('type', 50);
            
            // Icon untuk display (warning, success, info, danger)
            $table->string('icon', 50)->default('bi-info-circle-fill');
            
            // Judul notifikasi
            $table->string('title', 200);
            
            // Pesan detail notifikasi
            $table->text('message');
            
            // ID referensi (misal: id_rab, id_kwitansi)
            $table->unsignedBigInteger('reference_id')->nullable();
            
            // Nama referensi (misal: no_rab, no_invoice)
            $table->string('reference_name', 100)->nullable();
            
            // Status: unread, read
            $table->enum('status', ['unread', 'read'])->default('unread');
            
            // User yang terkait (nullable untuk notif global)
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->timestamps();
            
            // Index untuk performa
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};