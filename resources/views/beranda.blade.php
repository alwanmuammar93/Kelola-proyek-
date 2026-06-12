@extends('layouts.frontend')

@section('title', 'PT Surabaya Las - Solusi Las & Konstruksi Terbaik di Maros')

@section('meta_description', 'PT Surabaya Las - Spesialis jasa bengkel las profesional dengan pengalaman puluhan tahun. Layanan terbaik dalam pekerjaan dan konstruksi pengelasan.')

@section('styles')
    <link rel="stylesheet" href="/css/beranda.css">
@endsection

@section('content')

@php
    // ✅ Ambil data dari database CMS
    $hero = \App\Models\CMSSection::where('section_key', 'hero_beranda')->where('is_active', true)->first();
    $about = \App\Models\CMSSection::where('section_key', 'about_company')->where('is_active', true)->first();
    $services = \App\Models\CMSSection::where('section_key', 'services_title')->where('is_active', true)->first();
    
    // Default values jika data belum ada di database
    $heroTitle = $hero->title ?? 'PT SURABAYA LAS';
    $heroSubtitleLines = $hero ? $hero->subtitle_lines : ['Solusi Konstruksi & Penjualan', 'Alat dan Bahan Bangunan', 'Terbaik Untuk Anda'];
    $heroImage = $hero ? $hero->image_url : asset('images/WhatsApp Image 2025-11-23 at 00.20.09_da1f500c.jpg');
    $heroLogo = $hero ? $hero->logo_url : asset('images/SURABAYA_LAS_INTI-removebg-preview.png');
    $heroButton1Text = $hero->button1_text ?? 'LIHAT PROYEK KAMI';
    $heroButton1Link = $hero->button1_link ?? route('galeri.proyek');
    $heroButton2Text = $hero->button2_text ?? 'HUBUNGI KAMI SEKARANG';
    $heroButton2Link = $hero->button2_link ?? route('kontak');
    
    $aboutTitle = $about->title ?? 'PT SURABAYA LAS';
    $aboutContent = $about->content ?? 'Kami merupakan spesialis jasa bengkel las profesional yang telah berpengalaman puluhan tahun dibidangnya. Komitmen kami adalah memberikan layanan terbaik dalam pekerjaan dan konstruksi pengelasan dengan harga yang kompetitif.';
    
    $servicesTitle = $services->title ?? 'Layanan Unggulan Kami';
    $servicesButton = $services->button1_text ?? 'Pelajari Lebih Lanjut';
    $servicesButtonLink = $services->button1_link ?? route('tentang-kami');
@endphp

    <!-- HERO SECTION - TANPA SLIDESHOW -->
    <section class="hero" id="beranda">
        <!-- Background Image Container (SINGLE IMAGE) - ✅ DARI DATABASE -->
        <div class="hero-bg-container">
            <div class="hero-bg-overlay"></div>
            
            <!-- Single Image - Posisi diperbaiki agar kepala kelihatan - ✅ DARI DATABASE -->
            <img src="{{ $heroImage }}" 
                 alt="Welding Background" 
                 class="hero-bg-image"
                 onerror="this.src='https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1350&q=80'">
        </div>
        
        <div class="hero-content">
            <!-- Logo dan Nama PT - ✅ DARI DATABASE -->
            <div class="hero-brand">
                <img src="{{ $heroLogo }}" 
                     alt="Logo PT Surabaya Las" 
                     class="hero-logo"
                     onerror="this.src='https://via.placeholder.com/70x70/cf2025/ffffff?text=SL'">
                <h1 class="hero-company-name">{{ $heroTitle }}</h1>
            </div>

            <!-- Tagline - ✅ DARI DATABASE -->
            <h2 class="hero-tagline">
                {{ $heroSubtitleLines[0] ?? 'Solusi Konstruksi & Penjualan' }}<br>
                {{ $heroSubtitleLines[1] ?? 'Alat dan Bahan Bangunan' }}<br>
                <span class="yellow">{{ $heroSubtitleLines[2] ?? 'Terbaik Untuk Anda' }}</span>
            </h2>

            <!-- Buttons - ✅ DARI DATABASE -->
            <div class="buttons">
                <a class="btn-outline" href="{{ $heroButton1Link }}">{{ $heroButton1Text }}</a>
                <a class="btn-orange" href="{{ $heroButton2Link }}">{{ $heroButton2Text }}</a>
            </div>
        </div>

        <!-- Diagonal Stripes -->
        <div class="hero-diagonal">
            <div class="diagonal-red"></div>
            <div class="diagonal-yellow"></div>
            <div class="diagonal-blue"></div>
        </div>
    </section>

    <!-- SECTION INFO & LAYANAN -->
    <section class="info-section" id="info">
        <div class="info-container">
            <!-- Left: Company Info - ✅ DARI DATABASE -->
            <div class="company-info">
                <h3>{{ $aboutTitle }}</h3>
                <p>{{ $aboutContent }}</p>
            </div>

            <!-- Right: Services - ✅ DARI DATABASE -->
            <div class="services-section">
                <h3>{{ $servicesTitle }}</h3>
                
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#0d2946">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-4H6v-2h4V7h2v4h4v2h-4v4z"/>
                            </svg>
                        </div>
                        <p>Jasa Las<br>Profesional</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#0d2946">
                                <path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
                            </svg>
                        </div>
                        <p>Kontraktor<br>Umum</p>
                    </div>

                    <div class="service-card">
                        <div class="service-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="#0d2946">
                                <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM10 4h4v2h-4V4zm6 11h-3v3h-2v-3H8v-2h3v-3h2v3h3v2z"/>
                            </svg>
                        </div>
                        <p>Supplier<br>Baja</p>
                    </div>
                </div>

                <a class="btn-learn" href="{{ $servicesButtonLink }}">{{ $servicesButton }}</a>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<!-- TIDAK ADA SCRIPT SLIDESHOW LAGI -->
<script>
    // Smooth scroll dengan offset navbar
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const navbarHeight = document.querySelector('.navbar').offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Hero animation on load
    window.addEventListener('load', function() {
        const heroContent = document.querySelector('.hero-content');
        if (heroContent) {
            heroContent.style.opacity = '0';
            heroContent.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                heroContent.style.transition = 'all 0.8s ease';
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
            }, 100);
        }
    });
</script>
@endsection