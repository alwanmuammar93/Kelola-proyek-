@extends('layouts.frontend')

@section('title', 'Galeri Proyek Kami')

@section('meta_description', 'Lihat berbagai proyek konstruksi dan pengelasan yang telah dikerjakan oleh PT Surabaya Las dengan hasil profesional dan berkualitas tinggi.')

@section('styles')
    <link rel="stylesheet" href="/css/galeri-proyek.css">
@endsection

@section('content')

@php
    // Ambil data hero proyek dari database
    $proyek = \App\Models\CMSSection::where('section_key', 'proyek_hero')->where('is_active', true)->first();
    
    // Default values jika data belum ada
    $proyekTitle = $proyek->title ?? 'LAYANAN KONSTRUKSI';
    $proyekSubtitle = $proyek->subtitle ?? 'PT SURABAYA LAS';
    $proyekBgImage = $proyek ? $proyek->background_image_url : asset('images/proyek/asasasaasa.jpg');
    $proyekButtonText = $proyek->button1_text ?? 'HUBUNGI KAMI SEKARANG';
    $proyekButtonLink = $proyek->button1_link ?? 'https://wa.me/6285211887779';

    // Ambil data proyek dari database (PERBAIKAN)
    $projects = $proyek && isset($proyek->projects) && is_array($proyek->projects) ? $proyek->projects : [];
@endphp

<!-- Hero Section -->
<section class="hero-proyek" style="background-image: url('{{ $proyekBgImage }}');">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>{{ $proyekTitle }}<br><span class="highlight">{{ $proyekSubtitle }}</span></h1>
            <a href="{{ $proyekButtonLink }}" target="_blank" class="btn-wa">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="white">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                {{ $proyekButtonText }}
            </a>
        </div>
    </div>
</section>

<!-- Gallery Grid Section -->
<section class="gallery-proyek">
    <div class="container">
        @if(count($projects) > 0)
            <div class="projects-grid">
                @foreach($projects as $index => $project)
                    <div class="project-card" onclick="openModal({{ $index }})" data-index="{{ $index }}">
                        <div class="project-image-wrapper">
                            @if(isset($project['image']) && $project['image'])
                                <img src="{{ asset($project['image']) }}" alt="{{ $project['name'] }}" loading="lazy" class="project-img">
                            @else
                                <div style="width: 100%; height: 250px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #6c757d;">No Image</span>
                                </div>
                            @endif
                            <div class="image-overlay">
                                <div class="overlay-content">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                    <span class="view-text">Lihat Detail</span>
                                </div>
                            </div>
                        </div>
                        <div class="project-info">
                            <h3 class="project-name">{{ $project['name'] }}</h3>
                            <p class="project-preview">{{ Str::limit($project['description'] ?? 'Tidak ada deskripsi', 100) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 80px 20px; color: #6c757d;">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 20px; opacity: 0.3;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <h3 style="font-size: 24px; margin-bottom: 10px; color: #343a40;">Belum Ada Proyek</h3>
                <p style="font-size: 16px; color: #6c757d;">semua proyek yang telah dikerja akan ditampilkan disni tunggu update selanjutnya.</p>
            </div>
        @endif
    </div>
</section>

<!-- Modal Pop-up -->
<div class="modal-overlay" id="projectModal" onclick="closeModalOnBackdrop(event)">
    <div class="modal-wrapper">
        <div class="modal-container">
            <!-- Close Button -->
            <button class="modal-close" onclick="closeModal()" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <!-- Modal Content -->
            <div class="modal-content">
                <!-- Image Section -->
                <div class="modal-image-section">
                    <img id="modalImage" src="" alt="" class="modal-img">
                </div>

                <!-- Info Section -->
                <div class="modal-info-section">
                    <div class="modal-header">
                        <h2 id="modalTitle" class="modal-title"></h2>
                    </div>
                    
                    <div class="modal-divider"></div>
                    
                    <div class="modal-description-wrapper">
                        <p id="modalDescription" class="modal-description"></p>
                    </div>
                    
                    <!-- Contact Button -->
                    <a href="{{ $proyekButtonLink }}" target="_blank" class="modal-btn-contact">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="white">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Hubungi Kami untuk Proyek Ini</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Data proyek dari backend
const projects = @json($projects);

// Function untuk membuka modal
function openModal(index) {
    if (!projects[index]) {
        console.error('Project not found at index:', index);
        return;
    }
    
    const project = projects[index];
    const modal = document.getElementById('projectModal');
    
    // Set content modal
    const imageUrl = project.image ? (project.image.startsWith('http') ? project.image : '{{ asset('') }}' + project.image.replace(/^\//, '')) : '';
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalImage').alt = project.name || 'Project Image';
    document.getElementById('modalTitle').textContent = project.name || 'Untitled Project';
    document.getElementById('modalDescription').textContent = project.description || 'Tidak ada deskripsi';
    
    // Tampilkan modal dengan animasi
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.querySelector('.modal-wrapper').classList.add('show');
    }, 10);
}

// Function untuk menutup modal
function closeModal() {
    const modal = document.getElementById('projectModal');
    const wrapper = modal.querySelector('.modal-wrapper');
    
    wrapper.classList.remove('show');
    
    setTimeout(() => {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }, 300);
}

// Close modal ketika klik backdrop
function closeModalOnBackdrop(event) {
    if (event.target.id === 'projectModal') {
        closeModal();
    }
}

// Close modal dengan tombol ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Lazy loading images
document.addEventListener('DOMContentLoaded', function() {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        imageObserver.observe(img);
    });
});
</script>
@endsection