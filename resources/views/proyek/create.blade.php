@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Tambah Proyek Baru</h2>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong><br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Tambah Proyek --}}
    <form action="{{ route('proyek.store') }}" method="POST">
        @csrf

        {{-- Nama Proyek --}}
        <div class="mb-3">
            <label for="nama_proyek" class="form-label">Nama Proyek</label>
            <input type="text" name="nama_proyek" id="nama_proyek" class="form-control"
                   placeholder="Masukkan nama proyek" value="{{ old('nama_proyek') }}" required>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"
                      placeholder="Masukkan deskripsi proyek">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Status
             NOTE: value harus persis sama seperti enum di migration:
             'Belum_Dimulai', 'Sedang Berjalan', 'Selesai'
        --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="Belum_Dimulai" {{ old('status') == 'Belum_Dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                <option value="Sedang Berjalan" {{ old('status') == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        {{-- Tanggal Mulai --}}
        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control"
                   value="{{ old('tanggal_mulai') }}">
        </div>

        {{-- Tanggal Selesai --}}
        <div class="mb-3">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control"
                   value="{{ old('tanggal_selesai') }}">
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </form>
</div>

{{-- Script: disable tanggal bila status = Belum_Dimulai --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusSelect = document.getElementById('status');
        const mulaiInput = document.getElementById('tanggal_mulai');
        const selesaiInput = document.getElementById('tanggal_selesai');

        function toggleTanggal() {
            // value must match the enum string
            if (statusSelect.value === 'Belum_Dimulai') {
                mulaiInput.value = '';
                selesaiInput.value = '';
                mulaiInput.disabled = true;
                selesaiInput.disabled = true;
            } else {
                mulaiInput.disabled = false;
                selesaiInput.disabled = false;
            }
        }

        toggleTanggal();
        statusSelect.addEventListener('change', toggleTanggal);
    });
</script>
@endsection
