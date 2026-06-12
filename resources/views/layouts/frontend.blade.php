<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Surabaya Las - @yield('title', 'Solusi Las & Konstruksi Terbaik')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Global - Navbar & Footer -->
    <link rel="stylesheet" href="/css/global.css">
    
    <!-- CSS Tambahan Per Halaman -->
    @yield('styles')
    
    <!-- Meta Description -->
    <meta name="description" content="@yield('meta_description', 'PT Surabaya Las - Spesialis jasa bengkel las profesional dengan pengalaman puluhan tahun. Layanan terbaik dalam pekerjaan dan konstruksi pengelasan.')">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar" id="mainNavbar">
        <div class="navbar-container">
            <!-- Hamburger Menu (Mobile) - KIRI -->
            <button class="hamburger-menu" id="hamburgerMenu" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Social Icons - TENGAH -->
            <div class="social-icons">
                <!-- Instagram -->
                <a href="https://www.instagram.com/cvsurabayalas?igsh=dXEzem9paGhlZndm" target="_blank" class="icon-circle icon-instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                
                <!-- WhatsApp -->
                <a href="https://wa.me/c/6285211887779" target="_blank" class="icon-circle icon-whatsapp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                </a>
                
                <!-- TikTok -->
                <a href="https://www.tiktok.com/@tokosurabayalasinti?_r=1&_t=ZS-91dmI6HZfiD" target="_blank" class="icon-circle icon-tiktok">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                    </svg>
                </a>
                
                <!-- Shopee -->
                <a href="https://id.shp.ee/njzpTQM" target="_blank" class="icon-circle icon-shopee">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/2048px-Shopee.svg.png" alt="Shopee" width="20" height="20">
                </a>
            </div>

            <!-- Navigation - Desktop -->
            <nav class="main-nav" id="mainNav">
                <a href="{{ route('beranda') }}" class="{{ Request::routeIs('beranda') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('tentang-kami') }}" class="{{ Request::routeIs('tentang-kami') ? 'active' : '' }}">Tentang Kami</a>
                <a href="{{ route('catalog') }}" class="{{ Request::routeIs('catalog') ? 'active' : '' }}">Catalog</a>
                <a href="{{ route('galeri.proyek') }}" class="{{ Request::routeIs('galeri.proyek') ? 'active' : '' }}">Proyek</a>
                <a href="{{ route('kontak') }}" class="{{ Request::routeIs('kontak') ? 'active' : '' }}">Kontak</a>
            </nav>

            <!-- Search Icon - KANAN -->
            <button class="search-icon" id="searchIcon" onclick="openSearchModal()" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENT - Di-inject dari halaman child -->
    @yield('content')

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2024 PT Surabaya Las. All Rights Reserved.</p>
        <p class="address">Jl. Bandara lama, Maros, Sulawesi Selatan</p>
    </footer>

    <!-- STICKY NAVBAR & HAMBURGER SCRIPT -->
    <script>
        // Sticky Navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Hamburger Menu Toggle
        const hamburger = document.getElementById('hamburgerMenu');
        const mainNav = document.getElementById('mainNav');
        const body = document.body;

        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            mainNav.classList.toggle('active');
            body.classList.toggle('nav-open');
        });

        // Close menu when clicking nav link
        const navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                mainNav.classList.remove('active');
                body.classList.remove('nav-open');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = mainNav.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnHamburger && mainNav.classList.contains('active')) {
                hamburger.classList.remove('active');
                mainNav.classList.remove('active');
                body.classList.remove('nav-open');
            }
        });
    </script>

    <!-- Smooth Scroll Script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navbarHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    <!-- Scripts Tambahan Per Halaman -->
    @yield('scripts')
    
    <!-- Stack untuk Scripts Tambahan -->
    @stack('scripts')

    <!-- ==================== SEARCH MODAL HTML (UPDATED) ==================== -->
    <div class="search-modal-overlay" id="searchModalOverlay">
        <div class="search-modal-container">
            
            <!-- HEADER -->
            <div class="search-modal-header">
                <div class="search-input-wrapper">
                    <svg class="search-input-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                    <input 
                        type="text" 
                        id="searchModalInput" 
                        class="search-modal-input" 
                        placeholder="Cari layanan, produk, proyek, atau informasi kontak..."
                        autocomplete="off"
                        spellcheck="false"
                    >
                </div>
                <button class="search-modal-close" onclick="closeSearchModal()" title="Tutup (ESC)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- FILTER TABS (UPDATED - Sesuai Dashboard) -->
            <div class="search-filter-tabs">
                <button class="search-filter-tab active" data-category="all" onclick="filterSearchCategory('all')">
                    <span class="tab-icon">🔍</span> Semua
                </button>
                <button class="search-filter-tab" data-category="beranda" onclick="filterSearchCategory('beranda')">
                    <span class="tab-icon">🏠</span> Beranda
                </button>
                <button class="search-filter-tab" data-category="tentang-kami" onclick="filterSearchCategory('tentang-kami')">
                    <span class="tab-icon">ℹ️</span> Tentang Kami
                </button>
                <button class="search-filter-tab" data-category="catalog" onclick="filterSearchCategory('catalog')">
                    <span class="tab-icon">📦</span> Catalog
                </button>
                <button class="search-filter-tab" data-category="proyek" onclick="filterSearchCategory('proyek')">
                    <span class="tab-icon">🏗️</span> Proyek
                </button>
                <button class="search-filter-tab" data-category="kontak" onclick="filterSearchCategory('kontak')">
                    <span class="tab-icon">📞</span> Kontak
                </button>
            </div>

            <!-- RESULTS -->
            <div class="search-modal-results" id="searchModalResults">
                <div class="search-empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                    <h3>Mulai Pencarian Anda</h3>
                    <p>Ketik kata kunci untuk mencari layanan, produk, atau informasi</p>
                </div>
            </div>

            <!-- KEYBOARD SHORTCUTS HINT -->
            <div class="search-modal-footer">
                <div class="keyboard-hints">
                    <span class="hint"><kbd>ESC</kbd> untuk tutup</span>
                    <span class="hint"><kbd>↵</kbd> untuk buka hasil pertama</span>
                    <span class="hint"><kbd>Ctrl</kbd> + <kbd>K</kbd> untuk membuka pencarian</span>
                </div>
            </div>

        </div>
    </div>

    <!-- LOAD SEARCH MODAL CSS -->
    <link rel="stylesheet" href="/css/search-modal.css">

    <!-- ADDITIONAL STYLES FOR HAMBURGER MENU & RESPONSIVE NAVBAR -->
    <style>
        /* ========== HAMBURGER MENU ========== */
        .hamburger-menu {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 28px;
            height: 24px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1001;
            position: relative;
        }

        .hamburger-menu span {
            width: 100%;
            height: 3px;
            background: white;
            border-radius: 3px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .hamburger-menu.active span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger-menu.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }

        .hamburger-menu.active span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        /* ========== SEARCH BUTTON STYLING ========== */
        .search-icon {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border-radius: 6px;
        }

        .search-icon:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }

        .search-icon:active {
            transform: scale(0.95);
        }

        .search-icon svg {
            display: block;
        }

        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 768px) {
            /* Show hamburger menu */
            .hamburger-menu {
                display: flex;
                order: 2;
            }

            /* Reorder navbar items */
            .navbar-container {
                justify-content: space-between;
            }

            .social-icons {
                order: 1;
                gap: 8px;
            }

            .main-nav {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background: #0d2946;
                flex-direction: column;
                padding: 20px;
                gap: 0;
                transition: left 0.3s ease;
                z-index: 999;
                overflow-y: auto;
            }

            .main-nav.active {
                left: 0;
            }

            .main-nav a {
                width: 100%;
                padding: 18px 20px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 16px;
                text-align: left;
            }

            .main-nav a:hover,
            .main-nav a.active {
                background: rgba(255, 255, 255, 0.1);
            }

            .search-icon {
                order: 3;
                padding: 6px;
            }
            
            .search-icon svg {
                width: 18px;
                height: 18px;
            }

            /* Prevent body scroll when nav is open */
            body.nav-open {
                overflow: hidden;
            }
        }

        @media (max-width: 480px) {
            .social-icons .icon-circle {
                width: 35px;
                height: 35px;
            }

            .social-icons svg {
                width: 16px;
                height: 16px;
            }

            .hamburger-menu {
                width: 26px;
                height: 22px;
            }

            .hamburger-menu span {
                height: 2.5px;
            }

            .main-nav {
                top: 60px;
                height: calc(100vh - 60px);
            }

            .main-nav a {
                padding: 16px 18px;
                font-size: 15px;
            }
        }

        /* ========== TAB ICONS ========== */
        .tab-icon {
            font-size: 14px;
            margin-right: 4px;
        }

        /* ========== SEARCH RESULTS INFO ========== */
        .search-results-info {
            padding: 12px 16px;
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            margin-bottom: 16px;
            border-radius: 6px;
            font-size: 14px;
            color: #0c4a6e;
        }

        /* ========== CATEGORY ICON IN SECTION TITLE ========== */
        .search-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-icon {
            font-size: 18px;
        }

        .result-count {
            margin-left: auto;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ========== RESULT ITEM ICON ========== */
        .search-result-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .search-result-icon {
            font-size: 24px;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
        }

        /* ========== TAGS ========== */
        .search-result-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .search-result-tags .tag {
            background: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        /* ========== KEYBOARD SHORTCUTS ========== */
        .search-modal-footer {
            padding: 12px 24px;
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
        }

        .keyboard-hints {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .keyboard-hints .hint {
            font-size: 12px;
            color: #6c757d;
        }

        .keyboard-hints kbd {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            padding: 2px 6px;
            font-family: monospace;
            font-size: 11px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* ========== RESPONSIVE SEARCH MODAL ========== */
        @media (max-width: 768px) {
            .search-filter-tabs {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            
            .search-filter-tabs::-webkit-scrollbar {
                display: none;
            }
            
            .search-filter-tab {
                white-space: nowrap;
            }
            
            .keyboard-hints {
                display: none;
            }
            
            .tab-icon {
                font-size: 16px;
            }

            .search-result-icon {
                width: 36px;
                height: 36px;
                font-size: 20px;
            }

            .search-results-info {
                font-size: 13px;
                padding: 10px 14px;
            }
        }

        @media (max-width: 480px) {
            .search-modal-container {
                width: 95%;
                max-height: 85vh;
            }

            .search-modal-input {
                font-size: 14px;
            }

            .tab-icon {
                font-size: 14px;
                margin-right: 2px;
            }

            .search-filter-tab {
                font-size: 13px;
                padding: 8px 12px;
            }
        }
    </style>

    <!-- LOAD SEARCH DATA & MODAL JAVASCRIPT -->
    <script src="/js/search-data.js"></script>
    <script src="/js/search-modal.js"></script>

</body>
</html>