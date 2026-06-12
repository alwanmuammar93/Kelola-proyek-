@extends('layouts.app')

@section('title', 'Kelola Proyek')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/proyek-style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* ========== KELOLA PROYEK SPECIFIC STYLES ========== */
    
    /* Main Content */
    .proyek-main {
        margin-left: 0;
        padding: 2rem;
        padding-top: 66px;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    /* Dark Theme Main */
    body.dark-theme .proyek-main {
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
        transition: color 0.3s ease;
    }

    /* Dark Theme Title */
    body.dark-theme .dashboard-title {
        color: #60a5fa;
        text-shadow: 0 2px 8px rgba(96, 165, 250, 0.3);
    }

    /* Control Bar - GRID LAYOUT */
    .control-bar {
        display: grid;
        grid-template-columns: auto auto auto;
        gap: 15px;
        margin-bottom: 25px;
        align-items: center;
        justify-content: flex-start;
    }

    /* Search Box */
    .search-box {
        position: relative;
        width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 16px;
        pointer-events: none;
        transition: color 0.3s ease;
    }

    body.dark-theme .search-icon {
        color: #64748b;
    }

    .search-input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #ffd93d;
        border-radius: 50px;
        font-size: 1rem;
        background-color: #fff;
        transition: all 0.3s ease;
        color: #212529;
    }

    .search-input:focus {
        outline: none;
        border-color: #ffb800;
        box-shadow: 0 0 0 3px rgba(255, 217, 61, 0.2);
    }

    .search-input::placeholder {
        color: #adb5bd;
    }

    /* Dark Theme Search */
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
       SORT DROPDOWN - STRUKTUR BARU (Seperti Penjualan)
       ============================================ */

    .sort-wrapper {
        position: relative;
        flex-shrink: 0;
        min-width: 180px;
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
        width: 100%;
        justify-content: center;
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
        min-width: 220px;
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
        cursor: pointer;
        background: white;
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
        background: #1e293b;
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    body.dark-theme .sort-item:hover {
        background: #334155;
    }

    body.dark-theme .sort-item.active {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #ffffff;
    }

    /* Tombol Tambah Proyek */
    .btn-tambah-proyek {
        padding: 12px 24px;
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
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
        min-width: 180px;
    }

    .btn-tambah-proyek:hover {
        background-color: #ffb800;
        color: #1a1a4d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 217, 61, 0.4);
        text-decoration: none;
    }

    .btn-tambah-proyek i {
        font-size: 16px;
    }

    /* Dark Theme Tambah Button */
    body.dark-theme .btn-tambah-proyek {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    body.dark-theme .btn-tambah-proyek:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5);
    }

    /* Table Container */
    .table-container {
        background-color: white;
        border-radius: 12px;
        overflow-x: auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
    .proyek-table {
        width: 100%;
        border-collapse: collapse;
    }

    .proyek-table thead {
        background-color: #1a1a4d;
        color: white;
    }

    /* Dark Theme Table Header */
    body.dark-theme .proyek-table thead {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
    }

    .proyek-table thead th {
        padding: 18px 15px;
        text-align: center;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .proyek-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
        transition: all 0.3s ease;
        color: #212529;
    }

    /* Dark Theme Table Body */
    body.dark-theme .proyek-table tbody td {
        border-bottom-color: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }

    .proyek-table tbody td:nth-child(1) {
        text-align: center;
        font-weight: 600;
    }

    .proyek-table tbody td:nth-child(3) {
        text-align: center;
    }

    .proyek-table tbody td:nth-child(4) {
        text-align: center;
    }

    .proyek-table tbody tr:last-child td {
        border-bottom: none;
    }

    .proyek-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Dark Theme Table Row Hover */
    body.dark-theme .proyek-table tbody tr:hover {
        background-color: #334155;
    }

    /* Table Actions */
    .table-actions {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }

    .btn-edit,
    .btn-hapus {
        padding: 8px 20px;
        border: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-edit {
        background-color: #ffd93d;
        color: #1a1a4d;
        box-shadow: 0 2px 4px rgba(255, 217, 61, 0.2);
    }

    .btn-edit:hover {
        background-color: #ffb800;
        color: #1a1a4d;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(255, 217, 61, 0.3);
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
        background-color: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
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
        padding: 60px 20px !important;
        text-align: center;
        color: #6c757d;
        transition: color 0.3s ease;
    }

    body.dark-theme .empty-state {
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 48px;
        color: #dee2e6;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    body.dark-theme .empty-state-icon {
        color: #475569;
    }

    .empty-state-text {
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Alert Styles */
    .alert {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInDown 0.3s ease;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        color: #721c24;
        border-color: #f5c2c7;
    }

    /* Dark Theme Alerts */
    body.dark-theme .alert-success {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
        color: #86efac;
        border-color: #065f46;
    }

    body.dark-theme .alert-danger {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
        color: #fca5a5;
        border-color: #991b1b;
    }

    /* ========== RESPONSIVE ========== */
    
    /* Tablet (768px - 992px) */
    @media (max-width: 992px) {
        .proyek-main {
            padding: 1.5rem;
        }

        .control-bar {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .search-box,
        .sort-wrapper,
        .btn-tambah-proyek {
            width: 100%;
            max-width: 100%;
            min-width: 100%;
        }

        .table-container {
            overflow-x: auto;
        }

        .proyek-table {
            min-width: 700px;
        }
    }

    /* Mobile (576px - 768px) */
    @media (max-width: 768px) {
        .proyek-main {
            padding: 1rem;
            padding-top: 66px;
        }

        .dashboard-title {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .control-bar {
            gap: 10px;
        }

        .search-input {
            font-size: 0.95rem;
            padding: 11px 15px 11px 42px;
        }

        .sort-btn,
        .btn-tambah-proyek {
            font-size: 0.95rem;
            padding: 11px 20px;
        }

        .table-actions {
            flex-direction: column;
            gap: 6px;
            width: 100%;
        }

        .btn-edit,
        .btn-hapus {
            width: 100%;
            padding: 10px 20px;
        }
    }

    /* Small Mobile (< 576px) */
    @media (max-width: 576px) {
        .proyek-main {
            padding: 0.75rem;
        }

        .dashboard-title {
            font-size: 20px;
            margin-bottom: 18px;
        }

        .search-input {
            font-size: 0.9rem;
            padding: 10px 15px 10px 40px;
        }

        .search-icon {
            font-size: 14px;
            left: 12px;
        }

        .sort-btn,
        .btn-tambah-proyek {
            font-size: 0.9rem;
            padding: 10px 18px;
        }

        .btn-tambah-proyek {
            min-width: 100%;
        }

        .proyek-table thead th {
            padding: 14px 10px;
            font-size: 0.85rem;
        }

        .proyek-table tbody td {
            padding: 12px 10px;
            font-size: 0.85rem;
        }

        .btn-edit,
        .btn-hapus {
            font-size: 0.85rem;
            padding: 8px 16px;
        }
    }

    /* Extra Small Mobile (< 400px) */
    @media (max-width: 400px) {
        .proyek-main {
            padding: 0.5rem;
        }

        .dashboard-title {
            font-size: 18px;
        }

        .proyek-table {
            min-width: 600px;
        }

        .proyek-table thead th,
        .proyek-table tbody td {
            padding: 10px 8px;
            font-size: 0.8rem;
        }
    }

    /* ========== SMOOTH TRANSITIONS ========== */
    .proyek-main,
    .dashboard-title,
    .search-input,
    .search-icon,
    .sort-btn,
    .sort-dropdown,
    .sort-item,
    .btn-tambah-proyek,
    .table-container,
    .proyek-table tbody td,
    .btn-edit,
    .btn-hapus,
    .empty-state,
    .empty-state-icon,
    .alert {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<main class="proyek-main">

    <h1 class="dashboard-title">DASHBOARD KELOLA PROYEK</h1>

    {{-- 🔔 Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center alert-dismissible fade show">
            <div>
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @if(session('last_proyek_id'))
                <a href="{{ route('proyek.edit', session('last_proyek_id')) }}" class="btn btn-sm btn-primary">
                    Lihat Proyek
                </a>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔔 Notifikasi error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Control Bar: Search, Sort, Add Button --}}
    <div class="control-bar">
        {{-- Search Box --}}
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input 
                type="text" 
                class="search-input" 
                id="searchProyek" 
                placeholder="Cari Proyek"
                onkeyup="searchTable()"
            >
        </div>

        {{-- Sort Dropdown - NEW STYLE --}}
        <div class="sort-wrapper">
            <button class="sort-btn" id="sortButton">
                Sort by Status
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="sort-dropdown" id="sortDropdown">
                <div class="sort-item active" onclick="sortByStatus('')">
                    Sort by Status
                </div>
                <div class="sort-item" onclick="sortByStatus('RAB_Belum_Dibuat')">
                    RAB Belum Dibuat
                </div>
                <div class="sort-item" onclick="sortByStatus('RAB_Telah_Dibuat')">
                    RAB Telah Dibuat
                </div>
                <div class="sort-item" onclick="sortByStatus('Proyek_Dikerjakan')">
                    Proyek Dikerjakan
                </div>
                <div class="sort-item" onclick="sortByStatus('Proyek_Selesai_Dikerjakan')">
                    Proyek Selesai Dikerjakan
                </div>
            </div>
        </div>

        {{-- Tombol Tambah Proyek --}}
        <a href="{{ route('proyek.create') }}" class="btn-tambah-proyek">
            <i class="fas fa-plus"></i>
            Tambah Proyek
        </a>
    </div>

    {{-- Table Container --}}
    <div class="table-container">
        <table class="proyek-table" id="proyekTable">
            <thead>
                <tr>
                    <th style="width: 60px;">NO</th>
                    <th>NAMA PROYEK</th>
                    <th style="width: 200px;">STATUS</th>
                    <th style="width: 200px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proyeks as $index => $proyek)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $proyek->nama_proyek }}</td>
                        <td>{{ $proyek->status }}</td>
                        <td>
                            <div class="table-actions">
                                {{-- Edit Button --}}
                                <a href="{{ route('proyek.edit', $proyek->id_proyek) }}" class="btn-edit">
                                    Edit
                                </a>

                                {{-- Hapus Button --}}
                                <form action="{{ route('proyek.destroy', $proyek->id_proyek) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-hapus"
                                        onclick="return confirm('Yakin ingin hapus proyek ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <div class="empty-state-text">
                                Belum ada proyek yang ditambahkan.
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
    // 2. SEARCH FUNCTION
    // ========================================
    function searchTable() {
        const input = document.getElementById('searchProyek');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('proyekTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td')[1]; // Kolom Nama Proyek
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

    // ========================================
    // 3. SORT BY STATUS FUNCTION
    // ========================================
    function sortByStatus(filter) {
        const table = document.getElementById('proyekTable');
        const tr = table.getElementsByTagName('tr');
        const sortDropdown = document.getElementById('sortDropdown');
        const sortItems = sortDropdown.querySelectorAll('.sort-item');

        // Update active state
        sortItems.forEach(item => item.classList.remove('active'));
        event.target.classList.add('active');

        // Filter table
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td')[2]; // Kolom Status
            if (td) {
                if (filter === '') {
                    tr[i].style.display = '';
                } else {
                    const txtValue = td.textContent || td.innerText;
                    // Ubah spasi menjadi underscore untuk matching
                    if (txtValue.replace(/\s+/g, '_') === filter) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        // Close dropdown
        sortDropdown.classList.remove('show');
    }

    console.log('✅ Dashboard Kelola Proyek dengan Dark Theme Support loaded successfully!');
</script>
@endsection