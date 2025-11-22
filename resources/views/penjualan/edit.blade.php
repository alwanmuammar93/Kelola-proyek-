@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Edit Data Penjualan</h2>

    {{-- Tampilkan pesan sukses bila ada --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('penjualan.update', $penjualan->id_barang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" 
                   value="{{ old('nama_barang', $penjualan->nama_barang) }}" required>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" 
                   value="{{ old('jumlah', $penjualan->jumlah) }}" min="1" required>
        </div>

        <div class="mb-3">
            <label for="harga_satuan" class="form-label">Harga Satuan</label>
            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control"
                   value="{{ old('harga_satuan', $penjualan->harga_satuan) }}" min="0" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="total" class="form-label">Total (otomatis dihitung)</label>
            <input type="number" id="total" class="form-control" value="{{ $penjualan->total }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    // Hitung otomatis total ketika jumlah atau harga_satuan berubah
    document.addEventListener('DOMContentLoaded', function() {
        const jumlah = document.getElementById('jumlah');
        const harga = document.getElementById('harga_satuan');
        const total = document.getElementById('total');

        function hitungTotal() {
            total.value = (jumlah.value * harga.value) || 0;
        }

        jumlah.addEventListener('input', hitungTotal);
        harga.addEventListener('input', hitungTotal);
    });
</script>
@endsection
