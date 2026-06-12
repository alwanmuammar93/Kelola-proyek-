<!-- Modal Theme Settings -->
<div id="themeModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-palette"></i> Tema Aplikasi
            </h2>
            <button type="button" class="modal-close" onclick="closeThemeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="themeForm">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Pilih tema yang sesuai dengan preferensi Anda.
                </div>

                <div class="theme-options">
                    <!-- Light Theme -->
                    <div class="theme-card" data-theme="light">
                        <input 
                            type="radio" 
                            id="theme_light" 
                            name="theme" 
                            value="light"
                            {{ auth()->user()->theme_preference === 'light' ? 'checked' : '' }}
                        >
                        <label for="theme_light" class="theme-label">
                            <div class="theme-preview light-preview">
                                <div class="preview-header"></div>
                                <div class="preview-sidebar"></div>
                                <div class="preview-content">
                                    <div class="preview-card"></div>
                                    <div class="preview-card"></div>
                                </div>
                            </div>
                            <div class="theme-info">
                                <h3>
                                    <i class="fas fa-sun"></i> Tema Terang
                                </h3>
                                <p>Sempurna untuk siang hari dengan pencahayaan yang baik</p>
                            </div>
                            <div class="theme-check">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </label>
                    </div>

                    <!-- Dark Theme -->
                    <div class="theme-card" data-theme="dark">
                        <input 
                            type="radio" 
                            id="theme_dark" 
                            name="theme" 
                            value="dark"
                            {{ auth()->user()->theme_preference === 'dark' ? 'checked' : '' }}
                        >
                        <label for="theme_dark" class="theme-label">
                            <div class="theme-preview dark-preview">
                                <div class="preview-header"></div>
                                <div class="preview-sidebar"></div>
                                <div class="preview-content">
                                    <div class="preview-card"></div>
                                    <div class="preview-card"></div>
                                </div>
                            </div>
                            <div class="theme-info">
                                <h3>
                                    <i class="fas fa-moon"></i> Tema Gelap
                                </h3>
                                <p>Nyaman untuk mata di lingkungan dengan cahaya rendah</p>
                            </div>
                            <div class="theme-check">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="theme-benefits">
                    <h4>Keuntungan Tema Gelap:</h4>
                    <ul>
                        <li><i class="fas fa-moon"></i> Mengurangi ketegangan mata</li>
                        <li><i class="fas fa-battery-half"></i> Menghemat baterai pada layar OLED</li>
                        <li><i class="fas fa-clock"></i> Ideal untuk bekerja malam hari</li>
                    </ul>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeThemeModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> Terapkan Tema
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* ========================================
   MODAL OVERLAY & CONTAINER - FIXED
   ======================================== */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 700px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Dark Theme Modal Container */
body.dark-theme .modal-container {
    background: #1a1a2e;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

/* ========================================
   MODAL HEADER
   ======================================== */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* ========================================
   MODAL BODY
   ======================================== */
.modal-body {
    padding: 30px;
    max-height: calc(90vh - 180px);
    overflow-y: auto;
}

/* Custom Scrollbar */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

body.dark-theme .modal-body::-webkit-scrollbar-track {
    background: #16213e;
}

body.dark-theme .modal-body::-webkit-scrollbar-thumb {
    background: #764ba2;
}

/* ========================================
   ALERT INFO
   ======================================== */
.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border: 1px solid #abdde5;
    border-left: 4px solid #17a2b8;
    color: #0c5460;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    line-height: 1.5;
}

.alert-info i {
    font-size: 20px;
    flex-shrink: 0;
}

/* Dark Theme Alert */
body.dark-theme .alert-info {
    background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
    border-color: #4299e1;
    color: #bee3f8;
}

/* ========================================
   THEME OPTIONS
   ======================================== */
.theme-options {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

@media (min-width: 640px) {
    .theme-options {
        grid-template-columns: repeat(2, 1fr);
    }
}

.theme-card {
    position: relative;
}

.theme-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.theme-label {
    display: block;
    background: #f8f9fa;
    border: 3px solid #e9ecef;
    border-radius: 16px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
}

.theme-label:hover {
    border-color: #667eea;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
}

.theme-card input:checked + .theme-label {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

/* Dark Theme Label */
body.dark-theme .theme-label {
    background: #16213e;
    border-color: #0f3460;
}

body.dark-theme .theme-label:hover {
    border-color: #667eea;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

body.dark-theme .theme-card input:checked + .theme-label {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5);
}

/* ========================================
   THEME PREVIEW
   ======================================== */
.theme-preview {
    width: 100%;
    height: 140px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
    position: relative;
    display: grid;
    grid-template-columns: 60px 1fr;
    grid-template-rows: 40px 1fr;
    gap: 4px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.light-preview {
    background: #ffffff;
}

.dark-preview {
    background: #1a1a2e;
}

.preview-header {
    grid-column: 1 / -1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.preview-sidebar {
    background: rgba(102, 126, 234, 0.1);
}

.light-preview .preview-sidebar {
    background: #f8f9fa;
}

.dark-preview .preview-sidebar {
    background: #16213e;
}

.preview-content {
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.preview-card {
    height: 30px;
    border-radius: 6px;
}

.light-preview .preview-card {
    background: #e9ecef;
}

.dark-preview .preview-card {
    background: #0f3460;
}

/* ========================================
   THEME INFO
   ======================================== */
.theme-info {
    margin-bottom: 12px;
}

.theme-info h3 {
    margin: 0 0 8px 0;
    font-size: 17px;
    font-weight: 700;
    color: #212529;
    display: flex;
    align-items: center;
    gap: 10px;
}

.theme-info p {
    margin: 0;
    font-size: 13px;
    color: #6c757d;
    line-height: 1.5;
}

/* Dark Theme Info */
body.dark-theme .theme-info h3 {
    color: #e9ecef;
}

body.dark-theme .theme-info p {
    color: #adb5bd;
}

/* ========================================
   THEME CHECK ICON
   ======================================== */
.theme-check {
    position: absolute;
    top: 24px;
    right: 24px;
    color: #667eea;
    font-size: 28px;
    opacity: 0;
    transition: all 0.3s ease;
}

.theme-card input:checked + .theme-label .theme-check {
    opacity: 1;
    transform: scale(1.2);
}

/* ========================================
   THEME BENEFITS
   ======================================== */
.theme-benefits {
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.theme-benefits h4 {
    margin: 0 0 14px 0;
    font-size: 15px;
    font-weight: 700;
    color: #212529;
}

.theme-benefits ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.theme-benefits li {
    padding: 8px 0;
    font-size: 14px;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 12px;
}

.theme-benefits li i {
    color: #667eea;
    width: 20px;
    font-size: 16px;
}

/* Dark Theme Benefits */
body.dark-theme .theme-benefits {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    border-color: rgba(102, 126, 234, 0.3);
}

body.dark-theme .theme-benefits h4 {
    color: #e9ecef;
}

body.dark-theme .theme-benefits li {
    color: #cbd5e0;
}

body.dark-theme .theme-benefits li i {
    color: #a78bfa;
}

/* ========================================
   MODAL FOOTER
   ======================================== */
.modal-footer {
    padding: 20px 30px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

body.dark-theme .modal-footer {
    background: #0f1419;
    border-top-color: #0f3460;
}

/* ========================================
   BUTTONS
   ======================================== */
.btn-secondary,
.btn-primary {
    padding: 12px 28px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(108, 117, 125, 0.3);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.btn-primary:disabled,
.btn-secondary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Dark Theme Buttons */
body.dark-theme .btn-secondary {
    background: #4a5568;
    color: #e9ecef;
}

body.dark-theme .btn-secondary:hover {
    background: #2d3748;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media (max-width: 639px) {
    .modal-container {
        border-radius: 15px;
        max-height: 95vh;
    }

    .modal-header {
        padding: 20px;
    }

    .modal-title {
        font-size: 18px;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        padding: 16px 20px;
        flex-direction: column;
    }

    .btn-secondary,
    .btn-primary {
        width: 100%;
        justify-content: center;
    }

    .theme-preview {
        height: 120px;
    }

    .theme-check {
        top: 16px;
        right: 16px;
        font-size: 24px;
    }
}
</style>

<script>
// Open Theme Modal
function openThemeModal() {
    if (typeof closeSettingsModal === 'function') {
        closeSettingsModal();
    }
    loadCurrentTheme();
    document.getElementById('themeModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Close Theme Modal
function closeThemeModal() {
    document.getElementById('themeModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('themeModal');
        if (modal && modal.style.display === 'flex') {
            closeThemeModal();
        }
    }
});

// Close on overlay click
document.getElementById('themeModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeThemeModal();
    }
});

// Load Current Theme
async function loadCurrentTheme() {
    try {
        const response = await fetch('{{ route("settings.index") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            const themeRadio = document.querySelector(`input[name="theme"][value="${data.settings.theme}"]`);
            if (themeRadio) {
                themeRadio.checked = true;
            }
        }
    } catch (error) {
        console.error('Error loading theme:', error);
    }
}

// Handle Form Submit
document.getElementById('themeForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const selectedTheme = document.querySelector('input[name="theme"]:checked').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menerapkan...';
    
    try {
        const response = await fetch('{{ route("settings.theme") }}', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                theme: selectedTheme
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show notification if available
            if (typeof showNotification === 'function') {
                showNotification('success', data.message);
            }
            
            // Apply theme to body with smooth transition
            document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            
            if (selectedTheme === 'dark') {
                document.body.classList.add('dark-theme');
            } else {
                document.body.classList.remove('dark-theme');
            }
            
            // Save to localStorage
            localStorage.setItem('theme', selectedTheme);
            
            // Close modal after short delay
            setTimeout(() => {
                closeThemeModal();
            }, 300);
        } else {
            if (typeof showNotification === 'function') {
                showNotification('error', data.message);
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (typeof showNotification === 'function') {
            showNotification('error', 'Terjadi kesalahan saat menerapkan tema');
        } else {
            alert('Terjadi kesalahan saat menerapkan tema');
        }
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Terapkan Tema';
    }
});

// Preview theme on selection (IMPROVED - instant visual feedback)
document.querySelectorAll('input[name="theme"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
        
        if (this.value === 'dark') {
            document.body.classList.add('dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
        }
    });
});

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
});
</script>