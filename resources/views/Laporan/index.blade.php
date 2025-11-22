@extends('layouts.app')

@section('content')
<div class="container">

    <h3>📄 Daftar Laporan</h3>
    <a href="{{ route('laporan.create') }}" class="btn btn-primary mb-3">➕ Tambah Laporan</a>

    {{-- ALERT ERROR --}}
    <div id="alert-error" class="alert alert-danger d-none">
        Gagal memuat detail data. Periksa koneksi atau endpoint server.
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>No Nota</th>
                <th>Nama Laporan</th>
                <th>Owner</th>
                <th>Tanggal</th>
                <th>Total Profit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $l)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $l->no_nota }}</td>
                <td>{{ $l->nama_laporan }}</td>
                <td>{{ $l->owner }}</td>
                <td>{{ $l->tanggal }}</td>
                <td>Rp {{ number_format($l->total_profit, 0, ',', '.') }}</td>
                <td>
                    {{-- Tombol menuju halaman detail --}}
                    <a href="{{ route('laporan.detail', $l->id) }}" class="btn btn-info btn-sm">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada laporan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- 
    ❗ Script dropdown DIHAPUS karena:
    - Halaman ini tidak menggunakan dropdown
    - Dropdown hanya dipakai create.blade.php
    - Menghindari error JS & konflik 
--}}

@endsection
