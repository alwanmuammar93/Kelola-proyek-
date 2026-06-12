// ========================================
// PUSH NOTIFICATION MANAGER
// ========================================
// File: public/js/push-notification-manager.js

class PushNotificationManager {
    constructor() {
        this.messaging = null;
        this.currentToken = null;
        this.isSupported = false;
        this.permissionStatus = "default";
    }

    // ========================================
    // INITIALIZE
    // ========================================
    async initialize() {
        console.log("🔔 Initializing Push Notification Manager...");

        // Check if browser supports notifications
        if (!("Notification" in window)) {
            console.warn("⚠️ Browser tidak support notification");
            return false;
        }

        // Check if service worker is supported
        if (!("serviceWorker" in navigator)) {
            console.warn("⚠️ Browser tidak support service worker");
            return false;
        }

        this.isSupported = true;
        this.permissionStatus = Notification.permission;

        // Initialize Firebase
        const initialized = window.initializeFirebase();
        if (!initialized) {
            console.error("❌ Firebase initialization failed");
            return false;
        }

        this.messaging = window.getMessaging();

        // Register Service Worker
        await this.registerServiceWorker();

        // Check current token status
        await this.checkTokenStatus();

        console.log("✅ Push Notification Manager initialized");
        return true;
    }

    // ========================================
    // REGISTER SERVICE WORKER
    // ========================================
    async registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register(
                "/firebase-messaging-sw.js"
            );
            console.log("✅ Service Worker registered:", registration);
            return registration;
        } catch (error) {
            console.error("❌ Service Worker registration failed:", error);
            throw error;
        }
    }

    // ========================================
    // CHECK TOKEN STATUS
    // ========================================
    async checkTokenStatus() {
        if (this.permissionStatus === "granted") {
            try {
                this.currentToken = await this.messaging.getToken({
                    vapidKey: window.vapidKey,
                    serviceWorkerRegistration: await navigator.serviceWorker
                        .ready,
                });

                if (this.currentToken) {
                    console.log(
                        "✅ FCM Token exists:",
                        this.currentToken.substring(0, 20) + "..."
                    );
                    return true;
                }
            } catch (error) {
                console.error("❌ Error getting token:", error);
            }
        }
        return false;
    }

    // ========================================
    // REQUEST PERMISSION & GET TOKEN
    // ========================================
    async requestPermission() {
        console.log("🔔 Requesting notification permission...");

        try {
            // Request notification permission
            const permission = await Notification.requestPermission();
            this.permissionStatus = permission;

            if (permission !== "granted") {
                console.warn("⚠️ Notification permission denied");
                return {
                    success: false,
                    message:
                        "Izin notifikasi ditolak. Silakan aktifkan di pengaturan browser.",
                };
            }

            console.log("✅ Notification permission granted");

            // Get FCM Token
            const token = await this.messaging.getToken({
                vapidKey: window.vapidKey,
                serviceWorkerRegistration: await navigator.serviceWorker.ready,
            });

            if (!token) {
                throw new Error("Failed to get FCM token");
            }

            this.currentToken = token;
            console.log("✅ FCM Token:", token.substring(0, 20) + "...");

            // Save token to server
            const saved = await this.saveTokenToServer(token);

            if (saved) {
                return {
                    success: true,
                    message: "Push notification berhasil diaktifkan!",
                    token: token,
                };
            } else {
                throw new Error("Failed to save token to server");
            }
        } catch (error) {
            console.error("❌ Error requesting permission:", error);
            return {
                success: false,
                message:
                    "Gagal mengaktifkan push notification: " + error.message,
            };
        }
    }

    // ========================================
    // SAVE TOKEN TO SERVER
    // ========================================
    async saveTokenToServer(token) {
        try {
            const response = await fetch("/settings/fcm-token", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
                body: JSON.stringify({ fcm_token: token }),
            });

            const data = await response.json();

            if (data.success) {
                console.log("✅ Token saved to server");
                return true;
            } else {
                console.error("❌ Server error:", data.message);
                return false;
            }
        } catch (error) {
            console.error("❌ Error saving token:", error);
            return false;
        }
    }

    // ========================================
    // UNSUBSCRIBE (REMOVE TOKEN)
    // ========================================
    async unsubscribe() {
        console.log("🔕 Unsubscribing from push notifications...");

        try {
            // Delete token from Firebase
            if (this.currentToken) {
                await this.messaging.deleteToken();
                console.log("✅ FCM Token deleted");
            }

            // Remove token from server
            const response = await fetch("/settings/fcm-token", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (data.success) {
                this.currentToken = null;
                console.log("✅ Token removed from server");
                return {
                    success: true,
                    message: "Push notification berhasil dinonaktifkan",
                };
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error("❌ Error unsubscribing:", error);
            return {
                success: false,
                message: "Gagal menonaktifkan: " + error.message,
            };
        }
    }

    // ========================================
    // HANDLE FOREGROUND MESSAGES (✅ DIPERBAIKI)
    // ========================================
    onMessageReceived(callback) {
        if (!this.messaging) {
            console.error("❌ Messaging not initialized");
            return;
        }

        this.messaging.onMessage((payload) => {
            console.log("🔔 Message received (foreground):", payload);
            console.log("📨 Notification data:", payload.notification);

            // ✅ PERBAIKAN: Tampilkan sebagai NATIVE Windows notification
            if (Notification.permission === "granted") {
                const notificationTitle = payload.notification?.title || "Kelola Proyek";
                const notificationOptions = {
                    body: payload.notification?.body || "Notifikasi baru",
                    icon: payload.notification?.icon || this.getDefaultIconDataURI(),
                    badge: this.getDefaultIconDataURI(),
                    tag: 'notif-' + Date.now(), // Unique tag
                    requireInteraction: false, // Auto dismiss
                    silent: false,
                    data: payload.data || {}
                };

                // Buat native notification
                const notification = new Notification(notificationTitle, notificationOptions);

                // Handle click
                notification.onclick = (event) => {
                    event.preventDefault();
                    window.focus();
                    
                    // Navigate to URL if exists
                    const url = payload.data?.url || "/admin";
                    if (url !== window.location.pathname) {
                        window.location.href = url;
                    }
                    
                    notification.close();
                };

                // Auto-close after 8 seconds
                setTimeout(() => {
                    notification.close();
                }, 8000);

                console.log("✅ Native Windows notification displayed");
            } else {
                console.warn("⚠️ Notification permission not granted");
            }

            // Call custom callback if provided
            if (callback && typeof callback === "function") {
                callback(payload);
            }
        });
    }

    // ========================================
    // HELPER: DEFAULT ICON DATA URI (✅ BARU)
    // ========================================
    getDefaultIconDataURI() {
        // Bell icon as SVG data URI (fallback icon)
        return 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%234F46E5"%3E%3Cpath d="M12 2c1.1 0 2 .9 2 2v1h1c1.1 0 2 .9 2 2v8l2 2v1H5v-1l2-2V7c0-1.1.9-2 2-2h1V4c0-1.1.9-2 2-2zm0 18c-1.1 0-2-.9-2-2h4c0 1.1-.9 2-2 2z"/%3E%3C/svg%3E';
    }

    // ========================================
    // CHECK SUPPORT
    // ========================================
    static isNotificationSupported() {
        return "Notification" in window && "serviceWorker" in navigator;
    }

    // ========================================
    // GET PERMISSION STATUS
    // ========================================
    getPermissionStatus() {
        return this.permissionStatus;
    }

    // ========================================
    // CHECK IF SUBSCRIBED
    // ========================================
    isSubscribed() {
        return (
            this.currentToken !== null && this.permissionStatus === "granted"
        );
    }
}

// ========================================
// INITIALIZE GLOBALLY
// ========================================
window.PushNotificationManager = PushNotificationManager;

// Create global instance
window.pushNotificationManager = null;

// Auto-initialize when DOM ready
document.addEventListener("DOMContentLoaded", async () => {
    console.log("📱 Initializing Push Notification Manager...");

    window.pushNotificationManager = new PushNotificationManager();
    await window.pushNotificationManager.initialize();

    // Setup foreground message handler
    window.pushNotificationManager.onMessageReceived((payload) => {
        console.log("🔔 Notification received in foreground:", payload);

        // ✅ TIDAK PERLU ALERT LAGI - sudah ditampilkan sebagai native notification
        // Tampilkan toast/alert hanya jika function tersedia (optional)
        if (typeof showNotification === "function") {
            showNotification(
                "success",
                "Notifikasi baru diterima"
            );
        }
    });

    console.log("✅ Push Notification Manager ready");
});

console.log("✅ Push Notification Manager script loaded");