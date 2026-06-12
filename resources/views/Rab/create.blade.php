@extends('layouts.app')

@section('title', 'Tambah RAB')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/rab-create-style.css') }}">
<style>
    /* ========================================
       CRITICAL FIX: Proper RAB Main Styling
       ======================================== */
    .rab-main {
        /* FIXED: No margin-left (handled by content-wrapper in layout) */
        margin-left: 0 !important;
        padding: 2rem;
        padding-top: 90px !important;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    /* Dark Theme Main */
    body.dark-theme .rab-main {
        background-color: #0f172a;
    }

    /* CRITICAL: Ensure modals are hidden by default */
    .modal-overlay {
        display: none !important;
    }

    .modal-overlay.active {
        display: block !important;
    }

    .modal-proyek {
        display: none !important;
    }

    .modal-proyek.active {
        display: flex !important;
    }

    /* Ensure body can scroll */
    body:not(.modal-open) {
        overflow-x: hidden !important;
        overflow-y: auto !important;
    }
    
    /* ========================================
       HIDE COLUMN NO
       ======================================== */
    .col-no-hidden {
        display: none !important;
    }

    /* ========================================
       🔥 FIX MODAL ANIMATION - NO JUMPING!
       ======================================== */
    
    /* Override animation dari CSS file */
    .modal-proyek {
        /* Hapus animation yang bikin jump */
        animation: none !important;
        
        /* Gunakan smooth opacity fade saja */
        opacity: 0;
        transition: opacity 0.25s ease !important;
    }

    .modal-proyek.active {
        opacity: 1 !important;
    }

    /* Smooth fade untuk overlay */
    .modal-overlay {
        animation: none !important;
        opacity: 0;
        transition: opacity 0.25s ease !important;
    }

    .modal-overlay.active {
        opacity: 1 !important;
    }

    /* ========================================
       DARK THEME MODAL OVERLAY
       ======================================== */
    body.dark-theme .modal-overlay {
        background: rgba(15, 23, 42, 0.9) !important;
    }

    /* ========================================
       DARK THEME MODAL PROYEK
       ======================================== */
    body.dark-theme .modal-proyek {
        background: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .modal-header-custom {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .modal-header-custom h2 {
        color: #ffffff !important;
    }

    body.dark-theme .modal-close {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .modal-close:hover {
        background: rgba(255, 255, 255, 0.2) !important;
    }

    body.dark-theme .modal-body-custom {
        background: #1e293b !important;
    }

    /* ========================================
       DARK THEME MODAL SEARCH & SORT
       ======================================== */
    body.dark-theme .modal-search {
        background: #0f172a !important;
        border: 2px solid #60a5fa !important;
    }

    body.dark-theme .modal-search input {
        background: transparent !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .modal-search input::placeholder {
        color: #64748b !important;
    }

    body.dark-theme .modal-search i {
        color: #60a5fa !important;
    }

    body.dark-theme .modal-sort select {
        background: #0f172a !important;
        border: 2px solid #60a5fa !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .modal-sort select option {
        background: #1e293b !important;
        color: #e2e8f0 !important;
    }

    /* ========================================
       DARK THEME PROYEK CARD
       ======================================== */
    body.dark-theme .proyek-card {
        background: linear-gradient(135deg, #334155 0%, #475569 100%) !important;
        border: 2px solid rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .proyek-card:hover {
        border-color: #3b82f6 !important;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3) !important;
    }

    body.dark-theme .proyek-card.selected {
        border-color: #10b981 !important;
        background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4) !important;
    }

    body.dark-theme .proyek-icon {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        color: white !important;
    }

    body.dark-theme .proyek-name {
        color: #e2e8f0 !important;
    }

    body.dark-theme .proyek-owner {
        color: #94a3b8 !important;
    }

    body.dark-theme .proyek-owner i {
        color: #60a5fa !important;
    }

    body.dark-theme .proyek-check {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    }

    /* ========================================
       DARK THEME STATUS BADGES
       ======================================== */
    body.dark-theme .proyek-status.status-belum {
        background: linear-gradient(135deg, #854d0e 0%, #92400e 100%) !important;
        color: #fef3c7 !important;
    }

    body.dark-theme .proyek-status.status-sudah {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
        color: #d1fae5 !important;
    }

    /* ========================================
       DARK THEME EMPTY STATE
       ======================================== */
    body.dark-theme .empty-proyek {
        color: #94a3b8 !important;
    }

    body.dark-theme .empty-proyek i {
        color: #475569 !important;
    }

    body.dark-theme .empty-proyek h3 {
        color: #cbd5e0 !important;
    }

    body.dark-theme .empty-proyek p {
        color: #64748b !important;
    }
</style>
@endsection

@section('content')

{{-- Main Content dengan Wrapper RAB --}}
<main class="rab-main">
    
    {{-- Content --}}
    <div class="proyek-content">
        <div class="form-container">
            <h1 class="form-title">TAMBAH RAB</h1>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert-validation">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form id="rab-form" action="{{ route('rab.store') }}" method="POST">
                @csrf

                {{-- Baris 1: Pilih Proyek & Owner --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="custom-label">Pilih Proyek <span class="text-danger">*</span></label>
                        {{-- BUTTON UNTUK BUKA MODAL --}}
                        <button type="button" class="btn-pilih-proyek" onclick="openModalProyek()">
                            <span id="proyekSelectedText" class="placeholder-text">Klik untuk memilih proyek</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        {{-- Hidden Input untuk ID Proyek --}}
                        <input type="hidden" name="id_proyek" id="proyekIdInput" required>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Owner</label>
                        <input type="text" 
                               id="ownerInput" 
                               name="owner" 
                               class="custom-input" 
                               placeholder="Terisi Otomatis setelah memilih proyek"
                               value="{{ old('owner') }}"
                               readonly 
                               required>
                        <small class="form-hint">Terisi Otomatis setelah memilih proyek</small>
                    </div>
                </div>

                {{-- Baris 2: No RAB & Perihal (Kolom No disembunyikan) --}}
                <div class="row g-3 mb-4">
                    {{-- KOLOM NO DISEMBUNYIKAN --}}
                    <div class="col-md-2 col-no-hidden">
                        <label class="custom-label">No</label>
                        <input type="text" 
                               name="no" 
                               class="custom-input" 
                               value="{{ old('no', '1') }}" 
                               readonly>
                    </div>

                    {{-- NO RAB: Diperlebar dari col-md-4 menjadi col-md-6 --}}
                    <div class="col-md-6">
                        <label class="custom-label">No RAB <span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="text" 
                                   id="noRabInput"
                                   name="no_rab" 
                                   class="custom-input" 
                                   placeholder="Pilih proyek untuk auto-generate"
                                   value="{{ old('no_rab') }}" 
                                   readonly
                                   required>
                            <i class="fas fa-lock" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 14px;"></i>
                        </div>
                        <small class="form-hint">Auto-generate setelah memilih proyek</small>
                    </div>

                    {{-- PERIHAL: Tetap col-md-6 --}}
                    <div class="col-md-6">
                        <label class="custom-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="perihal" 
                               class="custom-input" 
                               placeholder="Contoh: Pembuatan Tangga Besi"
                               value="{{ old('perihal') }}" 
                               required>
                    </div>
                </div>

                {{-- Container Rincian --}}
                <div id="rincian-container">
                    <div class="rincian-card">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="custom-label">Rincian Pekerjaan atau alat/bahan <span class="text-danger">*</span></label>
                                <textarea name="rincian_pekerjaan[]" 
                                          class="custom-textarea" 
                                          placeholder="Contoh: Pembuatan rangka tangga besi hollow 4x4"
                                          required>{{ old('rincian_pekerjaan.0') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="custom-label">Satuan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="satuan[]" 
                                       class="custom-input" 
                                       placeholder="Unit, Meter, Kg"
                                       value="{{ old('satuan.0', 'Unit') }}" 
                                       required>
                            </div>

                            <div class="col-md-3">
                                <label class="custom-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="jumlah[]" 
                                       class="custom-input jumlah-input" 
                                       min="1" 
                                       placeholder="0"
                                       value="{{ old('jumlah.0', 1) }}" 
                                       onchange="hitungTotal()"
                                       required>
                            </div>

                            {{-- 🔥 BIAYA MATERIAL DENGAN FORMAT RUPIAH --}}
                            <div class="col-md-3">
                                <label class="custom-label">Biaya Material <span class="text-danger">*</span></label>
                                <div style="position: relative;">
                                    <input type="text" 
                                           class="custom-input biaya-material-display-0" 
                                           placeholder="Rp 0"
                                           value="{{ old('biaya_material_rincian.0') ? 'Rp ' . number_format(old('biaya_material_rincian.0'), 0, ',', '.') : '' }}"
                                           data-index="0"
                                           required
                                           style="font-weight: 600; color: #1a1f71;">
                                    <input type="hidden" 
                                           name="biaya_material_rincian[]" 
                                           class="biaya-material-input biaya-material-hidden-0"
                                           value="{{ old('biaya_material_rincian.0', 0) }}"
                                           data-index="0">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="custom-label">Subtotal</label>
                                <div class="subtotal-display">Rp 0</div>
                            </div>
                        </div>

                        <div class="rincian-actions">
                            <button type="button" 
                                    class="btn-hapus-rincian" 
                                    onclick="hapusRincian(this)">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Total RAB & Tombol Tambah Rincian Sejajar --}}
                <div class="total-tambah-section">
                    <div class="total-rab-wrapper">
                        <label class="total-label">Total RAB</label>
                        <div class="total-display" id="total_display">Rp 0</div>
                    </div>
                    
                    <div class="tambah-rincian-wrapper">
                        <button type="button" 
                                class="btn-tambah-rincian-bottom" 
                                onclick="tambahRincian()">
                            <i class="fas fa-plus"></i> Tambah Rincian
                        </button>
                    </div>
                </div>

                {{-- Hidden Fields --}}
                <input type="hidden" name="total" id="total_hidden" value="0">
                <input type="hidden" name="nama_pekerjaan" id="nama_pekerjaan" value="">
                <input type="hidden" name="status" value="Perencanaan">

                {{-- Tombol Aksi --}}
                <div class="form-actions">
                    <a href="{{ route('rab.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</main>

{{-- MODAL PILIH PROYEK --}}
<div class="modal-overlay" id="modalOverlay" onclick="closeModalProyek()"></div>

<div class="modal-proyek" id="modalProyek">
    <div class="modal-header-custom">
        <h2><i class="fas fa-folder-open me-2"></i>Pilih Proyek</h2>
        <button class="modal-close" onclick="closeModalProyek()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="modal-body-custom">
        {{-- Search & Sort --}}
        <div class="modal-controls">
            <div class="modal-search">
                <i class="fas fa-search"></i>
                <input type="text" 
                       id="searchProyek" 
                       placeholder="Cari nama proyek atau owner..."
                       onkeyup="filterProyek()">
            </div>

            <div class="modal-sort">
                <select id="sortProyek" onchange="sortProyek()">
                    <option value="">Sort By</option>
                    <option value="nama-asc">Nama A-Z</option>
                    <option value="nama-desc">Nama Z-A</option>
                    <option value="status-belum">Status: Belum RAB</option>
                    <option value="status-sudah">Status: Sudah RAB</option>
                </select>
            </div>
        </div>

        {{-- List Proyek --}}
        <div class="proyek-list" id="proyekList">
            @forelse($proyeks as $proyek)
                <div class="proyek-card" 
                     data-id="{{ $proyek->id_proyek }}"
                     data-nama="{{ $proyek->nama_proyek }}"
                     data-owner="{{ $proyek->nama_owner ?? '' }}"
                     data-status="{{ $proyek->status }}"
                     onclick="selectProyek(this)">
                    
                    <div class="proyek-card-header">
                        <div class="proyek-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="proyek-name">{{ $proyek->nama_proyek }}</div>
                        <div class="proyek-check">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                    <div class="proyek-owner">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ $proyek->nama_owner ?? 'Tidak ada owner' }}</span>
                    </div>

                    <span class="proyek-status {{ $proyek->status == 'RAB Belum Dibuat' ? 'status-belum' : 'status-sudah' }}">
                        {{ $proyek->status }}
                    </span>
                </div>
            @empty
                <div class="empty-proyek" style="grid-column: 1 / -1;">
                    <i class="fas fa-folder-open"></i>
                    <h3>Belum Ada Proyek</h3>
                    <p>Silakan tambahkan proyek terlebih dahulu</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ========================================
    // 🔥 CRITICAL FIX: Force Close Modal on Page Load
    // ========================================
    
    (function() {
        console.log('🚀 RAB Create - Immediate Modal Fix');
        
        // Run immediately
        const forceCloseModals = function() {
            const modalOverlay = document.getElementById('modalOverlay');
            const modalProyek = document.getElementById('modalProyek');
            
            if (modalOverlay) {
                modalOverlay.classList.remove('active');
                modalOverlay.style.display = 'none';
                console.log('✅ Modal overlay force hidden');
            }
            
            if (modalProyek) {
                modalProyek.classList.remove('active');
                modalProyek.style.display = 'none';
                console.log('✅ Modal proyek force hidden');
            }
            
            // Ensure body can scroll
            document.body.style.overflow = 'auto';
            document.body.classList.remove('modal-open');
            console.log('✅ Body overflow restored');
        };
        
        // Execute immediately
        forceCloseModals();
        
        // Execute after DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', forceCloseModals);
        } else {
            forceCloseModals();
        }
        
        // Execute after window load (fallback)
        window.addEventListener('load', forceCloseModals);
    })();

    // ========================================
    // 🔥 FUNGSI FORMAT RUPIAH
    // ========================================
    
    function formatRupiahInput(angka) {
        const number = parseFloat(angka) || 0;
        return number.toLocaleString('id-ID');
    }

    function parseRupiahToNumber(rupiah) {
        return parseFloat(rupiah.replace(/[^\d]/g, '')) || 0;
    }

    // 🔥 FUNGSI FORMAT INPUT BIAYA MATERIAL
    function formatBiayaMaterialInput(input, index) {
        // Ambil nilai input, hapus semua karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format dengan pemisah ribuan
        if (value) {
            const number = parseFloat(value);
            input.value = 'Rp ' + number.toLocaleString('id-ID');
        } else {
            input.value = '';
        }
        
        // Update hidden input dengan angka murni
        const hiddenInput = document.querySelector(`.biaya-material-hidden-${index}`);
        if (hiddenInput) {
            hiddenInput.value = value || 0;
        }
        
        // Hitung total
        hitungTotal();
    }

    // ========================================
    // 🔥 AUTO GENERATE NO RAB
    // ========================================
    
    async function generateNoRab(proyekId) {
        try {
            const response = await fetch('{{ route("rab.generate-no") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id_proyek: proyekId
                })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('noRabInput').value = data.no_rab;
                
                const noRabInput = document.getElementById('noRabInput');
                noRabInput.style.background = '#d4edda';
                noRabInput.style.borderColor = '#28a745';
                
                setTimeout(() => {
                    noRabInput.style.background = '#f8f9fa';
                    noRabInput.style.borderColor = '#dee2e6';
                }, 1500);
            } else {
                alert('Gagal generate No RAB: ' + data.message);
            }
        } catch (error) {
            console.error('Error generating No RAB:', error);
            alert('Terjadi kesalahan saat generate No RAB');
        }
    }

    // ========================================
    // MODAL PROYEK FUNCTIONS
    // ========================================
    
    function openModalProyek() {
        console.log('📂 Opening modal proyek');
        const modalOverlay = document.getElementById('modalOverlay');
        const modalProyek = document.getElementById('modalProyek');
        
        modalOverlay.classList.add('active');
        modalOverlay.style.display = 'block';
        
        modalProyek.classList.add('active');
        modalProyek.style.display = 'flex';
        
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
    }

    function closeModalProyek() {
        console.log('❌ Closing modal proyek');
        const modalOverlay = document.getElementById('modalOverlay');
        const modalProyek = document.getElementById('modalProyek');
        
        modalOverlay.classList.remove('active');
        modalOverlay.style.display = 'none';
        
        modalProyek.classList.remove('active');
        modalProyek.style.display = 'none';
        
        document.body.style.overflow = 'auto';
        document.body.classList.remove('modal-open');
    }

    function selectProyek(card) {
        document.querySelectorAll('.proyek-card').forEach(c => {
            c.classList.remove('selected');
        });

        card.classList.add('selected');

        const proyekId = card.getAttribute('data-id');
        const proyekNama = card.getAttribute('data-nama');
        const proyekOwner = card.getAttribute('data-owner');

        document.getElementById('proyekIdInput').value = proyekId;
        document.getElementById('proyekSelectedText').textContent = proyekNama;
        document.getElementById('proyekSelectedText').classList.remove('placeholder-text');
        document.getElementById('proyekSelectedText').classList.add('selected-text');

        document.getElementById('ownerInput').value = proyekOwner;
        document.getElementById('nama_pekerjaan').value = proyekNama;

        generateNoRab(proyekId);

        setTimeout(() => {
            closeModalProyek();
        }, 300);
    }

    function filterProyek() {
        const searchValue = document.getElementById('searchProyek').value.toUpperCase();
        const cards = document.querySelectorAll('.proyek-card');

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama').toUpperCase();
            const owner = card.getAttribute('data-owner').toUpperCase();

            if (nama.includes(searchValue) || owner.includes(searchValue)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function sortProyek() {
        const sortValue = document.getElementById('sortProyek').value;
        const container = document.getElementById('proyekList');
        const cards = Array.from(document.querySelectorAll('.proyek-card'));

        if (sortValue === '') return;

        cards.sort((a, b) => {
            const namaA = a.getAttribute('data-nama').toLowerCase();
            const namaB = b.getAttribute('data-nama').toLowerCase();
            const statusA = a.getAttribute('data-status');
            const statusB = b.getAttribute('data-status');

            switch(sortValue) {
                case 'nama-asc':
                    return namaA.localeCompare(namaB);
                
                case 'nama-desc':
                    return namaB.localeCompare(namaA);
                
                case 'status-belum':
                    if (statusA === 'RAB Belum Dibuat' && statusB !== 'RAB Belum Dibuat') return -1;
                    if (statusA !== 'RAB Belum Dibuat' && statusB === 'RAB Belum Dibuat') return 1;
                    return 0;
                
                case 'status-sudah':
                    if (statusA === 'RAB Telah Dibuat' && statusB !== 'RAB Telah Dibuat') return -1;
                    if (statusA !== 'RAB Telah Dibuat' && statusB === 'RAB Telah Dibuat') return 1;
                    return 0;
                
                default:
                    return 0;
            }
        });

        cards.forEach(card => container.appendChild(card));
    }

    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModalProyek();
        }
    });

    // ========================================
    // RAB LOGIC
    // ========================================
    
    let rincianCounter = 1;

    window.tambahRincian = function() {
        const container = document.getElementById('rincian-container');

        const newItem = document.createElement('div');
        newItem.classList.add('rincian-card');

        newItem.innerHTML = `
            <div class="row g-3 mb-3">
                <div class="col-12">
                    <label class="custom-label">Rincian Pekerjaan atau alat/bahan <span class="text-danger">*</span></label>
                    <textarea name="rincian_pekerjaan[]" 
                              class="custom-textarea" 
                              placeholder="Contoh: Pembuatan rangka tangga besi hollow 4x4"
                              required></textarea>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="custom-label">Satuan <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="satuan[]" 
                           class="custom-input" 
                           placeholder="Unit, Meter, Kg"
                           value="Unit" 
                           required>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" 
                           name="jumlah[]" 
                           class="custom-input jumlah-input" 
                           min="1" 
                           placeholder="0"
                           value="1" 
                           onchange="hitungTotal()"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Biaya Material <span class="text-danger">*</span></label>
                    <div style="position: relative;">
                        <input type="text" 
                               class="custom-input biaya-material-display-${rincianCounter}" 
                               placeholder="Rp 0"
                               data-index="${rincianCounter}"
                               required
                               style="font-weight: 600; color: #1a1f71;">
                        <input type="hidden" 
                               name="biaya_material_rincian[]" 
                               class="biaya-material-input biaya-material-hidden-${rincianCounter}"
                               value="0"
                               data-index="${rincianCounter}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Subtotal</label>
                    <div class="subtotal-display">Rp 0</div>
                </div>
            </div>

            <div class="rincian-actions">
                <button type="button" 
                        class="btn-hapus-rincian" 
                        onclick="hapusRincian(this)">
                    Hapus
                </button>
            </div>
        `;

        container.appendChild(newItem);
        attachEventListeners(newItem);
        rincianCounter++;
    };

    window.hapusRincian = function(btn) {
        const items = document.querySelectorAll('.rincian-card');
        if (items.length > 1) {
            btn.closest('.rincian-card').remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada satu rincian!');
        }
    };

    function safe(v) {
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function hitungTotal() {
        let totalRAB = 0;

        document.querySelectorAll('.rincian-card').forEach(card => {
            const jumlahInput = card.querySelector('.jumlah-input');
            const biayaMaterialHidden = card.querySelector('input[name="biaya_material_rincian[]"][type="hidden"]');
            const subtotalDisplay = card.querySelector('.subtotal-display');

            const jumlah = safe(jumlahInput ? jumlahInput.value : 0);
            const biayaMaterial = safe(biayaMaterialHidden ? biayaMaterialHidden.value : 0);

            const subtotal = jumlah * biayaMaterial;

            if (subtotalDisplay) {
                subtotalDisplay.textContent = formatRupiah(subtotal);
            }

            totalRAB += subtotal;
        });

        document.getElementById('total_display').textContent = formatRupiah(totalRAB);
        document.getElementById('total_hidden').value = totalRAB;
    }

    function attachEventListeners(element) {
        const jumlahInput = element.querySelector('.jumlah-input');
        const biayaMaterialDisplay = element.querySelector('input[class*="biaya-material-display-"]');

        if (jumlahInput) {
            jumlahInput.addEventListener('input', hitungTotal);
        }
        
        if (biayaMaterialDisplay) {
            const index = biayaMaterialDisplay.getAttribute('data-index');
            biayaMaterialDisplay.addEventListener('input', function() {
                formatBiayaMaterialInput(this, index);
            });
        }
    }

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', () => {
        console.log('✅ RAB Form initialized WITH RUPIAH FORMAT & SMOOTH MODAL (NO JUMP)');
        
        document.querySelectorAll('.rincian-card').forEach(card => {
            attachEventListeners(card);
        });
        
        hitungTotal();
    });
</script>
@endsection