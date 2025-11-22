@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Data RAB untuk Proyek: <strong>{{ $proyek->nama_proyek }}</strong></h3>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tabel daftar RAB --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rabs as $index => $rab)

                        {{-- Pastikan id_rab digunakan --}}
                        @php 
                            $idRab = $rab->id_rab;
                        @endphp

                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>

                            {{-- Total Biaya --}}
                            <td>
                                Rp {{ number_format($rab->total ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @switch($rab->status)
                                    @case('Perencanaan')
                                    @case('Belum Disetujui')
                                        <span class="badge bg-secondary">{{ $rab->status }}</span>
                                        @break
                                    @case('Disetujui')
                                    @case('Berjalan')
                                        <span class="badge bg-primary">{{ $rab->status }}</span>
                                        @break
                                    @default
                                        <span class="badge bg-success">{{ $rab->status }}</span>
                                @endswitch
                            </td>

                            {{-- Tanggal Dibuat --}}
                            <td class="text-center">
                                {{ $rab->created_at ? $rab->created_at->format('d-m-Y') : '-' }}
                            </td>

                            {{-- Aksi --}}
                            <td class="text-center">

                                <a href="{{ route('rab.edit', ['id_rab' => $idRab]) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('rab.destroy', ['id_rab' => $idRab]) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data RAB ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data RAB untuk proyek ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tombol navigasi --}}
    <div class="mt-4 d-flex gap-2">

        {{-- Tombol tambah --}}
        <a href="{{ route('rab.create', ['id_proyek' => $proyek->id_proyek]) }}"
           class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah RAB Baru
        </a>

    </div>

</div>
@endsection
