@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Data Penjualan</h2>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary mb-3">+ Tambah Penjualan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $p)
            <tr>
                <td>{{ $p->id_barang }}</td>
                <td>{{ $p->nama_barang }}</td>
                <td>{{ $p->jumlah }}</td>
                <td>{{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ number_format($p->total, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('penjualan.edit', $p->id_barang) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('penjualan.destroy', $p->id_barang) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                    </form>

                    {{-- Tombol Kwitansi (fitur baru) --}}
                    <a href="{{ route('kwitansi.index', ['id_penjualan' => $p->id_barang]) }}" class="btn btn-info btn-sm mt-1">
                        Kwitansi
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
