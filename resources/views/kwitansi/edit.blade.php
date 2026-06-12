@extends('layouts.app')

@section('title', 'Edit Kwitansi')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kwitansi-edit.css') }}">
    <style>
        /* ========================================
           BOOTSTRAP MODAL DARK THEME OVERRIDE
           PRIORITY: NUCLEAR LEVEL - FORCE EVERYTHING
           ======================================== */
        
        /* 🔥 LEVEL 1: Force Modal Dialog & XL */
        body.dark-theme .modal-dialog,
        body.dark-theme .modal-dialog.modal-xl {
            background: transparent !important;
            background-color: transparent !important;
        }

        /* 🔥 LEVEL 2: Force Modal Content Background */
        body.dark-theme .modal-content {
            background: #1e293b !important;
            background-color: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* 🔥 LEVEL 3: Modal Header */
        body.dark-theme .modal-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-theme .modal-title {
            color: #ffffff !important;
        }

        /* 🔥 LEVEL 4: Modal Body - CRITICAL */
        body.dark-theme .modal-body {
            background: #1e293b !important;
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }

        /* 🔥 LEVEL 5: Modal Footer */
        body.dark-theme .modal-footer {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* 🔥 LEVEL 6: Button Close */
        body.dark-theme .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
        }

        /* 🔥 LEVEL 7: Secondary Button */
        body.dark-theme .modal-footer .btn-secondary {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
            border: none !important;
            color: white !important;
        }

        body.dark-theme .modal-footer .btn-secondary:hover {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%) !important;
        }

        /* ========================================
           BOOTSTRAP OVERRIDE - NUCLEAR OPTION
           ======================================== */
        
        /* Force remove all Bootstrap white backgrounds in modal */
        body.dark-theme .modal .bg-white {
            background: #1e293b !important;
            background-color: #1e293b !important;
        }

        /* Force all modal children backgrounds */
        body.dark-theme .modal-content *:not(.btn):not(.badge):not(.alert) {
            background-color: inherit;
        }

        /* Force specific modal elements */
        body.dark-theme .modal-content,
        body.dark-theme .modal-body,
        body.dark-theme .modal-footer,
        body.dark-theme .modal [class*="modal-"] {
            background-color: inherit !important;
        }

        /* Override any potential white from containers */
        body.dark-theme .modal .container,
        body.dark-theme .modal .container-fluid,
        body.dark-theme .modal .row,
        body.dark-theme .modal .col,
        body.dark-theme .modal [class*="col-"] {
            background: transparent !important;
            background-color: transparent !important;
        }
    </style>
@endsection

@section('content')
<main class="kwitansi-main">
    <div class="form-container">
        <h1 class="form-title">EDIT KWITANSI</h1>

        <form action="{{ route('kwitansi.update', $kwitansi->Id_Kwitansi) }}" method="POST" id="formKwitansi">
            @csrf
            @method('PUT')

            {{-- Row 1: Pilih Sumber & Pilih Data (DIKUNCI - READONLY) --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="custom-label">Pilih Sumber</label>
                    <select name="Sumber_Tabel" id="Sumber_Tabel" class="custom-select" disabled>
                        <option value="">-- Pilih Sumber --</option>
                        <option value="rabs" {{ $kwitansi->Sumber_Tabel === 'rabs' ? 'selected' : '' }}>RAB</option>
                        <option value="penjualan" {{ $kwitansi->Sumber_Tabel === 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                    </select>
                    {{-- Hidden input agar data tetap tersubmit --}}
                    <input type="hidden" name="Sumber_Tabel" value="{{ $kwitansi->Sumber_Tabel }}">
                </div>

                <div class="col-md-6">
                    <label class="custom-label">Pilih Data</label>
                    <select name="Id_Sumber" id="Id_Sumber" class="custom-select" disabled>
                        <option value="{{ $kwitansi->Id_Sumber }}" selected>
                            @if($kwitansi->Sumber_Tabel === 'rabs')
                                RAB #{{ $kwitansi->Id_Sumber }}
                            @else
                                Penjualan #{{ $kwitansi->Id_Sumber }}
                            @endif
                        </option>
                    </select>
                    {{-- Hidden input agar data tetap tersubmit --}}
                    <input type="hidden" name="Id_Sumber" value="{{ $kwitansi->Id_Sumber }}">
                </div>
            </div>

            {{-- 🔥 KOLOM NO DAN ID KWITANSI - DISEMBUNYIKAN (d-none) --}}
            <div class="row g-3 mb-3 d-none">
                <div class="col-md-3">
                    <label class="custom-label">No</label>
                    <input type="text" class="custom-input" value="Auto" readonly>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Id Kwitansi</label>
                    <input type="text" id="displayIdKwitansi" class="custom-input" readonly 
                           value="{{ $kwitansi->Id_Kwitansi }}" placeholder="Auto dari sumber">
                </div>
            </div>

            {{-- 🔥 KOLOM TANGGAL DAN TOTAL RAB - NAIK KE ATAS (MENGGANTIKAN POSISI NO & ID KWITANSI) --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="custom-label">Tanggal 📅</label>
                    <input type="date" name="Tanggal_Kwitansi" id="Tanggal_Kwitansi" 
                           class="custom-input" value="{{ old('Tanggal_Kwitansi', $kwitansi->Tanggal_Kwitansi) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="custom-label">Total RAB</label>
                    <input type="number" name="Total" id="Total" class="custom-input" 
                           placeholder="Otomatis terisi" value="{{ old('Total', $kwitansi->Total) }}" readonly required>
                    <small class="small-hint">Total dari RAB/Penjualan</small>
                </div>
            </div>

            {{-- Row 3: Metode Pembayaran, Untuk Pembayaran, Total Pembayaran, Status --}}
            <div class="row g-3 mb-4 payment-row" id="paymentRow">
                <div class="col-md-3">
                    <label class="custom-label">Metode Pembayaran</label>
                    <select name="Metode_Pembayaran" id="Metode_Pembayaran" class="custom-select" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Cash" {{ $kwitansi->Metode_Pembayaran === 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="QRIS" {{ $kwitansi->Metode_Pembayaran === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Transfer" {{ $kwitansi->Metode_Pembayaran === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Untuk Pembayaran</label>
                    <input type="text" name="Untuk_Pembayaran" id="Untuk_Pembayaran" 
                           class="custom-input" placeholder="Keterangan pembayaran" 
                           value="{{ old('Untuk_Pembayaran', $kwitansi->Untuk_Pembayaran ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Total Pembayaran</label>
                    <input type="number" name="Total_Pembayaran" id="Total_Pembayaran" 
                           class="custom-input" placeholder="Masukkan jumlah bayar" 
                           value="{{ old('Total_Pembayaran', $kwitansi->Total_Pembayaran ?? 0) }}" required
                           style="border-left: 4px solid #6c757d;">
                    <small class="small-hint">Jumlah yang dibayarkan client</small>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Status Pembayaran</label>
                    <div id="displayStatus" class="status-display dp-50">{{ $kwitansi->Status ?? 'DP 50%' }}</div>
                    <div class="status-info-box" id="statusInfo">
                        <span class="percentage" id="percentageText">0%</span> terbayar
                    </div>
                </div>
            </div>

            <input type="hidden" name="Sales" id="Sales" value="{{ old('Sales', $kwitansi->Sales ?? 'Admin') }}">

            <div class="form-actions">
                <a href="{{ route('kwitansi.index') }}" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-simpan">
                    <i class="fas fa-save"></i> Update Kwitansi
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalInput = document.getElementById('Total');
        const totalPembayaranInput = document.getElementById('Total_Pembayaran');
        const displayStatusDiv = document.getElementById('displayStatus');
        const percentageText = document.getElementById('percentageText');
        const paymentRow = document.getElementById('paymentRow');

        function calculateStatus(totalPembayaran, totalRAB) {
            if (totalPembayaran >= totalRAB) {
                return 'Lunas';
            }

            const persentase = (totalPembayaran / totalRAB) * 100;

            if (persentase >= 50) {
                return 'Belum Lunas';
            }

            return 'DP 50%';
        }

        function updateStatusAndColor() {
            const total = parseFloat(totalInput.value) || 0;
            const totalPembayaran = parseFloat(totalPembayaranInput.value) || 0;

            if (total <= 0) {
                return;
            }

            const persentase = total > 0 ? ((totalPembayaran / total) * 100).toFixed(1) : 0;
            percentageText.textContent = persentase + '%';

            const status = calculateStatus(totalPembayaran, total);
            displayStatusDiv.textContent = status;
            displayStatusDiv.className = 'status-display';
            
            if (status === 'Lunas') {
                displayStatusDiv.classList.add('lunas');
                paymentRow.style.borderLeft = '4px solid #0d6efd';
                totalPembayaranInput.style.borderLeft = '4px solid #0d6efd';
            } else if (status === 'Belum Lunas') {
                displayStatusDiv.classList.add('belum-lunas');
                paymentRow.style.borderLeft = '4px solid #fd7e14';
                totalPembayaranInput.style.borderLeft = '4px solid #fd7e14';
            } else {
                displayStatusDiv.classList.add('dp-50');
                paymentRow.style.borderLeft = '4px solid #ffc107';
                totalPembayaranInput.style.borderLeft = '4px solid #ffc107';
            }
        }

        // Event listener untuk update status saat Total Pembayaran berubah
        totalPembayaranInput.addEventListener('input', updateStatusAndColor);

        // Initialize status saat halaman load
        updateStatusAndColor();

        // Validasi form sebelum submit
        document.getElementById('formKwitansi').addEventListener('submit', function(e) {
            const total = parseFloat(totalInput.value) || 0;
            const totalPembayaran = parseFloat(totalPembayaranInput.value) || 0;

            if (total <= 0) {
                e.preventDefault();
                alert('Total harus lebih dari 0. Pastikan Anda sudah memilih data sumber.');
                return false;
            }

            if (totalPembayaran <= 0) {
                e.preventDefault();
                alert('Total Pembayaran harus diisi!');
                return false;
            }

            const status = calculateStatus(totalPembayaran, total);
            const persentase = ((totalPembayaran / total) * 100).toFixed(1);
            
            const confirmMsg = `Apakah Anda yakin ingin mengupdate kwitansi ini?\n\n` +
                               `Total RAB: Rp ${Number(total).toLocaleString('id-ID')}\n` +
                               `Total Pembayaran: Rp ${Number(totalPembayaran).toLocaleString('id-ID')}\n` +
                               `Persentase: ${persentase}%\n` +
                               `Status: ${status}`;
            
            if (!confirm(confirmMsg)) {
                e.preventDefault();
                return false;
            }
        });

        console.log('✅ Edit Kwitansi with NUCLEAR Dark Theme loaded!');
    });
</script>
@endsection