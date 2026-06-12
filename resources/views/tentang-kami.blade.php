@extends('layouts.frontend')

@section('title', 'Tentang Kami - PT Surabaya Las')

@section('meta_description', 'Tentang PT Surabaya Las - Perusahaan konstruksi besi dan penjualan alat bahan bangunan berkualitas di Maros, Sulawesi Selatan.')

@section('styles')
    <link rel="stylesheet" href="/css/tentang-kami.css">
@endsection

@section('content')

    <!-- HERO SECTION -->
    <section class="tentang-hero">
        <img src="{{ asset('images/hero-store.jpg') }}" 
             alt="PT Surabaya Las" 
             class="hero-image"
             onerror="this.src='https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1350&q=80'">
        <div class="hero-overlay"></div>

        <!-- Hero Content (tanpa wrapper yang tidak perlu) -->
        <div class="hero-content">
            <div class="hero-brand">
                <img src="{{ asset('images/SURABAYA_LAS_INTI-removebg-preview.png') }}" 
                     alt="Logo PT Surabaya Las" 
                     class="hero-logo"
                     onerror="this.src='https://via.placeholder.com/70x70/cf2025/ffffff?text=SL'">

                <h1 class="hero-company-name">PT SURABAYA LAS</h1>
            </div>

            <h2 class="hero-tagline">
                Tentang Kami<br>
                <span class="yellow">"Kualitas & Kepuasan Pelanggan adalah Prioritas Kami"</span>
            </h2>
        </div>
    </section>

    <!-- CONTENT SECTION - CARD SECTION -->
    <section class="tentang-content">
        <div class="tentang-container">
            
            <!-- Grid 2 Kolom - Cards dengan Height Sama -->
            <div class="tentang-grid">
                
                <!-- CARD 1: TENTANG KAMI -->
                <div class="tentang-card tentang-card-white">
                    <h3 class="tentang-card-title">TENTANG KAMI</h3>
                    <p class="tentang-card-text">
                        Surabaya Las, Perusahaan yang menyediakan konstruksi besi hingga 
                        penjualan alat dan bahan bangunan berkualitas. Perusahaan kami memiliki 
                        pengalaman bertahun-tahun dalam industri las, dengan tim teknisi ahli 
                        yang terlatih dan berpengalaman.
                    </p>
                </div>

                <!-- CARD 2: MENGAPA MEMILIH KAMI -->
                <div class="tentang-card tentang-card-purple">
                    <h3 class="tentang-card-title">MENGAPA MEMILIH KAMI?</h3>
                    <p class="tentang-card-text">
                        Di perusahaan konstruksi besi, kami menyediakan layanan lengkap mulai dari 
                        perencanaan dan desain hingga konstruksi dan instalasi struktur baja. Kami 
                        juga Menjual berbagai alat dan bahan bangunan yang berkualitas dan harga terjangkau
                    </p>
                </div>

            </div>

            <!-- CARD 3: TUJUAN KAMI (FULL WIDTH) -->
            <div class="tentang-card tentang-card-full">
                <h3 class="tentang-card-title">TUJUAN KAMI</h3>
                <p class="tentang-card-text">
                    Kami berkomitmen untuk memberikan pelayanan terbaik melalui pengembangan 
                    teknologi dan praktik kerja yang efisien, guna memastikan setiap proyek 
                    konstruksi besi yang kami tangani terlaksana dengan aman, tepat waktu, 
                    dan sesuai anggaran.
                </p>
            </div>

        </div>
    </section>

@endsection