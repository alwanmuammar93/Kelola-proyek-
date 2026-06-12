// ========================================
// FIREBASE MESSAGING SERVICE WORKER
// ========================================
// File: public/firebase-messaging-sw.js

// IMPORTANT: File ini HARUS ada di root public/ folder
// URL: https://your-domain.com/firebase-messaging-sw.js

// Import Firebase scripts dari CDN
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDOkms5WqSe7NUZa9elfWuTsnUHGB4n0mQ",
    authDomain: "kelolaproyeknotif.firebaseapp.com",
    projectId: "kelolaproyeknotif",
    storageBucket: "kelolaproyeknotif.firebasestorage.app",
    messagingSenderId: "1094928517724",
    appId: "1:1094928517724:web:07c430d9b6847225fe3499"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Messaging
const messaging = firebase.messaging();

// ========================================
// HANDLE BACKGROUND MESSAGES
// ========================================
// Notification muncul saat browser/tab ditutup atau minimize
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message:', payload);

    // Ekstrak data notification
    const notificationTitle = payload.notification?.title || 'Kelola Proyek';
    const notificationOptions = {
        body: payload.notification?.body || 'Anda memiliki notifikasi baru',
        icon: payload.notification?.icon || '/images/logo.png',
        badge: '/images/badge.png',
        tag: payload.data?.tag || 'default',
        requireInteraction: true, // Notification tidak auto-close
        data: {
            url: payload.data?.url || '/',
            ...payload.data
        },
        actions: [
            {
                action: 'open',
                title: 'Buka'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ]
    };

    // Tampilkan notification
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// ========================================
// HANDLE NOTIFICATION CLICK
// ========================================
self.addEventListener('notificationclick', (event) => {
    console.log('[Service Worker] Notification clicked:', event);

    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/';

    if (event.action === 'open' || !event.action) {
        // Buka URL
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true })
                .then((clientList) => {
                    // Cek apakah ada window yang sudah buka
                    for (const client of clientList) {
                        if (client.url === urlToOpen && 'focus' in client) {
                            return client.focus();
                        }
                    }
                    // Kalau belum ada, buka window baru
                    if (clients.openWindow) {
                        return clients.openWindow(urlToOpen);
                    }
                })
        );
    } else if (event.action === 'close') {
        // User klik "Tutup", tidak perlu action
        console.log('[Service Worker] Notification closed by user');
    }
});

// ========================================
// HANDLE SERVICE WORKER INSTALL & ACTIVATE
// ========================================
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activated');
    event.waitUntil(clients.claim());
});

console.log('[Service Worker] Firebase Messaging SW loaded successfully')