// ========================================
// FIREBASE CONFIGURATION
// ========================================
// File: public/js/firebase-config.js

// 🔥 Firebase Config dari Firebase Console
const firebaseConfig = {
    apiKey: "AIzaSyDOkms5WqSe7NUZa9elfWuTsnUHGB4n0mQ",
    authDomain: "kelolaproyeknotif.firebaseapp.com",
    projectId: "kelolaproyeknotif",
    storageBucket: "kelolaproyeknotif.firebasestorage.app",
    messagingSenderId: "1094928517724",
    appId: "1:1094928517724:web:07c430d9b6847225fe3499",
    measurementId: "G-1RYYNWPCH1"
};

// 🔥 VAPID Key (Web Push Certificate)
const vapidKey = "BCrWkGm_m3016DcarDzG2guyh29XAg12d-ww015ynuLe33yb3o0QzW8Gh6zX_s9dLP_I2EdMJa2xOBCINWBcSd8";

// Initialize Firebase (akan dipanggil oleh push-notification-manager.js)
let app;
let messaging;

function initializeFirebase() {
    try {
        // Import Firebase dari CDN sudah dilakukan di HTML
        if (typeof firebase === 'undefined') {
            console.error('❌ Firebase SDK not loaded!');
            return false;
        }

        // Initialize Firebase App
        app = firebase.initializeApp(firebaseConfig);
        console.log('✅ Firebase initialized successfully');

        // Initialize Firebase Messaging
        if (firebase.messaging.isSupported()) {
            messaging = firebase.messaging();
            console.log('✅ Firebase Messaging is supported');
            return true;
        } else {
            console.warn('⚠️ Firebase Messaging is not supported in this browser');
            return false;
        }
    } catch (error) {
        console.error('❌ Error initializing Firebase:', error);
        return false;
    }
}

// Export untuk digunakan oleh file lain
window.firebaseConfig = firebaseConfig;
window.vapidKey = vapidKey;
window.initializeFirebase = initializeFirebase;
window.getMessaging = () => messaging;

console.log('✅ Firebase config loaded');