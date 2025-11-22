{{-- resources/views/kwitansi/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Kelola Kwitansi</h3>

            @if(!empty($idProyek))
                <small class="text-muted">
                    Menampilkan kwitansi untuk proyek:
                    <strong>{{ optional($proyek->first())->nama_proyek ?? 'Proyek #' . $idProyek }}</strong>
                </small>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">
                Kembali ke Proyek
            </a>

            @php
                $createUrl = route('kwitansi.create');
                if(!empty($idProyek)) {
                    $createUrl .= '?id_proyek=' . $idProyek . '&sumber_tabel=proyek';
                }
            @endphp

            <a href="{{ $createUrl }}" class="btn btn-success">+ Tambah Kwitansi</a>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORM PENCARIAN --}}
    <form action="{{ route('kwitansi.search') }}" method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="keyword" class="form-control"
                   placeholder="Cari Sales, Metode, Status..."
                   value="{{ request('keyword') }}">
        </div>

        @if(!empty($idProyek))
            <input type="hidden" name="id_proyek" value="{{ $idProyek }}">
        @endif

        <div class="col-auto">
            <button class="btn btn-primary">Cari</button>
            <a href="{{ route('kwitansi.index') }}{{ !empty($idProyek) ? '?id_proyek='.$idProyek : '' }}"
               class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    {{-- TABEL --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>No</th>
                    <th>ID Kwitansi</th>
                    <th>Sumber</th>
                    <th>Sales</th>
                    <th>Tanggal</th>
                    <th>Total (Rp)</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kwitansi as $i => $k)
                    @php
                        $kwId = $k->Id_Kwitansi;

                        $table = strtolower($k->Sumber_Tabel);
                        $label = match($table) {
                            'rab','rabs' => 'RAB',
                            'penjualan','penjualans' => 'Penjualan',
                            'proyek','proyeks' => 'Proyek',
                            default => $k->Sumber_Tabel
                        };

                        $sumberInfo = null;
                        if(!empty($k->sumber_obj)) {
                            if($table === 'rab' || $table === 'rabs') {
                                $sumberInfo = $k->sumber_obj->nama_pekerjaan ?? null;
                            } elseif($table === 'penjualan' || $table === 'penjualans') {
                                $sumberInfo = $k->sumber_obj->nama_barang ?? null;
                            } elseif($table === 'proyek' || $table === 'proyeks') {
                                $sumberInfo = $k->sumber_obj->nama_proyek ?? null;
                            }
                        }
                    @endphp

                    <tr>
                        <td class="text-center">{{ $i+1 }}</td>
                        <td class="text-center">{{ $kwId }}</td>

                        <td class="text-center">
                            <strong>{{ $label }}</strong><br>
                            @if($sumberInfo)
                                <small class="text-muted">{{ $sumberInfo }}</small><br>
                            @endif
                            <small class="text-muted">ID: {{ $k->Id_Sumber }}</small>
                        </td>

                        <td>{{ $k->Sales }}</td>

                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($k->Tanggal_Kwitansi)->format('d-m-Y') }}
                        </td>

                        <td class="text-end">Rp {{ number_format($k->Total,0,',','.') }}</td>

                        <td>{{ $k->Metode_Pembayaran }}</td>

                        <td class="text-center">
                            @if($k->Status === 'Lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('kwitansi.edit', $kwId) }}" class="btn btn-sm btn-primary mb-1">Edit</a>

                            <form action="{{ route('kwitansi.ubahStatus',$kwId) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-outline-info mb-1">Ubah Status</button>
                            </form>

                            <button class="btn btn-sm btn-outline-secondary mb-1"
                                onclick="ubahSalesPrompt({{ $kwId }}, '{{ $k->Sales }}')">
                                Ubah Sales
                            </button>

                            <form action="{{ route('kwitansi.destroy',$kwId) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Yakin hapus kwitansi?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger mb-1">Hapus</button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada kwitansi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- JS ubah sales --}}
<script>
function ubahSalesPrompt(id, currentSales) {
    let newName = prompt("Masukkan nama sales baru:", currentSales);
    if (newName === null) return;

    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/kwitansi/ubah-sales/" + id;

    const csrf = document.createElement("input");
    csrf.type = "hidden";
    csrf.name = "_token";
    csrf.value = "{{ csrf_token() }}";
    form.appendChild(csrf);

    const method = document.createElement("input");
    method.type = "hidden";
    method.name = "_method";
    method.value = "PUT";
    form.appendChild(method);

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "Sales";
    input.value = newName;
    form.appendChild(input);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
