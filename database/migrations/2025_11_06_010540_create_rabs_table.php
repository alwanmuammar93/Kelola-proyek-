<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel `rabs` sesuai model & controller terbaru:
     */
    public function up()
    {
        Schema::create('rabs', function (Blueprint $table) {

            // Primary key custom name
            $table->bigIncrements('id_rab');

            // Foreign key to proyek
            $table->unsignedBigInteger('id_proyek')->index();

            // Identifikasi RAB
            $table->string('no_rab', 50)->unique();
            $table->string('perihal', 100);
            $table->string('owner', 100);
            $table->string('nama_pekerjaan', 150);

            // Rincian pekerjaan disimpan sebagai JSON
            $table->json('rincian_pekerjaan')->nullable();

            // Agregat jumlah dan satuan ringkasan
            $table->unsignedBigInteger('jumlah')->default(0);
            $table->string('satuan', 60)->nullable()->default('Mixed');

            // Total biaya (hasil perhitungan dari rincian)
            $table->decimal('total', 15, 2)->default(0);

            // ✔ PERBAIKAN: Menambah total_rab agar sesuai dengan Controller
            $table->decimal('total_rab', 15, 2)->nullable()->default(0);

            // Status RAB
            $table->string('status', 50)->default('Perencanaan');

            // Timestamps
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_proyek')
                  ->references('id_proyek')
                  ->on('proyeks')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rabs', function (Blueprint $table) {

            if (Schema::hasColumn('rabs', 'id_proyek')) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                try {
                    $sm->listTableDetails('rabs')->getForeignKeys();
                    $table->dropForeign(['id_proyek']);
                } catch (\Throwable $e) {
                    // abaikan jika FK tidak ada
                }
            }
        });

        Schema::dropIfExists('rabs');
    }
}
