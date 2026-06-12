@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/laporan-create.css?v=' . time()) }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection

@section('content')
{{-- Main Content TANPA Navbar Lama --}}
<main class="laporan-main">
    
    {{-- CONTENT AREA --}}
    <div class="laporan-container">
        
        {{-- Form Container --}}
        <div class="form-container">
            <h1 class="form-title">EDIT LAPORAN</h1>

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="alert-validation">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-validation">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form id="formLaporanEdit" action="{{ route('laporan.update', $laporan->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ✅ HIDDEN INPUTS UNTUK DATA YANG REQUIRED --}}
                <input type="hidden" name="tanggal" value="{{ $laporan->tanggal }}">
                <input type="hidden" name="owner" value="{{ $laporan->owner }}">
                <input type="hidden" name="sumber" value="{{ $laporan->sumber }}">
                <input type="hidden" name="data_id" value="{{ $laporan->data_id }}">

                {{-- Baris 1: Pilih Sumber & Pilih Data (READONLY) --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Pilih Sumber <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="custom-input" 
                               value="{{ $laporan->sumber ?? '-' }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Pilih Data <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="custom-input" 
                               value="{{ $laporan->sumber }} #{{ $laporan->data_id }} - {{ $laporan->owner }}"
                               readonly>
                    </div>
                </div>

                {{-- Baris 2: Tanggal & Hasil Profit --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="custom-input" 
                               value="{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Hasil Profit</label>
                        <div id="hasilProfitBox" 
                             class="profit-display"
                             style="color: {{ $laporan->total_profit >= 0 ? '#28a745' : '#dc3545' }}">
                            Rp {{ number_format($laporan->total_profit, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                {{-- 🔥 WRAPPER UNTUK HORIZONTAL SCROLL DI MOBILE --}}
                <div class="table-responsive-wrapper">
                    {{-- Baris 3: Header Row untuk 7 kolom --}}
                    <div class="table-header-row mb-2">
                        <div class="table-header-cell" style="width: 220px; flex-shrink: 0;">Rincian</div>
                        <div class="table-header-cell" style="flex: 1; min-width: 90px;">Jumlah</div>
                        <div class="table-header-cell" style="flex: 0.8; min-width: 80px;">Satuan</div>
                        <div class="table-header-cell" style="flex: 1.5; min-width: 120px;">Total</div>
                        <div class="table-header-cell" style="flex: 1.5; min-width: 120px;">Modal Satuan</div>
                        <div class="table-header-cell" style="flex: 1.5; min-width: 120px;">Modal Total</div>
                        <div class="table-header-cell" style="flex: 1.5; min-width: 120px;">Profit</div>
                    </div>

                    {{-- Container untuk Multiple Rincian --}}
                    <div id="detailContainer">
                        @foreach($laporan->details as $index => $detail)
                            <div class="detail-row">
                                {{-- RINCIAN --}}
                                <div class="detail-cell" style="width: 220px; flex-shrink: 0;">
                                    <input type="text" 
                                           name="detail[{{ $index }}][rincian]" 
                                           class="custom-input" 
                                           value="{{ $detail->rincian }}" 
                                           required 
                                           readonly>
                                </div>

                                {{-- JUMLAH --}}
                                <div class="detail-cell" style="flex: 1; min-width: 90px;">
                                    <input type="number" 
                                           name="detail[{{ $index }}][jumlah]" 
                                           class="custom-input jumlah-{{ $index }}" 
                                           value="{{ $detail->jumlah }}" 
                                           required 
                                           readonly>
                                </div>

                                {{-- SATUAN --}}
                                <div class="detail-cell" style="flex: 0.8; min-width: 80px;">
                                    <input type="text" 
                                           name="detail[{{ $index }}][satuan]" 
                                           class="custom-input" 
                                           value="{{ $detail->satuan }}" 
                                           required 
                                           readonly>
                                </div>

                                {{-- TOTAL --}}
                                <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
                                    <input type="text" 
                                           class="custom-input total-display-{{ $index }}" 
                                           value="Rp {{ number_format($detail->total, 0, ',', '.') }}" 
                                           required 
                                           readonly
                                           style="font-weight: 600; color: #1a1f71;">
                                    <input type="hidden" 
                                           name="detail[{{ $index }}][total]" 
                                           class="total-hidden-{{ $index }}"
                                           value="{{ $detail->total }}">
                                </div>

                                {{-- MODAL SATUAN (EDITABLE) --}}
                                <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
                                    <input type="text" 
                                           class="custom-input modal-satuan-display-{{ $index }}" 
                                           value="Rp {{ number_format($detail->modal_satuan, 0, ',', '.') }}" 
                                           data-index="{{ $index }}"
                                           placeholder="Rp 0"
                                           required
                                           style="font-weight: 600; color: #1a1f71;">
                                    <input type="hidden" 
                                           name="detail[{{ $index }}][modal_satuan]" 
                                           class="modal-satuan-input modal-satuan-hidden-{{ $index }}"
                                           value="{{ $detail->modal_satuan }}"
                                           data-index="{{ $index }}">
                                    <input type="hidden" 
                                           name="detail[{{ $index }}][id]" 
                                           value="{{ $detail->id }}">
                                </div>

                                {{-- MODAL TOTAL --}}
                                <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
                                    <input type="text" 
                                           class="custom-input modal-total-display-{{ $index }}" 
                                           value="Rp {{ number_format($detail->total_modal, 0, ',', '.') }}" 
                                           required 
                                           readonly 
                                           style="font-weight: 600; color: #dc3545;">
                                    <input type="hidden" 
                                           name="detail[{{ $index }}][total_modal]" 
                                           class="modal-total-hidden-{{ $index }}"
                                           value="{{ $detail->total_modal }}">
                                </div>

                                {{-- PROFIT --}}
                                <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
                                    <input type="text" 
                                           class="custom-input profit-display-{{ $index }}" 
                                           value="Rp {{ number_format($detail->profit, 0, ',', '.') }}" 
                                           required 
                                           readonly 
                                           style="font-weight: 600; color: {{ $detail->profit >= 0 ? '#28a745' : '#dc3545' }};">
                                    <input type="hidden" 
                                           name="detail[{{ $index }}][profit]" 
                                           class="profit-hidden-{{ $index }}"
                                           value="{{ $detail->profit }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Baris 4: Nama Laporan dan Owner --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Nama Laporan <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama_laporan" 
                               class="custom-input" 
                               value="{{ $laporan->nama_laporan }}"
                               placeholder="Masukkan Nama Laporan" 
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Owner</label>
                        <input type="text" 
                               class="custom-input" 
                               value="{{ $laporan->owner ?? '-' }}"
                               readonly>
                    </div>
                </div>

                {{-- Hidden field untuk total profit --}}
                <input type="hidden" name="total_profit" id="total_profit_hidden" value="{{ $laporan->total_profit }}">

                {{-- Form Actions --}}
                <div class="form-actions">
                    <a href="{{ route('laporan.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan" id="btnSubmit">Update Laporan</button>
                </div>
            </form>
        </div>

    </div>

</main>
@endsection

@section('scripts')
<script>
console.log('🚀 Laporan Edit - WITH DARK THEME');

// ===============================
// FUNGSI HELPER
// ===============================
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

// ===============================
// 🔥 FUNGSI FORMAT INPUT MODAL SATUAN
// ===============================
function formatModalSatuanInput(input, index) {
    let value = input.value.replace(/[^\d]/g, '');
    
    if (value) {
        const number = parseFloat(value);
        input.value = 'Rp ' + number.toLocaleString('id-ID');
    } else {
        input.value = '';
    }
    
    const hiddenInput = document.querySelector(`.modal-satuan-hidden-${index}`);
    if (hiddenInput) {
        hiddenInput.value = value || 0;
    }
    
    calculateRowProfit(index);
}

// ===============================
// FUNGSI KALKULASI PROFIT PER ROW
// ===============================
function calculateRowProfit(index) {
    const modalSatuanInput = document.querySelector(`.modal-satuan-hidden-${index}`);
    const jumlahInput = document.querySelector(`.jumlah-${index}`);
    const totalModalDisplay = document.querySelector(`.modal-total-display-${index}`);
    const totalModalHidden = document.querySelector(`.modal-total-hidden-${index}`);
    const totalHidden = document.querySelector(`.total-hidden-${index}`);
    const profitDisplay = document.querySelector(`.profit-display-${index}`);
    const profitHidden = document.querySelector(`.profit-hidden-${index}`);

    if (!modalSatuanInput || !jumlahInput || !totalHidden || !profitDisplay) {
        console.warn(`❌ Input tidak ditemukan untuk row ${index}`);
        return;
    }

    const modalSatuan = parseFloat(modalSatuanInput.value) || 0;
    const jumlah = parseFloat(jumlahInput.value) || 0;
    const total = parseFloat(totalHidden.value) || 0;

    const totalModal = modalSatuan * jumlah;
    totalModalHidden.value = totalModal;
    totalModalDisplay.value = 'Rp ' + totalModal.toLocaleString('id-ID');

    const profit = total - totalModal;
    profitHidden.value = profit;
    profitDisplay.value = 'Rp ' + profit.toLocaleString('id-ID');
    
    profitDisplay.style.color = profit >= 0 ? '#28a745' : '#dc3545';

    updateTotalProfit();

    console.log(`💰 Row ${index + 1} - Profit: ${profit}`);
}

// ===============================
// FUNGSI UPDATE TOTAL PROFIT KESELURUHAN
// ===============================
function updateTotalProfit() {
    let totalProfit = 0;
    const profitInputs = document.querySelectorAll('input[class*="profit-hidden-"]');
    
    profitInputs.forEach(input => {
        totalProfit += parseFloat(input.value) || 0;
    });

    const profitBox = document.getElementById('hasilProfitBox');
    const profitHidden = document.getElementById('total_profit_hidden');
    
    profitBox.innerText = formatRupiah(totalProfit);
    profitBox.style.color = totalProfit >= 0 ? '#28a745' : '#dc3545';
    profitHidden.value = totalProfit;

    console.log('📊 Total Profit:', formatRupiah(totalProfit));
}

// ===============================
// ATTACH EVENT LISTENERS
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM Ready - Laporan Edit');
    
    const modalSatuanDisplays = document.querySelectorAll('input[class*="modal-satuan-display-"]');
    
    modalSatuanDisplays.forEach(input => {
        input.addEventListener('input', function() {
            const index = parseInt(this.getAttribute('data-index'));
            console.log('💰 Formatting modal satuan for row:', index);
            formatModalSatuanInput(this, index);
        });
        
        const index = parseInt(input.getAttribute('data-index'));
        calculateRowProfit(index);
    });

    console.log('✅ Event listeners attached to', modalSatuanDisplays.length, 'modal satuan inputs');
});

// ===============================
// VALIDASI FORM SEBELUM SUBMIT
// ===============================
document.getElementById('formLaporanEdit').addEventListener('submit', function(e) {
    const namaLaporan = document.querySelector('input[name="nama_laporan"]').value.trim();
    
    if (!namaLaporan) {
        e.preventDefault();
        alert('❌ Nama Laporan tidak boleh kosong!');
        return false;
    }

    const totalProfit = document.getElementById('total_profit_hidden').value;
    const confirmMsg = `Apakah Anda yakin ingin mengupdate laporan ini?\n\nTotal Profit: ${formatRupiah(parseFloat(totalProfit))}`;
    
    if (!confirm(confirmMsg)) {
        e.preventDefault();
        return false;
    }
    
    console.log('✅ Form validation passed, submitting...');
    return true;
});

console.log('✅ Laporan Edit Ready WITH DARK THEME!');
</script>
@endsection