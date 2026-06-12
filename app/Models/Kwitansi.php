<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kwitansi extends Model
{
    use HasFactory;

    protected $table = 'kwitansi';
    protected $primaryKey = 'Id_Kwitansi';
    public $incrementing = false; // ✅ DIUBAH: Karena sekarang pakai string
    protected $keyType = 'string'; // ✅ DIUBAH: Dari int ke string

    protected $fillable = [
        'Id_Kwitansi', // ✅ DITAMBAHKAN: Agar bisa diisi otomatis
        'Id_Sumber',
        'Sumber_Tabel',
        'Sales',
        'Tanggal_Kwitansi',
        'Total',
        'Total_Pembayaran',
        'Metode_Pembayaran',
        'Untuk_Pembayaran',
        'Status'
    ];

    protected $casts = [
        'Tanggal_Kwitansi' => 'date',
        'Total' => 'decimal:2',
        'Total_Pembayaran' => 'decimal:2',
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
     * 🔧 Accessor sumber dinamis (RAB atau Penjualan)
     */
    public function getSumberAttribute()
    {
        switch ($this->Sumber_Tabel) {
            case 'rabs':
                return $this->rab;
            case 'penjualan':
                return $this->penjualan;
            default:
                return null;
        }
    }

    /**
     * ✅ Accessor: ID Kwitansi (Sudah dalam format profesional)
     */
    public function getIdKwitansiAttribute()
    {
        return $this->attributes['Id_Kwitansi'] ?? null;
    }

    /**
     * ✅ Accessor: Nomor Kwitansi (Sekarang return Id_Kwitansi langsung)
     */
    public function getNoKwitansiAttribute()
    {
        return $this->attributes['Id_Kwitansi'];
    }

    /**
     * 🎨 Accessor: Warna Line berdasarkan Status (UPDATED - DINAMIS)
     */
    public function getLineColorAttribute()
    {
        // Jika status adalah Lunas
        if ($this->Status === 'Lunas') {
            return 'blue';
        }
        
        // Jika status adalah DP dengan persentase
        if (str_starts_with($this->Status, 'DP ')) {
            // Ekstrak persentase dari status (contoh: "DP 66%" -> 66)
            preg_match('/DP (\d+)%/', $this->Status, $matches);
            $persentase = isset($matches[1]) ? intval($matches[1]) : 0;
            
            // Warna berdasarkan persentase
            if ($persentase >= 75) {
                return 'orange'; // Mendekati lunas
            } elseif ($persentase >= 50) {
                return 'yellow'; // Setengah
            } else {
                return 'gray'; // Masih sedikit
            }
        }
        
        // Default
        return 'yellow';
    }

    /**
     * 🔄 Boot Method: AUTO GENERATE ID KWITANSI
     */
    protected static function boot()
    {
        parent::boot();

        // ✅ AUTO GENERATE ID KWITANSI saat creating
        static::creating(function ($kwitansi) {
            if (empty($kwitansi->Id_Kwitansi)) {
                $kwitansi->Id_Kwitansi = self::generateIdKwitansi();
            }
        });
    }

    /**
     * 🆕 GENERATE ID KWITANSI UNIK
     * Format: KWT-YYYYMMDD-XXXXXX
     * Contoh: KWT-20251210-A7X9K2
     */
    private static function generateIdKwitansi()
    {
        do {
            // Format: KWT-TANGGAL-RANDOM
            $tanggal = now()->format('Ymd'); // 20251210
            $random = strtoupper(Str::random(6)); // A7X9K2
            
            $idKwitansi = "KWT-{$tanggal}-{$random}";
            
            // Cek apakah ID sudah ada di database
            $exists = self::where('Id_Kwitansi', $idKwitansi)->exists();
            
        } while ($exists); // Ulangi jika ID sudah ada
        
        return $idKwitansi;
    }

    /**
     * 📊 Scope: Filter by Sumber Tabel
     */
    public function scopeBySumber($query, $sumber)
    {
        return $query->where('Sumber_Tabel', $sumber);
    }

    /**
     * 📊 Scope: Filter by Status (UPDATED - SUPPORT DINAMIS)
     */
    public function scopeByStatus($query, $status)
    {
        // Jika mencari status "Lunas"
        if ($status === 'Lunas') {
            return $query->where('Status', 'Lunas');
        }
        
        // Jika mencari semua DP (berapapun persentasenya)
        if ($status === 'DP') {
            return $query->where('Status', 'like', 'DP %');
        }
        
        // Jika mencari DP dengan persentase tertentu
        if (str_starts_with($status, 'DP ')) {
            return $query->where('Status', $status);
        }
        
        // Default: exact match
        return $query->where('Status', $status);
    }

    /**
     * 🔍 Scope: Search Multi-Column (UNTUK POSTGRESQL)
     */
    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            // Cari berdasarkan ID Kwitansi (sekarang sudah string)
            $q->where('Id_Kwitansi', 'ILIKE', "%{$search}%")
              
              // Cari berdasarkan Sales
              ->orWhere('Sales', 'ILIKE', "%{$search}%")
              
              // Cari berdasarkan Tanggal
              ->orWhereRaw("TO_CHAR(\"Tanggal_Kwitansi\", 'DD-MM-YYYY') LIKE ?", ["%{$search}%"])
              ->orWhereRaw("TO_CHAR(\"Tanggal_Kwitansi\", 'YYYY-MM-DD') LIKE ?", ["%{$search}%"])
              
              // Cari berdasarkan Status
              ->orWhere('Status', 'ILIKE', "%{$search}%")
              
              // Cari berdasarkan Metode Pembayaran
              ->orWhere('Metode_Pembayaran', 'ILIKE', "%{$search}%")
              
              // Cari berdasarkan Sumber Tabel
              ->orWhere('Sumber_Tabel', 'ILIKE', "%{$search}%");
        });
    }

    /**
     * 💰 Helper: Get Persentase Pembayaran (UPDATED)
     */
    public function getPersentasePembayaranAttribute()
    {
        if ($this->Total <= 0) {
            return 0;
        }

        $persentase = ($this->Total_Pembayaran / $this->Total) * 100;
        
        // Batasi maksimal 100% untuk display
        return round(min(100, $persentase), 1);
    }

    /**
     * 🏷️ Helper: Get Status Badge Class untuk UI (UPDATED - DINAMIS)
     */
    public function getStatusBadgeClassAttribute()
    {
        // Jika Lunas
        if ($this->Status === 'Lunas') {
            return 'badge-lunas'; // Biru
        }
        
        // Jika DP dengan persentase
        if (str_starts_with($this->Status, 'DP ')) {
            // Ekstrak persentase
            preg_match('/DP (\d+)%/', $this->Status, $matches);
            $persentase = isset($matches[1]) ? intval($matches[1]) : 0;
            
            // Badge berdasarkan persentase
            if ($persentase >= 75) {
                return 'badge-dp-tinggi'; // Hijau/Orange
            } elseif ($persentase >= 50) {
                return 'badge-dp-sedang'; // Kuning
            } else {
                return 'badge-dp-rendah'; // Abu-abu
            }
        }
        
        // Default
        return 'badge-dp'; // Kuning
    }

    /**
     * 🆕 Helper: Cek apakah sudah lunas
     */
    public function isLunas()
    {
        return $this->Status === 'Lunas';
    }

    /**
     * 🆕 Helper: Get persentase dari status string
     * Contoh: "DP 66%" -> 66
     */
    public function getPersentaseFromStatus()
    {
        if ($this->Status === 'Lunas') {
            return 100;
        }
        
        if (str_starts_with($this->Status, 'DP ')) {
            preg_match('/DP (\d+)%/', $this->Status, $matches);
            return isset($matches[1]) ? intval($matches[1]) : 0;
        }
        
        return 0;
    }

    /**
     * 🆕 Helper: Format status untuk display
     * Contoh: "DP 66%" -> "Down Payment 66%"
     */
    public function getFormattedStatusAttribute()
    {
        if ($this->Status === 'Lunas') {
            return 'Lunas (100%)';
        }
        
        if (str_starts_with($this->Status, 'DP ')) {
            preg_match('/DP (\d+)%/', $this->Status, $matches);
            $persentase = isset($matches[1]) ? $matches[1] : '0';
            return "Down Payment {$persentase}%";
        }
        
        return $this->Status;
    }
}