{{-- ========================================
     TAB 1: HERO BERANDA SECTION
     ======================================== --}}
<div id="tab-hero" class="cms-tab-content active">
    <form action="{{ route('admin.cms.hero.update') }}" method="POST" enctype="multipart/form-data" class="cms-form">
        @csrf
        
        {{-- CARD: Hero Section Content --}}
        <div class="cms-card">
            <div class="cms-card-header">
                <i class="bi bi-card-text"></i>
                <h3>Hero Section Content</h3>
            </div>
            <div class="cms-card-body">
                
                {{-- Nama Perusahaan --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Nama Perusahaan 
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        class="cms-input" 
                        value="{{ old('title', $hero->title ?? 'PT SURABAYA LAS') }}" 
                        placeholder="Contoh: PT SURABAYA LAS"
                        required>
                    <small class="cms-help-text">Nama perusahaan akan ditampilkan di bagian utama website</small>
                </div>

                {{-- Tagline Baris 1 & 2 --}}
                <div class="cms-form-row">
                    <div class="cms-form-group">
                        <label class="cms-label">
                            Tagline Baris 1 
                            <span class="cms-required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="subtitle_line1" 
                            class="cms-input" 
                            value="{{ old('subtitle_line1', $hero->subtitle_lines[0] ?? 'Solusi Konstruksi & Penjualan') }}"
                            placeholder="Tagline baris pertama"
                            required>
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label">
                            Tagline Baris 2 
                            <span class="cms-required">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="subtitle_line2" 
                            class="cms-input" 
                            value="{{ old('subtitle_line2', $hero->subtitle_lines[1] ?? 'Alat dan Bahan Bangunan') }}"
                            placeholder="Tagline baris kedua"
                            required>
                    </div>
                </div>

                {{-- Tagline Baris 3 (Highlight) --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Tagline Baris 3 (Highlight) 
                        <span class="cms-required">*</span>
                        <span class="cms-badge cms-badge-warning">Highlighted</span>
                    </label>
                    <input 
                        type="text" 
                        name="subtitle_line3" 
                        class="cms-input" 
                        value="{{ old('subtitle_line3', $hero->subtitle_lines[2] ?? 'Terbaik Untuk Anda') }}"
                        placeholder="Tagline highlight"
                        required>
                    <small class="cms-help-text">
                        <i class="bi bi-info-circle"></i>
                        Baris ini akan ditampilkan dengan warna kuning (highlight)
                    </small>
                </div>

                {{-- Section Divider --}}
                <div class="cms-section-divider">
                    <span>Tombol Call-to-Action</span>
                </div>

                {{-- Tombol 1 --}}
                <div class="cms-form-row">
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol 1 - Text</label>
                        <input 
                            type="text" 
                            name="button1_text" 
                            class="cms-input" 
                            value="{{ old('button1_text', $hero->button1_text ?? 'LIHAT PROYEK KAMI') }}"
                            placeholder="Text tombol pertama">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol 1 - Link</label>
                        <input 
                            type="text" 
                            name="button1_link" 
                            class="cms-input" 
                            value="{{ old('button1_link', $hero->button1_link ?? '/galeri-proyek') }}"
                            placeholder="/galeri-proyek">
                    </div>
                </div>

                {{-- Tombol 2 --}}
                <div class="cms-form-row">
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol 2 - Text</label>
                        <input 
                            type="text" 
                            name="button2_text" 
                            class="cms-input" 
                            value="{{ old('button2_text', $hero->button2_text ?? 'HUBUNGI KAMI SEKARANG') }}"
                            placeholder="Text tombol kedua">
                    </div>
                    <div class="cms-form-group">
                        <label class="cms-label">Tombol 2 - Link</label>
                        <input 
                            type="text" 
                            name="button2_link" 
                            class="cms-input" 
                            value="{{ old('button2_link', $hero->button2_link ?? '/kontak') }}"
                            placeholder="/kontak">
                    </div>
                </div>

            </div>
        </div>

        {{-- CARD: Hero Images --}}
        <div class="cms-card">
            <div class="cms-card-header">
                <i class="bi bi-image"></i>
                <h3>Hero Images</h3>
            </div>
            <div class="cms-card-body">
                
                {{-- Background Image --}}
                <div class="cms-form-group">
                    <label class="cms-label">Background Image</label>
                    <div class="cms-upload-area" onclick="document.getElementById('heroImageInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Upload Background Image</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG, JPG, WEBP (Max 5MB)</span>
                        </div>
                        <input 
                            type="file" 
                            id="heroImageInput" 
                            name="image" 
                            accept="image/*" 
                            onchange="previewImage(this, 'heroImagePreview')"
                            style="display: none;">
                    </div>
                    
                    @if(isset($hero->image))
                    <div class="cms-image-preview" id="heroImagePreview">
                        <img src="{{ $hero->image_url }}" alt="Current Background">
                        <div class="cms-image-overlay">
                            <button type="button" class="cms-btn-icon cms-btn-danger" onclick="removePreview('heroImagePreview', 'heroImageInput')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="cms-image-preview" id="heroImagePreview" style="display: none;"></div>
                    @endif
                </div>

                {{-- Logo Perusahaan --}}
                <div class="cms-form-group">
                    <label class="cms-label">Logo Perusahaan</label>
                    <div class="cms-upload-area" onclick="document.getElementById('heroLogoInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-file-earmark-image"></i>
                            <h4>Upload Logo Perusahaan</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG (Recommended with transparent background)</span>
                        </div>
                        <input 
                            type="file" 
                            id="heroLogoInput" 
                            name="logo" 
                            accept="image/*" 
                            onchange="previewImage(this, 'heroLogoPreview')"
                            style="display: none;">
                    </div>
                    
                    @if(isset($hero->logo))
                    <div class="cms-image-preview" id="heroLogoPreview">
                        <img src="{{ $hero->logo_url }}" alt="Current Logo">
                        <div class="cms-image-overlay">
                            <button type="button" class="cms-btn-icon cms-btn-danger" onclick="removePreview('heroLogoPreview', 'heroLogoInput')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="cms-image-preview" id="heroLogoPreview" style="display: none;"></div>
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
                            id="heroActive" 
                            class="cms-toggle-input"
                            value="1"
                            {{ old('is_active', $hero->is_active ?? true) ? 'checked' : '' }}>
                        <label for="heroActive" class="cms-toggle-label">
                            <span class="cms-toggle-button"></span>
                            <span class="cms-toggle-text">Aktifkan Hero Section</span>
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

{{-- ========================================
     TAB 2: CATALOG HERO SECTION
     ======================================== --}}
<div id="tab-catalog" class="cms-tab-content">
    <form action="{{ route('admin.cms.catalog.update') }}" method="POST" enctype="multipart/form-data" class="cms-form">
        @csrf
        
        {{-- CARD: Catalog Hero Section --}}
        <div class="cms-card">
            <div class="cms-card-header">
                <i class="bi bi-shop"></i>
                <h3>Catalog Hero Section</h3>
            </div>
            <div class="cms-card-body">
                
                {{-- Judul Header --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Judul Header Catalog
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        class="cms-input" 
                        value="{{ old('title', $catalog->title ?? 'KATALOG PRODUK') }}"
                        placeholder="Contoh: KATALOG PRODUK"
                        required>
                </div>

                {{-- Subtitle Header --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Subtitle Header Catalog
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="subtitle" 
                        class="cms-input" 
                        value="{{ old('subtitle', $catalog->subtitle ?? 'Jelajahi berbagai produk berkualitas dari PT Surabaya Las') }}"
                        placeholder="Subtitle untuk catalog"
                        required>
                </div>

                {{-- Section Divider --}}
                <div class="cms-section-divider">
                    <span>Background Image Header</span>
                </div>

                {{-- Background Image --}}
                <div class="cms-form-group">
                    <label class="cms-label">Background Image</label>
                    <div class="cms-upload-area" onclick="document.getElementById('catalogBgInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Upload Background Image</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG, JPG, WEBP (Max 5MB)</span>
                        </div>
                        <input 
                            type="file" 
                            id="catalogBgInput" 
                            name="background_image" 
                            accept="image/*" 
                            onchange="previewImage(this, 'catalogBgPreview')"
                            style="display: none;">
                    </div>
                    
                    @if(isset($catalog->background_image))
                    <div class="cms-image-preview" id="catalogBgPreview">
                        <img src="{{ $catalog->background_image_url }}" alt="Current Background">
                        <div class="cms-image-overlay">
                            <button type="button" class="cms-btn-icon cms-btn-danger" onclick="removePreview('catalogBgPreview', 'catalogBgInput')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="cms-image-preview" id="catalogBgPreview" style="display: none;"></div>
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
                            id="catalogActive" 
                            class="cms-toggle-input"
                            value="1"
                            {{ old('is_active', $catalog->is_active ?? true) ? 'checked' : '' }}>
                        <label for="catalogActive" class="cms-toggle-label">
                            <span class="cms-toggle-button"></span>
                            <span class="cms-toggle-text">Aktifkan Catalog Hero</span>
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