<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Get all user settings
     */
    public function index()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'settings' => [
                'theme' => $user->theme_preference ?? 'light',
                'notification_email' => $user->notification_email ?? true,
                'notification_system' => $user->notification_system ?? true,
                'push_notifications_enabled' => !empty($user->fcm_token), // 🔥 NEW
                'two_factor_enabled' => $user->two_factor_enabled ?? false,
            ]
        ]);
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'notification_email' => 'required|boolean',
            'notification_system' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update([
                'notification_email' => $request->notification_email,
                'notification_system' => $request->notification_system,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan notifikasi berhasil diperbarui',
                'settings' => [
                    'notification_email' => $user->notification_email,
                    'notification_system' => $user->notification_system,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NEW: Save FCM Token for Push Notifications
     */
    public function saveFcmToken(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string|min:10',
        ], [
            'fcm_token.required' => 'FCM Token tidak boleh kosong',
            'fcm_token.string' => 'FCM Token harus berupa string',
            'fcm_token.min' => 'FCM Token tidak valid (terlalu pendek)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Save FCM Token
            $user->saveFcmToken($request->fcm_token);

            // Log untuk debugging
            Log::info('FCM Token saved', [
                'user_id' => $user->id_user,
                'username' => $user->username,
                'token_preview' => substr($request->fcm_token, 0, 20) . '...',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Push notification berhasil diaktifkan! Anda akan menerima notifikasi di perangkat ini.',
                'push_enabled' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save FCM Token', [
                'user_id' => $user->id_user,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan token: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔥 NEW: Remove FCM Token (Unsubscribe Push Notifications)
     */
    public function removeFcmToken(Request $request)
    {
        $user = auth()->user();
        
        try {
            // Remove FCM Token
            $user->removeFcmToken();

            // Log untuk debugging
            Log::info('FCM Token removed', [
                'user_id' => $user->id_user,
                'username' => $user->username,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Push notification berhasil dinonaktifkan.',
                'push_enabled' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to remove FCM Token', [
                'user_id' => $user->id_user,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan push notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update theme preference
     */
    public function updateTheme(Request $request)
    {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'theme' => 'required|in:light,dark',
        ], [
            'theme.required' => 'Tema harus dipilih',
            'theme.in' => 'Tema harus light atau dark',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user->update(['theme_preference' => $request->theme]);

            return response()->json([
                'success' => true,
                'message' => 'Tema berhasil diubah ke ' . ($request->theme === 'dark' ? 'gelap' : 'terang'),
                'theme' => $user->theme_preference
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah tema: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        // Placeholder untuk privacy settings
        return response()->json([
            'success' => true,
            'message' => 'Pengaturan privasi berhasil diperbarui'
        ]);
    }

    /**
     * Enable Two-Factor Authentication
     */
    public function enable2FA(Request $request)
    {
        $user = auth()->user();
        
        // Generate 2FA secret
        $secret = bin2hex(random_bytes(16));
        
        try {
            $user->update([
                'two_factor_enabled' => true,
                'two_factor_secret' => encrypt($secret)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Two-Factor Authentication berhasil diaktifkan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan 2FA: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable Two-Factor Authentication
     */
    public function disable2FA(Request $request)
    {
        $user = auth()->user();
        
        try {
            $user->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Two-Factor Authentication berhasil dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan 2FA: ' . $e->getMessage()
            ], 500);
        }
    }
}