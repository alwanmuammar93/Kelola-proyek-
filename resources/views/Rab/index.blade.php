@extends('layouts.app')

@section('title', 'Kelola RAB')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/rab-styles.css') }}">
<style>
    /* ========================================
       DARK THEME OVERRIDE - CRITICAL
       ======================================== */
    
    /* PAKSA HORIZONTAL - OVERRIDE SEMUA CSS */
    .action-buttons {
        display: flex !important;
        flex-direction: row !important;
        gap: 8px !important;
        justify-content: center !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
    }

    .action-buttons form {
        display: inline !important;
        margin: 0 !important;
    }

    .btn-download,
    .btn-edit,
    .btn-hapus {
        padding: 8px 16px !important;
        border: none !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        display: inline-block !important;
        white-space: nowrap !important;
    }

    .btn-download {
        background-color: #1a1a4d !important;
        color: white !important;
    }

    .btn-edit {
        background-color: #ffd93d !important;
        color: #1a1a4d !important;
    }

    .btn-hapus {
        background-color: #dc3545 !important;
        color: white !important;
    }

    /* Dark Theme Button Override */
    body.dark-theme .btn-download {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
        color: white !important;
    }

    body.dark-theme .btn-download:hover {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;
    }

    body.dark-theme .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
    }

    body.dark-theme .btn-edit:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%) !important;
    }

    body.dark-theme .btn-hapus {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }

    body.dark-theme .btn-hapus:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    }

    /* INLINE STYLES UNTUK DROPDOWN */
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

    /* Dark Theme Sort Dropdown */
    body.dark-theme .sort-dropdown {
        background-color: #1e293b;
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
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

    /* Dark Theme Sort Item */
    body.dark-theme .sort-item {
        color: #e2e8f0;
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    .sort-item:last-child {
        border-bottom: none;
    }

    .sort-item:hover {
        background-color: #f8f9fa;
        color: #667eea;
        padding-left: 20px;
    }

    /* Dark Theme Sort Item Hover */
    body.dark-theme .sort-item:hover {
        background-color: #334155;
        color: #60a5fa;
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
<main class="rab-main">
    
    <div class="rab-container">
        
        {{-- Header Dashboard --}}
        <div class="rab-header-title">
            <h1 class="rab-title">DASHBOARD KELOLA RAB</h1>
        </div>

        {{-- Notifikasi Success --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search, Sort, dan Tambah --}}
        <div class="rab-controls">
            
            {{-- Search Bar --}}
            <div class="search-wrapper">
                <div class="search-form">
                    <input 
                        type="text" 
                        id="searchInput"
                        class="search-input" 
                        placeholder="Cari berdasarkan No RAB atau Nama Proyek..."
                        onkeyup="searchTable()"
                    >
                    <button type="button" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            {{-- Sort Dropdown --}}
            <div class="sort-wrapper">
                <button class="sort-btn" id="sortButton">
                    Sort by <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <div class="sort-dropdown" id="sortDropdown">
                    <a href="#" class="sort-item" onclick="sortTable('terbaru'); return false;">Terbaru</a>
                    <a href="#" class="sort-item" onclick="sortTable('terlama'); return false;">Terlama</a>
                    <a href="#" class="sort-item" onclick="sortTable('no_asc'); return false;">No RAB (A-Z)</a>
                    <a href="#" class="sort-item" onclick="sortTable('no_desc'); return false;">No RAB (Z-A)</a>
                    <a href="#" class="sort-item" onclick="sortTable('total_high'); return false;">Total Tertinggi</a>
                    <a href="#" class="sort-item" onclick="sortTable('total_low'); return false;">Total Terendah</a>
                    <a href="#" class="sort-item" onclick="sortTable('default'); return false;">Default</a>
                </div>
            </div>

            {{-- Tombol Tambah RAB --}}
            <a href="{{ route('rab.create') }}" class="btn-tambah">
                <i class="bi bi-plus-lg me-2"></i> Tambah Laporan
            </a>

        </div>

        {{-- Tabel RAB --}}
        <div class="rab-table-wrapper">
            <table class="rab-table" id="rabTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No RAB</th>
                        <th>Proyek</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($rabs as $index => $rab)
                        <tr data-total="{{ $rab->total ?? 0 }}" 
                            data-no-rab="{{ $rab->no_rab ?? '' }}" 
                            data-created="{{ $rab->created_at ?? '' }}">
                            
                            {{-- Nomor --}}
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- No RAB --}}
                            <td>{{ $rab->no_rab ?? '-' }}</td>

                            {{-- Nama Proyek --}}
                            <td class="nama-proyek">
                                <strong>{{ $rab->proyek->nama_proyek ?? 'Proyek Tidak Ditemukan' }}</strong>
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @switch($rab->status)
                                    @case('Perencanaan')
                                    @case('Belum Disetujui')
                                        <span class="badge-status badge-secondary">{{ $rab->status }}</span>
                                        @break
                                    @case('Disetujui')
                                    @case('Berjalan')
                                        <span class="badge-status badge-primary">{{ $rab->status }}</span>
                                        @break
                                    @case('Selesai')
                                        <span class="badge-status badge-success">{{ $rab->status }}</span>
                                        @break
                                    @default
                                        <span class="badge-status badge-secondary">{{ $rab->status }}</span>
                                @endswitch
                            </td>

                            {{-- Total --}}
                            <td class="text-end">
                                <strong>Rp {{ number_format($rab->total ?? 0, 0, ',', '.') }}</strong>
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">
                                <div class="action-buttons">
                                    {{-- Tombol Download PDF --}}
                                    <a href="{{ route('rab.downloadPDF', ['id_rab' => $rab->id_rab]) }}" 
                                       class="btn-download"
                                       target="_blank"
                                       title="Download PDF">
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('rab.edit', ['id_rab' => $rab->id_rab]) }}" 
                                       class="btn-edit">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('rab.destroy', ['id_rab' => $rab->id_rab]) }}"
                                          method="POST"
                                          onsubmit="return confirmDelete('{{ $rab->no_rab }}', '{{ $rab->proyek->nama_proyek ?? 'Proyek Tidak Ditemukan' }}', {{ $rab->total ?? 0 }})">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="6" class="text-center empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <p class="mt-3 mb-0 empty-state-text">Belum ada data RAB</p>
                                <a href="{{ route('rab.create') }}" 
                                    class="btn-tambah-first">
                                    <i class="bi bi-plus-circle"></i> Tambah RAB Pertama
                                </a>
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

    // FUNCTION SORTING TABLE
    function sortTable(order) {
        const tableBody = document.getElementById('tableBody');
        const rows = Array.from(tableBody.querySelectorAll('tr:not(#emptyRow)'));
        
        if (rows.length === 0) return;

        document.getElementById('sortDropdown').classList.remove('show');

        if (order === 'default') {
            location.reload();
            return;
        }

        rows.sort((a, b) => {
            if (order === 'total_high' || order === 'total_low') {
                const totalA = parseFloat(a.getAttribute('data-total')) || 0;
                const totalB = parseFloat(b.getAttribute('data-total')) || 0;
                return order === 'total_high' ? totalB - totalA : totalA - totalB;
            }
            
            if (order === 'no_asc' || order === 'no_desc') {
                const noA = a.getAttribute('data-no-rab') || '';
                const noB = a.getAttribute('data-no-rab') || '';
                return order === 'no_asc' ? noA.localeCompare(noB) : noB.localeCompare(noA);
            }
            
            if (order === 'terbaru' || order === 'terlama') {
                const dateA = new Date(a.getAttribute('data-created'));
                const dateB = new Date(b.getAttribute('data-created'));
                return order === 'terbaru' ? dateB - dateA : dateA - dateB;
            }
        });

        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
            tableBody.appendChild(row);
        });
    }

    // FUNCTION SEARCH TABLE
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toUpperCase();
        const tbody = document.getElementById('tableBody');
        const tr = tbody.getElementsByTagName('tr');
        let visibleCount = 0;

        for (let i = 0; i < tr.length; i++) {
            if (tr[i].id === 'emptyRow') continue;

            const tdProyek = tr[i].querySelector('.nama-proyek');
            const tdNoRab = tr[i].getElementsByTagName('td')[1];

            if (tdProyek || tdNoRab) {
                const txtProyek = tdProyek ? tdProyek.textContent || tdProyek.innerText : '';
                const txtNoRab = tdNoRab ? tdNoRab.textContent || tdNoRab.innerText : '';
                
                if (txtProyek.toUpperCase().indexOf(filter) > -1 || 
                    txtNoRab.toUpperCase().indexOf(filter) > -1) {
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
                <td colspan="6" class="text-center empty-state">
                    <i class="bi bi-search empty-state-icon"></i>
                    <p class="mt-3 mb-0 empty-state-text">Tidak ada data yang cocok dengan pencarian</p>
                </td>
            `;
            tbody.appendChild(newEmptyRow);
        } else if (visibleCount > 0 && emptyRow && emptyRow.querySelector('.bi-search')) {
            emptyRow.remove();
        }
    }

    // FUNCTION CONFIRM DELETE
    function confirmDelete(noRab, namaProyek, total) {
        const totalFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(total);

        const message = `Apakah Anda yakin ingin menghapus RAB ini?\n\n` +
                       `📋 No RAB: ${noRab}\n` +
                       `🏗️ Proyek: ${namaProyek}\n` +
                       `💰 Total: ${totalFormatted}`;

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

        console.log('✅ Dashboard Kelola RAB with Dark Theme loaded!');
    });
</script>
@endsection