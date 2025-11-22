@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Kwitansi</h3>

    {{-- Form Update --}}
    <form action="{{ route('kwitansi.update', $kwitansi->Id_Kwitansi) }}" method="POST" id="formKwitansi">
        @csrf
        @method('PUT')

        {{-- Pilihan Sumber Data --}}
        <div class="mb-3">
            <label for="Sumber_Tabel" class="form-label">Sumber Data</label>
            <select name="Sumber_Tabel" id="Sumber_Tabel" class="form-select" required>
                <option value="">-- Pilih Sumber --</option>
                <option value="proyek" {{ $kwitansi->Sumber_Tabel == 'proyek' ? 'selected' : '' }}>Proyek</option>
                <option value="rabs" {{ $kwitansi->Sumber_Tabel == 'rabs' ? 'selected' : '' }}>RAB</option>
                <option value="penjualan" {{ $kwitansi->Sumber_Tabel == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
            </select>
        </div>

        {{-- ID Sumber --}}
        <div class="mb-3">
            <label for="Id_Sumber" class="form-label">ID Sumber</label>
            <select name="Id_Sumber" id="Id_Sumber" class="form-select" required>
                <option value="">-- Pilih ID Sumber --</option>
                {{-- Akan diisi otomatis lewat JavaScript --}}
            </select>
        </div>

        {{-- Sales --}}
        <div class="mb-3">
            <label for="Sales" class="form-label">Sales</label>
            <input type="text" name="Sales" id="Sales" class="form-control" 
                   value="{{ old('Sales', $kwitansi->Sales) }}" required>
        </div>

        {{-- Tanggal Kwitansi --}}
        <div class="mb-3">
            <label for="Tanggal_Kwitansi" class="form-label">Tanggal Kwitansi</label>
            <input type="date" name="Tanggal_Kwitansi" id="Tanggal_Kwitansi" 
                   value="{{ old('Tanggal_Kwitansi', $kwitansi->Tanggal_Kwitansi) }}" 
                   class="form-control" required>
        </div>

        {{-- Total --}}
        <div class="mb-3">
            <label for="Total" class="form-label">Total (Rp)</label>
            <input type="number" name="Total" id="Total" 
                   value="{{ old('Total', $kwitansi->Total) }}" 
                   class="form-control" required>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="mb-3">
            <label for="Metode_Pembayaran" class="form-label">Metode Pembayaran</label>
            <input type="text" name="Metode_Pembayaran" id="Metode_Pembayaran" 
                   value="{{ old('Metode_Pembayaran', $kwitansi->Metode_Pembayaran) }}" 
                   class="form-control">
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="Status" class="form-label">Status Pembayaran</label>
            <select name="Status" id="Status" class="form-select" required>
                <option value="Belum Lunas" {{ $kwitansi->Status == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                <option value="Lunas" {{ $kwitansi->Status == 'Lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>

        {{-- Tombol Aksi --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('kwitansi.index', ['id_proyek' => $kwitansi->Id_Sumber]) }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

{{-- Script Dinamis --}}
<script>
    // Data dari Controller
    const dataProyek = @json($proyeks ?? []);
    const dataRabs = @json($rabs ?? []);
    const dataPenjualan = @json($penjualan ?? []);

    const sumberSelect = document.getElementById('Sumber_Tabel');
    const idSumberSelect = document.getElementById('Id_Sumber');
    const totalInput = document.getElementById('Total');

    const sumberAwal = "{{ $kwitansi->Sumber_Tabel }}";
    const idAwal = "{{ $kwitansi->Id_Sumber }}";

    // 🔹 Fungsi untuk memperbarui daftar ID sumber
    function updateIdSumberOptions() {
        const sumber = sumberSelect.value;
        idSumberSelect.innerHTML = '<option value="">-- Pilih ID Sumber --</option>';
        let data = [];

        if (sumber === 'proyek') data = dataProyek;
        else if (sumber === 'rabs') data = dataRabs;
        else if (sumber === 'penjualan') data = dataPenjualan;

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.Id_Proyek || item.Id_Rab || item.Id_Penjualan;
            option.textContent = (item.Nama_Proyek || item.Nama_Rab || item.Nama_Penjualan || 'Tanpa Nama') +
                                 ' (ID: ' + option.value + ')';
            if (option.value == idAwal) option.selected = true;
            idSumberSelect.appendChild(option);
        });
    }

    // 🔹 Fungsi untuk mengisi total otomatis
    function isiTotalOtomatis() {
        const sumber = sumberSelect.value;
        const id = idSumberSelect.value;
        let total = 0;

        if (sumber === 'proyek') {
            const found = dataProyek.find(p => p.Id_Proyek == id);
            total = found?.Total_Anggaran ?? found?.Total ?? 0;
        } else if (sumber === 'rabs') {
            const found = dataRabs.find(r => r.Id_Rab == id);
            total = found?.Total_Rab ?? found?.Total ?? 0;
        } else if (sumber === 'penjualan') {
            const found = dataPenjualan.find(p => p.Id_Penjualan == id);
            total = found?.Total_Penjualan ?? found?.Total ?? 0;
        }

        if (total > 0) totalInput.value = total;
    }

    // Event Listener
    sumberSelect.addEventListener('change', updateIdSumberOptions);
    idSumberSelect.addEventListener('change', isiTotalOtomatis);

    // Jalankan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        if (sumberAwal) {
            sumberSelect.value = sumberAwal;
            updateIdSumberOptions();
            setTimeout(() => isiTotalOtomatis(), 250);
        }
    });
</script>
@endsection
