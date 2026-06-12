<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    use HasFactory;

    protected $table = 'rabs';
    protected $primaryKey = 'id_rab';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'no_rab',
        'perihal',
        'owner',
        'nama_pekerjaan',
        'rincian_pekerjaan',
        'jumlah',
        'satuan',
        'total',
        'id_proyek',
        'status',
    ];

    protected $casts = [
        'rincian_pekerjaan' => 'array',
        'total' => 'float',
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek');
    }

    /**
     * Booting: Hitung total otomatis sebelum simpan
     * 🔥 DIPERBAIKI: Sekarang menghitung subtotal (jumlah × biaya_material) per item
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($rab) {

            // --- Normalisasi rincian_pekerjaan ---
            $rincian = $rab->rincian_pekerjaan;

            // Jika berupa string JSON → decode
            if (is_string($rincian)) {
                $decoded = json_decode($rincian, true);
                $rincian = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            }

            // Jika bukan array → jadikan array kosong
            if (!is_array($rincian)) {
                $rincian = [];
            }

            // --- 🔥 PERBAIKAN: Hitung total RAB dengan subtotal (jumlah × biaya_material) ---
            $totalRAB = 0;
            $totalJumlah = 0;

            foreach ($rincian as &$item) {
                if (is_array($item)) {
                    // Ambil jumlah dan biaya_material
                    $jumlah = isset($item['jumlah']) && is_numeric($item['jumlah']) 
                        ? floatval($item['jumlah']) 
                        : 0;

                    $biayaMaterial = isset($item['biaya_material']) && is_numeric($item['biaya_material']) 
                        ? floatval($item['biaya_material']) 
                        : 0;

                    // 🔥 HITUNG SUBTOTAL: jumlah × biaya_material
                    $subtotal = $jumlah * $biayaMaterial;

                    // Simpan subtotal ke dalam item (untuk referensi di view)
                    $item['subtotal'] = $subtotal;

                    // Tambahkan ke total RAB
                    $totalRAB += $subtotal;

                    // Tambahkan ke total jumlah
                    $totalJumlah += intval($jumlah);
                }
            }
            unset($item); // Hapus referensi

            // Simpan kembali
            $rab->rincian_pekerjaan = $rincian;
            $rab->jumlah = $totalJumlah;
            $rab->total = $totalRAB; // 🔥 Sekarang total adalah SUM dari semua subtotal
        });
    }

    /**
     * Accessor total_rab agar tetap kompatibel
     */
    public function getTotalRabAttribute()
    {
        return $this->attributes['total'] ?? 0;
    }
}