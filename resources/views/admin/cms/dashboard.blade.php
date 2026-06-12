@extends('layouts.app')

@section('title', 'Kelola Website - CMS Management')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cms.css') }}">
@endsection

@section('content')
<div class="cms-wrapper">
    
    {{-- PAGE HEADER --}}
    <div class="cms-page-header">
        <div class="cms-header-left">
            <div class="cms-title-section">
                <div class="cms-icon-circle">
                    <i class="bi bi-globe2"></i>
                </div>
                <div>
                    <h1 class="cms-page-title">Kelola Website</h1>
                    <div class="cms-breadcrumb">
                        <a href="{{ route('admin.index') }}">
                            <i class="bi bi-house-door"></i>
                            Dashboard
                        </a>
                        <i class="bi bi-chevron-right"></i>
                        <span>Kelola Website</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="cms-alert cms-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <div class="cms-alert-content">
            <strong>Berhasil!</strong>
            <p>{{ session('success') }}</p>
        </div>
        <button class="cms-alert-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="cms-alert cms-alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div class="cms-alert-content">
            <strong>Error!</strong>
            <p>{{ session('error') }}</p>
        </div>
        <button class="cms-alert-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="cms-alert cms-alert-error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div class="cms-alert-content">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="cms-alert-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    {{-- TAB NAVIGATION --}}
    <div class="cms-tab-navigation">
        <button class="cms-tab-btn active" onclick="switchTab('hero')" data-tab="hero">
            <i class="bi bi-image"></i>
            <span>Hero Beranda</span>
        </button>
        <button class="cms-tab-btn" onclick="switchTab('catalog')" data-tab="catalog">
            <i class="bi bi-shop"></i>
            <span>Catalog Hero</span>
        </button>
        <button class="cms-tab-btn" onclick="switchTab('catalog-products')" data-tab="catalog-products">
            <i class="bi bi-box-seam"></i>
            <span>Kelola Katalog</span>
        </button>
        <button class="cms-tab-btn" onclick="switchTab('proyek')" data-tab="proyek">
            <i class="bi bi-building"></i>
            <span>Proyek Hero</span>
        </button>
        <button class="cms-tab-btn" onclick="switchTab('proyek-gallery')" data-tab="proyek-gallery">
            <i class="bi bi-camera"></i>
            <span>Kelola Proyek</span>
        </button>
    </div>

    {{-- CONTENT CONTAINER --}}
    <div class="cms-content-container">
        
        {{-- Include semua tab content --}}
        @include('admin.cms.tab1-2')   {{-- Tab Hero & Catalog Hero --}}
        @include('admin.cms.tab3')     {{-- Tab Kelola Katalog --}}
        @include('admin.cms.tab4')     {{-- Tab Proyek Hero --}}
        @include('admin.cms.tab5')     {{-- Tab Kelola Proyek --}}
        
    </div>

    {{-- ========================================
         SEMUA MODAL DI-INCLUDE DI SINI SAJA
         Jangan include modal di dalam file tab!
         ======================================== --}}
    @include('admin.cms.modals.add-product-modal')
    @include('admin.cms.modals.add-project-modal')

</div>
@endsection

@section('scripts')
{{-- Core CMS Scripts (Load First) --}}
<script src="{{ asset('js/admin/cms.js') }}"></script>
<script src="{{ asset('js/admin/cms-image.js') }}"></script>
<script src="{{ asset('js/admin/cms-alerts.js') }}"></script>
<script src="{{ asset('js/admin/cms-form.js') }}"></script>

{{-- Module-Specific Scripts (Load After Core) --}}
<script src="{{ asset('js/admin/cms-products.js') }}"></script>
<script src="{{ asset('js/admin/cms-projects.js') }}"></script>

{{-- INLINE SCRIPT: Memastikan tab system berfungsi dengan benar --}}
<script>
// Fungsi untuk switch tab
function switchTab(tabName) {
    console.log('Switching to tab:', tabName); // Debug log
    
    // Hapus class active dari semua button
    const allButtons = document.querySelectorAll('.cms-tab-btn');
    allButtons.forEach(btn => btn.classList.remove('active'));
    
    // Tambahkan class active ke button yang diklik
    const activeButton = document.querySelector(`.cms-tab-btn[data-tab="${tabName}"]`);
    if (activeButton) {
        activeButton.classList.add('active');
    }
    
    // Sembunyikan semua tab content
    const allTabs = document.querySelectorAll('.cms-tab-content');
    allTabs.forEach(tab => {
        tab.classList.remove('active');
        tab.style.display = 'none'; // Paksa hide dengan inline style
    });
    
    // Tampilkan tab yang dipilih
    const activeTab = document.getElementById(`tab-${tabName}`);
    if (activeTab) {
        activeTab.classList.add('active');
        activeTab.style.display = 'block'; // Paksa show dengan inline style
        console.log('Tab activated:', tabName); // Debug log
    } else {
        console.error('Tab not found:', `tab-${tabName}`); // Debug log
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('CMS Page loaded'); // Debug log
    
    // Pastikan hanya tab pertama (hero) yang aktif saat load
    switchTab('hero');
    
    // Tambahkan event listener ke semua tab button
    const tabButtons = document.querySelectorAll('.cms-tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });
});
</script>
@endsection