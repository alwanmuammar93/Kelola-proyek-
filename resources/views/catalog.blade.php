@extends('layouts.frontend')

@section('title', 'Katalog Produk - PT Surabaya Las')

@section('meta_description', 'Katalog produk PT Surabaya Las - Jelajahi berbagai alat dan bahan bangunan berkualitas dari toko online kami')

@section('styles')
    <link rel="stylesheet" href="/css/catalog.css">
@endsection

@section('content')

@php
    // ✅ Ambil data dari database CMS
    $catalog = \App\Models\CMSSection::where('section_key', 'catalog_hero')->where('is_active', true)->first();
    
    // Default values jika data belum ada di database
    $catalogTitle = $catalog->title ?? 'KATALOG PRODUK';
    $catalogSubtitle = $catalog->subtitle ?? 'Jelajahi berbagai produk berkualitas dari PT Surabaya Las';
    $catalogBgImage = $catalog ? $catalog->background_image_url : asset('images/PRESENTATION (1).png');
    
    // ✅ Ambil produk dari database (bukan hardcoded)
    $products = $catalog ? $catalog->catalog_products : [];
    
    // ✅ Ambil kategori dari database
    $categories = $catalog ? $catalog->catalog_categories : [];
@endphp

<!-- HERO SECTION CATALOG - DENGAN BACKGROUND IMAGE - ✅ DARI DATABASE -->
<section class="catalog-hero">
    <!-- Background Image - ✅ DARI DATABASE -->
    <div class="catalog-hero-bg" style="background-image: url('{{ $catalogBgImage }}');"></div>
    
    <!-- Blue Shadow Overlay untuk Text Clarity -->
    <div class="catalog-hero-overlay"></div>
    
    <!-- Content - ✅ DARI DATABASE -->
    <div class="catalog-hero-content">
        <h1>{{ $catalogTitle }}</h1>
        <p>{{ $catalogSubtitle }}</p>
    </div>
</section>

<!-- CATALOG CONTENT -->
<section class="catalog-section">
    <div class="catalog-container">
        
        <!-- SEARCH & FILTER BAR -->
        <div class="filter-bar">
            <!-- Search Bar -->
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari produk...">
            </div>

            <!-- Filter Kategori - ✅ Dynamic dari database -->
            <div class="filter-group">
                <label>Kategori:</label>
                <select id="categoryFilter">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- PRODUCT GRID - ✅ Loop dari database -->
        <div class="products-grid" id="productsGrid">
            
            @forelse($products as $product)
            <div class="product-card" 
                 data-category="{{ $product['category'] ?? '' }}" 
                 data-name="{{ strtolower($product['name'] ?? '') }}">
                
                @if(isset($product['ecommerce_link']) && $product['ecommerce_link'])
                    <a href="{{ $product['ecommerce_link'] }}" target="_blank" class="product-link">
                @else
                    <div class="product-link">
                @endif
                
                    <div class="product-image">
                        @if(isset($product['image']) && $product['image'])
                            <img src="{{ asset($product['image']) }}" 
                                 alt="{{ $product['name'] ?? 'Product' }}"
                                 onerror="this.src='https://via.placeholder.com/300x300/0d2946/ffffff?text=No+Image'">
                        @else
                            <img src="https://via.placeholder.com/300x300/0d2946/ffffff?text=No+Image" 
                                 alt="No Image">
                        @endif
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name">{{ $product['name'] ?? 'Unnamed Product' }}</h3>
                        
                        {{-- ❌ HAPUS BADGE KATEGORI INI --}}
                        {{-- 
                        @if(isset($product['category']))
                        <span class="product-category-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                            {{ $product['category'] }}
                        </span>
                        @endif
                        --}}
                    </div>
                
                @if(isset($product['ecommerce_link']) && $product['ecommerce_link'])
                    </a>
                @else
                    </div>
                @endif
                
            </div>
            @empty
            <!-- Empty State jika belum ada produk -->
            <div class="empty-state-wrapper">
                <div class="empty-state-content">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <h3>Belum Ada Produk</h3>
                    <p>Produk sedang dalam proses penambahan. Silakan kunjungi toko online kami di bawah ini untuk melihat katalog lengkap.</p>
                </div>
            </div>
            @endforelse

        </div>

        <!-- NO RESULTS MESSAGE (untuk search) -->
        <div class="no-results" id="noResults" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
            <h3>Produk tidak ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian Anda</p>
        </div>

    </div>
</section>

<!-- SECTION: BELUM MENEMUKAN BARANG -->
<section class="cta-ecommerce-section">
    <div class="cta-container">
        
        <!-- Poster Image - Kiri -->
        <div class="cta-poster">
            <img src="{{ asset('images/poster-ecommerce.jpg') }}" alt="Surabaya Las E-Commerce">
        </div>

        <!-- Content - Kanan -->
        <div class="cta-content">
            <h2>Belum Menemukan Barang yang Anda Cari?</h2>
            <p>Kunjungi toko online kami di berbagai platform e-commerce untuk melihat koleksi produk lengkap PT Surabaya Las</p>
            
            <!-- E-Commerce Buttons -->
            <div class="ecommerce-buttons">
                <!-- Shopee -->
                <a href="https://id.shp.ee/njzpTQM" target="_blank" class="ecom-btn shopee-btn">
                    <img src="{{ asset('images/Shopee.png') }}" alt="Shopee">
                    <span>Shopee</span>
                </a>

                <!-- Tokopedia -->
                <a href="https://tokopedia.link/hCuTPSIHTYb" target="_blank" class="ecom-btn tokopedia-btn">
                    <img src="{{ asset('images/Tokopedia.png') }}" alt="Tokopedia">
                    <span>Tokopedia</span>
                </a>

                <!-- TikTok Shop -->
                <a href="https://www.tiktok.com/@tokosurabayalasinti?_r=1&_t=ZS-91dmI6HZfiD" target="_blank" class="ecom-btn tiktok-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                    </svg>
                    <span>TikTok Shop</span>
                </a>

                <!-- Facebook Marketplace -->
                <a href="https://www.facebook.com/marketplace/profile/100055999577827/?ref=share_attachment" target="_blank" class="ecom-btn facebook-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm2.5 9.5h-2v7h-3v-7h-1.5V9h1.5V7.5c0-1.93 1.17-3 3-3h2v2.5h-1.5c-.55 0-1 .45-1 1V9h2.5l-.5 2.5z"/>
                    </svg>
                    <span>Facebook</span>
                </a>

                <!-- WhatsApp Catalog -->
                <a href="https://wa.me/c/6285211887779" target="_blank" class="ecom-btn whatsapp-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>

    </div>
</section>

@endsection

@section('scripts')
<script>
// FILTER & SEARCH FUNCTIONALITY
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const productsGrid = document.getElementById('productsGrid');
const noResults = document.getElementById('noResults');

function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase();
    const selectedCategory = categoryFilter.value;
    
    const cards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const name = card.dataset.name;
        const category = card.dataset.category;
        
        const matchSearch = name.includes(searchTerm);
        const matchCategory = selectedCategory === 'all' || category === selectedCategory;
        
        if (matchSearch && matchCategory) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    if (visibleCount === 0) {
        productsGrid.style.display = 'none';
        noResults.style.display = 'block';
    } else {
        productsGrid.style.display = 'grid';
        noResults.style.display = 'none';
    }
}

// Event listeners
if (searchInput) {
    searchInput.addEventListener('input', filterProducts);
}

if (categoryFilter) {
    categoryFilter.addEventListener('change', filterProducts);
}

// Lazy load images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.product-image img');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.src; // Trigger load
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});
</script>
@endsection