<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    // ✅ Nama tabel di database
    protected $table = 'penjualan_details';

    // ✅ Primary key default (id)
    protected $primaryKey = 'id';

    // ✅ Auto increment aktif
    public $incrementing = true;

    // ✅ Tipe primary key
    protected $keyType = 'int';

    // ✅ Timestamps aktif
    public $timestamps = true;

    // ✅ Kolom yang dapat diisi
    protected $fillable = [
        'penjualan_id',
        'rincian',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    // ✅ Cast attributes
    protected $casts = [
        'penjualan_id' => 'integer',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ✅ RELASI: Belongs To - Detail milik satu penjualan
     */
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id_penjualan');
    }

    /**
     * ✅ Accessor untuk format harga satuan dengan Rupiah
     */
    public function getHargaSatuanFormatAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * ✅ Accessor untuk format subtotal dengan Rupiah
     */
    public function getSubtotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * ✅ Method untuk menghitung subtotal otomatis
     */
    public function calculateSubtotal()
    {
        return $this->jumlah * $this->harga_satuan;
    }

    /**
     * ✅ Event: Sebelum menyimpan, hitung subtotal otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($detail) {
            $detail->subtotal = $detail->calculateSubtotal();
        });

        // Update total penjualan setelah detail disimpan/dihapus
        static::saved(function ($detail) {
            $detail->penjualan->updateTotal();
        });

        static::deleted(function ($detail) {
            $detail->penjualan->updateTotal();
        });
    }
}