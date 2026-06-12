@extends('layouts.app')

@section('title', 'Kelola Laporan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/laporan-styles.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    /* ========================================
       CRITICAL FIX: Proper Laporan Main Styling
       ======================================== */
    .laporan-main {
        margin-left: 0 !important;
        padding: 2rem;
        padding-top: 90px !important;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    /* Dark Theme Main */
    body.dark-theme .laporan-main {
        background-color: #0f172a;
    }

    /* ========================================
       DARK THEME OVERRIDES
       ======================================== */
    
    /* Container */
    body.dark-theme .laporan-container {
        background: transparent !important;
    }

    /* Title */
    body.dark-theme .laporan-title {
        color: #60a5fa !important;
        text-shadow: 0 2px 8px rgba(96, 165, 250, 0.3) !important;
    }

    /* Alerts */
    body.dark-theme .alert-success {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
        border-color: #10b981 !important;
        color: #d1fae5 !important;
    }

    body.dark-theme .alert-danger {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%) !important;
        border-color: #dc2626 !important;
        color: #fca5a5 !important;
    }

    body.dark-theme .btn-close {
        filter: invert(1) brightness(2);
    }

    /* Search Input */
    body.dark-theme .search-input {
        background: #0f172a !important;
        border-color: #ffffff !important;
        color: #e2e8f0 !important;
    }

    body.dark-theme .search-input::placeholder {
        color: #64748b !important;
    }

    body.dark-theme .search-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2) !important;
    }

    body.dark-theme .search-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
    }

    body.dark-theme .search-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    }

    /* Sort Button */
    body.dark-theme .sort-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border: none !important;
    }

    body.dark-theme .sort-btn:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    }

    /* Sort Dropdown */
    body.dark-theme .sort-dropdown {
        background-color: #1e293b !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
    }

    body.dark-theme .sort-item {
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .sort-item:hover {
        background-color: #334155 !important;
        color: #60a5fa !important;
    }

    /* Button Tambah */
    body.dark-theme .btn-tambah {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: none !important;
    }

    body.dark-theme .btn-tambah:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5) !important;
    }

    /* Table Wrapper */
    body.dark-theme .laporan-table-wrapper {
        background: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5) !important;
    }

    /* Table */
    body.dark-theme .laporan-table {
        background: transparent !important;
    }

    body.dark-theme .laporan-table thead {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }

    body.dark-theme .laporan-table thead th {
        color: #ffffff !important;
        border-bottom-color: rgba(255, 255, 255, 0.2) !important;
    }

    body.dark-theme .laporan-table tbody tr {
        background: #1e293b !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    body.dark-theme .laporan-table tbody tr:hover {
        background: #334155 !important;
    }

    body.dark-theme .laporan-table tbody td {
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Empty State */
    body.dark-theme .empty-state-icon {
        color: #475569 !important;
    }

    body.dark-theme .empty-state-title {
        color: #cbd5e0 !important;
    }

    body.dark-theme .empty-state-text {
        color: #64748b !important;
    }

    body.dark-theme .btn-tambah-first {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    }

    body.dark-theme .btn-tambah-first:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5) !important;
    }

    body.dark-theme .search-empty-icon {
        color: #475569 !important;
    }

    body.dark-theme .search-empty-text {
        color: #94a3b8 !important;
    }

    /* Action Buttons */
    body.dark-theme .btn-edit {
        background-color: #fbbf24 !important;
        color: #1a1a4d !important;
    }

    body.dark-theme .btn-edit:hover {
        background-color: #f59e0b !important;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.5) !important;
    }

    body.dark-theme .btn-hapus {
        background-color: #ef4444 !important;
    }

    body.dark-theme .btn-hapus:hover {
        background-color: #dc2626 !important;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.5) !important;
    }

    /* ========== FIX COLUMN ALIGNMENT ========== */
    .laporan-table thead th,
    .laporan-table tbody td {
        vertical-align: middle !important;
        padding: 15px 12px !important;
    }

    /* Nama Laporan - SINGLE LINE dengan ellipsis */
    .nama-laporan {
        max-width: 350px !important;
        min-width: 220px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        line-height: 1.5 !important;
    }

    .nama-laporan strong {
        display: inline-block !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 100% !important;
    }

    /* Text alignment fixes */
    .text-center {
        text-align: center !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    .text-end {
        text-align: right !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }

    /* Total Profit column - prevent wrap */
    .laporan-table tbody td.text-end strong {
        white-space: nowrap !important;
        display: inline-block !important;
    }

    /* ========== PAKSA HORIZONTAL BUTTONS ========== */
    .action-buttons {
        display: flex !important;
        flex-direction: row !important;
        gap: 6px !important;
        justify-content: center !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
    }

    .action-buttons form {
        display: inline !important;
        margin: 0 !important;
    }

    .btn-edit,
    .btn-hapus {
        padding: 7px 14px !important;
        border: none !important;
        border-radius: 6px !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-block !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .btn-edit {
        background-color: #ffd93d !important;
        color: #1a1a4d !important;
    }

    .btn-edit:hover {
        background-color: #ffc107 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 2px 8px rgba(255, 217, 61, 0.3) !important;
        color: #1a1a4d !important;
        text-decoration: none !important;
    }

    .btn-hapus {
        background-color: #dc3545 !important;
        color: white !important;
    }

    .btn-hapus:hover {
        background-color: #bb2d3b !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3) !important;
    }

    /* ========== EMPTY STATE STYLING (FIXED) ========== */
    .empty-state {
        padding: 60px 20px !important;
    }

    .empty-state-icon {
        font-size: 4rem !important;
        color: #cbd5e1 !important;
        margin-bottom: 1.5rem !important;
    }

    .empty-state-title {
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        margin-bottom: 0.5rem !important;
    }

    .empty-state-text {
        font-size: 0.9rem !important;
        color: #94a3b8 !important;
        margin-bottom: 1.5rem !important;
    }

    /* Button Tambah Laporan Pertama (FIXED - Smaller & Better) */
    .btn-tambah-first {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 10px 20px !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        text-decoration: none !important;
        border-radius: 8px !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3) !important;
    }

    .btn-tambah-first:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
        color: white !important;
        text-decoration: none !important;
    }

    .btn-tambah-first i {
        font-size: 1rem !important;
    }

    /* Search No Results State */
    .search-empty-state {
        padding: 60px 20px !important;
    }

    .search-empty-icon {
        font-size: 3.5rem !important;
        color: #cbd5e1 !important;
        margin-bottom: 1rem !important;
    }

    .search-empty-text {
        font-size: 1rem !important;
        color: #64748b !important;
        margin: 0 !important;
    }

    /* Sort Dropdown Styling */
    .sort-wrapper {
        position: relative;
        display: inline-block;
    }

    .sort-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 8px;
        background-color: white;
        min-width: 200px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        z-index: 1000;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    .sort-dropdown.show {
        display: block;
        animation: fadeInDown 0.3s ease;
    }

    .sort-item {
        display: block;
        padding: 12px 16px;
        text-decoration: none;
        color: #333;
        font-size: 14px;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .sort-item:last-child {
        border-bottom: none;
    }

    .sort-item:hover {
        background-color: #f8f9fa;
        color: #dc3545;
        padding-left: 20px;
    }

    .sort-btn {
        cursor: pointer;
        user-select: none;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .sort-dropdown {
            right: 0;
            left: auto;
        }
    }
</style>
@endsection

@section('content')
<main class="laporan-main">

    <div class="laporan-container">
        
        <div class="laporan-header-title">
            <h1 class="laporan-title">DASHBOARD KELOLA LAPORAN</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="laporan-controls">
            
            <div class="search-wrapper">
                <div class="search-form">
                    <input type="text" class="search-input" id="searchInput"
                        placeholder="Cari Nama Laporan" onkeyup="searchTable()">
                    <button type="button" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="sort-wrapper">
                <button class="sort-btn" id="sortButton">
                    Sort by <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <div class="sort-dropdown" id="sortDropdown">
                    <a href="#" class="sort-item" onclick="sortByProfit('high'); return false;">Profit Tertinggi</a>
                    <a href="#" class="sort-item" onclick="sortByProfit('low'); return false;">Profit Terendah</a>
                    <a href="#" class="sort-item" onclick="sortByProfit(''); return false;">Default</a>
                </div>
            </div>

            @if(Route::has('laporan.create'))
                <a href="{{ route('laporan.create') }}" class="btn-tambah">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Laporan
                </a>
            @endif
        </div>

        <div class="laporan-table-wrapper">
            <table class="laporan-table" id="laporanTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th style="min-width: 250px; max-width: 400px;">Nama Laporan</th>
                        <th style="width: 140px; white-space: nowrap;">Tanggal</th>
                        <th style="width: 180px; white-space: nowrap;">Total Profit</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($laporan as $index => $l)
                        <tr data-profit="{{ $l->total_profit }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="nama-laporan"><strong>{{ $l->nama_laporan }}</strong></td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($l->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <strong style="color: {{ $l->total_profit >= 0 ? '#198754' : '#dc3545' }}">
                                    Rp {{ number_format($l->total_profit, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td class="text-center">
                                <div class="action-buttons">

                                    @if(Route::has('laporan.edit'))
                                        <a href="{{ route('laporan.edit', $l->id) }}" class="btn-edit">
                                            Edit
                                        </a>
                                    @endif

                                    @if(Route::has('laporan.destroy'))
                                        <form action="{{ route('laporan.destroy', $l->id) }}"
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirmDelete('{{ $l->nama_laporan }}', {{ $l->total_profit }}, {{ $l->details_count ?? 0 }})">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-hapus">Hapus</button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="empty-state">
                                <div>
                                    <i class="bi bi-inbox empty-state-icon"></i>
                                    <div class="empty-state-title">Belum Ada Data Laporan</div>
                                    <p class="empty-state-text">Mulai tambahkan laporan pertama Anda untuk memulai tracking profit</p>
                                    
                                    @if(Route::has('laporan.create'))
                                        <a href="{{ route('laporan.create') }}" class="btn-tambah-first">
                                            <i class="bi bi-plus-circle"></i>
                                            <span>Tambah Laporan Pertama</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</main>
@endsection

@section('scripts')
<script>
    // TOGGLE DROPDOWN SORT BY
    document.addEventListener('DOMContentLoaded', function() {
        const sortButton = document.getElementById('sortButton');
        const sortDropdown = document.getElementById('sortDropdown');

        if (sortButton && sortDropdown) {
            sortButton.addEventListener('click', function(e) {
                e.stopPropagation();
                sortDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!sortButton.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.remove('show');
                }
            });
        }
    });

    // FUNCTION SORTING BY PROFIT
    function sortByProfit(order) {
        const tableBody = document.getElementById('tableBody');
        const rows = Array.from(tableBody.querySelectorAll('tr:not(#emptyRow)'));
        
        if (rows.length === 0) return;

        document.getElementById('sortDropdown').classList.remove('show');

        if (order === '') {
            location.reload();
            return;
        }

        rows.sort((a, b) => {
            const profitA = parseFloat(a.getAttribute('data-profit')) || 0;
            const profitB = parseFloat(b.getAttribute('data-profit')) || 0;
            
            if (order === 'high') {
                return profitB - profitA;
            } else {
                return profitA - profitB;
            }
        });

        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
            tableBody.appendChild(row);
        });
    }

    // FUNCTION SEARCH TABLE (UPDATED - NO NOTA REMOVED)
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const tbody = document.getElementById('tableBody');
        const tr = tbody.getElementsByTagName('tr');
        let visibleCount = 0;

        for (let i = 0; i < tr.length; i++) {
            if (tr[i].id === 'emptyRow') continue;

            const tdNama = tr[i].querySelector('.nama-laporan');

            if (tdNama) {
                const txtNama = tdNama.textContent || tdNama.innerText;
                
                if (txtNama.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                    visibleCount++;
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }

        const emptyRow = document.getElementById('emptyRow');
        if (visibleCount === 0 && !emptyRow) {
            const newEmptyRow = document.createElement('tr');
            newEmptyRow.id = 'emptyRow';
            newEmptyRow.innerHTML = `
                <td colspan="5" class="search-empty-state">
                    <div>
                        <i class="bi bi-search search-empty-icon"></i>
                        <p class="search-empty-text">Tidak ada data yang cocok dengan pencarian "${input.value}"</p>
                    </div>
                </td>
            `;
            tbody.appendChild(newEmptyRow);
        } else if (visibleCount > 0 && emptyRow && emptyRow.querySelector('.search-empty-state')) {
            emptyRow.remove();
        }
    }

    // FUNCTION CONFIRM DELETE (UPDATED - NO NOTA REMOVED)
    function confirmDelete(namaLaporan, totalProfit, detailsCount) {
        const profitFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(totalProfit);

        const detailText = detailsCount > 0 ? 
            `\n\n⚠️ Laporan ini memiliki ${detailsCount} detail transaksi yang juga akan dihapus!` : '';

        const message = `Apakah Anda yakin ingin menghapus laporan ini?\n\n` +
                       `📋 Nama: ${namaLaporan}\n` +
                       `💰 Total Profit: ${profitFormatted}` +
                       detailText;

        return confirm(message);
    }

    // AUTO DISMISS ALERT
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    console.log('✅ Dashboard Kelola Laporan loaded WITH DARK THEME SUPPORT!');
</script>
@endsection