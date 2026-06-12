<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing data ke status baru
        DB::statement("
            UPDATE proyeks 
            SET status = CASE 
                WHEN status = 'Belum_Dimulai' THEN 'RAB Belum Dibuat'
                WHEN status = 'Sedang Berjalan' THEN 'Proyek Dikerjakan'
                WHEN status = 'Selesai' THEN 'Proyek Selesai Dikerjakan'
                ELSE 'RAB Belum Dibuat'
            END
            WHERE status IN ('Belum_Dimulai', 'Sedang Berjalan', 'Selesai')
        ");
        
        // 2. Drop constraint check lama
        DB::statement('ALTER TABLE proyeks DROP CONSTRAINT IF EXISTS proyeks_status_check');
        
        // 3. Ubah tipe kolom menjadi varchar
        DB::statement('ALTER TABLE proyeks ALTER COLUMN status TYPE VARCHAR(255)');
        
        // 4. Buat constraint check baru
        DB::statement("
            ALTER TABLE proyeks 
            ADD CONSTRAINT proyeks_status_check 
            CHECK (status IN (
                'RAB Belum Dibuat', 
                'RAB Telah Dibuat', 
                'Proyek Dikerjakan', 
                'Proyek Selesai Dikerjakan'
            ))
        ");
        
        // 5. Set default value
        DB::statement("ALTER TABLE proyeks ALTER COLUMN status SET DEFAULT 'RAB Belum Dibuat'");
    }

    public function down(): void
    {
        // Kembalikan constraint lama
        DB::statement('ALTER TABLE proyeks DROP CONSTRAINT IF EXISTS proyeks_status_check');
        
        DB::statement("
            ALTER TABLE proyeks 
            ADD CONSTRAINT proyeks_status_check 
            CHECK (status IN ('Belum_Dimulai', 'Sedang Berjalan', 'Selesai'))
        ");
        
        DB::statement("ALTER TABLE proyeks ALTER COLUMN status SET DEFAULT 'Belum_Dimulai'");
    }
};