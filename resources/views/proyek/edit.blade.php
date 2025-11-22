@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Proyek</h2>

    <form action="{{ route('proyek.update', $proyek->id_proyek) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Proyek</label>
            <input type="text" name="nama_proyek" value="{{ $proyek->nama_proyek }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Belum_Dimulai" {{ $proyek->status == 'Belum_Dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                <option value="Sedang Berjalan" {{ $proyek->status == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="Selesai" {{ $proyek->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $proyek->tanggal_mulai }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $proyek->tanggal_selesai }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
    const statusSelect = document.getElementById('status');
    const mulai = document.getElementById('tanggal_mulai');
    const selesai = document.getElementById('tanggal_selesai');

    function toggleTanggal() {
        if (statusSelect.value === 'Belum_Dimulai') {
            mulai.disabled = true;
            selesai.disabled = true;
        } else {
            mulai.disabled = false;
            selesai.disabled = false;
        }
    }

    toggleTanggal();
    statusSelect.addEventListener('change', toggleTanggal);
</script>
@endsection
