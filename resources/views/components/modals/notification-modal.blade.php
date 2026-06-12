<!-- Modal Notification Settings -->
<div id="notificationModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-bell"></i> Pengaturan Notifikasi
            </h2>
            <button type="button" class="modal-close" onclick="closeNotificationModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="notificationForm">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Atur bagaimana Anda ingin menerima notifikasi dari sistem.
                </div>

                <!-- Email Notifications -->
                <div class="setting-card">
                    <div class="setting-info">
                        <div class="setting-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="setting-details">
                            <h3>Notifikasi Email</h3>
                            <p>Terima notifikasi melalui email untuk update penting</p>
                        </div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                id="notification_email" 
                                name="notification_email"
                                {{ auth()->user()->notification_email ? 'checked' : '' }}
                            >
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- System Notifications -->
                <div class="setting-card">
                    <div class="setting-info">
                        <div class="setting-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="setting-details">
                            <h3>Notifikasi Sistem</h3>
                            <p>Terima notifikasi di dalam aplikasi saat ada aktivitas baru</p>
                        </div>
                    </div>
                    <div class="setting-control">
                        <label class="toggle-switch">
                            <input 
                                type="checkbox" 
                                id="notification_system" 
                                name="notification_system"
                                {{ auth()->user()->notification_system ? 'checked' : '' }}
                            >
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- 🔥 Push Notifications -->
                <div class="setting-card" id="pushNotificationCard">
                    <div class="setting-info">
                        <div class="setting-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="setting-details">
                            <h3>Push Notification</h3>
                            <p id="pushNotificationStatus">Terima notifikasi langsung ke perangkat Anda</p>
                        </div>
                    </div>
                    <div class="setting-control">
                        <!-- Button untuk aktifkan push notification -->
                        <button 
                            type="button" 
                            class="btn-push-toggle" 
                            id="pushNotificationBtn"
                            onclick="togglePushNotification()"
                        >
                            <i class="fas fa-bell"></i>
                            <span id="pushBtnText">Aktifkan</span>
                        </button>
                    </div>
                </div>

                <!-- Push Notification Info (jika sudah aktif) -->
                <div class="push-info" id="pushInfo" style="display: none;">
                    <div class="push-status">
                        <i class="fas fa-check-circle"></i>
                        <span>Push notification aktif untuk perangkat ini</span>
                    </div>
                    <div class="push-device-info">
                        <small><i class="fas fa-laptop"></i> <span id="deviceInfo">Browser ini</span></small>
                    </div>
                </div>

                <div class="notification-types">
                    <h4>Jenis Notifikasi yang Anda Terima:</h4>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Transaksi berhasil diproses</li>
                        <li><i class="fas fa-check-circle"></i> Proyek baru ditambahkan</li>
                        <li><i class="fas fa-check-circle"></i> Status proyek berubah</li>
                        <li><i class="fas fa-check-circle"></i> Update penting sistem</li>
                        <li><i class="fas fa-check-circle"></i> Pengingat deadline</li>
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeNotificationModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Setting Card */
.setting-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.setting-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.setting-info {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.setting-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.setting-details h3 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.setting-details p {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
    line-height: 1.4;
}

.setting-control {
    flex-shrink: 0;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 30px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 30px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.toggle-switch input:focus + .toggle-slider {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

/* 🔥 Push Notification Button */
.btn-push-toggle {
    padding: 10px 20px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.btn-push-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-push-toggle.active {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-push-toggle.active:hover {
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-push-toggle:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Push Info */
.push-info {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 16px;
}

.push-status {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #155724;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
}

.push-status i {
    color: #28a745;
}

.push-device-info {
    padding-left: 24px;
    color: #155724;
    font-size: 12px;
}

/* Notification Types List */
.notification-types {
    margin-top: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-radius: 12px;
}

.notification-types h4 {
    margin: 0 0 16px 0;
    font-size: 14px;
    font-weight: 600;
    color: #212529;
}

.notification-types ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.notification-types li {
    padding: 8px 0;
    font-size: 13px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-types li i {
    color: #28a745;
    font-size: 14px;
}

/* Alert Info */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

.btn-primary {
    padding: 10px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Dark Theme */
body.dark-theme .setting-card {
    background: #16213e;
}

body.dark-theme .setting-card:hover {
    background: #0f3460;
}

body.dark-theme .setting-details h3 {
    color: #e9ecef;
}

body.dark-theme .setting-details p {
    color: #adb5bd;
}

body.dark-theme .push-info {
    background: #0f3460;
    border-color: #1a1f71;
}

body.dark-theme .push-status,
body.dark-theme .push-device-info {
    color: #e9ecef;
}

body.dark-theme .notification-types {
    background: linear-gradient(135deg, #667eea25 0%, #764ba225 100%);
}

body.dark-theme .notification-types h4 {
    color: #e9ecef;
}

body.dark-theme .notification-types li {
    color: #adb5bd;
}

body.dark-theme .alert-info {
    background: #0f3460;
    border-color: #1a1f71;
    color: #e9ecef;
}
</style>

<script>
// Open Notification Modal
function openNotificationModal() {
    closeSettingsModal();
    loadNotificationSettings();
    
    // ✅ Tunggu sebentar agar modal sudah ter-render
    setTimeout(() => {
        updatePushNotificationUI();
    }, 100);
    
    document.getElementById('notificationModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close Notification Modal
function closeNotificationModal() {
    document.getElementById('notificationModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Load Current Notification Settings
async function loadNotificationSettings() {
    try {
        const response = await fetch('{{ route("settings.index") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('notification_email').checked = data.settings.notification_email;
            document.getElementById('notification_system').checked = data.settings.notification_system;
        }
    } catch (error) {
        console.error('Error loading settings:', error);
    }
}

// ✅ PERBAIKAN: Update Push Notification UI
function updatePushNotificationUI() {
    console.log('🔄 Updating Push Notification UI...');
    
    const pushBtn = document.getElementById('pushNotificationBtn');
    const pushInfo = document.getElementById('pushInfo');
    const pushStatus = document.getElementById('pushNotificationStatus');
    
    if (!pushBtn) {
        console.error('❌ pushNotificationBtn element not found');
        return;
    }
    
    // Cek apakah pushNotificationManager tersedia
    if (!window.pushNotificationManager) {
        console.warn('⚠️ pushNotificationManager not available');
        pushBtn.disabled = true;
        pushBtn.innerHTML = '<i class="fas fa-times-circle"></i> <span>Tidak Didukung</span>';
        pushStatus.textContent = 'Browser Anda tidak mendukung push notification';
        return;
    }
    
    // Cek status subscription
    const isSubscribed = window.pushNotificationManager.isSubscribed();
    console.log('📊 Push Notification Status:', isSubscribed ? 'SUBSCRIBED' : 'NOT SUBSCRIBED');
    
    // ✅ Update UI berdasarkan status YANG BENAR
    if (isSubscribed) {
        // Status: AKTIF - tampilkan tombol NONAKTIFKAN (merah)
        pushBtn.classList.add('active');
        pushBtn.innerHTML = '<i class="fas fa-bell-slash"></i> <span>Nonaktifkan</span>';
        pushInfo.style.display = 'block';
        pushStatus.textContent = 'Push notification aktif';
        console.log('✅ UI: Menampilkan status AKTIF');
    } else {
        // Status: TIDAK AKTIF - tampilkan tombol AKTIFKAN (hijau)
        pushBtn.classList.remove('active');
        pushBtn.innerHTML = '<i class="fas fa-bell"></i> <span>Aktifkan</span>';
        pushInfo.style.display = 'none';
        pushStatus.textContent = 'Terima notifikasi langsung ke perangkat Anda';
        console.log('✅ UI: Menampilkan status TIDAK AKTIF');
    }
    
    pushBtn.disabled = false;
}

// ✅ PERBAIKAN: Toggle Push Notification
async function togglePushNotification() {
    console.log('🔔 Toggle Push Notification clicked');
    
    if (!window.pushNotificationManager) {
        console.error('❌ pushNotificationManager not available');
        alert('Push notification tidak tersedia di browser ini');
        return;
    }
    
    const pushBtn = document.getElementById('pushNotificationBtn');
    const isCurrentlySubscribed = window.pushNotificationManager.isSubscribed();
    
    console.log('📊 Current Status:', isCurrentlySubscribed ? 'SUBSCRIBED' : 'NOT SUBSCRIBED');
    console.log('🎯 Action:', isCurrentlySubscribed ? 'UNSUBSCRIBE' : 'SUBSCRIBE');
    
    // Disable button dan show loading
    pushBtn.disabled = true;
    pushBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Memproses...</span>';
    
    try {
        let result;
        
        if (isCurrentlySubscribed) {
            // ✅ Jika sudah subscribe → UNSUBSCRIBE
            console.log('🔕 Attempting to unsubscribe...');
            result = await window.pushNotificationManager.unsubscribe();
        } else {
            // ✅ Jika belum subscribe → SUBSCRIBE
            console.log('🔔 Attempting to subscribe...');
            result = await window.pushNotificationManager.requestPermission();
        }
        
        console.log('📤 Result:', result);
        
        if (result.success) {
            // ✅ Tampilkan notifikasi sukses
            showNotification('success', result.message);
            console.log('✅ Action successful:', result.message);
        } else {
            // ❌ Tampilkan error
            showNotification('error', result.message || 'Terjadi kesalahan');
            console.error('❌ Action failed:', result.message);
        }
    } catch (error) {
        console.error('❌ Error toggling push notification:', error);
        showNotification('error', 'Terjadi kesalahan: ' + error.message);
    } finally {
        // ✅ PENTING: Update UI setelah toggle selesai
        setTimeout(() => {
            updatePushNotificationUI();
        }, 500);
    }
}

// Handle Form Submit
document.addEventListener('DOMContentLoaded', function() {
    const notificationForm = document.getElementById('notificationForm');
    
    if (notificationForm) {
        notificationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            try {
                const response = await fetch('{{ route("settings.notifications") }}', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        notification_email: document.getElementById('notification_email').checked,
                        notification_system: document.getElementById('notification_system').checked,
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', data.message);
                    closeNotificationModal();
                } else {
                    showNotification('error', data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Terjadi kesalahan saat menyimpan');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Pengaturan';
            }
        });
    }
});
</script>