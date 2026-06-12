/* ========================================
   CMS PROJECTS MODAL MANAGEMENT
   File: public/js/admin/cms-projects.js
   Purpose: Manage Add/Edit Project Modals
   ======================================== */

// ========================================
// OPEN ADD PROJECT MODAL
// ========================================
function openAddProjectModal() {
    // PENTING: Tutup semua modal lain dulu!
    closeAddProductModal();
    
    const modal = document.getElementById('addProjectModal');
    if (!modal) {
        console.error('❌ Add Project Modal not found!');
        return;
    }
    
    // Tampilkan modal dengan display flex
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Focus pada input pertama
    setTimeout(() => {
        const firstInput = modal.querySelector('input[name="name"]');
        if (firstInput) firstInput.focus();
    }, 100);
    
    console.log('✅ Add Project Modal opened');
}

// ========================================
// CLOSE ADD PROJECT MODAL
// ========================================
function closeAddProjectModal() {
    const modal = document.getElementById('addProjectModal');
    if (!modal) return;
    
    // Sembunyikan modal
    modal.style.display = 'none';
    document.body.style.overflow = '';
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) form.reset();
    
    // Reset image preview
    const preview = document.getElementById('addProjectImagePreview');
    if (preview) {
        preview.style.display = 'none';
        preview.innerHTML = '';
    }
    
    // Reset file input
    const fileInput = document.getElementById('addProjectImageInput');
    if (fileInput) fileInput.value = '';
    
    // Reset character counter
    const counter = modal.querySelector('.cms-char-counter');
    if (counter) {
        counter.textContent = '0 / 2000 karakter';
        counter.style.color = '#6c757d';
    }
    
    console.log('✅ Add Project Modal closed');
}

// ========================================
// HELPER: CLOSE PRODUCT MODAL (JIKA ADA)
// ========================================
function closeAddProductModal() {
    const productModal = document.getElementById('addProductModal');
    if (productModal) {
        productModal.style.display = 'none';
    }
}

// ========================================
// CLOSE MODAL ON OVERLAY CLICK
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const addProjectModal = document.getElementById('addProjectModal');
    
    if (addProjectModal) {
        // Tambahkan event listener ke overlay
        const overlay = addProjectModal.querySelector('.cms-modal-overlay');
        if (overlay) {
            overlay.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAddProjectModal();
            });
        }
        
        // Juga handle klik langsung ke modal wrapper
        addProjectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddProjectModal();
            }
        });
    }
});

// ========================================
// CLOSE MODAL ON ESC KEY
// ========================================
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // Close project modal if visible
        const projectModal = document.getElementById('addProjectModal');
        if (projectModal && projectModal.style.display !== 'none') {
            closeAddProjectModal();
        }
    }
});

// ========================================
// FORM VALIDATION (TANPA KATEGORI & LINK)
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const addProjectForm = document.querySelector('#addProjectModal form');
    
    if (addProjectForm) {
        addProjectForm.addEventListener('submit', function(e) {
            const nameInput = this.querySelector('input[name="name"]');
            const descriptionInput = this.querySelector('textarea[name="description"]');
            const imageInput = this.querySelector('input[name="image"]');
            
            let errors = [];
            
            // Validate name
            if (!nameInput || !nameInput.value.trim()) {
                errors.push('Nama proyek wajib diisi!');
            } else if (nameInput.value.trim().length < 3) {
                errors.push('Nama proyek minimal 3 karakter!');
            }
            
            // Validate description
            if (!descriptionInput || !descriptionInput.value.trim()) {
                errors.push('Deskripsi proyek wajib diisi!');
            } else if (descriptionInput.value.trim().length < 10) {
                errors.push('Deskripsi proyek minimal 10 karakter!');
            } else if (descriptionInput.value.length > 2000) {
                errors.push('Deskripsi proyek maksimal 2000 karakter!');
            }
            
            // Validate image
            if (!imageInput || !imageInput.files || imageInput.files.length === 0) {
                errors.push('Gambar proyek wajib diupload!');
            } else {
                const file = imageInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                if (file.size > maxSize) {
                    errors.push('Ukuran gambar maksimal 5MB!');
                }
                
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    errors.push('Format gambar harus: JPEG, JPG, PNG, atau WEBP!');
                }
            }
            
            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                alert('❌ Validasi gagal:\n\n' + errors.join('\n'));
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
                
                // Restore button jika ada error dari server
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                }, 10000);
            }
        });
    }
});

// ========================================
// CHARACTER COUNTER FOR DESCRIPTION
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const descriptionTextarea = document.querySelector('#addProjectModal textarea[name="description"]');
    
    if (descriptionTextarea) {
        // Create counter element
        const counterDiv = document.createElement('div');
        counterDiv.className = 'cms-char-counter';
        counterDiv.style.cssText = 'text-align: right; font-size: 0.85rem; color: #6c757d; margin-top: 5px;';
        
        const updateCounter = () => {
            const current = descriptionTextarea.value.length;
            const max = 2000;
            counterDiv.textContent = `${current} / ${max} karakter`;
            
            if (current > max) {
                counterDiv.style.color = '#dc3545';
            } else if (current > max * 0.9) {
                counterDiv.style.color = '#ffc107';
            } else {
                counterDiv.style.color = '#6c757d';
            }
        };
        
        // Insert counter after help text (if exists) or after textarea
        const helpText = descriptionTextarea.parentElement.querySelector('.cms-help-text');
        if (helpText) {
            helpText.parentElement.insertBefore(counterDiv, helpText.nextSibling);
        } else {
            descriptionTextarea.parentElement.appendChild(counterDiv);
        }
        
        descriptionTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }
});

// ========================================
// CONFIRM DELETE PROJECT
// ========================================
function confirmDeleteProject(projectName) {
    return confirm(`⚠️ PERINGATAN!\n\nYakin ingin menghapus proyek "${projectName}"?\n\nTindakan ini tidak dapat dibatalkan!`);
}

// ========================================
// CONFIRM DELETE PROJECT IMAGE
// ========================================
function confirmDeleteProjectImage(projectName) {
    return confirm(`Yakin ingin menghapus gambar proyek "${projectName}"?\n\nGambar akan dihapus permanen, tapi data proyek tetap tersimpan.`);
}

// ========================================
// PREVENT DOUBLE SUBMIT
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('#addProjectModal form');
    
    forms.forEach(form => {
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            // Skip if already submitting
            if (isSubmitting) {
                e.preventDefault();
                console.warn('Form sudah dalam proses submit!');
                return false;
            }
            
            // Check validation first
            const nameInput = this.querySelector('input[name="name"]');
            const descriptionInput = this.querySelector('textarea[name="description"]');
            const imageInput = this.querySelector('input[name="image"]');
            
            if (!nameInput?.value.trim() || !descriptionInput?.value.trim() || !imageInput?.files?.length) {
                // Let the form validation handler above handle this
                return;
            }
            
            // Mark as submitting
            isSubmitting = true;
            console.log('✅ Form validation passed, submitting...');
            
            // Reset after 10 seconds (in case of network issues)
            setTimeout(() => {
                isSubmitting = false;
            }, 10000);
        });
    });
});

// ========================================
// CONSOLE LOG (FOR DEBUGGING)
// ========================================
console.log('✅ CMS Projects JS loaded successfully');
console.log('📝 Validasi aktif: Nama, Deskripsi, Gambar');
console.log('❌ Kategori & Link Eksternal: TIDAK ada validasi');