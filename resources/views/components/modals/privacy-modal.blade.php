<!-- Modal Privacy & Security -->
<div id="privacyModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-shield-alt"></i> Privasi & Keamanan
            </h2>
            <button type="button" class="modal-close" onclick="closePrivacyModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Kelola pengaturan privasi dan keamanan akun Anda.
            </div>

            <!-- Two-Factor Authentication -->
            <div class="security-section">
                <div class="section-header">
                    <h3><i class="fas fa-lock"></i> Two-Factor Authentication (2FA)</h3>
                    <p>Tambahkan lapisan keamanan ekstra ke akun Anda</p>
                </div>
                
                <div class="setting-card">
                    <div class="setting-info">
                        <div class="setting-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="setting-details">
                            <h3>Autentikasi Dua Faktor</h3>
                            <p id="twoFactorStatus">
                                @if(auth()->user()->two_factor_enabled)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-warning">Nonaktif</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="setting-control">
                        @if(auth()->user()->two_factor_enabled)
                            <button type="button" class="btn-danger-outline" onclick="disable2FA()">
                                <i class="fas fa-times"></i> Nonaktifkan
                            </button>
                        @else
                            <button type="button" class="btn-primary-outline" onclick="enable2FA()">
                                <i class="fas fa-check"></i> Aktifkan
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Login History -->
            <div class="security-section">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Riwayat Login</h3>
                    <p>Informasi login terakhir Anda</p>
                </div>
                
                <div class="info-card">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-clock"></i> Login Terakhir
                        </span>
                        <span class="info-value">{{ auth()->user()->last_login_formatted }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-map-marker-alt"></i> IP Address
                        </span>
                        <span class="info-value">{{ auth()->user()->last_login_ip ?? 'Tidak ada data' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar"></i> Akun Dibuat
                        </span>
                        <span class="info-value">{{ auth()->user()->joined_date }}</span>
                    </div>
                </div>
            </div>

            <!-- Privacy Settings -->
            <div class="security-section">
                <div class="section-header">
                    <h3><i class="fas fa-user-shield"></i> Pengaturan Privasi</h3>
                    <p>Kontrol siapa yang dapat melihat informasi Anda</p>
                </div>
                
                <div class="privacy-options">
                    <div class="privacy-item">
                        <i class="fas fa-envelope"></i>
                        <span>Email Anda hanya terlihat oleh administrator</span>
                    </div>
                    <div class="privacy-item">
                        <i class="fas fa-phone"></i>
                        <span>Nomor telepon bersifat pribadi</span>
                    </div>
                    <div class="privacy-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Data Anda dilindungi dengan enkripsi</span>
                    </div>
                </div>
            </div>

            <!-- Account Actions -->
            <div class="security-section">
                <div class="section-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Tindakan Akun</h3>
                    <p>Kelola akun Anda dengan hati-hati</p>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn-warning-outline" onclick="clearSessions()">
                        <i class="fas fa-sign-out-alt"></i> Keluar dari Semua Perangkat
                    </button>
                    <button type="button" class="btn-danger-outline" onclick="confirmDeleteAccount()">
                        <i class="fas fa-user-times"></i> Hapus Akun
                    </button>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn-secondary" onclick="closePrivacyModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* Security Section */
.security-section {
    margin-bottom: 32px;
}

.section-header {
    margin-bottom: 16px;
}

.section-header h3 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-header p {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
}

/* Info Card */
.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 2px solid transparent;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 14px;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-value {
    font-size: 14px;
    font-weight: 500;
    color: #212529;
}

/* Badge */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-warning {
    background: #fff3cd;
    color: #856404;
}

/* Privacy Options */
.privacy-options {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
}

.privacy-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    font-size: 14px;
    color: #495057;
}

.privacy-item i {
    color: #667eea;
    font-size: 16px;
    width: 24px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-primary-outline,
.btn-warning-outline,
.btn-danger-outline {
    padding: 12px 20px;
    border: 2px solid;
    background: transparent;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
}

.btn-primary-outline {
    border-color: #667eea;
    color: #667eea;
}

.btn-primary-outline:hover {
    background: #667eea;
    color: white;
}

.btn-warning-outline {
    border-color: #ffc107;
    color: #ffc107;
}

.btn-warning-outline:hover {
    background: #ffc107;
    color: #212529;
}

.btn-danger-outline {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-danger-outline:hover {
    background: #dc3545;
    color: white;
}

/* Dark Theme */
body.dark-theme .section-header h3 {
    color: #e9ecef;
}

body.dark-theme .section-header p {
    color: #adb5bd;
}

body.dark-theme .info-card,
body.dark-theme .privacy-options {
    background: #16213e;
    border-color: #0f3460;
}

body.dark-theme .info-row {
    border-bottom-color: #0f3460;
}

body.dark-theme .info-label {
    color: #adb5bd;
}

body.dark-theme .info-value {
    color: #e9ecef;
}

body.dark-theme .privacy-item {
    color: #adb5bd;
}
</style>

<script>
// Open Privacy Modal
function openPrivacyModal() {
    closeSettingsModal();
    document.getElementById('privacyModal').style.display = 'flex';
}

// Close Privacy Modal
function closePrivacyModal() {
    document.getElementById('privacyModal').style.display = 'none';
}

// Enable 2FA
async function enable2FA() {
    if (!confirm('Aktifkan Two-Factor Authentication untuk keamanan ekstra?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("settings.2fa.enable") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('success', data.message);
            
            // Update UI
            document.getElementById('twoFactorStatus').innerHTML = '<span class="badge badge-success">Aktif</span>';
            
            // Reload modal untuk update tombol
            closePrivacyModal();
            setTimeout(() => openPrivacyModal(), 300);
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Gagal mengaktifkan 2FA');
    }
}

// Disable 2FA
async function disable2FA() {
    if (!confirm('Nonaktifkan Two-Factor Authentication? Ini akan mengurangi keamanan akun Anda.')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("settings.2fa.disable") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('success', data.message);
            
            // Update UI
            document.getElementById('twoFactorStatus').innerHTML = '<span class="badge badge-warning">Nonaktif</span>';
            
            // Reload modal untuk update tombol
            closePrivacyModal();
            setTimeout(() => openPrivacyModal(), 300);
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Gagal menonaktifkan 2FA');
    }
}

// Clear All Sessions
function clearSessions() {
    if (!confirm('Keluar dari semua perangkat? Anda harus login ulang di semua perangkat.')) {
        return;
    }
    
    showNotification('info', 'Fitur ini akan segera tersedia');
}

// Confirm Delete Account
function confirmDeleteAccount() {
    if (confirm('PERINGATAN: Menghapus akun bersifat permanen dan tidak dapat dibatalkan. Lanjutkan?')) {
        if (confirm('Apakah Anda BENAR-BENAR yakin ingin menghapus akun Anda?')) {
            showNotification('error', 'Hubungi administrator untuk menghapus akun');
        }
    }
}
</script>