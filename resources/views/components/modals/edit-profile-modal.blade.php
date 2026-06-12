<!-- Modal Edit Profile -->
<div id="editProfileModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-user-edit"></i> Edit Profil
            </h2>
            <button type="button" class="modal-close" onclick="closeEditProfileModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editProfileForm">
            @csrf
            <div class="modal-body">
                <!-- Profile Photo Section -->
                <div class="profile-photo-section">
                    <div class="current-photo">
                        <img id="profilePhotoPreview" src="{{ auth()->user()->profile_photo_url }}" alt="Profile">
                        <div class="photo-overlay">
                            <label for="profilePhotoInput" class="photo-upload-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                            <button type="button" class="photo-delete-btn" onclick="deleteProfilePhoto()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;" onchange="previewProfilePhoto(event)">
                    <p class="photo-info">JPG, PNG maksimal 2MB</p>
                </div>

                <!-- Name -->
                <div class="form-group">
                    <label for="edit_name">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        id="edit_name" 
                        name="name" 
                        class="form-control"
                        placeholder="Masukkan nama lengkap"
                        value="{{ auth()->user()->name }}"
                        required
                    >
                    <span class="error-message" id="error_name"></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="edit_email">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input 
                        type="email" 
                        id="edit_email" 
                        name="email" 
                        class="form-control"
                        placeholder="Masukkan email"
                        value="{{ auth()->user()->email }}"
                        required
                    >
                    <span class="error-message" id="error_email"></span>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="edit_phone">
                        <i class="fas fa-phone"></i> Nomor Telepon
                    </label>
                    <input 
                        type="tel" 
                        id="edit_phone" 
                        name="phone" 
                        class="form-control"
                        placeholder="Contoh: 081234567890"
                        value="{{ auth()->user()->phone }}"
                    >
                    <span class="error-message" id="error_phone"></span>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="edit_address">
                        <i class="fas fa-map-marker-alt"></i> Alamat
                    </label>
                    <textarea 
                        id="edit_address" 
                        name="address" 
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat lengkap"
                    >{{ auth()->user()->address }}</textarea>
                    <span class="error-message" id="error_address"></span>
                </div>

                <!-- Username (Read Only) -->
                <div class="form-group">
                    <label for="edit_username">
                        <i class="fas fa-id-badge"></i> Username
                    </label>
                    <input 
                        type="text" 
                        id="edit_username" 
                        class="form-control"
                        value="{{ auth()->user()->username }}"
                        readonly
                        disabled
                    >
                    <small class="form-text">Username tidak dapat diubah</small>
                </div>

                <!-- Role (Read Only) -->
                <div class="form-group">
                    <label for="edit_role">
                        <i class="fas fa-user-tag"></i> Role
                    </label>
                    <input 
                        type="text" 
                        id="edit_role" 
                        class="form-control"
                        value="{{ auth()->user()->role_display }}"
                        readonly
                        disabled
                    >
                    <small class="form-text">Role ditentukan oleh administrator</small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeEditProfileModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* 🔥 Profile Photo Section - DARK BLUE THEME */
.profile-photo-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #1a1f7115 0%, #16213e15 100%);
    border-radius: 12px;
}

.current-photo {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #1a1f71;
    box-shadow: 0 4px 12px rgba(26, 31, 113, 0.3);
    margin-bottom: 12px;
}

.current-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.current-photo:hover .photo-overlay {
    opacity: 1;
}

.photo-upload-btn,
.photo-delete-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.photo-upload-btn:hover,
.photo-delete-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.photo-info {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.form-text {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6c757d;
}

input:disabled,
textarea:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Dark Theme */
body.dark-theme .profile-photo-section {
    background: linear-gradient(135deg, #1a1f7125 0%, #16213e25 100%);
}

body.dark-theme input:disabled,
body.dark-theme textarea:disabled {
    background-color: #16213e;
    color: #6c757d;
}

body.dark-theme .form-text {
    color: #adb5bd;
}
</style>

<script>
// Open Edit Profile Modal
function openEditProfileModal() {
    closeSettingsModal();
    loadCurrentProfileData();
    document.getElementById('editProfileModal').style.display = 'flex';
}

// Close Edit Profile Modal
function closeEditProfileModal() {
    document.getElementById('editProfileModal').style.display = 'none';
    document.getElementById('editProfileForm').reset();
    clearFormErrors('editProfileForm');
}

// Load Current Profile Data
async function loadCurrentProfileData() {
    try {
        const response = await fetch('{{ route("profile.show") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('edit_name').value = data.user.name || '';
            document.getElementById('edit_email').value = data.user.email || '';
            document.getElementById('edit_phone').value = data.user.phone || '';
            document.getElementById('edit_address').value = data.user.address || '';
            document.getElementById('edit_username').value = data.user.username || '';
            document.getElementById('edit_role').value = data.user.role_display || '';
            document.getElementById('profilePhotoPreview').src = data.user.profile_photo_url;
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

// Preview Profile Photo
function previewProfilePhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('error', 'Ukuran file maksimal 2MB');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            showNotification('error', 'File harus berupa gambar');
            event.target.value = '';
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhotoPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Upload immediately
        uploadProfilePhoto(file);
    }
}

// Upload Profile Photo
async function uploadProfilePhoto(file) {
    const formData = new FormData();
    formData.append('avatar', file);
    
    try {
        const response = await fetch('{{ route("profile.update-avatar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('success', data.message);
            // Update all profile photos on page
            document.querySelectorAll('.user-profile-photo').forEach(img => {
                img.src = data.photo_url;
            });
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error uploading photo:', error);
        showNotification('error', 'Gagal mengupload foto');
    }
}

// Delete Profile Photo
async function deleteProfilePhoto() {
    if (!confirm('Yakin ingin menghapus foto profil?')) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("profile.delete-avatar") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('success', data.message);
            document.getElementById('profilePhotoPreview').src = data.photo_url;
            // Update all profile photos on page
            document.querySelectorAll('.user-profile-photo').forEach(img => {
                img.src = data.photo_url;
            });
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error deleting photo:', error);
        showNotification('error', 'Gagal menghapus foto');
    }
}

// Handle Form Submit
document.getElementById('editProfileForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    clearFormErrors('editProfileForm');
    
    try {
        const response = await fetch('{{ route("profile.update") }}', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                address: formData.get('address'),
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('success', data.message);
            closeEditProfileModal();
            
            // Update displayed name on page
            document.querySelectorAll('.user-display-name').forEach(el => {
                el.textContent = data.user.name;
            });
        } else {
            if (data.errors) {
                displayFormErrors('editProfileForm', data.errors);
            } else {
                showNotification('error', data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat menyimpan');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
    }
});
</script>