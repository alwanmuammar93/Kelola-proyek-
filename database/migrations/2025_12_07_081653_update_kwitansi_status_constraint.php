<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Hapus constraint lama
        DB::statement('ALTER TABLE kwitansi DROP CONSTRAINT IF EXISTS "kwitansi_Status_check"');
        
        // Buat constraint baru yang lebih fleksibel
        // PENTING: Pakai tanda kutip ganda untuk kolom "Status" karena case-sensitive di PostgreSQL
        DB::statement('
            ALTER TABLE kwitansi ADD CONSTRAINT "kwitansi_Status_check" 
            CHECK (
                "Status" = \'Lunas\' OR 
                "Status" LIKE \'DP %\'
            )
        ');
    }

    public function down()
    {
        // Kembalikan ke constraint lama
        DB::statement('ALTER TABLE kwitansi DROP CONSTRAINT IF EXISTS "kwitansi_Status_check"');
        
        DB::statement('
            ALTER TABLE kwitansi ADD CONSTRAINT "kwitansi_Status_check" 
            CHECK ("Status" IN (\'Lunas\', \'Belum Lunas\', \'DP 50%\'))
        ');
    }
};