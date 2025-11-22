<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanDetail extends Model
{
    protected $table = 'laporan_detail';

    protected $fillable = [
        'laporan_id',
        'rincian',
        'jumlah',
        'satuan',
        'total',
        'modal_satuan',
        'total_modal',
        'profit'
    ];

    /**
     * Relasi ke tabel laporan
     * setiap detail pasti dimiliki oleh satu laporan
     */
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id');
    }
}
