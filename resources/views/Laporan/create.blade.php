@extends('layouts.app')

@section('title', 'Tambah Laporan')

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
            <h1 class="form-title">TAMBAH LAPORAN</h1>

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="alert-validation">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Terjadi kesalahan!</strong>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- FORM --}}
            <form id="formLaporan" action="{{ route('laporan.store') }}" method="POST">
                @csrf

                {{-- Baris 1: Pilih Sumber & Pilih Data --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Pilih Sumber <span class="text-danger">*</span></label>
                        <select id="sumber" name="sumber" class="custom-select" required>
                            <option value="">Pilih Sumber</option>
                            <option value="RAB">RAB</option>
                            <option value="Penjualan">Penjualan</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Pilih Data <span class="text-danger">*</span></label>
                        <button type="button" id="btnPilihData" class="btn-pilih-data" disabled>
                            <span id="selectedDataText">-- Pilih Sumber Terlebih Dahulu --</span>
                        </button>
                        <input type="hidden" name="data_id" id="data_id" required>
                    </div>
                </div>

                {{-- Baris 2: Tanggal & Hasil Profit --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="custom-input" required>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Hasil Profit</label>
                        <div id="hasilProfitBox" class="profit-display">Rp 0</div>
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
                        {{-- Rincian akan ditambahkan di sini oleh JavaScript --}}
                    </div>
                </div>

                {{-- Baris 4: Nama Laporan dan Owner --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="custom-label">Nama Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_laporan" class="custom-input" placeholder="Masukkan Nama Laporan" required>
                    </div>

                    <div class="col-md-6">
                        <label class="custom-label">Owner</label>
                        <input type="text" id="owner" name="owner" class="custom-input" placeholder="Terisi otomatis" readonly>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="form-actions">
                    <a href="{{ route('laporan.index') }}" class="btn-batal">Batal</a>
                    <button type="submit" class="btn-simpan" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>

    </div>

</main>

{{-- Modal Pop-up untuk Pilih Data --}}
<div class="modal fade" id="modalPilihData" tabindex="-1" aria-labelledby="modalPilihDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihDataLabel">
                    <i class="bi bi-search me-2"></i>Pilih Data <span id="modalSumberType"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Search, Sort, dan Filter --}}
                <div class="search-filter-container">
                    <div class="search-box">
                        <input type="text" id="modalSearchInput" placeholder="Cari owner, No RAB, nama pekerjaan, sales..." autocomplete="off">
                    </div>
                    <div class="sort-box">
                        <select id="modalSortSelect">
                            <option value="">-- Urutkan --</option>
                            <option value="tertinggi">Total Tertinggi</option>
                            <option value="terendah">Total Terendah</option>
                        </select>
                    </div>
                    <div class="filter-box">
                        <input type="date" id="filterTanggalDari" placeholder="Dari Tanggal">
                    </div>
                    <div class="filter-box">
                        <input type="date" id="filterTanggalSampai" placeholder="Sampai Tanggal">
                    </div>
                    <div class="filter-action">
                        <button type="button" id="btnApplyFilter" class="btn-filter">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button type="button" id="btnResetFilter" class="btn-reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>

                {{-- Loading --}}
                <div id="loadingData" class="loading-spinner" style="display:none;">
                    <i class="bi bi-arrow-repeat spin-animation"></i>
                    <p class="mt-3">Memuat data...</p>
                </div>

                {{-- Table Data --}}
                <div class="data-table-wrapper" id="dataTableWrapper" style="display:none;">
                    <table class="table data-table">
                        <thead id="tableHead">
                            {{-- Header akan di-generate oleh JavaScript --}}
                        </thead>
                        <tbody id="dataTableBody">
                            {{-- Data akan di-generate oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>

                {{-- No Data --}}
                <div id="noDataMessage" class="no-data" style="display:none;">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p>Tidak ada data ditemukan</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
console.log('🚀 Laporan Create - WITH DARK THEME & MODERN SORT');

// ===============================
// GLOBAL VARIABLES
// ===============================
let currentSumber = '';
let searchTimeout;
let modalPilihData;

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

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}

function resetDetailInputs() {
    document.getElementById('detailContainer').innerHTML = '';
    document.getElementById('hasilProfitBox').innerText = 'Rp 0';
    document.getElementById('hasilProfitBox').style.color = '#28a745';
    document.getElementById('owner').value = '';
}

function showError(message) {
    alert('❌ ERROR: ' + message);
    console.error('ERROR:', message);
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
    const jumlahInput = document.querySelector(`input[name="detail[${index}][jumlah]"]`);
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
    profitBox.innerText = formatRupiah(totalProfit);
    profitBox.style.color = totalProfit >= 0 ? '#28a745' : '#dc3545';

    console.log('📊 Total Profit:', formatRupiah(totalProfit));
}

// ===============================
// 🔥 FUNGSI UNTUK MEMBUAT ROW RINCIAN
// ===============================
function createDetailRow(index, detail) {
    const row = document.createElement('div');
    row.className = 'detail-row';
    
    const totalValue = parseFloat(detail.total) || 0;
    const modalSatuanValue = parseFloat(detail.modal_satuan) || 0;
    const totalModalValue = parseFloat(detail.total_modal) || 0;
    const profitValue = parseFloat(detail.profit) || 0;
    
    row.innerHTML = `
        <div class="detail-cell" style="width: 220px; flex-shrink: 0;">
            <input type="text" 
                   name="detail[${index}][rincian]" 
                   class="custom-input" 
                   value="${detail.rincian || ''}" 
                   required 
                   readonly>
        </div>

        <div class="detail-cell" style="flex: 1; min-width: 90px;">
            <input type="number" 
                   name="detail[${index}][jumlah]" 
                   class="custom-input" 
                   value="${detail.jumlah || 0}" 
                   required 
                   readonly>
        </div>

        <div class="detail-cell" style="flex: 0.8; min-width: 80px;">
            <input type="text" 
                   name="detail[${index}][satuan]" 
                   class="custom-input" 
                   value="${detail.satuan || ''}" 
                   required 
                   readonly>
        </div>

        <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
            <input type="text" 
                   class="custom-input total-display-${index}" 
                   value="Rp ${totalValue.toLocaleString('id-ID')}" 
                   required 
                   readonly
                   style="font-weight: 600; color: #1a1f71;">
            <input type="hidden" 
                   name="detail[${index}][total]" 
                   class="total-hidden-${index}"
                   value="${totalValue}">
        </div>

        <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
            <input type="text" 
                   class="custom-input modal-satuan-display-${index}" 
                   value="Rp ${modalSatuanValue.toLocaleString('id-ID')}" 
                   data-index="${index}"
                   placeholder="Rp 0"
                   required
                   style="font-weight: 600; color: #1a1f71;">
            <input type="hidden" 
                   name="detail[${index}][modal_satuan]" 
                   class="modal-satuan-input modal-satuan-hidden-${index}"
                   value="${modalSatuanValue}"
                   data-index="${index}">
        </div>

        <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
            <input type="text" 
                   class="custom-input modal-total-display-${index}" 
                   value="Rp ${totalModalValue.toLocaleString('id-ID')}" 
                   required 
                   readonly 
                   style="font-weight: 600; color: #dc3545;">
            <input type="hidden" 
                   name="detail[${index}][total_modal]" 
                   class="modal-total-hidden-${index}"
                   value="${totalModalValue}">
        </div>

        <div class="detail-cell" style="flex: 1.5; min-width: 120px;">
            <input type="text" 
                   class="custom-input profit-display-${index}" 
                   value="Rp ${profitValue.toLocaleString('id-ID')}" 
                   required 
                   readonly 
                   style="font-weight: 600; color: ${profitValue >= 0 ? '#28a745' : '#dc3545'};">
            <input type="hidden" 
                   name="detail[${index}][profit]" 
                   class="profit-hidden-${index}"
                   value="${profitValue}">
        </div>
    `;
    
    return row;
}

// ===============================
// EVENT: PILIH SUMBER
// ===============================
document.getElementById('sumber').addEventListener('change', function () {
    currentSumber = this.value;
    const btnPilihData = document.getElementById('btnPilihData');
    const selectedDataText = document.getElementById('selectedDataText');
    const hiddenDataId = document.getElementById('data_id');

    console.log('📌 Sumber dipilih:', currentSumber);

    if (currentSumber) {
        btnPilihData.disabled = false;
        selectedDataText.textContent = '-- Pilih Data --';
        btnPilihData.classList.remove('selected');
    } else {
        btnPilihData.disabled = true;
        selectedDataText.textContent = '-- Pilih Sumber Terlebih Dahulu --';
        btnPilihData.classList.remove('selected');
    }

    hiddenDataId.value = '';
    resetDetailInputs();
});

// ===============================
// EVENT: BUTTON PILIH DATA CLICK
// ===============================
document.getElementById('btnPilihData').addEventListener('click', function() {
    if (!currentSumber) {
        alert('Pilih sumber data terlebih dahulu!');
        return;
    }
    
    console.log('🔍 Modal dibuka untuk sumber:', currentSumber);
    
    document.getElementById('modalSumberType').textContent = currentSumber;
    document.getElementById('modalSearchInput').value = '';
    document.getElementById('modalSortSelect').value = '';
    document.getElementById('filterTanggalDari').value = '';
    document.getElementById('filterTanggalSampai').value = '';
    
    modalPilihData.show();
    loadModalData();
});

// ===============================
// EVENT: MODAL SEARCH INPUT
// ===============================
document.getElementById('modalSearchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        console.log('🔍 Search triggered');
        loadModalData();
    }, 500);
});

// ===============================
// EVENT: MODAL SORT SELECT
// ===============================
document.getElementById('modalSortSelect').addEventListener('change', function() {
    console.log('📊 Sort triggered:', this.value);
    loadModalData();
});

// ===============================
// EVENT: BUTTON APPLY FILTER
// ===============================
document.getElementById('btnApplyFilter').addEventListener('click', function() {
    console.log('🎯 Filter applied');
    loadModalData();
});

// ===============================
// EVENT: BUTTON RESET FILTER
// ===============================
document.getElementById('btnResetFilter').addEventListener('click', function() {
    console.log('🔄 Filter reset');
    document.getElementById('modalSearchInput').value = '';
    document.getElementById('modalSortSelect').value = '';
    document.getElementById('filterTanggalDari').value = '';
    document.getElementById('filterTanggalSampai').value = '';
    loadModalData();
});

// ===============================
// FUNCTION: LOAD MODAL DATA
// ===============================
async function loadModalData() {
    const search = document.getElementById('modalSearchInput').value.trim();
    const sort = document.getElementById('modalSortSelect').value;
    const tanggalDari = document.getElementById('filterTanggalDari').value;
    const tanggalSampai = document.getElementById('filterTanggalSampai').value;

    const loadingData = document.getElementById('loadingData');
    const dataTableWrapper = document.getElementById('dataTableWrapper');
    const noDataMessage = document.getElementById('noDataMessage');

    loadingData.style.display = 'block';
    dataTableWrapper.style.display = 'none';
    noDataMessage.style.display = 'none';

    const url = `/laporan/get-data/${encodeURIComponent(currentSumber)}`;
    
    console.log('📡 Fetching data from:', url);
    
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });

        console.log('📥 Response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const result = await response.json();
        console.log('📦 Response data:', result);

        let data = [];
        
        if (result.success && Array.isArray(result.data)) {
            data = result.data;
        } else if (Array.isArray(result)) {
            data = result;
        } else {
            console.warn('⚠️ Format response tidak sesuai:', result);
            data = [];
        }

        console.log('✅ Data loaded:', data.length, 'items');

        if (!data || data.length === 0) {
            loadingData.style.display = 'none';
            noDataMessage.style.display = 'block';
            return;
        }

        if (search) {
            const originalLength = data.length;
            const searchLower = search.toLowerCase();
            
            data = data.filter(item => {
                const itemString = JSON.stringify(item).toLowerCase();
                return itemString.includes(searchLower);
            });
            
            console.log(`🔍 Search filter: ${originalLength} → ${data.length} items`);
        }

        if (tanggalDari || tanggalSampai) {
            const originalLength = data.length;
            data = data.filter(item => {
                const itemDate = new Date(item.tanggal || item.created_at);
                
                if (tanggalDari && tanggalSampai) {
                    return itemDate >= new Date(tanggalDari) && itemDate <= new Date(tanggalSampai);
                } else if (tanggalDari) {
                    return itemDate >= new Date(tanggalDari);
                } else if (tanggalSampai) {
                    return itemDate <= new Date(tanggalSampai);
                }
                
                return true;
            });
            console.log(`📅 Date filter: ${originalLength} → ${data.length} items`);
        }

        if (sort === 'tertinggi') {
            data.sort((a, b) => (parseFloat(b.total) || 0) - (parseFloat(a.total) || 0));
            console.log('📊 Sorted: Tertinggi');
        } else if (sort === 'terendah') {
            data.sort((a, b) => (parseFloat(a.total) || 0) - (parseFloat(b.total) || 0));
            console.log('📊 Sorted: Terendah');
        }

        loadingData.style.display = 'none';

        if (data.length === 0) {
            noDataMessage.style.display = 'block';
        } else {
            renderTable(data);
            dataTableWrapper.style.display = 'block';
        }

    } catch (error) {
        console.error('❌ Fetch Error:', error);
        loadingData.style.display = 'none';
        noDataMessage.style.display = 'block';
        alert('Gagal memuat data: ' + error.message);
    }
}

// ===============================
// FUNCTION: RENDER TABLE - SUDAH DIPERBAIKI DENGAN CLASS
// ===============================
function renderTable(data) {
    const tableHead = document.getElementById('tableHead');
    const dataTableBody = document.getElementById('dataTableBody');

    console.log('🎨 Rendering table with', data.length, 'items');

    if (currentSumber === 'RAB') {
        tableHead.innerHTML = `
            <tr class="table-header-modal">
                <th class="table-th-modal">Owner</th>
                <th class="table-th-modal">No RAB</th>
                <th class="table-th-modal">Nama Pekerjaan</th>
                <th class="table-th-modal">Tanggal</th>
                <th class="table-th-modal">Total</th>
                <th class="table-th-modal">Aksi</th>
            </tr>
        `;
    } else if (currentSumber === 'Penjualan') {
        tableHead.innerHTML = `
            <tr class="table-header-modal">
                <th class="table-th-modal">Nama Sales</th>
                <th class="table-th-modal">ID Penjualan</th>
                <th class="table-th-modal">Tanggal</th>
                <th class="table-th-modal">Total</th>
                <th class="table-th-modal">Aksi</th>
            </tr>
        `;
    }

    let html = '';
    data.forEach(function(item) {
        const id = item.id || item.id_rab || item.id_penjualan;
        const owner = item.owner || item.nama_sales || '-';
        const tanggal = formatDate(item.tanggal || item.created_at);
        const total = parseFloat(item.total) || 0;
        
        if (currentSumber === 'RAB') {
            const noRab = item.no_rab || '-';
            const perihal = item.perihal || '-';
            
            html += `
                <tr class="table-row-modal" data-id="${id}" data-owner="${owner}" style="cursor: pointer;">
                    <td class="table-td-modal">${owner}</td>
                    <td class="table-td-modal">${noRab}</td>
                    <td class="table-td-modal">${perihal}</td>
                    <td class="table-td-modal">${tanggal}</td>
                    <td class="table-td-modal">${formatRupiah(total)}</td>
                    <td class="table-td-modal">
                        <button type="button" class="btn-pilih-row" onclick="selectData(${id}, '${noRab}', '${owner.replace(/'/g, "\\'")}')">
                            Pilih
                        </button>
                    </td>
                </tr>
            `;
        } else {
            html += `
                <tr class="table-row-modal" data-id="${id}" data-owner="${owner}" style="cursor: pointer;">
                    <td class="table-td-modal">${owner}</td>
                    <td class="table-td-modal">${id}</td>
                    <td class="table-td-modal">${tanggal}</td>
                    <td class="table-td-modal">${formatRupiah(total)}</td>
                    <td class="table-td-modal">
                        <button type="button" class="btn-pilih-row" onclick="selectData(${id}, '${id}', '${owner.replace(/'/g, "\\'")}')">
                            Pilih
                        </button>
                    </td>
                </tr>
            `;
        }
    });

    dataTableBody.innerHTML = html;

    const rows = dataTableBody.querySelectorAll('tr');
    rows.forEach(function(row) {
        row.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                this.querySelector('.btn-pilih-row').click();
            }
        });
    });

    console.log('✅ Table rendered successfully');
}

// ===============================
// GLOBAL FUNCTION: SELECT DATA
// ===============================
window.selectData = function(id, idDisplay, owner) {
    console.log('✅ Data selected:', { id, idDisplay, owner });
    
    const hiddenDataId = document.getElementById('data_id');
    const selectedDataText = document.getElementById('selectedDataText');
    const btnPilihData = document.getElementById('btnPilihData');

    hiddenDataId.value = id;
    selectedDataText.textContent = `${currentSumber} #${idDisplay} - ${owner}`;
    btnPilihData.classList.add('selected');

    modalPilihData.hide();

    loadDetailData(id);
};

// ===============================
// FUNCTION: LOAD DETAIL DATA
// ===============================
async function loadDetailData(id) {
    console.log('📥 Loading detail for:', currentSumber, id);
    
    const btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.disabled = true;
    btnSubmit.textContent = '⏳ Memuat data...';

    resetDetailInputs();

    const url = `/laporan/get-detail/${encodeURIComponent(currentSumber)}/${encodeURIComponent(id)}`;
    
    console.log('📡 Fetching detail from:', url);
    
    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });

        console.log('📥 Detail response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Detail response error:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }

        const data = await response.json();
        console.log('📦 Detail data:', data);

        if (!data.success) {
            throw new Error(data.message || 'Format response tidak valid');
        }

        if (!data.details || !Array.isArray(data.details)) {
            throw new Error('Data details tidak tersedia');
        }

        document.getElementById('owner').value = data.owner || '-';
        console.log('👤 Owner set:', data.owner);

        const container = document.getElementById('detailContainer');
        container.innerHTML = '';

        data.details.forEach((detail, index) => {
            const row = createDetailRow(index, detail);
            container.appendChild(row);
        });

        console.log('✅ Created', data.details.length, 'detail rows');

        setTimeout(() => {
            document.querySelectorAll('input[class*="modal-satuan-display-"]').forEach(input => {
                input.addEventListener('input', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    console.log('💰 Formatting modal satuan for row:', index);
                    formatModalSatuanInput(this, index);
                });
                
                const index = parseInt(input.getAttribute('data-index'));
                calculateRowProfit(index);
            });
            
            console.log('✅ Event listeners attached');
        }, 100);

        updateTotalProfit();

        console.log('✅ Form detail berhasil diisi!');
        
    } catch (error) {
        console.error('❌ Detail Error:', error);
        showError('Gagal memuat detail: ' + error.message);
        resetDetailInputs();
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.textContent = 'Simpan';
    }
}

// ===============================
// VALIDASI FORM SEBELUM SUBMIT
// ===============================
document.getElementById('formLaporan').addEventListener('submit', function(e) {
    const detailContainer = document.getElementById('detailContainer');
    const dataId = document.getElementById('data_id').value;
    
    if (!dataId) {
        e.preventDefault();
        showError('Silakan pilih data terlebih dahulu!');
        return false;
    }
    
    if (!detailContainer.children.length) {
        e.preventDefault();
        showError('Data detail belum dimuat!');
        return false;
    }
    
    console.log('✅ Form validation passed, submitting...');
    return true;
});

// ===============================
// INITIALIZE AFTER DOM READY
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM Ready - Laporan Create WITH DARK THEME');
    
    if (typeof bootstrap === 'undefined') {
        console.error('❌ Bootstrap not loaded!');
        alert('Error: Bootstrap JS tidak ditemukan!');
        return;
    }
    
    console.log('✅ Bootstrap detected');
    
    try {
        modalPilihData = new bootstrap.Modal(document.getElementById('modalPilihData'), {
            backdrop: 'static',
            keyboard: true,
            focus: true
        });
        console.log('✅ Bootstrap Modal initialized');
        
    } catch (error) {
        console.error('❌ Modal initialization failed:', error);
        alert('Error: Gagal menginisialisasi modal!');
        return;
    }
    
    const today = new Date().toISOString().split('T')[0];
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    if (tanggalInput) {
        tanggalInput.value = today;
        console.log('📅 Tanggal set:', today);
    }
    
    console.log('✅ Laporan Create Ready WITH DARK THEME!');
});
</script>
@endsection