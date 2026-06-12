<?php

namespace App\Helpers;

use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * Send notification untuk event transaksi
     */
    public static function notifyTransactionProcessed($transactionId, $amount, $customerName)
    {
        $firebase = new FirebaseService();
        
        $title = "💰 Transaksi Berhasil Diproses";
        $body = "Transaksi {$customerName} senilai Rp " . number_format($amount, 0, ',', '.') . " telah diproses";
        
        $data = [
            'type' => 'transaction',
            'transaction_id' => (string) $transactionId,
            'url' => url('/admin/penjualan'),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all admins
        return $firebase->sendToRole('admin', $title, $body, $data);
    }

    /**
     * Send notification untuk proyek baru
     */
    public static function notifyNewProject($projectName, $projectId)
    {
        $firebase = new FirebaseService();
        
        $title = "🏗️ Proyek Baru Ditambahkan";
        $body = "Proyek '{$projectName}' telah ditambahkan ke sistem";
        
        $data = [
            'type' => 'project',
            'project_id' => (string) $projectId,
            'url' => url("/admin/proyek/{$projectId}"),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all admins
        return $firebase->sendToRole('admin', $title, $body, $data);
    }

    /**
     * Send notification untuk perubahan status proyek
     */
    public static function notifyProjectStatusChanged($projectName, $projectId, $oldStatus, $newStatus)
    {
        $firebase = new FirebaseService();
        
        $statusLabels = [
            'Belum Dibuat' => 'Belum Dibuat',
            'Dalam Proses' => 'Sedang Dikerjakan',
            'Selesai' => 'Selesai',
            'Batal' => 'Dibatalkan'
        ];
        
        $title = "🔄 Status Proyek Berubah";
        $body = "Proyek '{$projectName}' berubah dari {$statusLabels[$oldStatus]} → {$statusLabels[$newStatus]}";
        
        $data = [
            'type' => 'project_status',
            'project_id' => (string) $projectId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'url' => url("/admin/proyek/{$projectId}"),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all admins
        return $firebase->sendToRole('admin', $title, $body, $data);
    }

    /**
     * Send notification untuk RAB baru
     */
    public static function notifyNewRAB($rabNumber, $rabId, $projectName)
    {
        $firebase = new FirebaseService();
        
        $title = "📋 RAB Baru Dibuat";
        $body = "RAB {$rabNumber} untuk proyek '{$projectName}' telah dibuat";
        
        $data = [
            'type' => 'rab',
            'rab_id' => (string) $rabId,
            'url' => url("/admin/rab"),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all admins
        return $firebase->sendToRole('admin', $title, $body, $data);
    }

    /**
     * Send notification untuk kwitansi baru
     */
    public static function notifyNewKwitansi($kwitansiNumber, $kwitansiId, $amount)
    {
        $firebase = new FirebaseService();
        
        $title = "🧾 Kwitansi Baru Dibuat";
        $body = "Kwitansi {$kwitansiNumber} senilai Rp " . number_format($amount, 0, ',', '.') . " telah dibuat";
        
        $data = [
            'type' => 'kwitansi',
            'kwitansi_id' => (string) $kwitansiId,
            'url' => url("/admin/kwitansi"),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all users (admin + kasir)
        return $firebase->sendToAllUsers($title, $body, $data);
    }

    /**
     * Send notification untuk deadline reminder
     */
    public static function notifyDeadlineReminder($projectName, $projectId, $daysLeft)
    {
        $firebase = new FirebaseService();
        
        $title = "⏰ Pengingat Deadline";
        $body = "Proyek '{$projectName}' akan berakhir dalam {$daysLeft} hari lagi!";
        
        $data = [
            'type' => 'deadline',
            'project_id' => (string) $projectId,
            'days_left' => (string) $daysLeft,
            'url' => url("/admin/proyek/{$projectId}"),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all admins
        return $firebase->sendToRole('admin', $title, $body, $data);
    }

    /**
     * Send notification untuk sistem update
     */
    public static function notifySystemUpdate($message)
    {
        $firebase = new FirebaseService();
        
        $title = "🔔 Update Sistem";
        $body = $message;
        
        $data = [
            'type' => 'system',
            'url' => url('/admin'),
            'timestamp' => now()->toIso8601String()
        ];

        // Send to all users
        return $firebase->sendToAllUsers($title, $body, $data);
    }

    /**
     * Send custom notification to specific user
     */
    public static function sendCustomNotification($userId, $title, $body, $url = null)
    {
        $firebase = new FirebaseService();
        
        $data = [
            'type' => 'custom',
            'url' => $url ?? url('/admin'),
            'timestamp' => now()->toIso8601String()
        ];

        return $firebase->sendToUser($userId, $title, $body, $data);
    }

    /**
     * Send notification to specific role
     */
    public static function sendToRole($role, $title, $body, $url = null)
    {
        $firebase = new FirebaseService();
        
        $data = [
            'type' => 'announcement',
            'url' => $url ?? url('/admin'),
            'timestamp' => now()->toIso8601String()
        ];

        return $firebase->sendToRole($role, $title, $body, $data);
    }
}