{{-- resources/views/kwitansi/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Kwitansi')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/kwitansi.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        /* PAKSA HORIZONTAL - Override semua CSS lain */
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

        .btn-unduh,
        .btn-edit,
        .btn-hapus {
            padding: 8px 16px !important;
            border-radius: 6px !important;
            font-size: 0.85rem !important;
            display: inline-block !important;
            width: auto !important;
            text-align: center !important;
        }

        /* Dark Theme Override untuk Loading Overlay */
        body.dark-theme .loading-overlay {
            background: rgba(15, 23, 42, 0.95);
        }

        body.dark-theme .loading-spinner {
            background: #1e293b;
            color: #e2e8f0;
        }

        /* Dark Theme Override untuk No Results */
        body.dark-theme .no-results {
            color: #94a3b8;
        }

        body.dark-theme .no-results a {
            color: #60a5fa;
        }

        body.dark-theme .no-results a:hover {
            color: #3b82f6;
        }
    </style>
@endsection

@section('content')
{{-- Loading Overlay --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <i class="bi bi-arrow-repeat"></i>
        <p class="mt-3 mb-0">Mencari data...</p>
    </div>
</div>

{{-- Main Content --}}
<main class="kwitansi-main">

    {{-- CONTENT AREA --}}
    <div class="kwitansi-container">
        
        {{-- Header Dashboard --}}
        <div class="kwitansi-header-title">
            <h1 class="dashboard-title">DASHBOARD KELOLA KWITANSI</h1>
            
            @if(!empty($idProyek))
                <small class="text-muted project-info">
                    Menampilkan kwitansi untuk proyek:
                    <strong>{{ optional($proyek->first())->nama_proyek ?? 'Proyek #' . $idProyek }}</strong>
                </small>
            @endif
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Search & Controls --}}
        <div class="kwitansi-controls">
            
            {{-- Search Bar dengan AJAX --}}
            <div class="search-wrapper">
                <div class="search-form">
                    <input 
                        type="text" 
                        id="searchInput"
                        class="search-input" 
                        placeholder="Cari kwitansi..."
                        autocomplete="off"
                    >
                    <button type="button" class="search-btn" onclick="performSearch()">
                        <i class="bi bi-search"></i>
                    </button>
                    
                    {{-- Clear Search Button --}}
                    <button type="button" class="btn-clear-search" id="clearSearchBtn" style="display: none;" onclick="clearSearch()">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
                <small class="search-hint">
                    <i class="bi bi-info-circle"></i> Pencarian berdasarkan ID atau sumber data
                </small>
            </div>

            {{-- Sort Dropdown --}}
            <div class="sort-wrapper">
                <button class="sort-btn" id="sortButton">
                    Sort by
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <div class="sort-dropdown" id="sortDropdown">
                    <a href="{{ route('kwitansi.index', ['sort_by' => 'lunas']) }}" 
                       class="sort-item {{ request('sort_by') == 'lunas' ? 'active' : '' }}">
                        Lunas
                    </a>

                    <a href="{{ route('kwitansi.index', ['sort_by' => 'dp_tertinggi']) }}" 
                       class="sort-item {{ request('sort_by') == 'dp_tertinggi' ? 'active' : '' }}">
                        DP Tertinggi
                    </a>

                    <a href="{{ route('kwitansi.index', ['sort_by' => 'dp_terendah']) }}" 
                       class="sort-item {{ request('sort_by') == 'dp_terendah' ? 'active' : '' }}">
                        DP Terendah
                    </a>

                    <a href="{{ route('kwitansi.index', ['sort_by' => 'terbaru']) }}" 
                       class="sort-item {{ request('sort_by', 'terbaru') == 'terbaru' ? 'active' : '' }}">
                        Tanggal Terbaru
                    </a>
                </div>
            </div>

            {{-- Tombol Tambah Kwitansi --}}
            @php
                $createUrl = route('kwitansi.create');
                if(!empty($idProyek)) {
                    $createUrl .= '?id_proyek=' . $idProyek . '&sumber_tabel=proyek';
                }
            @endphp
            <a href="{{ $createUrl }}" class="btn-tambah">
                <i class="bi bi-plus-lg me-2"></i> Tambah Kwitansi
            </a>

        </div>

        {{-- Tabel Kwitansi --}}
        <div class="kwitansi-table-wrapper">
            <table class="table-kwitansi">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>SUMBER DATA</th>
                        <th>TANGGAL</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($kwitansi as $i => $k)
                        @php
                            $kwId = $k->Id_Kwitansi;
                            
                            // Determine sumber label
                            $table = strtolower($k->Sumber_Tabel);
                            $sumberLabel = match($table) {
                                'rab','rabs' => 'RAB',
                                'penjualan','penjualans' => 'Penjualan',
                                'proyek','proyeks' => 'Proyek',
                                default => ucfirst($k->Sumber_Tabel)
                            };
                            
                            // Get detail info from sumber object
                            $sumberDetail = null;
                            if(!empty($k->sumber_obj)) {
                                if($table === 'rab' || $table === 'rabs') {
                                    $sumberDetail = $k->sumber_obj->nama_pekerjaan ?? null;
                                } elseif($table === 'penjualan' || $table === 'penjualans') {
                                    $sumberDetail = $k->sumber_obj->nama_sales ?? null;
                                } elseif($table === 'proyek' || $table === 'proyeks') {
                                    $sumberDetail = $k->sumber_obj->nama_proyek ?? null;
                                }
                            }
                        @endphp

                        <tr>
                            {{-- Nomor --}}
                            <td>{{ $i+1 }}</td>

                            {{-- Sumber Data --}}
                            <td>
                                <span class="sumber-label">{{ $sumberLabel }}</span>
                                @if($sumberDetail)
                                    <span class="sumber-detail" title="{{ $sumberDetail }}">
                                        {{ $sumberDetail }}
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td>
                                {{ \Carbon\Carbon::parse($k->Tanggal_Kwitansi)->format('d-m-Y') }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($k->Status === 'Lunas')
                                    <span class="badge-status badge-lunas">Lunas</span>
                                @elseif(str_starts_with($k->Status, 'DP '))
                                    @php
                                        preg_match('/DP (\d+)%/', $k->Status, $matches);
                                        $persentase = isset($matches[1]) ? intval($matches[1]) : 0;
                                        
                                        if($persentase >= 75) {
                                            $badgeClass = 'badge-dp-tinggi';
                                        } elseif($persentase >= 50) {
                                            $badgeClass = 'badge-dp-sedang';
                                        } else {
                                            $badgeClass = 'badge-dp-rendah';
                                        }
                                    @endphp
                                    <span class="badge-status {{ $badgeClass }}">{{ $k->Status }}</span>
                                @else
                                    <span class="badge-status badge-dp">{{ $k->Status }}</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td>
                                <div class="action-buttons">
                                    {{-- Tombol Unduh --}}
                                    <a href="{{ url('kwitansi/download', $kwId) }}" class="btn-unduh" title="Unduh Kwitansi" target="_blank">
                                        Unduh
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('kwitansi.edit', $kwId) }}" class="btn-edit" title="Edit Kwitansi">
                                        Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('kwitansi.destroy', $kwId) }}" 
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus kwitansi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus" title="Hapus Kwitansi">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr id="emptyRow">
                            <td colspan="5" class="empty-state">
                                <i class="bi bi-inbox empty-state-icon"></i>
                                <p class="mt-3 mb-2 empty-state-text">Belum ada kwitansi</p>
                                <a href="{{ route('kwitansi.create') }}" class="btn-tambah-first">
                                    <i class="bi bi-plus-circle"></i> Tambah Kwitansi Pertama
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

            document.addEventListener('click', function(e) {
                if (!sortButton.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.remove('show');
                }
            });
        }

        // Search on Enter key
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });

            // Show clear button when typing
            searchInput.addEventListener('input', function() {
                const clearBtn = document.getElementById('clearSearchBtn');
                if (this.value.trim() !== '') {
                    clearBtn.style.display = 'block';
                } else {
                    clearBtn.style.display = 'none';
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
    // 2. PERFORM SEARCH DENGAN AJAX
    // ========================================
    function performSearch() {
        const searchInput = document.getElementById('searchInput');
        const keyword = searchInput.value.trim();

        if (keyword === '') {
            alert('Masukkan kata kunci pencarian');
            return;
        }

        // Show loading
        document.getElementById('loadingOverlay').classList.add('show');

        // AJAX Request
        fetch(`{{ route('kwitansi.search') }}?keyword=${encodeURIComponent(keyword)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading
            document.getElementById('loadingOverlay').classList.remove('show');

            if (data.success) {
                updateTable(data.data, keyword);
            } else {
                alert('Error: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            document.getElementById('loadingOverlay').classList.remove('show');
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mencari data');
        });
    }

    // ========================================
    // 3. UPDATE TABLE DENGAN HASIL SEARCH
    // ========================================
    function updateTable(data, keyword) {
        const tableBody = document.getElementById('tableBody');
        
        if (!data || data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="no-results">
                        <i class="bi bi-search"></i>
                        <p><strong>Tidak ada hasil untuk "${keyword}"</strong></p>
                        <p class="text-muted">Coba kata kunci lain atau <a href="#" onclick="clearSearch(); return false;">tampilkan semua data</a></p>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        data.forEach((k, index) => {
            // Determine sumber label
            const table = k.Sumber_Tabel.toLowerCase();
            let sumberLabel = '';
            let sumberDetail = '';

            if (table === 'rab' || table === 'rabs') {
                sumberLabel = 'RAB';
                sumberDetail = k.sumber_obj?.nama_pekerjaan || '';
            } else if (table === 'penjualan' || table === 'penjualans') {
                sumberLabel = 'Penjualan';
                sumberDetail = k.sumber_obj?.nama_sales || '';
            } else if (table === 'proyek' || table === 'proyeks') {
                sumberLabel = 'Proyek';
                sumberDetail = k.sumber_obj?.nama_proyek || '';
            }

            // Status badge
            let statusBadge = '';
            if (k.Status === 'Lunas') {
                statusBadge = '<span class="badge-status badge-lunas">Lunas</span>';
            } else if (k.Status.startsWith('DP ')) {
                const match = k.Status.match(/DP (\d+)%/);
                const persentase = match ? parseInt(match[1]) : 0;
                let badgeClass = 'badge-dp';
                
                if (persentase >= 75) badgeClass = 'badge-dp-tinggi';
                else if (persentase >= 50) badgeClass = 'badge-dp-sedang';
                else badgeClass = 'badge-dp-rendah';

                statusBadge = `<span class="badge-status ${badgeClass}">${k.Status}</span>`;
            } else {
                statusBadge = `<span class="badge-status badge-dp">${k.Status}</span>`;
            }

            // Format tanggal
            const tanggal = formatDate(k.Tanggal_Kwitansi);

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <span class="sumber-label">${sumberLabel}</span>
                        ${sumberDetail ? `<span class="sumber-detail" title="${sumberDetail}">${sumberDetail}</span>` : ''}
                    </td>
                    <td>${tanggal}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ url('kwitansi/download') }}/${k.Id_Kwitansi}" class="btn-unduh" title="Unduh Kwitansi" target="_blank">Unduh</a>
                            <a href="{{ url('kwitansi') }}/${k.Id_Kwitansi}/edit" class="btn-edit" title="Edit Kwitansi">Edit</a>
                            <form action="{{ url('kwitansi') }}/${k.Id_Kwitansi}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kwitansi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-hapus" title="Hapus Kwitansi">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    // ========================================
    // 4. CLEAR SEARCH
    // ========================================
    function clearSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = '';
        document.getElementById('clearSearchBtn').style.display = 'none';
        
        // Reload page untuk tampilkan semua data
        window.location.href = "{{ route('kwitansi.index') }}";
    }

    // ========================================
    // 5. HELPER FUNCTIONS
    // ========================================
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    console.log('✅ Dashboard Kelola Kwitansi with Dark Theme Support loaded successfully!');
</script>
@endsection