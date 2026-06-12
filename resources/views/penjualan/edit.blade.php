@extends('layouts.app')

@section('title', 'Edit Penjualan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/penjualan-edit-style.css') }}">
<style>
    /* Override untuk memastikan tombol tidak berubah di light mode */
    .btn-tambah-rincian,
    .btn-hapus-rincian {
        background: white !important;
        background-color: white !important;
    }
    
    .btn-tambah-rincian {
        border-color: #1a1f71 !important;
        color: #1a1f71 !important;
    }
    
    .btn-hapus-rincian {
        border-color: #6c757d !important;
        color: #6c757d !important;
    }

    /* Dark Theme Override Buttons */
    body.dark-theme .btn-tambah-rincian {
        background: transparent !important;
        background-color: transparent !important;
        border-color: #3b82f6 !important;
        color: #3b82f6 !important;
    }

    body.dark-theme .btn-hapus-rincian {
        background: transparent !important;
        background-color: transparent !important;
        border-color: #94a3b8 !important;
        color: #94a3b8 !important;
    }

    body.dark-theme .btn-tambah-rincian:hover {
        background: rgba(59, 130, 246, 0.1) !important;
    }

    body.dark-theme .btn-hapus-rincian:hover {
        background: rgba(148, 163, 184, 0.1) !important;
    }

    /* 🔥 STYLE KHUSUS UNTUK INPUT HARGA SATUAN DENGAN FORMAT RUPIAH */
    .harga-input-display {
        font-weight: 600;
        color: #1a1f71;
    }

    /* Dark Theme Harga Display */
    body.dark-theme .harga-input-display {
        color: #60a5fa;
    }
</style>
@endsection

@section('content')
<main class="penjualan-edit-main">
    <div class="form-container">
        <h1 class="form-title">EDIT PENJUALAN</h1>

        {{-- Pesan error validasi --}}
        @if ($errors->any())
            <div class="alert-validation">
                <strong><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit Penjualan --}}
        <form action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST" id="formPenjualan">
            @csrf
            @method('PUT')

            {{-- SECTION: NAMA SALES (READONLY - TIDAK BISA DIEDIT) --}}
            <div class="sales-section">
                <div class="sales-label">
                    <i class="fas fa-user-tie"></i>
                    Nama Sales
                </div>
                <div class="sales-display">
                    <i class="fas fa-id-badge sales-icon"></i>
                    {{ $penjualan->nama_sales }}
                </div>
                <div class="sales-note">
                    <i class="fas fa-lock"></i>
                    Nama sales tidak dapat diubah setelah data disimpan
                </div>
            </div>

            {{-- Tombol Tambah/Hapus Rincian --}}
            <div class="action-buttons">
                <button type="button" class="btn-tambah-rincian" onclick="tambahRincian()">
                    <i class="fas fa-plus"></i>
                    Tambah Rincian
                </button>
                <button type="button" class="btn-hapus-rincian" onclick="hapusRincian()">
                    <i class="fas fa-minus"></i>
                    Hapus Rincian
                </button>
            </div>

            {{-- Label Header Kolom --}}
            <div class="form-labels">
                <div>No</div>
                <div>Rincian</div>
                <div>Jumlah</div>
                <div>Harga Satuan</div>
            </div>

            {{-- Container untuk Rincian Items --}}
            <div id="rincianContainer">
                @if($penjualan->details && $penjualan->details->count() > 0)
                    @foreach($penjualan->details as $index => $detail)
                        <div class="rincian-item" data-row="{{ $index + 1 }}">
                            <input type="text" 
                                   class="custom-input" 
                                   value="{{ $index + 1 }}" 
                                   disabled>
                            
                            <input type="text" 
                                   name="rincian[]" 
                                   class="custom-input @error('rincian.'.$index) is-invalid @enderror"
                                   placeholder="Masukkan rincian" 
                                   value="{{ old('rincian.'.$index, $detail->rincian) }}"
                                   required>
                            
                            <input type="number" 
                                   name="jumlah[]" 
                                   class="custom-input jumlah-input @error('jumlah.'.$index) is-invalid @enderror"
                                   placeholder="Jumlah" 
                                   min="1"
                                   value="{{ old('jumlah.'.$index, $detail->jumlah) }}"
                                   onchange="hitungTotal()"
                                   required>
                            
                            {{-- 🔥 INPUT HARGA SATUAN DENGAN FORMAT RUPIAH --}}
                            <div style="position: relative;">
                                {{-- Input yang terlihat (dengan format Rupiah) --}}
                                <input type="text" 
                                       class="harga-input-display harga-display-{{ $index }}"
                                       placeholder="Rp 0"
                                       value="Rp {{ number_format(old('harga_satuan.'.$index, $detail->harga_satuan), 0, ',', '.') }}"
                                       oninput="formatHargaInput(this, {{ $index }})"
                                       required>
                                
                                {{-- Hidden input untuk submit (angka murni) --}}
                                <input type="hidden" 
                                       name="harga_satuan[]" 
                                       class="harga-input harga-hidden-{{ $index }}"
                                       value="{{ old('harga_satuan.'.$index, $detail->harga_satuan) }}"
                                       required>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="rincian-item" data-row="1">
                        <input type="text" 
                               class="custom-input" 
                               value="1" 
                               disabled>
                        
                        <input type="text" 
                               name="rincian[]" 
                               class="custom-input"
                               placeholder="Masukkan rincian" 
                               required>
                        
                        <input type="number" 
                               name="jumlah[]" 
                               class="custom-input jumlah-input"
                               placeholder="Jumlah" 
                               min="1"
                               onchange="hitungTotal()"
                               required>
                        
                        {{-- 🔥 INPUT HARGA SATUAN DENGAN FORMAT RUPIAH --}}
                        <div style="position: relative;">
                            <input type="text" 
                                   class="harga-input-display harga-display-0"
                                   placeholder="Rp 0"
                                   oninput="formatHargaInput(this, 0)"
                                   required>
                            
                            <input type="hidden" 
                                   name="harga_satuan[]" 
                                   class="harga-input harga-hidden-0"
                                   value="0"
                                   required>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Info Total --}}
            <div class="mt-4 p-3 total-section">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="total-label">
                        <i class="fas fa-calculator"></i> Total (Otomatis):
                    </span>
                    <span id="totalDisplay" class="total-value">
                        Rp 0
                    </span>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="form-actions">
                <a href="{{ route('penjualan.index') }}" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    let rowCount = {{ $penjualan->details->count() > 0 ? $penjualan->details->count() : 1 }};

    // 🔥 FUNGSI FORMAT RUPIAH
    function formatRupiah(angka) {
        const numberString = angka.toString().replace(/[^,\d]/g, '');
        const split = numberString.split(',');
        const sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        const ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        
        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        
        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    // 🔥 FUNGSI PARSE RUPIAH KE ANGKA
    function parseRupiah(rupiah) {
        return parseFloat(rupiah.replace(/[^,\d]/g, '').replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

    // 🔥 FUNGSI FORMAT INPUT HARGA SATUAN
    function formatHargaInput(input, index) {
        // Ambil nilai input, hapus semua karakter selain angka
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format dengan pemisah ribuan
        if (value) {
            input.value = 'Rp ' + formatRupiah(value);
        } else {
            input.value = '';
        }
        
        // Update hidden input dengan angka murni
        const hiddenInput = document.querySelector('.harga-hidden-' + index);
        if (hiddenInput) {
            hiddenInput.value = value || 0;
        }
        
        // Hitung total
        hitungTotal();
    }

    // Tambah Rincian Baru
    function tambahRincian() {
        rowCount++;
        const container = document.getElementById('rincianContainer');
        
        const newRow = document.createElement('div');
        newRow.className = 'rincian-item';
        newRow.setAttribute('data-row', rowCount);
        
        newRow.innerHTML = `
            <input type="text" 
                   class="custom-input" 
                   value="${rowCount}" 
                   disabled>
            
            <input type="text" 
                   name="rincian[]" 
                   class="custom-input"
                   placeholder="Masukkan rincian" 
                   required>
            
            <input type="number" 
                   name="jumlah[]" 
                   class="custom-input jumlah-input"
                   placeholder="Jumlah" 
                   min="1"
                   onchange="hitungTotal()"
                   required>
            
            <div style="position: relative;">
                <input type="text" 
                       class="harga-input-display harga-display-${rowCount - 1}"
                       placeholder="Rp 0"
                       oninput="formatHargaInput(this, ${rowCount - 1})"
                       required>
                
                <input type="hidden" 
                       name="harga_satuan[]" 
                       class="harga-input harga-hidden-${rowCount - 1}"
                       value="0"
                       required>
            </div>
        `;
        
        container.appendChild(newRow);
    }

    // Hapus Rincian Terakhir
    function hapusRincian() {
        const container = document.getElementById('rincianContainer');
        const items = container.querySelectorAll('.rincian-item');
        
        if (items.length > 1) {
            container.removeChild(items[items.length - 1]);
            rowCount--;
            updateRowNumbers();
            hitungTotal();
        } else {
            alert('Minimal harus ada 1 rincian!');
        }
    }

    // Update nomor urut setelah hapus
    function updateRowNumbers() {
        const items = document.querySelectorAll('.rincian-item');
        items.forEach((item, index) => {
            const noInput = item.querySelector('input[disabled]');
            noInput.value = index + 1;
            item.setAttribute('data-row', index + 1);
        });
        rowCount = items.length;
    }

    // 🔥 HITUNG TOTAL DARI SEMUA RINCIAN (DENGAN FORMAT RUPIAH)
    function hitungTotal() {
        const jumlahInputs = document.querySelectorAll('.jumlah-input');
        const hargaInputs = document.querySelectorAll('.harga-input');
        const totalDisplay = document.getElementById('totalDisplay');
        
        let grandTotal = 0;
        
        jumlahInputs.forEach((jumlahInput, index) => {
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const harga = parseFloat(hargaInputs[index].value) || 0;
            grandTotal += (jumlah * harga);
        });
        
        // Format total dengan Rupiah
        totalDisplay.textContent = 'Rp ' + formatRupiah(grandTotal.toString());
    }

    // 🔥 INITIALIZE SAAT HALAMAN LOAD
    document.addEventListener('DOMContentLoaded', function() {
        // Hitung total awal
        hitungTotal();
        
        console.log('✅ Edit Penjualan with Dark Theme Support loaded successfully!');
        console.log('✅ Format Rupiah aktif pada Harga Satuan!');
    });
</script>
@endsection