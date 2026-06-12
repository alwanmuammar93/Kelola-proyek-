@extends('layouts.app')

@section('title', 'Kelola Penjualan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/penjualan-style.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<style>
    /* ========== KELOLA PENJUALAN SPECIFIC STYLES ========== */
    
    /* Main Content */
    .penjualan-main {
        margin-left: 0;
        padding: 2rem;
        padding-top: 66px;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    /* Dark Theme Main */
    body.dark-theme .penjualan-main {
        background-color: #0f172a;
    }

    /* Dashboard Title */
    .dashboard-title {
        color: #1a1f71;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 30px;
        text-transform: uppercase;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    /* Dark Theme Title */
    body.dark-theme .dashboard-title {
        color: #60a5fa;
        text-shadow: 0 2px 8px rgba(96, 165, 250, 0.3);
    }

    /* Control Bar */
    .control-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    /* Search Box */
    .search-box {
        position: relative;
        flex: 1;
        max-width: 450px;
        min-width: 300px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    /* Dark Theme Search Icon */
    body.dark-theme .search-icon {
        color: #64748b;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #ffd93d;
        border-radius: 30px;
        font-size: 1rem;
        background-color: #fff;
        color: #212529;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #1a1a4d;
        box-shadow: 0 0 0 3px rgba(26, 26, 77, 0.1);
    }

    .search-input::placeholder {
        color: #adb5bd;
    }

    /* Dark Theme Search Input */
    body.dark-theme .search-input {
        background-color: #1e293b;
        border-color: #64748b;
        color: #e2e8f0;
    }

    body.dark-theme .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    body.dark-theme .search-input::placeholder {
        color: #64748b;
    }

    /* ============================================
       SORT DROPDOWN - STRUKTUR BARU
       ============================================ */

    .sort-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .sort-btn {
        background: #dc3545;
        color: white;
        padding: 12px 25px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
    }

    .sort-btn:hover {
        background: #bb2d3b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    /* Dark Theme Sort Button */
    body.dark-theme .sort-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    body.dark-theme .sort-btn:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.5);
    }

    .sort-dropdown {
        position: absolute;
        top: 110%;
        right: 0;
        background: white;
        border-radius: 8px;
        min-width: 200px;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        z-index: 1000;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Dark Theme Dropdown */
    body.dark-theme .sort-dropdown {
        background: #1e293b;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sort-dropdown.show {
        display: block;
        animation: fadeInDown 0.3s ease;
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

    .sort-item {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .sort-item:last-child {
        border-bottom: none;
    }

    .sort-item:hover {
        background: #f8f9fa;
        padding-left: 20px;
    }

    .sort-item.active {
        background: #ffd93d;
        font-weight: bold;
        color: #1a1a4d;
    }

    /* Dark Theme Sort Items */
    body.dark-theme .sort-item {
        color: #e2e8f0;
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    body.dark-theme .sort-item:hover {
        background: #334155;
    }

    body.dark-theme .sort-item.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #ffffff;
    }

    /* Tombol Tambah Penjualan */
    .btn-tambah-penjualan {
        padding: 12px 25px;
        background-color: #ffd93d;
        color: #1a1a4d;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(255, 217, 61, 0.2);
    }

    .btn-tambah-penjualan:hover {
        background-color: #1a1a4d;
        color: #ffd93d;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(26, 26, 77, 0.3);
        text-decoration: none;
    }

    /* Dark Theme Tambah Button */
    body.dark-theme .btn-tambah-penjualan {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    body.dark-theme .btn-tambah-penjualan:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5);
    }

    /* Table Container */
    .table-container {
        background-color: white;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    /* Dark Theme Table Container */
    body.dark-theme .table-container {
        background-color: #1e293b;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Table Styling */
    .penjualan-table {
        width: 100%;
        border-collapse: collapse;
    }

    .penjualan-table thead {
        background-color: #1a1a4d;
        color: white;
    }

    /* Dark Theme Table Header */
    body.dark-theme .penjualan-table thead {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    }

    .penjualan-table thead th {
        padding: 15px 10px;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .penjualan-table tbody td {
        padding: 15px 10px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
        color: #212529;
        transition: all 0.3s ease;
    }

    /* Dark Theme Table Body */
    body.dark-theme .penjualan-table tbody td {
        border-bottom-color: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }

    .penjualan-table tbody tr:last-child td {
        border-bottom: none;
    }

    .penjualan-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Dark Theme Table Row Hover */
    body.dark-theme .penjualan-table tbody tr:hover {
        background-color: #334155;
    }

    /* Sales Info */
    .sales-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sales-info i {
        font-size: 1.1rem;
    }

    body.dark-theme .sales-info i {
        color: #60a5fa !important;
    }

    /* Rincian Detail */
    .rincian-detail {
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    body.dark-theme .rincian-detail {
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }

    .rincian-info {
        margin-top: 5px;
    }

    .rincian-info small {
        font-size: 0.85rem;
    }

    body.dark-theme .rincian-info small {
        color: #94a3b8 !important;
    }

    body.dark-theme .rincian-info strong {
        color: #cbd5e0;
    }

    /* Text Utilities Dark Theme */
    body.dark-theme .text-muted {
        color: #64748b !important;
    }

    body.dark-theme .text-primary {
        color: #60a5fa !important;
    }

    /* Table Actions */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: nowrap;
        align-items: center;
    }

    .table-actions form {
        display: inline;
        margin: 0;
    }

    .btn-edit,
    .btn-hapus {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        white-space: nowrap;
    }

    .btn-edit {
        background-color: #ffd93d;
        color: #1a1a4d;
        box-shadow: 0 2px 4px rgba(255, 217, 61, 0.2);
    }

    .btn-edit:hover {
        background-color: #ffc107;
        color: #1a1a4d;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(255, 217, 61, 0.3);
        text-decoration: none;
    }

    /* Dark Theme Edit Button */
    body.dark-theme .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    body.dark-theme .btn-edit:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: #ffffff;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.5);
    }

    .btn-hapus {
        background-color: #dc3545;
        color: white;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
    }

    .btn-hapus:hover {
        background-color: #bb2d3b;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    /* Dark Theme Hapus Button */
    body.dark-theme .btn-hapus {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    body.dark-theme .btn-hapus:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.5);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    body.dark-theme .empty-state {
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    .empty-state-icon i {
        font-size: 3rem;
    }

    body.dark-theme .empty-state-icon {
        color: #475569;
    }

    .empty-state-text {
        font-size: 1.1rem;
        font-weight: 500;
        margin-top: 15px;
    }

    /* Alerts */
    .alert {
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .alert i {
        font-size: 1.2rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1e7dd 0%, #c3e6cb 100%);
        border: 1px solid #badbcc;
        color: #0f5132;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border: 1px solid #f5c2c7;
        color: #842029;
        border-left: 4px solid #dc3545;
    }

    /* Dark Theme Alerts */
    body.dark-theme .alert-success {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
        color: #86efac;
        border-color: #065f46;
        border-left-color: #10b981;
    }

    body.dark-theme .alert-danger {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
        color: #fca5a5;
        border-color: #991b1b;
        border-left-color: #ef4444;
    }

    .btn-close {
        background: transparent;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
        transition: color 0.3s ease;
    }

    body.dark-theme .btn-close {
        color: #e2e8f0;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .penjualan-main {
            padding: 1.5rem;
        }

        .control-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            max-width: 100%;
            min-width: 100%;
        }

        .sort-wrapper,
        .btn-tambah-penjualan {
            width: 100%;
            justify-content: center;
        }

        .table-container {
            overflow-x: auto;
        }

        .penjualan-table {
            min-width: 900px;
        }
    }

    @media (max-width: 768px) {
        .penjualan-main {
            padding: 1rem;
        }

        .dashboard-title {
            font-size: 1.5rem;
        }

        .penjualan-table thead th,
        .penjualan-table tbody td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }

        .btn-edit,
        .btn-hapus {
            font-size: 0.7rem;
            padding: 6px 10px;
            min-width: 55px;
        }
    }

    @media (max-width: 576px) {
        .dashboard-title {
            font-size: 1.25rem;
        }

        .search-input {
            font-size: 0.9rem;
            padding: 10px 15px 10px 40px;
        }

        .sort-btn,
        .btn-tambah-penjualan {
            font-size: 0.9rem;
            padding: 10px 20px;
        }

        .penjualan-table thead th,
        .penjualan-table tbody td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }

        .table-actions {
            gap: 4px;
        }

        .btn-edit,
        .btn-hapus {
            font-size: 0.65rem;
            padding: 5px 8px;
            min-width: 50px;
        }
    }

    /* ========== SMOOTH TRANSITIONS ========== */
    .penjualan-main,
    .dashboard-title,
    .search-input,
    .search-icon,
    .sort-btn,
    .sort-dropdown,
    .sort-item,
    .btn-tambah-penjualan,
    .table-container,
    .penjualan-table tbody td,
    .btn-edit,
    .btn-hapus,
    .empty-state,
    .empty-state-icon,
    .alert,
    .btn-close {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<main class="penjualan-main">

    <h1 class="dashboard-title">DASHBOARD KELOLA PENJUALAN</h1>

    {{-- 🔔 Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center alert-dismissible fade show">
            <div>
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔔 Notifikasi error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Control Bar: Search, Sort, Add Button --}}
    <div class="control-bar">
        {{-- Search Box - Cari Nama Sales --}}
        <div class="search-box">
            <i class="bi bi-search search-icon"></i>
            <input 
                type="text" 
                class="search-input" 
                id="searchPenjualan" 
                placeholder="Cari Nama Sales"
                onkeyup="searchTable()"
            >
        </div>

        {{-- Sort Dropdown - HANYA 2 OPSI --}}
        <div class="sort-wrapper">
            <button class="sort-btn" id="sortButton">
                Sort by
                <i class="bi bi-chevron-down ms-2"></i>
            </button>
            <div class="sort-dropdown" id="sortDropdown">
                <a href="{{ route('penjualan.index', ['sort_by' => 'total_tertinggi']) }}" 
                   class="sort-item {{ request('sort_by', 'total_tertinggi') == 'total_tertinggi' ? 'active' : '' }}">
                    Total Tertinggi
                </a>

                <a href="{{ route('penjualan.index', ['sort_by' => 'total_terendah']) }}" 
                   class="sort-item {{ request('sort_by') == 'total_terendah' ? 'active' : '' }}">
                    Total Terendah
                </a>
            </div>
        </div>

        {{-- Tombol Tambah Penjualan --}}
        <a href="{{ route('penjualan.create') }}" class="btn-tambah-penjualan">
            <i class="bi bi-plus-lg me-2"></i>
            Tambah Penjualan
        </a>
    </div>

    {{-- Table Container --}}
    <div class="table-container">
        <table class="penjualan-table" id="penjualanTable">
            <thead>
                <tr>
                    <th style="width: 80px;">NO</th>
                    <th style="width: 180px;">NAMA SALES</th>
                    <th>RINCIAN</th>
                    <th style="width: 180px;">TOTAL</th>
                    <th style="width: 180px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualans as $index => $p)
                    <tr data-id="{{ $p->id_penjualan }}" data-total="{{ $p->total }}">
                        {{-- Nomor Urut --}}
                        <td class="text-center">{{ $index + 1 }}</td>
                        
                        {{-- Nama Sales --}}
                        <td>
                            <div class="sales-info">
                                <i class="bi bi-person-circle text-primary"></i>
                                <strong>{{ $p->nama_sales }}</strong>
                            </div>
                        </td>
                        
                        {{-- Rincian --}}
                        <td>
                            @if($p->details && $p->details->count() > 0)
                                @foreach($p->details as $detail)
                                    <div class="rincian-detail" style="{{ !$loop->last ? 'border-bottom: 1px solid #e0e0e0;' : '' }}">
                                        <strong>{{ $detail->rincian }}</strong>
                                        <div class="rincian-info">
                                            <small class="text-muted">
                                                <i class="bi bi-box"></i> Jumlah: <strong>{{ $detail->jumlah }}</strong>
                                                &nbsp;|&nbsp;
                                                <i class="bi bi-tag"></i> Harga Satuan: <strong>{{ $detail->harga_satuan_format }}</strong>
                                                &nbsp;|&nbsp;
                                                <i class="bi bi-calculator"></i> Subtotal: <strong>{{ $detail->subtotal_format }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-muted"><em>Tidak ada rincian</em></span>
                            @endif
                        </td>
                        
                        {{-- Total --}}
                        <td class="text-end">
                            <strong class="text-primary">{{ $p->total_format }}</strong>
                        </td>
                        
                        {{-- Aksi --}}
                        <td>
                            <div class="table-actions">
                                {{-- Edit Button --}}
                                <a href="{{ route('penjualan.edit', $p->id_penjualan) }}" class="btn-edit" title="Edit Penjualan">
                                    Edit
                                </a>

                                {{-- Hapus Button --}}
                                <form action="{{ route('penjualan.destroy', $p->id_penjualan) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin hapus data ini? Semua rincian akan terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-hapus" title="Hapus Penjualan">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="empty-state-text">
                                Belum ada data penjualan yang ditambahkan.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>
@endsection

@section('scripts')
<script>
    // ========================================
    // 1. TOGGLE SORT DROPDOWN
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        const sortButton = document.getElementById('sortButton');
        const sortDropdown = document.getElementById('sortDropdown');

        if (sortButton && sortDropdown) {
            sortButton.addEventListener('click', function(e) {
                e.stopPropagation();
                sortDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!sortButton.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.remove('show');
                }
            });
        }

        // Auto dismiss alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });

    // ========================================
    // 2. SEARCH FUNCTION - Cari berdasarkan NAMA SALES
    // ========================================
    function searchTable() {
        const input = document.getElementById('searchPenjualan');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('penjualanTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td')[1]; // Index 1 = Kolom Nama Sales
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    }

    console.log('✅ Dashboard Kelola Penjualan with Dark Theme Support loaded successfully!');
</script>
@endsection