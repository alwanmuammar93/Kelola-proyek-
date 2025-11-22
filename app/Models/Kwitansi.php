<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    use HasFactory;

    protected $table = 'kwitansi';
    protected $primaryKey = 'Id_Kwitansi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Sumber',
        'Sumber_Tabel',
        'Sales',
        'Tanggal_Kwitansi',
        'Total',
        'Metode_Pembayaran',
        'Status'
    ];

    /**
     * 🔗 Relasi ke tabel RAB
     * Aktif jika Sumber_Tabel = 'rabs'
     */
    public function rab()
    {
        return $this->belongsTo(Rab::class, 'Id_Sumber', 'id_rab');
    }

    /**
     * 🔗 Relasi ke tabel Penjualan
     * Aktif jika Sumber_Tabel = 'penjualan'
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'Id_Sumber', 'id_penjualan');
    }

    /**
     * 🔗 Relasi ke tabel Proyek
     * Aktif jika Sumber_Tabel = 'proyek'
     */
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'Id_Sumber', 'id_proyek');
    }

    /**
     * 🔧 Accessor sumber dinamis (RAB, Penjualan, atau Proyek)
     */
    public function getSumberAttribute()
    {
        switch ($this->Sumber_Tabel) {
            case 'rabs':
                return $this->rab;
            case 'penjualan':
                return $this->penjualan;
            case 'proyek':
                return $this->proyek;
            default:
                return null;
        }
    }

    /**
     * ✅ Tambahan: pastikan ID selalu tersedia dengan nama 'id_kwitansi'
     * Agar kompatibel dengan route() dan Blade
     */
    public function getIdKwitansiAttribute()
    {
        return $this->attributes['Id_Kwitansi'] ?? null;
    }
}
