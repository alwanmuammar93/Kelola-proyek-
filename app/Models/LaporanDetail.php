<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanDetail extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'laporan_detail';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Tipe primary key
     */
    protected $keyType = 'int';

    /**
     * Auto increment
     */
    public $incrementing = true;

    /**
     * Timestamps (created_at, updated_at)
     */
    public $timestamps = true;

    /**
     * Field yang bisa diisi mass assignment
     */
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
     * Field yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'laporan_id' => 'integer',
        'jumlah' => 'integer',
        'total' => 'decimal:2',
        'modal_satuan' => 'decimal:2',
        'total_modal' => 'decimal:2',
        'profit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel laporan
     * Setiap detail pasti dimiliki oleh satu laporan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id');
    }

    /**
     * Accessor: Format total ke format Rupiah
     * 
     * @return string
     */
    public function getTotalFormatAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Accessor: Format modal satuan ke format Rupiah
     * 
     * @return string
     */
    public function getModalSatuanFormatAttribute()
    {
        return 'Rp ' . number_format($this->modal_satuan, 0, ',', '.');
    }

    /**
     * Accessor: Format total modal ke format Rupiah
     * 
     * @return string
     */
    public function getTotalModalFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_modal, 0, ',', '.');
    }

    /**
     * Accessor: Format profit ke format Rupiah
     * 
     * @return string
     */
    public function getProfitFormatAttribute()
    {
        return 'Rp ' . number_format($this->profit, 0, ',', '.');
    }

    /**
     * Accessor: Status profit (untung/rugi)
     * 
     * @return string
     */
    public function getProfitStatusAttribute()
    {
        if ($this->profit > 0) {
            return 'Untung';
        } elseif ($this->profit < 0) {
            return 'Rugi';
        } else {
            return 'Break Even';
        }
    }

    /**
     * Accessor: Persentase profit dari total
     * 
     * @return float
     */
    public function getProfitPercentageAttribute()
    {
        if ($this->total > 0) {
            return round(($this->profit / $this->total) * 100, 2);
        }
        return 0;
    }

    /**
     * Scope: Filter berdasarkan laporan_id
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $laporanId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByLaporan($query, $laporanId)
    {
        return $query->where('laporan_id', $laporanId);
    }

    /**
     * Scope: Filter detail yang untung
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProfitable($query)
    {
        return $query->where('profit', '>', 0);
    }

    /**
     * Scope: Filter detail yang rugi
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLosses($query)
    {
        return $query->where('profit', '<', 0);
    }

    /**
     * Mutator: Hitung profit otomatis sebelum save
     * 
     * @param float $value
     */
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = $value;
        
        // Auto-calculate profit jika total_modal sudah ada
        if (isset($this->attributes['total_modal'])) {
            $this->attributes['profit'] = $value - $this->attributes['total_modal'];
        }
    }

    /**
     * Mutator: Hitung total modal dan profit otomatis
     * 
     * @param float $value
     */
    public function setModalSatuanAttribute($value)
    {
        $this->attributes['modal_satuan'] = $value;
        
        // Auto-calculate total_modal dan profit
        if (isset($this->attributes['jumlah'])) {
            $this->attributes['total_modal'] = $value * $this->attributes['jumlah'];
            
            if (isset($this->attributes['total'])) {
                $this->attributes['profit'] = $this->attributes['total'] - $this->attributes['total_modal'];
            }
        }
    }
}