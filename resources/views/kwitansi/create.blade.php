@extends('layouts.app')

@section('title', 'Tambah Kwitansi')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kwitansi-create.css') }}">
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

        /* 🔥 LEVEL 3: Target Specific Modal by ID */
        body.dark-theme #modalPilihData {
            background: rgba(15, 23, 42, 0.9) !important;
        }

        body.dark-theme #modalPilihData .modal-dialog {
            background: transparent !important;
        }

        body.dark-theme #modalPilihData .modal-content {
            background: #1e293b !important;
            background-color: #1e293b !important;
        }

        /* 🔥 LEVEL 4: Modal Header */
        body.dark-theme .modal-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-theme #modalPilihData .modal-header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        }

        body.dark-theme .modal-title {
            color: #ffffff !important;
        }

        /* 🔥 LEVEL 5: Modal Body - CRITICAL */
        body.dark-theme .modal-body {
            background: #1e293b !important;
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }

        body.dark-theme #modalPilihData .modal-body {
            background: #1e293b !important;
            background-color: #1e293b !important;
        }

        /* 🔥 LEVEL 6: Modal Footer */
        body.dark-theme .modal-footer {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-theme #modalPilihData .modal-footer {
            background: #0f172a !important;
            background-color: #0f172a !important;
        }

        /* 🔥 LEVEL 7: Button Close */
        body.dark-theme .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
        }

        /* 🔥 LEVEL 8: Secondary Button */
        body.dark-theme .modal-footer .btn-secondary {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
            border: none !important;
            color: white !important;
        }

        body.dark-theme .modal-footer .btn-secondary:hover {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%) !important;
        }

        /* ========================================
           DATA TABLE AREA DARK THEME - FORCE
           ======================================== */
        
        /* 🔥 Loading Spinner */
        body.dark-theme .loading-spinner {
            background: transparent !important;
            background-color: transparent !important;
            color: #e2e8f0 !important;
        }

        body.dark-theme .loading-spinner i {
            color: #60a5fa !important;
        }

        body.dark-theme .loading-spinner p {
            color: #94a3b8 !important;
        }

        /* 🔥 No Data Message */
        body.dark-theme .no-data {
            color: #94a3b8 !important;
            background: transparent !important;
            background-color: transparent !important;
        }

        body.dark-theme .no-data i {
            color: #475569 !important;
        }

        /* 🔥 Data Table Wrapper - CRITICAL */
        body.dark-theme .data-table-wrapper,
        body.dark-theme #dataTableWrapper {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        /* 🔥 Data Table */
        body.dark-theme .data-table {
            background: transparent !important;
            background-color: transparent !important;
        }

        body.dark-theme .data-table thead {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        }

        body.dark-theme .data-table thead th {
            color: #ffffff !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        body.dark-theme .data-table tbody {
            background: transparent !important;
            background-color: transparent !important;
        }

        body.dark-theme .data-table tbody td {
            color: #e2e8f0 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        body.dark-theme .data-table tbody tr {
            background: transparent !important;
            background-color: transparent !important;
        }

        body.dark-theme .data-table tbody tr:hover {
            background: #334155 !important;
            background-color: #334155 !important;
        }

        /* 🔥 Search & Sort Box */
        body.dark-theme .search-box input,
        body.dark-theme #modalSearchInput {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border: 2px solid #ffffff !important;
            color: #e2e8f0 !important;
        }

        body.dark-theme .search-box input::placeholder,
        body.dark-theme #modalSearchInput::placeholder {
            color: #64748b !important;
        }

        body.dark-theme .search-box input:focus,
        body.dark-theme #modalSearchInput:focus {
            border-color: #3b82f6 !important;
        }

        body.dark-theme .sort-box select,
        body.dark-theme #modalSortSelect {
            background: #0f172a !important;
            background-color: #0f172a !important;
            border: 2px solid #ffffff !important;
            color: #e2e8f0 !important;
        }

        body.dark-theme .sort-box select option,
        body.dark-theme #modalSortSelect option {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }

        /* 🔥 Button Pilih Row */
        body.dark-theme .btn-pilih-row {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        }

        body.dark-theme .btn-pilih-row:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
        }

        /* ========================================
           SCROLLBAR DARK THEME
           ======================================== */
        
        body.dark-theme .data-table-wrapper::-webkit-scrollbar {
            width: 8px;
        }

        body.dark-theme .data-table-wrapper::-webkit-scrollbar-track {
            background: #1e293b !important;
        }

        body.dark-theme .data-table-wrapper::-webkit-scrollbar-thumb {
            background: #475569 !important;
            border-radius: 4px;
        }

        body.dark-theme .data-table-wrapper::-webkit-scrollbar-thumb:hover {
            background: #64748b !important;
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

        /* ========================================
           SEARCH SORT CONTAINER
           ======================================== */
        
        body.dark-theme .search-sort-container {
            background: transparent !important;
        }
    </style>
@endsection

@section('content')
<main class="kwitansi-main">
    <div class="form-container">
        <h1 class="form-title">TAMBAH KWITANSI</h1>

        <form action="{{ route('kwitansi.store') }}" method="POST" id="formKwitansi">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="custom-label">Pilih Sumber</label>
                    <select name="Sumber_Tabel" id="Sumber_Tabel" class="custom-select" required @if($isKasir ?? false) disabled @endif>
                        <option value="">-- Pilih Sumber --</option>
                        @if(!($isKasir ?? false))
                            <option value="rabs" {{ (old('Sumber_Tabel', $prefillSumberTabel ?? '') === 'rabs') ? 'selected' : '' }}>RAB</option>
                        @endif
                        <option value="penjualan" {{ (old('Sumber_Tabel', $prefillSumberTabel ?? '') === 'penjualan' || ($isKasir ?? false)) ? 'selected' : '' }}>Penjualan</option>
                    </select>
                    @if($isKasir ?? false)
                        <input type="hidden" name="Sumber_Tabel" value="penjualan">
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="custom-label">Pilih Data</label>
                    <button type="button" id="btnPilihData" class="btn-pilih-data" disabled>
                        <span id="selectedDataText">-- Pilih Sumber Terlebih Dahulu --</span>
                    </button>
                    <input type="hidden" name="Id_Sumber" id="Id_Sumber" required>
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
                    <input type="text" id="displayIdKwitansi" class="custom-input" readonly placeholder="Auto dari sumber">
                </div>
            </div>

            {{-- 🔥 KOLOM TANGGAL DAN TOTAL - NAIK KE ATAS (MENGGANTIKAN POSISI NO & ID KWITANSI) --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="custom-label">Tanggal 📅</label>
                    <input type="date" name="Tanggal_Kwitansi" id="Tanggal_Kwitansi" class="custom-input" value="{{ old('Tanggal_Kwitansi', date('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="custom-label">Total</label>
                    <div class="rupiah-prefix">
                        <input type="text" id="displayTotal" class="custom-input" placeholder="Otomatis terisi" readonly>
                        <input type="hidden" name="Total" id="Total" required>
                    </div>
                    <small class="small-hint">Otomatis terisi saat memilih data</small>
                </div>
            </div>

            <div class="row g-3 mb-4 payment-row" id="paymentRow">
                <div class="col-md-3">
                    <label class="custom-label">Metode Pembayaran</label>
                    <select name="Metode_Pembayaran" id="Metode_Pembayaran" class="custom-select" required>
                        <option value="">-- Pilih Metode --</option>
                        <option value="Cash" {{ old('Metode_Pembayaran') === 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="QRIS" {{ old('Metode_Pembayaran') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        <option value="Transfer" {{ old('Metode_Pembayaran') === 'Transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Untuk Pembayaran</label>
                    <input type="text" name="Untuk_Pembayaran" id="Untuk_Pembayaran" class="custom-input" placeholder="Keterangan pembayaran" value="{{ old('Untuk_Pembayaran') }}">
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Total Pembayaran</label>
                    <div class="rupiah-prefix">
                        <input type="text" id="displayTotalPembayaran" class="custom-input" placeholder="0" value="{{ old('Total_Pembayaran') }}" required style="border-left: 4px solid #6c757d;">
                        <input type="hidden" name="Total_Pembayaran" id="Total_Pembayaran" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="custom-label">Status</label>
                    <input type="text" id="displayStatus" class="status-display" readonly value="DP 0%">
                    <input type="hidden" name="Status" id="Status" value="DP 0%">
                </div>
            </div>

            <input type="hidden" name="Sales" id="Sales" value="{{ old('Sales', 'Admin') }}">

            <div class="form-actions">
                <a href="{{ route('kwitansi.index') }}" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-simpan">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</main>

<!-- Modal Pop-up untuk Pilih Data -->
<div class="modal fade" id="modalPilihData" tabindex="-1" aria-labelledby="modalPilihDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihDataLabel">
                    <i class="fas fa-search me-2"></i>Pilih Data <span id="modalSumberType"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="search-sort-container">
                    <div class="search-box">
                        <input type="text" id="modalSearchInput" placeholder="Cari berdasarkan nama owner / sales..." autocomplete="off">
                    </div>
                    <div class="sort-box">
                        <select id="modalSortSelect">
                            <option value="">-- Urutkan --</option>
                            <option value="tertinggi">Total Tertinggi</option>
                            <option value="terendah">Total Terendah</option>
                        </select>
                    </div>
                </div>

                <div id="loadingData" class="loading-spinner" style="display:none;">
                    <i class="fas fa-spinner"></i>
                    <p class="mt-3">Memuat data...</p>
                </div>

                <div class="data-table-wrapper" id="dataTableWrapper" style="display:none;">
                    <table class="table data-table">
                        <thead id="tableHead"></thead>
                        <tbody id="dataTableBody"></tbody>
                    </table>
                </div>

                <div id="noDataMessage" class="no-data" style="display:none;">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
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
    function formatRupiah(angka, prefix = '') {
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
        return prefix + rupiah;
    }

    function parseRupiah(rupiah) {
        return parseFloat(rupiah.replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const isKasir = {{ ($isKasir ?? false) ? 'true' : 'false' }};
        const sumberSelect = document.getElementById('Sumber_Tabel');
        const btnPilihData = document.getElementById('btnPilihData');
        const selectedDataText = document.getElementById('selectedDataText');
        const hiddenIdSumber = document.getElementById('Id_Sumber');
        const displayTotal = document.getElementById('displayTotal');
        const hiddenTotal = document.getElementById('Total');
        const displayTotalPembayaran = document.getElementById('displayTotalPembayaran');
        const hiddenTotalPembayaran = document.getElementById('Total_Pembayaran');
        const displayStatusInput = document.getElementById('displayStatus');
        const hiddenStatusInput = document.getElementById('Status');
        const displayIdKwitansi = document.getElementById('displayIdKwitansi');
        const salesInput = document.getElementById('Sales');
        const paymentRow = document.getElementById('paymentRow');

        const modalPilihData = new bootstrap.Modal(document.getElementById('modalPilihData'));
        const modalSearchInput = document.getElementById('modalSearchInput');
        const modalSortSelect = document.getElementById('modalSortSelect');
        const loadingData = document.getElementById('loadingData');
        const dataTableWrapper = document.getElementById('dataTableWrapper');
        const dataTableBody = document.getElementById('dataTableBody');
        const tableHead = document.getElementById('tableHead');
        const noDataMessage = document.getElementById('noDataMessage');
        const modalSumberType = document.getElementById('modalSumberType');

        let searchTimeout;
        let currentSumber = '';

        // 🔥 FORCE DARK THEME ON MODAL SHOW
        const modalElement = document.getElementById('modalPilihData');
        if (modalElement) {
            modalElement.addEventListener('show.bs.modal', function () {
                if (document.body.classList.contains('dark-theme')) {
                    setTimeout(() => {
                        const elements = {
                            content: modalElement.querySelector('.modal-content'),
                            body: modalElement.querySelector('.modal-body'),
                            footer: modalElement.querySelector('.modal-footer'),
                            wrapper: document.getElementById('dataTableWrapper')
                        };
                        
                        if (elements.content) {
                            elements.content.style.setProperty('background', '#1e293b', 'important');
                            elements.content.style.setProperty('background-color', '#1e293b', 'important');
                        }
                        
                        if (elements.body) {
                            elements.body.style.setProperty('background', '#1e293b', 'important');
                            elements.body.style.setProperty('background-color', '#1e293b', 'important');
                        }
                        
                        if (elements.footer) {
                            elements.footer.style.setProperty('background', '#0f172a', 'important');
                            elements.footer.style.setProperty('background-color', '#0f172a', 'important');
                        }
                        
                        if (elements.wrapper) {
                            elements.wrapper.style.setProperty('background', '#0f172a', 'important');
                            elements.wrapper.style.setProperty('background-color', '#0f172a', 'important');
                        }
                        
                        console.log('🔥 Force dark theme applied to modal');
                    }, 50);
                }
            });
        }

        displayTotalPembayaran.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d,]/g, '');
            e.target.value = formatRupiah(value);
            hiddenTotalPembayaran.value = formatRupiah(value);
            updateStatusAndColor();
        });

        function updateStatusAndColor() {
            const total = parseRupiah(hiddenTotal.value);
            const totalPembayaran = parseRupiah(hiddenTotalPembayaran.value);

            displayStatusInput.style.background = '';
            displayStatusInput.style.borderColor = '';
            displayStatusInput.style.color = '';

            if (totalPembayaran <= 0 || total <= 0) {
                paymentRow.style.borderLeft = '4px solid #6c757d';
                displayTotalPembayaran.style.borderLeft = '4px solid #6c757d';
                displayStatusInput.value = 'DP 0%';
                displayStatusInput.className = 'status-display';
                hiddenStatusInput.value = 'DP 0%';
            } 
            else if (totalPembayaran > total) {
                paymentRow.style.borderLeft = '4px solid #dc3545';
                displayTotalPembayaran.style.borderLeft = '4px solid #dc3545';
                const persentase = Math.round((totalPembayaran / total) * 100);
                displayStatusInput.value = 'Lebih Bayar (' + persentase + '%)';
                displayStatusInput.className = 'status-display lebih-bayar';
                hiddenStatusInput.value = 'Lunas';
            }
            else if (totalPembayaran >= total) {
                paymentRow.style.borderLeft = '4px solid #0d6efd';
                displayTotalPembayaran.style.borderLeft = '4px solid #0d6efd';
                displayStatusInput.value = 'Lunas';
                displayStatusInput.className = 'status-display lunas';
                hiddenStatusInput.value = 'Lunas';
            }
            else {
                paymentRow.style.borderLeft = '4px solid #ffc107';
                displayTotalPembayaran.style.borderLeft = '4px solid #ffc107';
                const persentase = Math.round((totalPembayaran / total) * 100);
                displayStatusInput.value = 'DP ' + persentase + '%';
                displayStatusInput.className = 'status-display';
                hiddenStatusInput.value = 'DP ' + persentase + '%';
            }
        }

        sumberSelect.addEventListener('change', function() {
            currentSumber = this.value;
            if (currentSumber) {
                btnPilihData.disabled = false;
                selectedDataText.textContent = '-- Pilih Data --';
                btnPilihData.classList.remove('selected');
            } else {
                btnPilihData.disabled = true;
                selectedDataText.textContent = '-- Pilih Sumber Terlebih Dahulu --';
                btnPilihData.classList.remove('selected');
            }
            
            hiddenIdSumber.value = '';
            displayTotal.value = '';
            hiddenTotal.value = '';
            displayIdKwitansi.value = '';
            salesInput.value = 'Admin';
            updateStatusAndColor();
        });

        btnPilihData.addEventListener('click', function() {
            if (!currentSumber) {
                alert('Pilih sumber data terlebih dahulu!');
                return;
            }
            
            modalSumberType.textContent = currentSumber === 'rabs' ? 'RAB' : 'Penjualan';
            modalSearchInput.value = '';
            modalSortSelect.value = '';
            modalPilihData.show();
            loadModalData();
        });

        modalSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                loadModalData();
            }, 500);
        });

        modalSortSelect.addEventListener('change', function() {
            loadModalData();
        });

        async function loadModalData() {
            const search = modalSearchInput.value.trim();
            const sort = modalSortSelect.value;

            loadingData.style.display = 'block';
            dataTableWrapper.style.display = 'none';
            noDataMessage.style.display = 'none';

            let url = '';
            if (currentSumber === 'rabs') {
                url = '{{ route("kwitansi.getRabData") }}';
            } else if (currentSumber === 'penjualan') {
                url = '{{ route("kwitansi.getPenjualanData") }}';
            }

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (sort) params.append('sort', sort);

            try {
                const response = await fetch(url + '?' + params.toString());
                const result = await response.json();

                loadingData.style.display = 'none';

                if (result.success && result.data && result.data.length > 0) {
                    renderTable(result.data);
                    dataTableWrapper.style.display = 'block';
                } else {
                    noDataMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading modal data:', error);
                loadingData.style.display = 'none';
                noDataMessage.style.display = 'block';
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        }

        function renderTable(data) {
            if (currentSumber === 'rabs') {
                tableHead.innerHTML = `
                    <tr>
                        <th>ID RAB</th>
                        <th>No RAB</th>
                        <th>Nama Pekerjaan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                `;
            } else if (currentSumber === 'penjualan') {
                tableHead.innerHTML = `
                    <tr>
                        <th>ID Penjualan</th>
                        <th>Nama Sales</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                `;
            }

            let html = '';
            data.forEach(function(item) {
                if (currentSumber === 'rabs') {
                    html += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.no_rab}</td>
                            <td>${item.nama_pekerjaan}</td>
                            <td>Rp ${formatRupiah(item.total.toString())}</td>
                            <td>
                                <button type="button" class="btn-pilih-row" onclick="selectData(${item.id}, '${item.no_rab}', ${item.total}, 'RAB #${item.no_rab} - ${item.nama_pekerjaan.replace(/'/g, "\\'")}', '')">
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    `;
                } else if (currentSumber === 'penjualan') {
                    html += `
                        <tr>
                            <td>${item.id}</td>
                            <td>${item.nama_sales}</td>
                            <td>${formatDate(item.tanggal)}</td>
                            <td>Rp ${formatRupiah(item.total.toString())}</td>
                            <td>
                                <button type="button" class="btn-pilih-row" onclick="selectData(${item.id}, '${item.id}', ${item.total}, 'Penjualan #${item.id} - ${item.nama_sales.replace(/'/g, "\\'")}', '${item.nama_sales.replace(/'/g, "\\'")}')">
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
        }

        window.selectData = function(id, idDisplay, total, displayText, sales) {
            hiddenIdSumber.value = id;
            displayIdKwitansi.value = idDisplay;
            displayTotal.value = formatRupiah(total.toString());
            hiddenTotal.value = formatRupiah(total.toString());
            selectedDataText.textContent = displayText;
            btnPilihData.classList.add('selected');

            if (currentSumber === 'penjualan' && sales) {
                salesInput.value = sales;
            } else {
                salesInput.value = 'Admin';
            }
            
            updateStatusAndColor();
            modalPilihData.hide();
        };

        if (isKasir) {
            sumberSelect.value = 'penjualan';
            currentSumber = 'penjualan';
            btnPilihData.disabled = false;
            selectedDataText.textContent = '-- Pilih Data --';
        }

        document.getElementById('formKwitansi').addEventListener('submit', function(e) {
            const total = parseRupiah(hiddenTotal.value);
            const totalPembayaran = parseRupiah(hiddenTotalPembayaran.value);

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

            if (totalPembayaran > total) {
                const lebihBayar = totalPembayaran - total;
                const konfirmasi = confirm(
                    'PERINGATAN!\n\n' +
                    'Total: Rp ' + total.toLocaleString('id-ID') + '\n' +
                    'Pembayaran: Rp ' + totalPembayaran.toLocaleString('id-ID') + '\n' +
                    'Lebih Bayar: Rp ' + lebihBayar.toLocaleString('id-ID') + '\n\n' +
                    'Pembayaran melebihi total tagihan. Apakah Anda yakin ingin melanjutkan?'
                );
                
                if (!konfirmasi) {
                    e.preventDefault();
                    return false;
                }
            }

            const minimumDP = total * 0.1;
            if (totalPembayaran < minimumDP) {
                e.preventDefault();
                alert(
                    'Pembayaran terlalu kecil!\n\n' +
                    'Minimum pembayaran: Rp ' + minimumDP.toLocaleString('id-ID') + ' (10% dari total)\n' +
                    'Pembayaran Anda: Rp ' + totalPembayaran.toLocaleString('id-ID')
                );
                return false;
            }

            return true;
        });

        console.log('✅ Tambah Kwitansi with NUCLEAR Dark Theme loaded!');
        console.log('🔥 Force override enabled for modal backgrounds');
    });
</script>
@endsection