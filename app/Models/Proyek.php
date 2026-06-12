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

    // ✅ Kolom yang dapat diisi (fillable) - SUDAH DIPERBAIKI & URUTAN BENAR
    protected $fillable = [
        'nama_proyek',
        'nama_owner',    // ← URUTAN BENAR (setelah nama_proyek)
        'nomor_hp',      // ← URUTAN BENAR (setelah nama_owner)
        'deskripsi',     // ← Setelah info kontak
        'status',
    ];

    // ✅ Timestamps aktif (created_at & updated_at)
    public $timestamps = true;

    // ✅ Cast attributes untuk memastikan tipe data yang benar
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ Default values untuk kolom
    protected $attributes = [
        'status' => 'RAB Belum Dibuat',
    ];

    // ✅ Relasi: satu proyek punya banyak RAB
    public function rabs()
    {
        return $this->hasMany(Rab::class, 'id_proyek', 'id_proyek');
    }

    // ✅ Konstanta untuk status (memudahkan validasi dan penggunaan)
    const STATUS_RAB_BELUM_DIBUAT = 'RAB Belum Dibuat';
    const STATUS_RAB_TELAH_DIBUAT = 'RAB Telah Dibuat';
    const STATUS_PROYEK_DIKERJAKAN = 'Proyek Dikerjakan';
    const STATUS_PROYEK_SELESAI = 'Proyek Selesai Dikerjakan';

    // ✅ Method helper untuk mendapatkan semua status yang tersedia
    public static function getStatusOptions()
    {
        return [
            self::STATUS_RAB_BELUM_DIBUAT,
            self::STATUS_RAB_TELAH_DIBUAT,
            self::STATUS_PROYEK_DIKERJAKAN,
            self::STATUS_PROYEK_SELESAI,
        ];
    }

    // ✅ Scope untuk filter berdasarkan status
    public function scopeRabBelumDibuat($query)
    {
        return $query->where('status', self::STATUS_RAB_BELUM_DIBUAT);
    }

    public function scopeRabTelahDibuat($query)
    {
        return $query->where('status', self::STATUS_RAB_TELAH_DIBUAT);
    }

    public function scopeProyekDikerjakan($query)
    {
        return $query->where('status', self::STATUS_PROYEK_DIKERJAKAN);
    }

    public function scopeProyekSelesai($query)
    {
        return $query->where('status', self::STATUS_PROYEK_SELESAI);
    }

    // ========================================
    // 🔥 METHOD BARU UNTUK STATISTIK DASHBOARD
    // ========================================

    /**
     * ✅ Method untuk mendapatkan statistik proyek berdasarkan status
     * Return: array dengan jumlah proyek per status
     */
    public static function getStatistikStatus()
    {
        return [
            'rab_belum_dibuat' => self::where('status', self::STATUS_RAB_BELUM_DIBUAT)->count(),
            'rab_telah_dibuat' => self::where('status', self::STATUS_RAB_TELAH_DIBUAT)->count(),
            'proyek_dikerjakan' => self::where('status', self::STATUS_PROYEK_DIKERJAKAN)->count(),
            'proyek_selesai' => self::where('status', self::STATUS_PROYEK_SELESAI)->count(),
            'total' => self::count(),
        ];
    }

    /**
     * ✅ Method untuk menentukan status PADAT atau SEPI
     * Logika:
     * - PADAT = Banyak proyek "RAB Belum Dibuat" (masih numpuk kerjaan)
     * - SEPI = Banyak proyek "RAB Telah Dibuat" (kerjaan sudah beres)
     * 
     * Return: array dengan status, jumlah, dan keterangan
     */
    public static function getStatusProyek()
    {
        $rabBelumDibuat = self::where('status', self::STATUS_RAB_BELUM_DIBUAT)->count();
        $rabTelahDibuat = self::where('status', self::STATUS_RAB_TELAH_DIBUAT)->count();
        
        // Logika: Jika RAB Belum Dibuat lebih banyak = PADAT, sebaliknya = SEPI
        if ($rabBelumDibuat > $rabTelahDibuat) {
            $status = 'PADAT';
            $jumlah = $rabBelumDibuat;
            $keterangan = 'Banyak proyek yang belum dibuatkan RAB';
        } else {
            $status = 'SEPI';
            $jumlah = $rabTelahDibuat;
            $keterangan = 'Banyak proyek yang sudah dibuatkan RAB';
        }
        
        return [
            'status' => $status,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'rab_belum_dibuat' => $rabBelumDibuat,
            'rab_telah_dibuat' => $rabTelahDibuat,
        ];
    }

    /**
     * ✅ Method helper untuk mendapatkan jumlah proyek berdasarkan status tertentu
     * 
     * @param string $status
     * @return int
     */
    public static function countByStatus($status)
    {
        return self::where('status', $status)->count();
    }
}