<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseService
{
    /**
     * Firebase Messaging Instance
     */
    private $messaging;
    private $factory;

    /**
     * Constructor
     */
    public function __construct()
    {
        try {
            // Load Service Account JSON
            $credentialsPath = storage_path('app/firebase/kelolaproyeknotif-firebase-adminsdk-fbsvc-c298eddebe.json');
            
            if (!file_exists($credentialsPath)) {
                throw new Exception("Firebase credentials file not found at: {$credentialsPath}");
            }

            // Initialize Firebase using Kreait SDK
            $this->factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->messaging = $this->factory->createMessaging();

            Log::info('Firebase Service initialized successfully');
        } catch (Exception $e) {
            Log::error('Failed to initialize Firebase Service', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send Push Notification to Single Device
     * 
     * @param string $fcmToken - FCM Token user
     * @param string $title - Notification title
     * @param string $body - Notification body
     * @param array $data - Additional data (optional)
     * @return bool
     */
    public function sendToDevice($fcmToken, $title, $body, $data = [])
    {
        try {
            // Validate FCM token
            if (empty($fcmToken)) {
                Log::warning('Empty FCM token provided');
                return false;
            }

            // Create notification
            $notification = Notification::create($title, $body);

            // Build message with web push config
            $messageData = [
                'notification' => $notification,
                'data' => array_map('strval', $data), // Firebase requires string values
            ];

            // Add web push configuration if URL is provided
            if (isset($data['url'])) {
                $messageData['webpush'] = [
                    'fcm_options' => [
                        'link' => $data['url']
                    ]
                ];
            }

            // Create cloud message
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData(array_map('strval', $data));

            // Send message
            $this->messaging->send($message);

            Log::info('Push notification sent successfully', [
                'token' => substr($fcmToken, 0, 20) . '...',
                'title' => $title
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to send push notification', [
                'token' => substr($fcmToken, 0, 20) . '...',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send Push Notification to Multiple Devices
     * 
     * @param array $fcmTokens - Array of FCM tokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array - ['success' => int, 'failed' => int]
     */
    public function sendToMultipleDevices($fcmTokens, $title, $body, $data = [])
    {
        $success = 0;
        $failed = 0;

        // Remove empty tokens
        $fcmTokens = array_filter($fcmTokens);

        if (empty($fcmTokens)) {
            Log::warning('No valid FCM tokens provided');
            return ['success' => 0, 'failed' => 0, 'total' => 0];
        }

        foreach ($fcmTokens as $token) {
            if ($this->sendToDevice($token, $title, $body, $data)) {
                $success++;
            } else {
                $failed++;
            }
        }

        Log::info('Bulk push notification completed', [
            'success' => $success,
            'failed' => $failed,
            'total' => count($fcmTokens)
        ]);

        return [
            'success' => $success,
            'failed' => $failed,
            'total' => count($fcmTokens)
        ];
    }

    /**
     * Send Push Notification to All Users with FCM Token
     * 
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToAllUsers($title, $body, $data = [])
    {
        // Get all users with FCM token
        $users = \App\Models\User::whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->get(['fcm_token']);

        $fcmTokens = $users->pluck('fcm_token')->toArray();

        if (empty($fcmTokens)) {
            Log::warning('No users with FCM tokens found');
            return ['success' => 0, 'failed' => 0, 'total' => 0];
        }

        return $this->sendToMultipleDevices($fcmTokens, $title, $body, $data);
    }

    /**
     * Send Push Notification to Specific User by ID
     * 
     * @param int $userId
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $user = \App\Models\User::find($userId);

        if (!$user || !$user->fcm_token) {
            Log::warning('User not found or has no FCM token', ['user_id' => $userId]);
            return false;
        }

        return $this->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send Push Notification to Users by Role
     * 
     * @param string $role - 'admin' or 'kasir'
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToRole($role, $title, $body, $data = [])
    {
        $users = \App\Models\User::where('role', $role)
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->get(['fcm_token']);

        $fcmTokens = $users->pluck('fcm_token')->toArray();

        if (empty($fcmTokens)) {
            Log::warning("No {$role} users with FCM tokens found");
            return ['success' => 0, 'failed' => 0, 'total' => 0];
        }

        return $this->sendToMultipleDevices($fcmTokens, $title, $body, $data);
    }

    /**
     * Validate FCM Token
     * 
     * @param string $fcmToken
     * @return bool
     */
    public function validateToken($fcmToken)
    {
        try {
            if (empty($fcmToken)) {
                return false;
            }

            // Try to send a dry-run message to validate token
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(Notification::create('Test', 'Test'));

            // Note: Kreait SDK doesn't have built-in dry-run, 
            // so we just check if token format is valid
            return strlen($fcmToken) > 50; // Basic validation
        } catch (Exception $e) {
            Log::error('Token validation failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}