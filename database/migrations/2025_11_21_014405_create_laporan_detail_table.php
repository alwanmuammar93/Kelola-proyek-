<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->onDelete('cascade');
            $table->string('rincian');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->double('total');
            $table->double('modal_satuan')->nullable();
            $table->double('total_modal')->nullable();
            $table->double('profit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_detail');
    }
};
