<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    // ✅ Nama tabel di database
    protected $table = 'penjualans';

    // ✅ Primary key yang benar (sesuai migration)
    protected $primaryKey = 'id_penjualan';

    // ✅ Auto increment aktif
    public $incrementing = true;

    // ✅ Tipe primary key
    protected $keyType = 'int';

    // ✅ Timestamps aktif
    public $timestamps = true;

    // ✅ DIUBAH: Kolom yang dapat diisi (HEADER saja, tanpa rincian)
    protected $fillable = [
        'nama_sales',
        'tanggal',
        'total',
    ];

    // ✅ Cast attributes
    protected $casts = [
        'tanggal' => 'date',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ✅ RELASI: One-to-Many dengan PenjualanDetail
     * Satu penjualan memiliki banyak detail
     */
    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id', 'id_penjualan');
    }

    /**
     * ✅ Accessor untuk format total dengan Rupiah
     */
    public function getTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * ✅ Method untuk menghitung total dari semua detail
     */
    public function calculateTotal()
    {
        return $this->details->sum('subtotal');
    }

    /**
     * ✅ Method untuk update total
     */
    public function updateTotal()
    {
        $this->total = $this->calculateTotal();
        $this->save();
    }
}