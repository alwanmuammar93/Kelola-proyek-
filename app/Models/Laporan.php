<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'nama_laporan',
        'tanggal',
        'no_nota',
        'owner',
        'total_profit'
    ];

    /**
     * Relasi ke tabel laporan_details
     * 1 laporan memiliki banyak detail laporan
     */
    public function details()
    {
        return $this->hasMany(LaporanDetail::class, 'laporan_id', 'id');
    }
}
