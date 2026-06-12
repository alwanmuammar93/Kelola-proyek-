{{-- ========================================
     TAB 4: PROYEK HERO SECTION
     ======================================== --}}
<div id="tab-proyek" class="cms-tab-content">
    <form action="{{ route('admin.cms.proyek.update') }}" method="POST" enctype="multipart/form-data" class="cms-form">
        @csrf
        
        {{-- CARD: Proyek Hero Content --}}
        <div class="cms-card">
            <div class="cms-card-header">
                <i class="bi bi-building"></i>
                <h3>Proyek Hero Section</h3>
            </div>
            <div class="cms-card-body">
                
                {{-- Judul Header (Baris 1) --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Judul Header Proyek (Baris 1)
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        class="cms-input" 
                        value="{{ old('title', $proyek->title ?? 'LAYANAN KONSTRUKSI') }}"
                        placeholder="Contoh: LAYANAN KONSTRUKSI"
                        required>
                </div>

                {{-- Subtitle (Baris 2 - Highlight) --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Subtitle (Baris 2 - Highlight)
                        <span class="cms-required">*</span>
                        <span class="cms-badge cms-badge-warning">Highlighted Yellow</span>
                    </label>
                    <input 
                        type="text" 
                        name="subtitle" 
                        class="cms-input" 
                        value="{{ old('subtitle', $proyek->subtitle ?? 'PT SURABAYA LAS') }}"
                        placeholder="Contoh: PT SURABAYA LAS"
                        required>
                    <small class="cms-help-text">
                        <i class="bi bi-info-circle"></i>
                        Teks ini akan ditampilkan dengan highlight warna kuning
                    </small>
                </div>

                {{-- Section Divider --}}
                <div class="cms-section-divider">
                    <span>Tombol Call-to-Action</span>
                </div>

                {{-- Tombol WhatsApp --}}
                <div class="cms-form-row">
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol WhatsApp - Text</label>
                        <input 
                            type="text" 
                            name="button1_text" 
                            class="cms-input" 
                            value="{{ old('button1_text', $proyek->button1_text ?? 'HUBUNGI KAMI SEKARANG') }}"
                            placeholder="Text tombol">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol WhatsApp - Link</label>
                        <input 
                            type="text" 
                            name="button1_link" 
                            class="cms-input" 
                            value="{{ old('button1_link', $proyek->button1_link ?? 'https://wa.me/6285211887779') }}"
                            placeholder="https://wa.me/...">
                    </div>
                </div>

                {{-- Section Divider --}}
                <div class="cms-section-divider">
                    <span>Background Image Header</span>
                </div>

                {{-- Background Image Upload --}}
                <div class="cms-form-group">
                    <label class="cms-label">Background Image</label>
                    <div class="cms-upload-area" onclick="document.getElementById('proyekBgInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Upload Background Image</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG, JPG, WEBP (Max 5MB)</span>
                        </div>
                        <input 
                            type="file" 
                            id="proyekBgInput" 
                            name="background_image" 
                            accept="image/*" 
                            onchange="previewImage(this, 'proyekBgPreview')"
                            style="display: none;">
                    </div>
                    
                    @if(isset($proyek->background_image))
                    <div class="cms-image-preview" id="proyekBgPreview">
                        <img src="{{ $proyek->background_image_url }}" alt="Current Background">
                        <div class="cms-image-overlay">
                            <button type="button" class="cms-btn-icon cms-btn-danger" onclick="removePreview('proyekBgPreview', 'proyekBgInput')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="cms-image-preview" id="proyekBgPreview" style="display: none;"></div>
                    @endif
                </div>

            </div>
        </div>

        {{-- CARD: Form Footer with Toggle & Submit --}}
        <div class="cms-card">
            <div class="cms-card-body">
                <input type="hidden" name="is_active" value="0">
                <div class="cms-form-footer">
                    <div class="cms-toggle-switch">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="proyekActive" 
                            class="cms-toggle-input"
                            value="1"
                            {{ old('is_active', $proyek->is_active ?? true) ? 'checked' : '' }}>
                        <label for="proyekActive" class="cms-toggle-label">
                            <span class="cms-toggle-button"></span>
                            <span class="cms-toggle-text">Aktifkan Proyek Hero</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="cms-btn cms-btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>