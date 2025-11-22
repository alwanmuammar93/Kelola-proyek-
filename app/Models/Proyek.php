<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    // ✅ Nama tabel di database
    protected $table = 'proyeks';

    // ✅ Primary key yang benar
    protected $primaryKey = 'id_proyek';

    // ✅ Aktifkan auto increment (karena id_proyek pakai tipe bigIncrements)
    public $incrementing = true;

    // ✅ Tipe primary key integer
    protected $keyType = 'int';

    // ✅ Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'nama_proyek',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // ✅ Timestamps aktif (created_at & updated_at)
    public $timestamps = true;

    // ✅ Relasi: satu proyek punya banyak RAB
    public function rabs()
    {
        return $this->hasMany(Rab::class, 'id_proyek', 'id_proyek');
    }
}
