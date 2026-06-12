{{-- ========================================
     TAB 3: KELOLA PRODUK CATALOG
     ======================================== --}}
<div id="tab-catalog-products" class="cms-tab-content">
    
    {{-- HEADER SECTION WITH ADD BUTTON --}}
    <div class="cms-products-header">
        <div>
            <h2 class="cms-products-title">
                <i class="bi bi-box-seam"></i>
                Kelola Produk Catalog
            </h2>
            <p class="cms-products-subtitle">
                Total: <strong>{{ count($catalog->catalog_products) }} Produk</strong> | 
                Kategori: <strong>{{ count($catalog->catalog_categories) }}</strong>
            </p>
        </div>
        <button type="button" class="cms-btn cms-btn-success" onclick="openAddProductModal()">
            <i class="bi bi-plus-circle"></i>
            Tambah Produk Baru
        </button>
    </div>

    {{-- CARD: CATEGORY MANAGEMENT --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <i class="bi bi-tags"></i>
            <h3>Kelola Kategori</h3>
        </div>
        <div class="cms-card-body">
            
            {{-- Add Category Form --}}
            <form action="{{ route('admin.cms.catalog.category.store') }}" method="POST" class="cms-category-form">
                @csrf
                <div class="cms-form-row">
                    <div class="cms-form-group" style="flex: 1;">
                        <input 
                            type="text" 
                            name="category" 
                            class="cms-input" 
                            placeholder="Nama kategori baru..."
                            required>
                    </div>
                    <button type="submit" class="cms-btn cms-btn-primary">
                        <i class="bi bi-plus"></i>
                        Tambah Kategori
                    </button>
                </div>
            </form>

            {{-- Categories List --}}
            <div class="cms-categories-list">
                @forelse($catalog->catalog_categories as $category)
                <div class="cms-category-item">
                    <div class="cms-category-info">
                        <i class="bi bi-tag"></i>
                        <span>{{ $category }}</span>
                        <span class="cms-badge cms-badge-info">
                            {{ count($catalog->getCatalogProductsByCategory($category)) }} produk
                        </span>
                    </div>
                    <form action="{{ route('admin.cms.catalog.category.delete', $category) }}" 
                          method="POST" 
                          class="cms-delete-form"
                          onsubmit="return confirmDeleteCategory(event, '{{ $category }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-btn-icon cms-btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                @empty
                <div class="cms-empty-state">
                    <i class="bi bi-tag"></i>
                    <p>Belum ada kategori. Tambahkan kategori pertama Anda!</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- CARD: PRODUCTS LIST (COMPACT LAYOUT) --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <i class="bi bi-grid"></i>
            <h3>Daftar Produk</h3>
        </div>
        <div class="cms-card-body">
            
            @forelse($catalog->catalog_products as $product)
            <div class="cms-product-card-compact">
                
                {{-- Product Image (Thumbnail) --}}
                <div class="cms-product-image-compact">
                    @if(isset($product['image']) && $product['image'])
                    <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
                    @else
                    <div class="cms-no-image-compact">
                        <i class="bi bi-image"></i>
                    </div>
                    @endif
                </div>
                
                {{-- Product Info --}}
                <div class="cms-product-info-compact">
                    <h4 class="cms-product-name-compact">{{ $product['name'] }}</h4>
                    
                    <div class="cms-product-meta-compact">
                        <span class="cms-badge cms-badge-primary">
                            <i class="bi bi-tag"></i>
                            {{ $product['category'] }}
                        </span>
                        
                        @if(isset($product['ecommerce_link']) && $product['ecommerce_link'])
                        <a href="{{ $product['ecommerce_link'] }}" 
                           target="_blank" 
                           class="cms-badge cms-badge-success">
                            <i class="bi bi-link-45deg"></i>
                            Link
                        </a>
                        @endif
                    </div>
                    
                    <div class="cms-product-date-compact">
                        <i class="bi bi-clock"></i>
                        <small>{{ \Carbon\Carbon::parse($product['created_at'])->format('d M Y, H:i') }}</small>
                    </div>
                </div>

                {{-- Product Actions (Compact) --}}
                <div class="cms-product-actions-compact">
                    
                    {{-- Delete Image Button --}}
                    @if(isset($product['image']) && $product['image'])
                    <form action="{{ route('admin.cms.catalog.product.delete-image', $product['id']) }}" 
                          method="POST" 
                          onsubmit="return confirmDeleteImage(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-btn cms-btn-warning cms-btn-compact" title="Hapus Gambar">
                            <i class="bi bi-image"></i>
                        </button>
                    </form>
                    @endif

                    {{-- Delete Product Button --}}
                    <form action="{{ route('admin.cms.catalog.product.delete', $product['id']) }}" 
                          method="POST" 
                          onsubmit="return confirmDeleteProduct(event, '{{ $product['name'] }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-btn cms-btn-danger cms-btn-compact" title="Hapus Produk">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    
                </div>
            </div>
            @empty
            <div class="cms-empty-state">
                <i class="bi bi-box-seam"></i>
                <h3>Belum Ada Produk</h3>
                <p>Klik tombol "Tambah Produk Baru" untuk menambahkan produk pertama Anda!</p>
            </div>
            @endforelse

        </div>
    </div>

</div>

{{-- 
====================================================================
PENTING: MODAL SUDAH DI-INCLUDE DI index.blade.php
Jangan include modal di sini lagi untuk menghindari duplikasi!
====================================================================
--}}