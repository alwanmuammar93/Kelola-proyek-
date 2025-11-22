<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'penjualans';

    // Primary key yang digunakan
    protected $primaryKey = 'id_barang';

    // WAJIB untuk PostgreSQL bila pakai custom primary key
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = true;

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total',
    ];

    /**
     * 🔹 Accessor agar bisa tetap dipanggil sebagai "id_penjualan"
     * meskipun kolom sebenarnya bernama "id_barang".
     * Ini akan mencegah error undefined di sisi frontend/JavaScript
     * yang mencari "id_penjualan".
     */
    public function getIdPenjualanAttribute()
    {
        return $this->attributes['id_barang'] ?? null;
    }

    /**
     * 🔹 Tambahan alias supaya properti "id" juga otomatis mengembalikan id_barang.
     * Beberapa script JS atau relasi Eloquent kadang mengakses "$penjualan->id".
     */
    public function getIdAttribute()
    {
        return $this->attributes['id_barang'] ?? null;
    }
}
