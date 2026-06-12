<!-- 🔥 SCRIPT HARUS DI ATAS SEBELUM HTML (Pindahkan ke sini!) -->
<script>
// ========================================
// 🔥 DEFINISI FUNCTION DI AWAL (PENTING!)
// ========================================

// Open Settings Modal
function openSettingsModal() {
    const modal = document.getElementById('settingsModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Close Settings Modal
function closeSettingsModal() {
    const modal = document.getElementById('settingsModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// 🔥 NEW: Open Password Role Selection Modal (ADMIN ONLY)
function openPasswordRoleSelectionModal() {
    console.log('🔑 Opening password role selection modal');
    
    closeSettingsModal();
    
    const modal = document.getElementById('passwordRoleSelectionModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        console.error('❌ passwordRoleSelectionModal not found!');
        alert('Error: Modal tidak ditemukan. Anda mungkin tidak memiliki akses.');
    }
}

// Close Password Role Selection Modal
function closePasswordRoleSelectionModal() {
    const modal = document.getElementById('passwordRoleSelectionModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// 🔥 NEW: Select Password Role
function selectPasswordRole(role) {
    console.log('🎯 Selected role:', role);
    
    closePasswordRoleSelectionModal();
    
    if (role === 'admin') {
        // Admin ubah password sendiri (perlu password lama)
        openChangePasswordModal('self');
    } else if (role === 'kasir') {
        // Admin ubah password kasir (pilih kasir dulu, tidak perlu password lama)
        openChangePasswordModal('kasir');
    }
}

// Placeholder functions for other modals (define sesuai kebutuhan)
function openEditProfileModal() {
    closeSettingsModal();
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// ✅ Function openNotificationModal() sudah didefinisi di notification-modal.blade.php
// Tidak perlu didefinisi ulang di sini untuk menghindari konflik

function openThemeModal() {
    closeSettingsModal();
    const modal = document.getElementById('themeModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function openPrivacyModal() {
    closeSettingsModal();
    const modal = document.getElementById('privacyModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Close functions for other modals
function closeEditProfileModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function closeNotificationModal() {
    const modal = document.getElementById('notificationModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function closeThemeModal() {
    const modal = document.getElementById('themeModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

function closePrivacyModal() {
    const modal = document.getElementById('privacyModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// ========================================
// 🔥 EVENT LISTENERS (Setelah DOM Ready)
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Settings modal scripts loaded');
    
    // Close modal when clicking outside
    const settingsModal = document.getElementById('settingsModal');
    if (settingsModal) {
        settingsModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeSettingsModal();
            }
        });
    }
    
    const passwordRoleModal = document.getElementById('passwordRoleSelectionModal');
    if (passwordRoleModal) {
        passwordRoleModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closePasswordRoleSelectionModal();
            }
        });
    }
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSettingsModal();
            closePasswordRoleSelectionModal();
            
            // Close other modals if they exist
            if (typeof closeChangePasswordModal === 'function') closeChangePasswordModal();
            if (typeof closeEditProfileModal === 'function') closeEditProfileModal();
            if (typeof closeNotificationModal === 'function') closeNotificationModal();
            if (typeof closeThemeModal === 'function') closeThemeModal();
            if (typeof closePrivacyModal === 'function') closePrivacyModal();
        }
    });
});
</script>

<!-- Modal Settings -->
<div id="settingsModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-cog"></i> Pengaturan
            </h2>
            <button type="button" class="modal-close" onclick="closeSettingsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <!-- Menu Items -->
            <div class="settings-menu">
                
                <!-- 🔥 Ubah Password - HANYA ADMIN -->
                @if(auth()->user()->role === 'admin')
                <div class="settings-item" onclick="openPasswordRoleSelectionModal()">
                    <div class="settings-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="settings-content">
                        <h3>Ubah Password</h3>
                        <p>Ganti password Admin atau Kasir</p>
                    </div>
                    <div class="settings-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                @endif

                <!-- Edit Profil - SEMUA ROLE -->
                <div class="settings-item" onclick="openEditProfileModal()">
                    <div class="settings-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="settings-content">
                        <h3>Edit Profil</h3>
                        <p>Ubah nama dan informasi profil Anda</p>
                    </div>
                    <div class="settings-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <!-- ✅ Notifikasi - Kelola Pengaturan Push Notification (HANYA ADMIN) -->
                @if(auth()->user()->role === 'admin')
                <div class="settings-item" onclick="openNotificationModal()">
                    <div class="settings-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="settings-content">
                        <h3>Notifikasi</h3>
                        <p>Kelola dan test push notification</p>
                    </div>
                    <div class="settings-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                @endif

                <!-- Tema Aplikasi - SEMUA ROLE -->
                <div class="settings-item" onclick="openThemeModal()">
                    <div class="settings-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="settings-content">
                        <h3>Tema Aplikasi</h3>
                        <p>Pilih tema terang atau gelap</p>
                    </div>
                    <div class="settings-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <!-- Privasi & Keamanan - HANYA ADMIN -->
                @if(auth()->user()->role === 'admin')
                <div class="settings-item" onclick="openPrivacyModal()">
                    <div class="settings-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="settings-content">
                        <h3>Privasi & Keamanan</h3>
                        <p>Kelola pengaturan privasi dan keamanan</p>
                    </div>
                    <div class="settings-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closeSettingsModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- 🔥 Modal Pilihan Role untuk Ubah Password (KHUSUS ADMIN) -->
@if(auth()->user()->role === 'admin')
<div id="passwordRoleSelectionModal" class="modal-overlay" style="display: none;">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-key"></i> Ubah Password
            </h2>
            <button type="button" class="modal-close" onclick="closePasswordRoleSelectionModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <p style="margin-bottom: 20px; color: #6c757d; text-align: center;">
                Pilih password yang ingin diubah:
            </p>

            <div class="role-selection-grid">
                <!-- Ubah Password Admin (Diri Sendiri) -->
                <div class="role-card" onclick="selectPasswordRole('admin')">
                    <div class="role-icon admin">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3>Password Admin</h3>
                    <p>Ubah password Anda sendiri</p>
                </div>

                <!-- Ubah Password Kasir -->
                <div class="role-card" onclick="selectPasswordRole('kasir')">
                    <div class="role-icon kasir">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Password Kasir</h3>
                    <p>Ubah password akun kasir</p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closePasswordRoleSelectionModal()">
                Batal
            </button>
        </div>
    </div>
</div>
@endif

<!-- Include Sub-Modals -->
@include('components.modals.change-password-modal')
@include('components.modals.edit-profile-modal')
@include('components.modals.notification-modal')
@include('components.modals.theme-modal')
@include('components.modals.privacy-modal')

<style>
/* Modal Overlay */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Modal Container */
.modal-container {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { 
        transform: translateY(50px);
        opacity: 0;
    }
    to { 
        transform: translateY(0);
        opacity: 1;
    }
}

/* 🔥 Modal Header - DARK BLUE THEME */
.modal-header {
    background: linear-gradient(135deg, #1a1f71 0%, #16213e 100%);
    color: white;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Modal Body */
.modal-body {
    padding: 24px;
    max-height: calc(90vh - 160px);
    overflow-y: auto;
}

/* Settings Menu */
.settings-menu {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Settings Item */
.settings-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.settings-item:hover {
    background: #e9ecef;
    border-color: #1a1f71;
    transform: translateX(4px);
}

/* 🔥 Settings Icon - DARK BLUE THEME */
.settings-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #1a1f71 0%, #16213e 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
}

.settings-content {
    flex: 1;
}

.settings-content h3 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.settings-content p {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
}

.settings-arrow {
    color: #adb5bd;
    font-size: 14px;
}

/* 🔥 Role Selection Grid */
.role-selection-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
}

.role-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.role-card:hover {
    background: white;
    border-color: #1a1f71;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(26, 31, 113, 0.2);
}

.role-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.role-icon.admin {
    background: linear-gradient(135deg, #1a1f71 0%, #16213e 100%);
}

.role-icon.kasir {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.role-card h3 {
    margin: 0 0 6px 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.role-card p {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}

/* Modal Footer */
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Buttons */
.btn-secondary {
    padding: 10px 24px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

/* Scrollbar Styling */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Dark Theme Support */
body.dark-theme .modal-container {
    background: #1a1a2e;
}

body.dark-theme .modal-header {
    background: linear-gradient(135deg, #1a1f71 0%, #16213e 100%);
}

body.dark-theme .settings-item,
body.dark-theme .role-card {
    background: #16213e;
    border-color: #0f3460;
}

body.dark-theme .settings-item:hover,
body.dark-theme .role-card:hover {
    background: #0f3460;
}

body.dark-theme .settings-content h3,
body.dark-theme .role-card h3 {
    color: #e9ecef;
}

body.dark-theme .settings-content p,
body.dark-theme .role-card p {
    color: #adb5bd;
}

body.dark-theme .modal-footer {
    border-top-color: #16213e;
}
</style>