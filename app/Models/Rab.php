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

            // --- Hitung total biaya material ---
            $totalBiaya = 0;
            foreach ($rincian as $item) {

                if (is_array($item)) {
                    $nilai = $item['biaya_material'] ?? 0;
                    if (!is_numeric($nilai)) $nilai = 0;
                } else {
                    $nilai = is_numeric($item) ? $item : 0;
                }

                $totalBiaya += floatval($nilai);
            }

            // --- Hitung total jumlah ---
            $totalJumlah = 0;
            foreach ($rincian as $item) {
                if (is_array($item) && isset($item['jumlah']) && is_numeric($item['jumlah'])) {
                    $totalJumlah += intval($item['jumlah']);
                }
            }

            // Simpan kembali
            $rab->rincian_pekerjaan = $rincian;
            $rab->jumlah = $totalJumlah;
            $rab->total = $totalBiaya;
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
