@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/penjualan-create-style.css') }}">
<style>
    /* Override untuk memastikan tombol tidak berubah */
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
    .custom-input.harga-display-0,
    .custom-input[class*="harga-display-"] {
        font-weight: 600;
        color: #1a1f71;
    }

    /* Dark Theme Harga Display */
    body.dark-theme .custom-input.harga-display-0,
    body.dark-theme .custom-input[class*="harga-display-"] {
        color: #60a5fa;
    }
</style>
@endsection

@section('content')
<main class="penjualan-main">
    <div class="form-container">
        <h1 class="form-title">TAMBAH PENJUALAN</h1>

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

        {{-- Form Tambah Penjualan --}}
        <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
            @csrf

            {{-- Nama Sales Section --}}
            <div class="sales-section">
                <div class="sales-label">
                    <i class="fas fa-user-tie"></i>
                    Nama Sales
                </div>
                <div class="sales-input-wrapper">
                    <i class="fas fa-id-badge sales-input-icon"></i>
                    <input type="text" 
                           name="nama_sales" 
                           class="sales-input @error('nama_sales') is-invalid @enderror"
                           placeholder="Masukkan nama sales"
                           value="{{ old('nama_sales') }}"
                           required>
                </div>
                @error('nama_sales')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="sales-note">
                    <i class="fas fa-info-circle"></i>
                    Nama sales akan tersimpan dan tidak dapat diubah setelah data disimpan
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
                {{-- Rincian item pertama (default) --}}
                <div class="rincian-item" data-row="1">
                    <input type="text" 
                           class="custom-input" 
                           value="1" 
                           disabled>
                    
                    <input type="text" 
                           name="rincian[]" 
                           class="custom-input @error('rincian.0') is-invalid @enderror"
                           placeholder="Masukkan rincian" 
                           value="{{ old('rincian.0') }}"
                           required>
                    
                    <input type="number" 
                           name="jumlah[]" 
                           class="custom-input jumlah-input @error('jumlah.0') is-invalid @enderror"
                           placeholder="Jumlah" 
                           value="{{ old('jumlah.0') }}"
                           min="1"
                           onchange="hitungTotal()"
                           required>
                    
                    {{-- 🔥 INPUT HARGA SATUAN DENGAN FORMAT RUPIAH --}}
                    <div style="position: relative;">
                        <input type="text" 
                               class="custom-input harga-display-0"
                               placeholder="Rp 0"
                               value="{{ old('harga_satuan.0') ? 'Rp ' . number_format(old('harga_satuan.0'), 0, ',', '.') : '' }}"
                               oninput="formatHargaInput(this, 0)"
                               required>
                        
                        <input type="hidden" 
                               name="harga_satuan[]" 
                               class="harga-input harga-hidden-0"
                               value="{{ old('harga_satuan.0', 0) }}"
                               required>
                    </div>
                </div>
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
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    let rowCount = 1;

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

    // 🔥 HITUNG TOTAL DARI SEMUA RINCIAN
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
                       class="custom-input harga-display-${rowCount - 1}"
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

    // Auto dismiss validation alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-validation');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 8000);
        });

        // 🔥 Initialize total saat halaman load
        hitungTotal();
        
        console.log('✅ Tambah Penjualan with Dark Theme Support loaded successfully!');
        console.log('✅ Format Rupiah aktif pada Harga Satuan!');
    });
</script>
@endsection