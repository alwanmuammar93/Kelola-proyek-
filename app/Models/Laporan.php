<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'laporan';

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
        'nama_laporan',
        'tanggal',
        'no_nota',
        'owner',
        'total_profit'
    ];

    /**
     * Field yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'tanggal' => 'date',
        'total_profit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel laporan_details
     * 1 laporan memiliki banyak detail laporan
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(LaporanDetail::class, 'laporan_id', 'id');
    }

    /**
     * Accessor: Format tanggal ke format Indonesia
     * 
     * @return string
     */
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    /**
     * Accessor: Format total profit ke format Rupiah
     * 
     * @return string
     */
    public function getTotalProfitFormatAttribute()
    {
        return 'Rp ' . number_format($this->total_profit, 0, ',', '.');
    }

    /**
     * Scope: Filter berdasarkan tanggal
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tanggal
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    /**
     * Scope: Filter berdasarkan owner
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $owner
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByOwner($query, $owner)
    {
        return $query->where('owner', 'like', '%' . $owner . '%');
    }

    /**
     * Scope: Order by tanggal terbaru
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');
    }
}