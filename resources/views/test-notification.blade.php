@extends('layouts.app')

@section('title', 'Test Push Notification')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="mb-2">🔔 Test Push Notification</h2>
                    <p class="text-muted mb-0">Test Firebase Cloud Messaging push notification system</p>
                </div>
            </div>

            <!-- User Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">📱 Status Push Notification Anda</h5>
                    @if(auth()->user()->fcm_token)
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle"></i>
                            <strong>Aktif</strong> - Anda sudah subscribe push notification
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Belum Aktif</strong> - Silakan aktifkan push notification di Pengaturan terlebih dahulu
                        </div>
                    @endif
                </div>
            </div>

            <!-- Test Buttons -->
            <div class="row g-4">
                
                <!-- Test to Self -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test ke Diri Sendiri</h5>
                                    <small class="text-muted">Kirim notifikasi ke akun Anda</small>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100" onclick="testNotification('send-to-self')">
                                <i class="fas fa-paper-plane"></i> Kirim Test Notification
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test to Admins -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-users-cog fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test ke Semua Admin</h5>
                                    <small class="text-muted">Kirim ke semua admin</small>
                                </div>
                            </div>
                            <button class="btn btn-success w-100" onclick="testNotification('send-to-admins')">
                                <i class="fas fa-broadcast-tower"></i> Kirim ke Admin
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test to All -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test Broadcast ke Semua</h5>
                                    <small class="text-muted">Kirim ke semua user</small>
                                </div>
                            </div>
                            <button class="btn btn-warning text-white w-100" onclick="testNotification('send-to-all')">
                                <i class="fas fa-bullhorn"></i> Broadcast ke Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test Transaction -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-money-bill-wave fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test Notif Transaksi</h5>
                                    <small class="text-muted">Simulasi transaksi selesai</small>
                                </div>
                            </div>
                            <button class="btn btn-info text-white w-100" onclick="testNotification('test-transaction')">
                                <i class="fas fa-receipt"></i> Test Transaksi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test Project -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-secondary text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-project-diagram fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test Notif Proyek Baru</h5>
                                    <small class="text-muted">Simulasi proyek baru</small>
                                </div>
                            </div>
                            <button class="btn btn-secondary w-100" onclick="testNotification('test-project')">
                                <i class="fas fa-folder-plus"></i> Test Proyek Baru
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Test Project Status -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger text-white rounded-circle p-3 me-3">
                                    <i class="fas fa-sync-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Test Status Proyek</h5>
                                    <small class="text-muted">Simulasi perubahan status</small>
                                </div>
                            </div>
                            <button class="btn btn-danger w-100" onclick="testNotification('test-project-status')">
                                <i class="fas fa-exchange-alt"></i> Test Status Change
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Custom Test -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title">✏️ Custom Notification</h5>
                    <form id="customNotificationForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Notification title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Body</label>
                            <textarea class="form-control" name="body" rows="3" placeholder="Notification message" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL (optional)</label>
                            <input type="url" class="form-control" name="url" placeholder="https://...">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Custom Notification
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* ========================================
   🌙 DARK THEME SUPPORT
   ======================================== */

/* Container & General Dark Theme */
body.dark-theme .container {
    color: #e9ecef;
}

/* Card Dark Theme */
body.dark-theme .card {
    background: #1a1f2e;
    border-color: #2d3748;
}

body.dark-theme .card:hover {
    background: #252b3b;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
}

body.dark-theme .card-body {
    background: transparent;
}

body.dark-theme .card-title {
    color: #e9ecef;
}

/* Text & Headings Dark Theme */
body.dark-theme h1,
body.dark-theme h2,
body.dark-theme h3,
body.dark-theme h4,
body.dark-theme h5,
body.dark-theme h6 {
    color: #e9ecef;
}

body.dark-theme .text-muted,
body.dark-theme small.text-muted {
    color: #a0aec0 !important;
}

body.dark-theme p {
    color: #cbd5e0;
}

/* Alert Dark Theme */
body.dark-theme .alert {
    background: #2d3748;
    border-color: #4a5568;
    color: #e9ecef;
}

body.dark-theme .alert-success {
    background: #1a4d2e;
    border-color: #22543d;
    color: #9ae6b4;
}

body.dark-theme .alert-success strong {
    color: #9ae6b4;
}

body.dark-theme .alert-warning {
    background: #4a3619;
    border-color: #744210;
    color: #fbd38d;
}

body.dark-theme .alert-warning strong {
    color: #fbd38d;
}

body.dark-theme .alert-info {
    background: #1e3a5f;
    border-color: #2c5282;
    color: #90cdf4;
}

/* Icon Circles Dark Theme - Keep Colorful */
body.dark-theme .bg-primary.rounded-circle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

body.dark-theme .bg-success.rounded-circle {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
}

body.dark-theme .bg-warning.rounded-circle {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
}

body.dark-theme .bg-info.rounded-circle {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
}

body.dark-theme .bg-secondary.rounded-circle {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
}

body.dark-theme .bg-danger.rounded-circle {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
}

/* Buttons Dark Theme */
body.dark-theme .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
}

body.dark-theme .btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
    border-color: #5568d3;
    transform: translateY(-2px);
}

body.dark-theme .btn-success {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #059669;
    color: white;
}

body.dark-theme .btn-success:hover {
    background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    transform: translateY(-2px);
}

body.dark-theme .btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: #f59e0b;
    color: white;
}

body.dark-theme .btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
}

body.dark-theme .btn-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-color: #3b82f6;
    color: white;
}

body.dark-theme .btn-info:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
}

body.dark-theme .btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    border-color: #6b7280;
    color: white;
}

body.dark-theme .btn-secondary:hover {
    background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
    transform: translateY(-2px);
}

body.dark-theme .btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #ef4444;
    color: white;
}

body.dark-theme .btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
}

body.dark-theme .btn:disabled {
    opacity: 0.6;
}

/* Form Dark Theme */
body.dark-theme .form-label {
    color: #e9ecef;
    font-weight: 500;
}

body.dark-theme .form-control {
    background: #2d3748;
    border-color: #4a5568;
    color: #e9ecef;
}

body.dark-theme .form-control:focus {
    background: #2d3748;
    border-color: #667eea;
    color: #e9ecef;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

body.dark-theme .form-control::placeholder {
    color: #718096;
}

body.dark-theme textarea.form-control {
    background: #2d3748;
    color: #e9ecef;
}

/* Shadow Dark Theme */
body.dark-theme .shadow-sm {
    box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.5) !important;
}

/* Scrollbar Dark Theme */
body.dark-theme ::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

body.dark-theme ::-webkit-scrollbar-track {
    background: #1a1f2e;
}

body.dark-theme ::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 5px;
}

body.dark-theme ::-webkit-scrollbar-thumb:hover {
    background: #667eea;
}

/* Keep Icons White in Dark Theme */
body.dark-theme .text-white {
    color: white !important;
}

body.dark-theme i.fas,
body.dark-theme i.far {
    opacity: 0.95;
}

/* Hover Effects Dark Theme */
body.dark-theme .card:hover {
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.15);
}

body.dark-theme .btn:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Smooth Transitions */
body.dark-theme * {
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
}
</style>

<script>
// Test notification function
async function testNotification(type) {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    
    try {
        const response = await fetch(`/test-notification/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ ' + data.message + '\n\nCek perangkat Anda untuk melihat notifikasi!');
            
            if (data.result) {
                console.log('Result:', data.result);
            }
        } else {
            alert('❌ ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat mengirim notification');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Custom notification form
document.getElementById('customNotificationForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/test-notification/test-custom', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title: formData.get('title'),
                body: formData.get('body'),
                url: formData.get('url') || null
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('✅ ' + data.message);
            this.reset();
        } else {
            alert('❌ ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan saat mengirim notification');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
@endsection