<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Helpers\NotificationHelper;

class TestNotificationController extends Controller
{
    /**
     * Show test notification page
     */
    public function index()
    {
        return view('test-notification');
    }

    /**
     * Test send notification to yourself
     */
    public function testSendToSelf()
    {
        $user = auth()->user();

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mengaktifkan push notification. Silakan aktifkan terlebih dahulu di Pengaturan.'
            ]);
        }

        $firebase = new FirebaseService();
        
        // 🔥 FIXED: Gunakan name atau username sebagai fallback
        $userName = $user->name ?? $user->username ?? 'User';
        
        $result = $firebase->sendToDevice(
            $user->fcm_token,
            '🎉 Test Notification',
            "Ini adalah test notification untuk {$userName}. Push notification Anda berfungsi dengan baik!",
            [
                'type' => 'test',
                'url' => url('/admin'),
                'timestamp' => now()->toIso8601String()
            ]
        );

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Test notification berhasil dikirim! Cek perangkat Anda.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim test notification. Cek log untuk detail.'
            ], 500);
        }
    }

    /**
     * Test send to all admins
     */
    public function testSendToAdmins()
    {
        $result = NotificationHelper::sendToRole(
            'admin',
            '👨‍💼 Test untuk Admin',
            'Ini adalah test notification untuk semua admin'
        );

        return response()->json([
            'success' => true,
            'message' => "Notification sent to admins",
            'result' => $result
        ]);
    }

    /**
     * Test send to all users
     */
    public function testSendToAll()
    {
        $firebase = new FirebaseService();
        
        $result = $firebase->sendToAllUsers(
            '📢 Pengumuman Sistem',
            'Ini adalah test broadcast notification untuk semua user',
            [
                'type' => 'broadcast',
                'url' => url('/admin'),
                'timestamp' => now()->toIso8601String()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => "Broadcast notification sent",
            'result' => $result
        ]);
    }

    /**
     * Test notification untuk transaksi
     */
    public function testTransactionNotification()
    {
        $result = NotificationHelper::notifyTransactionProcessed(
            123, // transaction ID
            1500000, // amount
            'PT Contoh Perusahaan'
        );

        return response()->json([
            'success' => true,
            'message' => 'Transaction notification sent',
            'result' => $result
        ]);
    }

    /**
     * Test notification untuk proyek baru
     */
    public function testProjectNotification()
    {
        $result = NotificationHelper::notifyNewProject(
            'Renovasi Gedung Kantor',
            456 // project ID
        );

        return response()->json([
            'success' => true,
            'message' => 'Project notification sent',
            'result' => $result
        ]);
    }

    /**
     * Test notification untuk perubahan status proyek
     */
    public function testProjectStatusNotification()
    {
        $result = NotificationHelper::notifyProjectStatusChanged(
            'Renovasi Gedung Kantor',
            456,
            'Belum Dibuat',
            'Dalam Proses'
        );

        return response()->json([
            'success' => true,
            'message' => 'Project status notification sent',
            'result' => $result
        ]);
    }

    /**
     * Test custom notification
     */
    public function testCustomNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mengaktifkan push notification'
            ]);
        }

        $firebase = new FirebaseService();
        
        $result = $firebase->sendToDevice(
            $user->fcm_token,
            $request->title,
            $request->body,
            [
                'type' => 'custom',
                'url' => $request->url ?? url('/admin'),
                'timestamp' => now()->toIso8601String()
            ]
        );

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Custom notification sent!' : 'Failed to send notification'
        ]);
    }
}