@extends('layouts.app')

@section('title', 'Edit RAB')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/rab-edit-style.css') }}">
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
    
    /* ========================================
       HIDE COLUMN NO
       ======================================== */
    .col-no-hidden {
        display: none !important;
    }

    /* ========================================
       DARK THEME OVERRIDES
       ======================================== */
    
    /* Form Container */
    body.dark-theme .form-container {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    /* Form Title */
    body.dark-theme .form-title {
        color: #60a5fa !important;
        text-shadow: 0 2px 8px rgba(96, 165, 250, 0.3) !important;
    }

    /* Labels */
    body.dark-theme .custom-label {
        color: #cbd5e0 !important;
    }

    /* Inputs */
    body.dark-theme .custom-input {
        background: #0f172a !important;
        border-color: #ffffff !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .custom-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
        background: #1e293b !important;
    }

    body.dark-theme .custom-input:read-only,
    body.dark-theme .custom-input[readonly] {
        background: #1e293b !important;
        color: #94a3b8 !important;
        border-color: #475569 !important;
    }

    body.dark-theme .custom-input::placeholder {
        color: #64748b !important;
    }

    /* Textarea */
    body.dark-theme .custom-textarea {
        background: #0f172a !important;
        border-color: #ffffff !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .custom-textarea:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
    }

    body.dark-theme .custom-textarea::placeholder {
        color: #64748b !important;
    }

    /* Select */
    body.dark-theme .custom-select {
        background: #0f172a !important;
        border-color: #ffffff !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .custom-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
    }

    body.dark-theme .custom-select option {
        background: #1e293b !important;
        color: #e2e8f0 !important;
    }

    /* Form Hint */
    body.dark-theme .form-hint {
        color: #94a3b8 !important;
    }

    /* Button Pilih Proyek Disabled */
    body.dark-theme .btn-pilih-proyek-disabled {
        background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
        border-color: #475569 !important;
        cursor: not-allowed !important;
    }

    body.dark-theme .btn-pilih-proyek-disabled .selected-text {
        color: #94a3b8 !important;
    }

    /* Alert Validation */
    body.dark-theme .alert-validation {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%) !important;
        border-color: #dc2626 !important;
        color: #fca5a5 !important;
    }

    body.dark-theme .alert-validation strong {
        color: #fecaca !important;
    }

    body.dark-theme .alert-validation li::before {
        color: #f87171 !important;
    }

    /* Rincian Cards */
    body.dark-theme .rincian-card {
        background: #1e293b !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .rincian-card:hover {
        border-color: #3b82f6 !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2) !important;
        background: #334155 !important;
    }

    body.dark-theme .rincian-actions {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Buttons */
    body.dark-theme .btn-hapus-rincian {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }

    body.dark-theme .btn-hapus-rincian:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.5) !important;
    }

    body.dark-theme .jumlah-input,
    body.dark-theme .biaya-material-input {
        color: #60a5fa !important;
    }

    body.dark-theme .subtotal-display {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }

    /* Total Section */
    body.dark-theme .total-tambah-section {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
    }

    body.dark-theme .total-label {
        color: #60a5fa !important;
    }

    body.dark-theme .total-display {
        background: #0f172a !important;
        color: #60a5fa !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3) !important;
    }

    body.dark-theme .total-display:hover {
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5) !important;
    }

    body.dark-theme .btn-tambah-rincian-bottom {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3) !important;
    }

    body.dark-theme .btn-tambah-rincian-bottom:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5) !important;
    }

    /* Form Actions */
    body.dark-theme .form-actions {
        border-top-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .btn-batal {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
        box-shadow: 0 4px 12px rgba(74, 85, 104, 0.3) !important;
    }

    body.dark-theme .btn-batal:hover {
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%) !important;
        box-shadow: 0 6px 16px rgba(74, 85, 104, 0.5) !important;
    }

    body.dark-theme .btn-simpan {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }

    body.dark-theme .btn-simpan:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5) !important;
    }
</style>
@endsection

@section('content')

{{-- Main Content dengan Wrapper RAB --}}
<main class="rab-main">
    
    {{-- Content --}}
    <div class="proyek-content">
        <div class="form-container">
            <h1 class="form-title">EDIT RAB - {{ $rab->proyek->nama_proyek ?? 'Proyek' }}</h1>

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
            <form id="rab-form" action="{{ route('rab.update', $rab->id_rab) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Baris 1: Pilih Proyek & Owner --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="custom-label">Proyek <span class="text-danger">*</span></label>
                        {{-- DISABLED BUTTON - Proyek tidak bisa diubah saat edit --}}
                        <button type="button" class="btn-pilih-proyek btn-pilih-proyek-disabled" disabled>
                            <span class="selected-text">{{ $rab->proyek->nama_proyek ?? 'Pilih Proyek' }}</span>
                            <i class="fas fa-lock"></i>
                        </button>
                        <small class="form-hint">Proyek tidak dapat diubah saat edit RAB</small>
                        {{-- Hidden Input untuk ID Proyek --}}
                        <input type="hidden" name="id_proyek" id="proyekIdInput" value="{{ old('id_proyek', $rab->id_proyek) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Owner</label>
                        <input type="text" 
                               id="ownerInput" 
                               name="owner" 
                               class="custom-input" 
                               placeholder="Terisi Otomatis setelah memilih proyek"
                               value="{{ old('owner', $rab->owner) }}"
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
                        <input type="text" 
                               name="no_rab" 
                               class="custom-input" 
                               placeholder="Contoh: RAB-001"
                               value="{{ old('no_rab', $rab->no_rab) }}" 
                               readonly
                               required>
                        <small class="form-hint">No RAB tidak dapat diubah</small>
                    </div>

                    {{-- PERIHAL: Tetap col-md-6 --}}
                    <div class="col-md-6">
                        <label class="custom-label">Perihal <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="perihal" 
                               class="custom-input" 
                               placeholder="Contoh: Pembuatan Tangga Besi"
                               value="{{ old('perihal', $rab->perihal) }}" 
                               required>
                    </div>
                </div>

                {{-- Container Rincian --}}
                <div id="rincian-container">
                    @php
                        // Fungsi decode aman
                        $decodeAman = function($val) {
                            if (is_array($val)) return $val;
                            if (is_object($val)) return (array) $val;
                            if (is_null($val) || $val === "") return [];

                            if (is_string($val)) {
                                $tmp = json_decode($val, true);
                                return (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) 
                                    ? $tmp 
                                    : [$val];
                            }
                            return [$val];
                        };

                        // Ambil old input atau data dari database
                        $oldRincian = old('rincian_pekerjaan');
                        $oldSatuan = old('satuan');
                        $oldJumlah = old('jumlah');
                        $oldBiaya = old('biaya_material_rincian');

                        $rincianList = [];
                        $satuanList  = [];
                        $jumlahList  = [];
                        $biayaList   = [];

                        if (is_array($oldRincian) || is_array($oldSatuan) || is_array($oldJumlah) || is_array($oldBiaya)) {
                            // Dari old input (validation error)
                            $maxOld = max(
                                count($oldRincian ?: []),
                                count($oldSatuan ?: []),
                                count($oldJumlah ?: []),
                                count($oldBiaya ?: [])
                            );
                            for ($i = 0; $i < max(1, $maxOld); $i++) {
                                $rincianList[] = $oldRincian[$i] ?? '';
                                $satuanList[]  = $oldSatuan[$i] ?? '';
                                $jumlahList[]  = $oldJumlah[$i] ?? 0;
                                $biayaList[]   = $oldBiaya[$i] ?? 0;
                            }
                        } else {
                            // Dari database
                            $decoded = $decodeAman($rab->rincian_pekerjaan);

                            if (is_array($decoded)) {
                                foreach ($decoded as $row) {
                                    if (is_object($row)) $row = (array)$row;

                                    $rincianList[] = $row['rincian'] ?? '';
                                    $satuanList[]  = $row['satuan'] ?? '';
                                    $jumlahList[]  = $row['jumlah'] ?? 0;
                                    $biayaList[]   = $row['biaya_material'] ?? 0;
                                }
                            }
                        }

                        $count = max(1, count($rincianList));
                    @endphp

                    {{-- Loop Rincian dari Database --}}
                    @for ($i = 0; $i < $count; $i++)
                    <div class="rincian-card">
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="custom-label">Rincian Pekerjaan atau alat/bahan <span class="text-danger">*</span></label>
                                <textarea name="rincian_pekerjaan[]" 
                                          class="custom-textarea" 
                                          placeholder="Contoh: Pembuatan rangka tangga besi hollow 4x4"
                                          required>{{ $rincianList[$i] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="custom-label">Satuan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       name="satuan[]" 
                                       class="custom-input" 
                                       placeholder="Unit, Meter, Kg"
                                       value="{{ $satuanList[$i] ?? 'Unit' }}" 
                                       required>
                            </div>

                            <div class="col-md-3">
                                <label class="custom-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="jumlah[]" 
                                       class="custom-input jumlah-input" 
                                       min="1" 
                                       placeholder="0"
                                       value="{{ $jumlahList[$i] ?? 1 }}" 
                                       onchange="hitungTotal()"
                                       required>
                            </div>

                            {{-- 🔥 BIAYA MATERIAL DENGAN FORMAT RUPIAH --}}
                            <div class="col-md-3">
                                <label class="custom-label">Biaya Material <span class="text-danger">*</span></label>
                                <div style="position: relative;">
                                    <input type="text" 
                                           class="custom-input biaya-material-display-{{ $i }}" 
                                           placeholder="Rp 0"
                                           value="Rp {{ number_format($biayaList[$i] ?? 0, 0, ',', '.') }}"
                                           data-index="{{ $i }}"
                                           required
                                           style="font-weight: 600; color: #1a1f71;">
                                    <input type="hidden" 
                                           name="biaya_material_rincian[]" 
                                           class="biaya-material-input biaya-material-hidden-{{ $i }}"
                                           value="{{ $biayaList[$i] ?? 0 }}"
                                           data-index="{{ $i }}">
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
                    @endfor
                </div>

                {{-- Total RAB & Tombol Tambah Rincian --}}
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

                {{-- Status Dropdown --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="custom-select" required>
                            <option value="Perencanaan" {{ old('status', $rab->status) == 'Perencanaan' ? 'selected' : '' }}>
                                Perencanaan
                            </option>
                            <option value="Belum Disetujui" {{ old('status', $rab->status) == 'Belum Disetujui' ? 'selected' : '' }}>
                                Belum Disetujui
                            </option>
                            <option value="Disetujui" {{ old('status', $rab->status) == 'Disetujui' ? 'selected' : '' }}>
                                Disetujui
                            </option>
                            <option value="Berjalan" {{ old('status', $rab->status) == 'Berjalan' ? 'selected' : '' }}>
                                Berjalan
                            </option>
                            <option value="Selesai" {{ old('status', $rab->status) == 'Selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Hidden Fields --}}
                <input type="hidden" name="total" id="total_hidden" value="{{ old('total', $rab->total ?? 0) }}">
                <input type="hidden" name="nama_pekerjaan" id="nama_pekerjaan" value="{{ old('nama_pekerjaan', $rab->nama_pekerjaan) }}">

                {{-- Tombol Aksi --}}
                <div class="form-actions">
                    <a href="{{ route('rab.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</main>

@endsection

@section('scripts')
<script>
    // ========================================
    // RAB EDIT - LOGIC
    // ========================================
    
    (function() {
        console.log('✅ RAB Edit Page Loaded WITH RUPIAH FORMAT & DARK THEME');
        
        // Proyek sudah terkunci, tidak perlu modal functions
        
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
    // RAB LOGIC - TAMBAH/HAPUS RINCIAN
    // ========================================
    
    let rincianCounter = {{ $count }};

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

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        console.log('✅ RAB Edit Form initialized WITH RUPIAH FORMAT & DARK THEME');
        
        document.querySelectorAll('.rincian-card').forEach(card => {
            attachEventListeners(card);
        });
        
        hitungTotal();
    });
</script>
@endsection