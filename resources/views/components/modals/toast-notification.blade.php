<!-- Toast Notification Container -->
<div id="toastContainer" class="toast-container"></div>

<style>
/* Toast Container */
.toast-container {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 400px;
}

/* Toast Notification */
.toast {
    background: white;
    border-radius: 12px;
    padding: 16px 20px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
    border-left: 4px solid;
}

@keyframes slideInRight {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

.toast.hiding {
    animation: slideOutRight 0.3s ease;
}

.toast-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 14px;
    margin: 0 0 4px 0;
}

.toast-message {
    font-size: 13px;
    margin: 0;
    color: #6c757d;
}

.toast-close {
    background: transparent;
    border: none;
    color: #6c757d;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    transition: color 0.3s ease;
    flex-shrink: 0;
}

.toast-close:hover {
    color: #212529;
}

/* Toast Types */
.toast.success {
    border-left-color: #28a745;
}

.toast.success .toast-icon {
    background: #d4edda;
    color: #28a745;
}

.toast.error {
    border-left-color: #dc3545;
}

.toast.error .toast-icon {
    background: #f8d7da;
    color: #dc3545;
}

.toast.warning {
    border-left-color: #ffc107;
}

.toast.warning .toast-icon {
    background: #fff3cd;
    color: #ffc107;
}

.toast.info {
    border-left-color: #17a2b8;
}

.toast.info .toast-icon {
    background: #d1ecf1;
    color: #17a2b8;
}

/* Dark Theme */
body.dark-theme .toast {
    background: #1a1a2e;
}

body.dark-theme .toast-title {
    color: #e9ecef;
}

body.dark-theme .toast-message {
    color: #adb5bd;
}

body.dark-theme .toast-close {
    color: #adb5bd;
}

body.dark-theme .toast-close:hover {
    color: #e9ecef;
}

body.dark-theme .toast.success .toast-icon {
    background: #28a74530;
}

body.dark-theme .toast.error .toast-icon {
    background: #dc354530;
}

body.dark-theme .toast.warning .toast-icon {
    background: #ffc10730;
}

body.dark-theme .toast.info .toast-icon {
    background: #17a2b830;
}

/* Responsive */
@media (max-width: 768px) {
    .toast-container {
        left: 20px;
        right: 20px;
        max-width: none;
    }
    
    .toast {
        min-width: auto;
    }
}
</style>

<script>
/**
 * Show Toast Notification
 * @param {string} type - success, error, warning, info
 * @param {string} message - The message to display
 * @param {number} duration - Duration in milliseconds (default: 3000)
 */
function showNotification(type = 'info', message = '', duration = 3000) {
    const container = document.getElementById('toastContainer');
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Icon mapping
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    // Title mapping
    const titles = {
        success: 'Berhasil',
        error: 'Error',
        warning: 'Peringatan',
        info: 'Informasi'
    };
    
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas ${icons[type]}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${titles[type]}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="closeToast(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add to container
    container.appendChild(toast);
    
    // Auto remove after duration
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, duration);
}

/**
 * Close Toast Notification
 */
function closeToast(button) {
    const toast = button.closest('.toast');
    if (!toast) return;
    
    toast.classList.add('hiding');
    
    setTimeout(() => {
        toast.remove();
    }, 300);
}

/**
 * Show Success Toast
 */
function showSuccess(message, duration = 3000) {
    showNotification('success', message, duration);
}

/**
 * Show Error Toast
 */
function showError(message, duration = 4000) {
    showNotification('error', message, duration);
}

/**
 * Show Warning Toast
 */
function showWarning(message, duration = 3500) {
    showNotification('warning', message, duration);
}

/**
 * Show Info Toast
 */
function showInfo(message, duration = 3000) {
    showNotification('info', message, duration);
}

// Example usage in console for testing:
// showSuccess('Data berhasil disimpan!');
// showError('Terjadi kesalahan saat menyimpan data');
// showWarning('Pastikan semua field sudah diisi');
// showInfo('Fitur ini masih dalam pengembangan');
</script>