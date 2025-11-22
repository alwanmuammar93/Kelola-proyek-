@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Proyek</h2>

    {{-- 🔔 Notifikasi sukses --}}
    @if(session('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center">
            <div>
                {{ session('success') }}
            </div>
            @if(session('last_proyek_id'))
                </a>
            @endif
        </div>
    @endif

    {{-- 🔹 Tombol tambah proyek --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('proyek.create') }}" class="btn btn-success">+ Tambah Proyek</a>
    </div>

    {{-- 🔹 Tabel daftar proyek --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Proyek</th>
                    <th>Status</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proyeks as $index => $proyek)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $proyek->nama_proyek }}</td>
                        <td class="text-center">{{ $proyek->status }}</td>
                        <td class="text-center">{{ $proyek->tanggal_mulai ?? '-' }}</td>
                        <td class="text-center">{{ $proyek->tanggal_selesai ?? '-' }}</td>
                        <td class="text-center">
                            {{-- Edit proyek --}}
                            <a href="{{ route('proyek.edit', $proyek->id_proyek) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            {{-- Buat RAB untuk proyek ini --}}
                            <a href="{{ route('rab.create', ['id_proyek' => $proyek->id_proyek]) }}" class="btn btn-info btn-sm">
                                Buat RAB
                            </a>

                            {{-- ✅ PERBAIKAN: Tombol Kwitansi langsung ke dashboard Kwitansi --}}
                            <a href="{{ url('/kwitansi/' . $proyek->id_proyek) }}" class="btn btn-success btn-sm">
                                Kwitansi
                            </a>

                            {{-- Hapus proyek --}}
                            <form action="{{ route('proyek.destroy', $proyek->id_proyek) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin hapus proyek ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada proyek yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
