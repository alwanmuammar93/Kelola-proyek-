{{-- Include CSS External --}}
<link rel="stylesheet" href="{{ asset('css/change-password-modal.css') }}">

{{-- ======================================== 
    🔥 SCRIPT HARUS DI ATAS SEBELUM HTML
    ======================================== --}}
<script>
// ========================================
// DEFINISI FUNCTION DI AWAL (GLOBAL SCOPE)
// ========================================

// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) {
        console.error('❌ Field not found:', fieldId);
        return;
    }
    
    const button = field.nextElementSibling;
    if (!button) return;
    
    const icon = button.querySelector('i');
    if (!icon) return;
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// 🔥 FIXED: Open Change Password Modal
function openChangePasswordModal(mode = 'self') {
    console.log('🔑 Opening change password modal, mode:', mode);
    
    // Get all elements with NULL checks
    const modal = document.getElementById('changePasswordModal');
    const form = document.getElementById('changePasswordForm');
    const passwordModeInput = document.getElementById('password_mode');
    const title = document.getElementById('modalPasswordTitle');
    const alertInfo = document.getElementById('alertInfoText');
    const currentPasswordGroup = document.getElementById('currentPasswordGroup');
    const kasirSelectGroup = document.getElementById('kasirSelectGroup');
    const currentPasswordInput = document.getElementById('current_password');
    const kasirIdInput = document.getElementById('kasir_id');
    
    // Validate critical elements
    if (!modal || !form) {
        console.error('❌ CRITICAL: Modal or form not found!');
        alert('Error: Modal tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    // Set password mode
    if (passwordModeInput) {
        passwordModeInput.value = mode;
    }
    
    // Reset form
    form.reset();
    if (typeof clearFormErrors === 'function') {
        clearFormErrors('changePasswordForm');
    }
    
    // Reset kasir dropdown
    if (typeof resetKasirDropdown === 'function') {
        resetKasirDropdown();
    }
    
    if (mode === 'self') {
        // Mode: Admin ubah password sendiri
        if (title) title.textContent = 'Ubah Password Admin';
        if (alertInfo) alertInfo.textContent = 'Anda akan mengubah password Admin. Masukkan password lama Anda.';
        if (currentPasswordGroup) currentPasswordGroup.style.display = 'block';
        if (kasirSelectGroup) kasirSelectGroup.style.display = 'none';
        if (currentPasswordInput) currentPasswordInput.required = true;
        if (kasirIdInput) kasirIdInput.removeAttribute('required');
        
    } else if (mode === 'kasir') {
        // Mode: Admin ubah password kasir
        if (title) title.textContent = 'Ubah Password Kasir';
        if (alertInfo) alertInfo.textContent = 'Anda akan mengubah password Kasir. Pilih kasir yang ingin diubah passwordnya.';
        if (currentPasswordGroup) currentPasswordGroup.style.display = 'none';
        if (kasirSelectGroup) kasirSelectGroup.style.display = 'block';
        if (currentPasswordInput) currentPasswordInput.required = false;
        if (kasirIdInput) kasirIdInput.setAttribute('required', 'required');
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    console.log('✅ Modal opened successfully, mode:', mode);
}

// Close Change Password Modal
function closeChangePasswordModal() {
    const modal = document.getElementById('changePasswordModal');
    const form = document.getElementById('changePasswordForm');
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (modal) modal.style.display = 'none';
    if (form) form.reset();
    if (strengthDiv) strengthDiv.style.display = 'none';
    
    if (typeof clearFormErrors === 'function') {
        clearFormErrors('changePasswordForm');
    }
    
    document.body.style.overflow = 'auto';
}

// Reset kasir dropdown
function resetKasirDropdown() {
    const kasirIdInput = document.getElementById('kasir_id');
    const kasirSelectedText = document.getElementById('kasirSelectedText');
    const kasirMenu = document.getElementById('kasirDropdownMenu');
    
    if (kasirIdInput) kasirIdInput.value = '';
    if (kasirSelectedText) kasirSelectedText.textContent = '-- Pilih Kasir --';
    
    if (kasirMenu) {
        const kasirItems = kasirMenu.querySelectorAll('.kasir-dropdown-item');
        kasirItems.forEach(item => item.classList.remove('selected'));
        if (kasirItems.length > 0) {
            kasirItems[0].classList.add('selected');
        }
    }
}

// Helper: Clear Form Errors
function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
}

// Helper: Display Form Errors
function displayFormErrors(formId, errors) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    for (const [field, messages] of Object.entries(errors)) {
        const errorEl = form.querySelector(`#error_${field}`);
        const inputEl = form.querySelector(`[name="${field}"]`);
        
        if (errorEl) {
            errorEl.textContent = messages[0];
        }
        if (inputEl) {
            inputEl.classList.add('is-invalid');
        }
    }
}

// Helper: Show Notification
function showNotification(type, message) {
    if (type === 'success') {
        alert('✅ ' + message);
    } else {
        alert('❌ ' + message);
    }
}

console.log('✅ Change Password Modal functions loaded');
</script>

<!-- Modal Change Password -->
<div id="changePasswordModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-key"></i> <span id="modalPasswordTitle">Ubah Password</span>
            </h2>
            <button type="button" class="modal-close" onclick="closeChangePasswordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="changePasswordForm">
            @csrf
            <input type="hidden" id="password_mode" name="password_mode" value="self">
            
            <div class="modal-body">
                <!-- Alert Info -->
                <div class="alert alert-info" id="alertInfo">
                    <i class="fas fa-info-circle"></i>
                    <span id="alertInfoText">Password minimal 8 karakter untuk keamanan akun Anda.</span>
                </div>

                <!-- Pilih Kasir (Custom Dropdown - Hanya muncul jika mode = kasir) -->
                <div class="form-group" id="kasirSelectGroup" style="display: none;">
                    <label for="kasir_id">
                        <i class="fas fa-user"></i> Pilih Kasir
                    </label>
                    
                    <input type="hidden" id="kasir_id" name="kasir_id" value="">
                    
                    <div class="kasir-dropdown-wrapper">
                        <button type="button" class="kasir-dropdown-btn" id="kasirDropdownBtn">
                            <span id="kasirSelectedText">-- Pilih Kasir --</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="kasir-dropdown-menu" id="kasirDropdownMenu">
                            <div class="kasir-dropdown-item" data-value="" data-name="-- Pilih Kasir --">
                                <i class="fas fa-user-circle"></i>
                                <span>-- Pilih Kasir --</span>
                            </div>
                            @foreach(\App\Models\User::where('role', 'kasir')->orderBy('username')->get() as $kasir)
                            <div class="kasir-dropdown-item" 
                                 data-value="{{ $kasir->id_user }}" 
                                 data-name="{{ $kasir->name ?? $kasir->username }}">
                                <i class="fas fa-user"></i>
                                <div class="kasir-info">
                                    <span class="kasir-name">{{ $kasir->name ?? $kasir->username }}</span>
                                    @if($kasir->email)
                                    <span class="kasir-email">({{ $kasir->email }})</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <span class="error-message" id="error_kasir_id"></span>
                </div>

                <!-- Current Password (Hanya untuk mode = self) -->
                <div class="form-group" id="currentPasswordGroup">
                    <label for="current_password">
                        <i class="fas fa-lock"></i> Password Lama
                    </label>
                    <div class="input-with-icon">
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-control"
                            placeholder="Masukkan password lama Anda"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="error-message" id="error_current_password"></span>
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label for="new_password">
                        <i class="fas fa-key"></i> Password Baru
                    </label>
                    <div class="input-with-icon">
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="form-control"
                            placeholder="Masukkan password baru"
                            required
                            minlength="8"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText"></span>
                    </div>
                    <span class="error-message" id="error_new_password"></span>
                </div>

                <!-- Confirm New Password -->
                <div class="form-group">
                    <label for="new_password_confirmation">
                        <i class="fas fa-check-circle"></i> Konfirmasi Password Baru
                    </label>
                    <div class="input-with-icon">
                        <input 
                            type="password" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation" 
                            class="form-control"
                            placeholder="Ketik ulang password baru"
                            required
                            minlength="8"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('new_password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="error-message" id="error_new_password_confirmation"></span>
                </div>

                <!-- Password Requirements -->
                <div class="password-requirements">
                    <p style="margin: 0 0 8px 0; font-weight: 500; font-size: 13px;">Password harus mengandung:</p>
                    <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #6c757d;">
                        <li id="req-length">Minimal 8 karakter</li>
                        <li id="req-uppercase">Minimal 1 huruf besar (A-Z)</li>
                        <li id="req-lowercase">Minimal 1 huruf kecil (a-z)</li>
                        <li id="req-number">Minimal 1 angka (0-9)</li>
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeChangePasswordModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary" id="submitPasswordBtn">
                    <i class="fas fa-save"></i> Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======================================== 
    EVENT LISTENERS (Setelah DOM Ready)
    ======================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Initializing change password modal...');
    
    // Kasir Dropdown Toggle
    const kasirBtn = document.getElementById('kasirDropdownBtn');
    const kasirMenu = document.getElementById('kasirDropdownMenu');
    const kasirIdInput = document.getElementById('kasir_id');
    const kasirSelectedText = document.getElementById('kasirSelectedText');
    
    if (kasirBtn && kasirMenu) {
        kasirBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            kasirMenu.classList.toggle('show');
            kasirBtn.classList.toggle('active');
        });
        
        const kasirItems = kasirMenu.querySelectorAll('.kasir-dropdown-item');
        kasirItems.forEach(item => {
            item.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const name = this.getAttribute('data-name');
                
                if (kasirIdInput) kasirIdInput.value = value;
                if (kasirSelectedText) kasirSelectedText.textContent = name;
                
                kasirItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                
                kasirMenu.classList.remove('show');
                kasirBtn.classList.remove('active');
                
                const errorEl = document.getElementById('error_kasir_id');
                if (errorEl) errorEl.textContent = '';
                if (kasirIdInput) kasirIdInput.classList.remove('is-invalid');
            });
        });
        
        document.addEventListener('click', function(e) {
            if (!kasirBtn.contains(e.target) && !kasirMenu.contains(e.target)) {
                kasirMenu.classList.remove('show');
                kasirBtn.classList.remove('active');
            }
        });
    }
    
    // Password Strength Checker
    const newPasswordInput = document.getElementById('new_password');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            
            if (!strengthDiv || !strengthFill || !strengthText) return;
            
            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }
            
            strengthDiv.style.display = 'block';
            
            let strength = 0;
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
            
            const reqLength = document.getElementById('req-length');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqNumber = document.getElementById('req-number');
            
            if (reqLength) reqLength.classList.toggle('valid', checks.length);
            if (reqUppercase) reqUppercase.classList.toggle('valid', checks.uppercase);
            if (reqLowercase) reqLowercase.classList.toggle('valid', checks.lowercase);
            if (reqNumber) reqNumber.classList.toggle('valid', checks.number);
            
            if (checks.length) strength += 25;
            if (checks.uppercase) strength += 20;
            if (checks.lowercase) strength += 20;
            if (checks.number) strength += 20;
            if (checks.special) strength += 15;
            
            strengthFill.style.width = strength + '%';
            
            if (strength < 40) {
                strengthFill.style.background = '#dc3545';
                strengthText.textContent = 'Lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength < 70) {
                strengthFill.style.background = '#ffc107';
                strengthText.textContent = 'Sedang';
                strengthText.style.color = '#ffc107';
            } else if (strength < 90) {
                strengthFill.style.background = '#28a745';
                strengthText.textContent = 'Kuat';
                strengthText.style.color = '#28a745';
            } else {
                strengthFill.style.background = '#059669';
                strengthText.textContent = 'Sangat Kuat';
                strengthText.style.color = '#059669';
            }
        });
    }
    
    // Form Submit Handler
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitPasswordBtn');
            const passwordModeInput = document.getElementById('password_mode');
            
            if (!submitBtn || !passwordModeInput) {
                console.error('❌ Required elements not found');
                return;
            }
            
            const mode = passwordModeInput.value;
            const newPassword = formData.get('new_password');
            const confirmPassword = formData.get('new_password_confirmation');
            
            if (newPassword !== confirmPassword) {
                showNotification('error', 'Password baru dan konfirmasi tidak cocok!');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            
            clearFormErrors('changePasswordForm');
            
            try {
                let endpoint = '';
                let requestBody = {};
                
                if (mode === 'self') {
                    endpoint = '{{ route("profile.change-password") }}';
                    requestBody = {
                        current_password: formData.get('current_password'),
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword,
                    };
                } else if (mode === 'kasir') {
                    endpoint = '{{ route("admin.change-kasir-password") }}';
                    requestBody = {
                        kasir_id: formData.get('kasir_id'),
                        new_password: newPassword,
                        new_password_confirmation: confirmPassword,
                    };
                }
                
                const response = await fetch(endpoint, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(requestBody)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', data.message);
                    closeChangePasswordModal();
                    this.reset();
                } else {
                    if (data.errors) {
                        displayFormErrors('changePasswordForm', data.errors);
                    } else {
                        showNotification('error', data.message || 'Gagal mengubah password');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Terjadi kesalahan saat mengubah password');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Password';
            }
        });
    }
    
    console.log('✅ Change password modal initialized');
});
</script>