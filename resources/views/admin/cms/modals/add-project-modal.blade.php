{{-- ========================================
     MODAL: ADD PROJECT
     ======================================== --}}
<div id="addProjectModal" class="cms-modal" style="display: none;">
    <div class="cms-modal-overlay" onclick="closeAddProjectModal()"></div>
    <div class="cms-modal-content">
        <div class="cms-modal-header">
            <h3>
                <i class="bi bi-plus-circle"></i>
                Tambah Proyek Baru
            </h3>
            <button type="button" class="cms-modal-close" onclick="closeAddProjectModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.cms.project.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="cms-modal-body">
                
                {{-- Nama Proyek --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Nama Proyek
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="cms-input" 
                        placeholder="Contoh: Kanopi Besi Hollow"
                        required>
                </div>

                {{-- Deskripsi Proyek --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Deskripsi Proyek
                        <span class="cms-required">*</span>
                    </label>
                    <textarea 
                        name="description" 
                        class="cms-textarea" 
                        rows="5"
                        placeholder="Deskripsi lengkap proyek..."
                        required></textarea>
                    <small class="cms-help-text">
                        <i class="bi bi-info-circle"></i>
                        Jelaskan detail proyek secara lengkap (max 2000 karakter)
                    </small>
                </div>

                {{-- Gambar Proyek --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Gambar Proyek
                        <span class="cms-required">*</span>
                    </label>
                    <div class="cms-upload-area" onclick="document.getElementById('addProjectImageInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Upload Gambar Proyek</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG, JPG, WEBP (Max 5MB)</span>
                        </div>
                        <input 
                            type="file" 
                            id="addProjectImageInput" 
                            name="image" 
                            accept="image/*" 
                            onchange="previewImage(this, 'addProjectImagePreview')"
                            style="display: none;"
                            required>
                    </div>
                    <div class="cms-image-preview" id="addProjectImagePreview" style="display: none;"></div>
                </div>

            </div>

            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-secondary" onclick="closeAddProjectModal()">
                    <i class="bi bi-x-circle"></i>
                    Batal
                </button>
                <button type="submit" class="cms-btn cms-btn-success">
                    <i class="bi bi-check-circle"></i>
                    Tambah Proyek
                </button>
            </div>
        </form>
    </div>
</div>