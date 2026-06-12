{{-- ========================================
     TAB 5: KELOLA PROYEK GALLERY
     ======================================== --}}
<div id="tab-proyek-gallery" class="cms-tab-content">
    
    {{-- HEADER SECTION WITH ADD BUTTON --}}
    <div class="cms-products-header">
        <div>
            <h2 class="cms-products-title">
                <i class="bi bi-camera"></i>
                Kelola Gallery Proyek
            </h2>
            <p class="cms-products-subtitle">
                Total: <strong>{{ count($proyek->projects) }} Proyek</strong>
            </p>
        </div>
        <button type="button" class="cms-btn cms-btn-success" onclick="openAddProjectModal()">
            <i class="bi bi-plus-circle"></i>
            Tambah Proyek Baru
        </button>
    </div>

    {{-- CARD: PROJECTS LIST (COMPACT LAYOUT) --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <i class="bi bi-grid"></i>
            <h3>Daftar Proyek</h3>
        </div>
        <div class="cms-card-body">
            
            @forelse($proyek->projects as $project)
            <div class="cms-product-card-compact">
                
                {{-- Project Image (Thumbnail) --}}
                <div class="cms-product-image-compact">
                    @if(isset($project['image']) && $project['image'])
                    <img src="{{ asset($project['image']) }}" alt="{{ $project['name'] }}">
                    @else
                    <div class="cms-no-image-compact">
                        <i class="bi bi-image"></i>
                    </div>
                    @endif
                </div>
                
                {{-- Project Info --}}
                <div class="cms-product-info-compact">
                    <h4 class="cms-product-name-compact">{{ $project['name'] }}</h4>
                    <p class="cms-product-description-compact">
                        {{ Str::limit($project['description'], 80) }}
                    </p>
                    
                    <div class="cms-product-date-compact">
                        <i class="bi bi-clock"></i>
                        <small>Ditambahkan: {{ \Carbon\Carbon::parse($project['created_at'])->format('d M Y, H:i') }}</small>
                    </div>
                </div>

                {{-- Project Actions (Compact) --}}
                <div class="cms-product-actions-compact">
                    
                    {{-- Delete Image Button --}}
                    @if(isset($project['image']) && $project['image'])
                    <form action="{{ route('admin.cms.project.delete-image', $project['id']) }}" 
                          method="POST" 
                          onsubmit="return confirmDeleteProjectImage('{{ $project['name'] }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-btn cms-btn-warning cms-btn-compact" title="Hapus Gambar">
                            <i class="bi bi-image"></i>
                        </button>
                    </form>
                    @endif

                    {{-- Delete Project Button --}}
                    <form action="{{ route('admin.cms.project.delete', $project['id']) }}" 
                          method="POST" 
                          onsubmit="return confirmDeleteProject('{{ $project['name'] }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-btn cms-btn-danger cms-btn-compact" title="Hapus Proyek">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    
                </div>
            </div>
            @empty
            <div class="cms-empty-state">
                <i class="bi bi-camera"></i>
                <h3>Belum Ada Proyek</h3>
                <p>Klik tombol "Tambah Proyek Baru" untuk menambahkan proyek pertama Anda!</p>
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