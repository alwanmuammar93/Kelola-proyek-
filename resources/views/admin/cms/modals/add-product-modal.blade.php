{{-- ========================================
     MODAL: ADD PRODUCT
     ======================================== --}}
<div id="addProductModal" class="cms-modal" style="display: none;">
    <div class="cms-modal-overlay" onclick="closeAddProductModal()"></div>
    <div class="cms-modal-content">
        <div class="cms-modal-header">
            <h3>
                <i class="bi bi-plus-circle"></i>
                Tambah Produk Baru
            </h3>
            <button type="button" class="cms-modal-close" onclick="closeAddProductModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.cms.catalog.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="cms-modal-body">
                
                {{-- Nama Produk --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Nama Produk
                        <span class="cms-required">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        class="cms-input" 
                        placeholder="Contoh: Besi Siku 40x40"
                        required>
                </div>

                {{-- Kategori Produk --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Kategori Produk
                        <span class="cms-required">*</span>
                    </label>
                    <select name="category" class="cms-input" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($catalog->catalog_categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Link E-commerce --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Link E-commerce (Opsional)
                    </label>
                    <input 
                        type="url" 
                        name="ecommerce_link" 
                        class="cms-input" 
                        placeholder="https://tokopedia.com/...">
                    <small class="cms-help-text">
                        <i class="bi bi-info-circle"></i>
                        Link ke toko online (Tokopedia, Shopee, dll)
                    </small>
                </div>

                {{-- Gambar Produk --}}
                <div class="cms-form-group">
                    <label class="cms-label">
                        Gambar Produk
                        <span class="cms-required">*</span>
                    </label>
                    <div class="cms-upload-area" onclick="document.getElementById('addProductImageInput').click()">
                        <div class="cms-upload-content">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <h4>Upload Gambar Produk</h4>
                            <p>Klik atau drag & drop file di sini</p>
                            <span class="cms-upload-format">PNG, JPG, WEBP (Max 5MB)</span>
                        </div>
                        <input 
                            type="file" 
                            id="addProductImageInput" 
                            name="image" 
                            accept="image/*" 
                            onchange="previewImage(this, 'addProductImagePreview')"
                            style="display: none;"
                            required>
                    </div>
                    <div class="cms-image-preview" id="addProductImagePreview" style="display: none;"></div>
                </div>

            </div>

            <div class="cms-modal-footer">
                <button type="button" class="cms-btn cms-btn-secondary" onclick="closeAddProductModal()">
                    <i class="bi bi-x-circle"></i>
                    Batal
                </button>
                <button type="submit" class="cms-btn cms-btn-success">
                    <i class="bi bi-check-circle"></i>
                    Tambah Produk
                </button>
            </div>
        </form>
    </div>
</div>