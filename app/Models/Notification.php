<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'icon',
        'title',
        'message',
        'reference_id',
        'reference_name',
        'status',
        'user_id',
    ];

    /**
     * Relasi ke User (jika ada)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Ambil notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope: Ambil notifikasi berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessor: Format waktu relatif (5 menit yang lalu, 1 jam yang lalu)
     */
    public function getTimeAgoAttribute()
    {
        Carbon::setLocale('id');
        return $this->created_at->diffForHumans();
    }

    /**
     * Helper: Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    // ========================================================================
    // 🔥 NOTIFIKASI RAB
    // ========================================================================
    
    /**
     * Helper: Buat notifikasi RAB baru
     */
    public static function createRabNotification($type, $rab)
    {
        $config = self::getRabNotificationConfig($type, $rab);
        
        return self::create([
            'type' => 'rab',
            'icon' => $config['icon'],
            'title' => $config['title'],
            'message' => $config['message'],
            'reference_id' => $rab->id ?? null, // ✅ PERBAIKAN: Gunakan ID numerik database
            'reference_name' => $rab->no_rab ?? 'N/A', // ✅ PERBAIKAN: Simpan no_rab di reference_name
            'status' => 'unread',
        ]);
    }

    /**
     * Config untuk notifikasi RAB berdasarkan tipe
     */
    private static function getRabNotificationConfig($type, $rab)
    {
        $namaProyek = $rab->proyek ? $rab->proyek->nama_proyek : 'N/A';
        $totalFormatted = number_format($rab->total, 0, ',', '.');
        $noRab = $rab->no_rab ?? 'N/A';
        
        $configs = [
            'created' => [
                'icon' => 'bi-file-earmark-plus-fill',
                'title' => 'RAB Baru Dibuat',
                'message' => "RAB {$noRab} untuk proyek {$namaProyek} telah dibuat dengan total Rp {$totalFormatted}",
            ],
            'belum_disetujui' => [
                'icon' => 'bi-exclamation-triangle-fill',
                'title' => 'RAB Menunggu Persetujuan',
                'message' => "RAB {$noRab} menunggu persetujuan. Total anggaran: Rp {$totalFormatted}",
            ],
            'disetujui' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'RAB Disetujui',
                'message' => "RAB {$noRab} telah disetujui. Proyek dapat dimulai dengan anggaran Rp {$totalFormatted}",
            ],
            'berjalan' => [
                'icon' => 'bi-hourglass-split',
                'title' => 'RAB Sedang Berjalan',
                'message' => "RAB {$noRab} sedang dalam tahap pengerjaan. Pastikan progres sesuai rencana.",
            ],
            'selesai' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'RAB Selesai',
                'message' => "RAB {$noRab} untuk proyek {$namaProyek} telah selesai dikerjakan.",
            ],
            'updated' => [
                'icon' => 'bi-pencil-square',
                'title' => 'RAB Diperbarui',
                'message' => "RAB {$noRab} telah diperbarui. Status: {$rab->status}",
            ],
            'deleted' => [
                'icon' => 'bi-trash-fill',
                'title' => 'RAB Dihapus',
                'message' => "RAB {$noRab} telah dihapus dari sistem.",
            ],
        ];

        return $configs[$type] ?? [
            'icon' => 'bi-info-circle-fill',
            'title' => 'Notifikasi RAB',
            'message' => "Terjadi perubahan pada RAB {$noRab}",
        ];
    }

    // ========================================================================
    // 🔥 NOTIFIKASI PROYEK
    // ========================================================================
    
    /**
     * Helper: Buat notifikasi Proyek baru
     */
    public static function createProyekNotification($type, $proyek)
    {
        $config = self::getProyekNotificationConfig($type, $proyek);
        
        return self::create([
            'type' => 'proyek',
            'icon' => $config['icon'],
            'title' => $config['title'],
            'message' => $config['message'],
            'reference_id' => $proyek->id ?? null, // ✅ PERBAIKAN: Gunakan ID numerik database
            'reference_name' => $proyek->nama_proyek ?? 'N/A', // ✅ PERBAIKAN: Simpan nama_proyek di reference_name
            'status' => 'unread',
        ]);
    }

    /**
     * Config untuk notifikasi Proyek berdasarkan tipe
     */
    private static function getProyekNotificationConfig($type, $proyek)
    {
        $namaOwner = $proyek->nama_owner ?? 'N/A';
        $jumlahRab = $proyek->rabs ? $proyek->rabs->count() : 0;
        $namaProyek = $proyek->nama_proyek ?? 'N/A';
        
        $configs = [
            'created' => [
                'icon' => 'bi-folder-plus',
                'title' => 'Proyek Baru Ditambahkan',
                'message' => "Proyek \"{$namaProyek}\" untuk owner {$namaOwner} telah ditambahkan dengan status: {$proyek->status}",
            ],
            'rab_belum_dibuat' => [
                'icon' => 'bi-exclamation-circle-fill',
                'title' => 'Proyek Menunggu RAB',
                'message' => "Proyek \"{$namaProyek}\" masih menunggu pembuatan RAB. Segera buat RAB untuk melanjutkan proyek.",
            ],
            'rab_telah_dibuat' => [
                'icon' => 'bi-file-earmark-check-fill',
                'title' => 'RAB Proyek Telah Dibuat',
                'message' => "Proyek \"{$namaProyek}\" telah memiliki RAB. Total {$jumlahRab} RAB telah dibuat.",
            ],
            'proyek_dikerjakan' => [
                'icon' => 'bi-tools',
                'title' => 'Proyek Sedang Dikerjakan',
                'message' => "Proyek \"{$namaProyek}\" sedang dalam tahap pengerjaan. Pastikan progres berjalan sesuai rencana.",
            ],
            'proyek_selesai' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'Proyek Selesai',
                'message' => "Proyek \"{$namaProyek}\" telah selesai dikerjakan. Silakan lakukan evaluasi akhir.",
            ],
            'status_changed' => [
                'icon' => 'bi-arrow-repeat',
                'title' => 'Status Proyek Berubah',
                'message' => "Status proyek \"{$namaProyek}\" telah diperbarui menjadi: {$proyek->status}",
            ],
            'updated' => [
                'icon' => 'bi-pencil-square',
                'title' => 'Proyek Diperbarui',
                'message' => "Informasi proyek \"{$namaProyek}\" telah diperbarui.",
            ],
            'deleted' => [
                'icon' => 'bi-trash-fill',
                'title' => 'Proyek Dihapus',
                'message' => "Proyek \"{$namaProyek}\" telah dihapus dari sistem.",
            ],
        ];

        return $configs[$type] ?? [
            'icon' => 'bi-info-circle-fill',
            'title' => 'Notifikasi Proyek',
            'message' => "Terjadi perubahan pada proyek \"{$namaProyek}\"",
        ];
    }

    // ========================================================================
    // 🔥 NOTIFIKASI PENJUALAN
    // ========================================================================
    
    /**
     * Helper: Buat notifikasi Penjualan baru
     */
    public static function createPenjualanNotification($type, $penjualan)
    {
        $config = self::getPenjualanNotificationConfig($type, $penjualan);
        
        return self::create([
            'type' => 'penjualan',
            'icon' => $config['icon'],
            'title' => $config['title'],
            'message' => $config['message'],
            'reference_id' => $penjualan->id ?? null, // ✅ PERBAIKAN: Gunakan ID numerik database
            'reference_name' => $penjualan->no_invoice ?? $penjualan->nama_pelanggan ?? 'N/A', // ✅ PERBAIKAN: Simpan no_invoice di reference_name
            'status' => 'unread',
        ]);
    }

    /**
     * Config untuk notifikasi Penjualan berdasarkan tipe
     */
    private static function getPenjualanNotificationConfig($type, $penjualan)
    {
        $namaPelanggan = $penjualan->nama_pelanggan ?? 'N/A';
        $noInvoice = $penjualan->no_invoice ?? 'N/A';
        $totalFormatted = isset($penjualan->total) ? number_format($penjualan->total, 0, ',', '.') : '0';
        
        $configs = [
            'created' => [
                'icon' => 'bi-cart-plus-fill',
                'title' => 'Penjualan Baru Dibuat',
                'message' => "Penjualan #{$noInvoice} untuk pelanggan {$namaPelanggan} telah dibuat dengan total Rp {$totalFormatted}",
            ],
            'pending' => [
                'icon' => 'bi-clock-fill',
                'title' => 'Penjualan Menunggu Pembayaran',
                'message' => "Penjualan #{$noInvoice} menunggu pembayaran dari {$namaPelanggan}. Total: Rp {$totalFormatted}",
            ],
            'paid' => [
                'icon' => 'bi-credit-card-fill',
                'title' => 'Pembayaran Diterima',
                'message' => "Pembayaran penjualan #{$noInvoice} dari {$namaPelanggan} telah diterima. Total: Rp {$totalFormatted}",
            ],
            'shipped' => [
                'icon' => 'bi-truck',
                'title' => 'Pesanan Dikirim',
                'message' => "Pesanan #{$noInvoice} untuk {$namaPelanggan} telah dikirim.",
            ],
            'completed' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'Penjualan Selesai',
                'message' => "Penjualan #{$noInvoice} untuk {$namaPelanggan} telah selesai.",
            ],
            'cancelled' => [
                'icon' => 'bi-x-circle-fill',
                'title' => 'Penjualan Dibatalkan',
                'message' => "Penjualan #{$noInvoice} untuk {$namaPelanggan} telah dibatalkan.",
            ],
            'updated' => [
                'icon' => 'bi-pencil-square',
                'title' => 'Penjualan Diperbarui',
                'message' => "Penjualan #{$noInvoice} telah diperbarui.",
            ],
            'deleted' => [
                'icon' => 'bi-trash-fill',
                'title' => 'Penjualan Dihapus',
                'message' => "Penjualan #{$noInvoice} telah dihapus dari sistem.",
            ],
        ];

        return $configs[$type] ?? [
            'icon' => 'bi-info-circle-fill',
            'title' => 'Notifikasi Penjualan',
            'message' => "Terjadi perubahan pada penjualan #{$noInvoice}",
        ];
    }

    // ========================================================================
    // 🔥 NOTIFIKASI KWITANSI - ✅ DIPERBAIKI LENGKAP
    // ========================================================================
    
    /**
     * Helper: Buat notifikasi Kwitansi baru
     */
    public static function createKwitansiNotification($type, $kwitansi)
    {
        $config = self::getKwitansiNotificationConfig($type, $kwitansi);
        
        return self::create([
            'type' => 'kwitansi',
            'icon' => $config['icon'],
            'title' => $config['title'],
            'message' => $config['message'],
            'reference_id' => $kwitansi->id ?? null, // ✅ PERBAIKAN: Gunakan ID numerik database (auto increment)
            'reference_name' => $kwitansi->no_kwitansi ?? $kwitansi->Id_Kwitansi ?? 'N/A', // ✅ PERBAIKAN: Simpan ID display (KWT-xxx) di reference_name
            'status' => 'unread',
        ]);
    }

    /**
     * Config untuk notifikasi Kwitansi berdasarkan tipe
     */
    private static function getKwitansiNotificationConfig($type, $kwitansi)
    {
        $noKwitansi = $kwitansi->no_kwitansi ?? $kwitansi->Id_Kwitansi ?? 'N/A';
        $penerima = $kwitansi->penerima ?? $kwitansi->Sales ?? $kwitansi->nama_penerima ?? 'N/A';
        $totalFormatted = isset($kwitansi->total) ? number_format($kwitansi->total, 0, ',', '.') : '0';
        $jumlahFormatted = isset($kwitansi->jumlah) ? number_format($kwitansi->jumlah, 0, ',', '.') : (isset($kwitansi->Total_Pembayaran) ? number_format($kwitansi->Total_Pembayaran, 0, ',', '.') : '0');
        
        $configs = [
            'created' => [
                'icon' => 'bi-receipt',
                'title' => 'Kwitansi Baru Dibuat',
                'message' => "Kwitansi {$noKwitansi} telah dibuat untuk {$penerima} dengan nominal Rp {$jumlahFormatted}",
            ],
            'lunas' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'Kwitansi Lunas',
                'message' => "Kwitansi {$noKwitansi} untuk {$penerima} telah lunas. Nominal: Rp {$jumlahFormatted}",
            ],
            'belum_lunas' => [
                'icon' => 'bi-exclamation-triangle-fill',
                'title' => 'Kwitansi Belum Lunas',
                'message' => "Kwitansi {$noKwitansi} untuk {$penerima} masih menunggu pembayaran. Nominal: Rp {$jumlahFormatted}",
            ],
            'printed' => [
                'icon' => 'bi-printer-fill',
                'title' => 'Kwitansi Dicetak',
                'message' => "Kwitansi {$noKwitansi} telah dicetak untuk {$penerima}.",
            ],
            'updated' => [
                'icon' => 'bi-pencil-square',
                'title' => 'Kwitansi Diperbarui',
                'message' => "Kwitansi {$noKwitansi} telah diperbarui.",
            ],
            'deleted' => [
                'icon' => 'bi-trash-fill',
                'title' => 'Kwitansi Dihapus',
                'message' => "Kwitansi {$noKwitansi} telah dihapus dari sistem.",
            ],
        ];

        return $configs[$type] ?? [
            'icon' => 'bi-info-circle-fill',
            'title' => 'Notifikasi Kwitansi',
            'message' => "Terjadi perubahan pada kwitansi {$noKwitansi}",
        ];
    }

    // ========================================================================
    // 🔥 NOTIFIKASI LAPORAN
    // ========================================================================
    
    /**
     * Helper: Buat notifikasi Laporan baru
     */
    public static function createLaporanNotification($type, $laporan)
    {
        $config = self::getLaporanNotificationConfig($type, $laporan);
        
        return self::create([
            'type' => 'laporan',
            'icon' => $config['icon'],
            'title' => $config['title'],
            'message' => $config['message'],
            'reference_id' => $laporan->id ?? null, // ✅ PERBAIKAN: Gunakan ID numerik database
            'reference_name' => $laporan->judul ?? $laporan->jenis_laporan ?? 'N/A', // ✅ PERBAIKAN: Simpan judul di reference_name
            'status' => 'unread',
        ]);
    }

    /**
     * Config untuk notifikasi Laporan berdasarkan tipe
     */
    private static function getLaporanNotificationConfig($type, $laporan)
    {
        $judulLaporan = $laporan->judul ?? $laporan->jenis_laporan ?? 'N/A';
        $periode = $laporan->periode ?? 'N/A';
        
        $configs = [
            'created' => [
                'icon' => 'bi-file-earmark-text-fill',
                'title' => 'Laporan Baru Dibuat',
                'message' => "Laporan \"{$judulLaporan}\" periode {$periode} telah dibuat.",
            ],
            'draft' => [
                'icon' => 'bi-file-earmark-minus',
                'title' => 'Laporan Draft',
                'message' => "Laporan \"{$judulLaporan}\" masih dalam status draft. Segera lengkapi untuk dipublikasikan.",
            ],
            'reviewed' => [
                'icon' => 'bi-eye-fill',
                'title' => 'Laporan Direview',
                'message' => "Laporan \"{$judulLaporan}\" sedang dalam proses review.",
            ],
            'approved' => [
                'icon' => 'bi-check-circle-fill',
                'title' => 'Laporan Disetujui',
                'message' => "Laporan \"{$judulLaporan}\" telah disetujui dan siap dipublikasikan.",
            ],
            'published' => [
                'icon' => 'bi-globe',
                'title' => 'Laporan Dipublikasikan',
                'message' => "Laporan \"{$judulLaporan}\" periode {$periode} telah dipublikasikan.",
            ],
            'rejected' => [
                'icon' => 'bi-x-circle-fill',
                'title' => 'Laporan Ditolak',
                'message' => "Laporan \"{$judulLaporan}\" ditolak. Silakan perbaiki sesuai catatan reviewer.",
            ],
            'updated' => [
                'icon' => 'bi-pencil-square',
                'title' => 'Laporan Diperbarui',
                'message' => "Laporan \"{$judulLaporan}\" telah diperbarui.",
            ],
            'deleted' => [
                'icon' => 'bi-trash-fill',
                'title' => 'Laporan Dihapus',
                'message' => "Laporan \"{$judulLaporan}\" telah dihapus dari sistem.",
            ],
        ];

        return $configs[$type] ?? [
            'icon' => 'bi-info-circle-fill',
            'title' => 'Notifikasi Laporan',
            'message' => "Terjadi perubahan pada laporan \"{$judulLaporan}\"",
        ];
    }
}