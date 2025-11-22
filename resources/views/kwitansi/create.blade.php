{{-- resources/views/kwitansi/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Tambah Kwitansi</h3>

    <form action="{{ route('kwitansi.store') }}" method="POST" id="formKwitansi">
        @csrf

        {{-- Pilih sumber data --}}
        <div class="mb-3">
            <label for="Sumber_Tabel" class="form-label">Sumber Data</label>
            <select name="Sumber_Tabel" id="Sumber_Tabel" class="form-select" required>
                <option value="">-- Pilih Sumber --</option>
                <option value="rabs" {{ (isset($prefillSumberTabel) && $prefillSumberTabel === 'rabs') ? 'selected' : '' }}>RAB (Rencana Anggaran Biaya)</option>
                <option value="penjualan" {{ (isset($prefillSumberTabel) && $prefillSumberTabel === 'penjualan') ? 'selected' : '' }}>Penjualan</option>
            </select>
            <div class="form-text text-muted">
                Pilih sumber kwitansi berdasarkan data RAB yang disetujui atau transaksi penjualan.
            </div>
        </div>

        {{-- ID Sumber --}}
        <div class="mb-3">
            <label for="Id_Sumber" class="form-label">Sumber ID & Nama</label>
            <select name="Id_Sumber" id="Id_Sumber" class="form-select" required>
                <option value="">-- Pilih Sumber --</option>
            </select>
            <div class="form-text text-muted">
                Data akan otomatis terisi berdasarkan sumber yang kamu pilih.
            </div>
        </div>

        {{-- Sales --}}
        <div class="mb-3">
            <label for="Sales" class="form-label">Sales</label>
            <input type="text" name="Sales" id="Sales" class="form-control" placeholder="Nama Sales" required>
        </div>

        {{-- Tanggal Kwitansi --}}
        <div class="mb-3">
            <label for="Tanggal_Kwitansi" class="form-label">Tanggal Kwitansi</label>
            <input type="date" name="Tanggal_Kwitansi" id="Tanggal_Kwitansi" class="form-control" required>
        </div>

        {{-- Total --}}
        <div class="mb-3">
            <label for="Total" class="form-label">Total (Rp)</label>
            <input type="number" name="Total" id="Total" class="form-control" placeholder="Total otomatis terisi" readonly required>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="mb-3">
            <label for="Metode_Pembayaran" class="form-label">Metode Pembayaran</label>
            <select name="Metode_Pembayaran" id="Metode_Pembayaran" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Tunai">Tunai</option>
                <option value="Transfer">Transfer</option>
                <option value="Kredit">Kredit</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="Status" class="form-label">Status Pembayaran</label>
            <select name="Status" id="Status" class="form-select" required>
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Lunas">Lunas</option>
            </select>
        </div>

        {{-- Tombol --}}
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('kwitansi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

{{-- Script untuk mengisi ID sumber & total otomatis --}}
<script>
    const dataRabs = @json($rabs ?? []);
    const dataPenjualan = @json($penjualan ?? []);
    const prefillId = "{{ $prefillIdSumber ?? '' }}";
    const prefillTable = "{{ $prefillSumberTabel ?? '' }}";

    const sumberSelect = document.getElementById('Sumber_Tabel');
    const idSumberSelect = document.getElementById('Id_Sumber');
    const totalInput = document.getElementById('Total');

    function updateIdSumberOptions() {
        const sumber = sumberSelect.value;
        idSumberSelect.innerHTML = '<option value="">-- Pilih Sumber --</option>';
        totalInput.value = '';

        let data = [];
        if (sumber === 'rabs') data = dataRabs;
        else if (sumber === 'penjualan') data = dataPenjualan;

        data.forEach(item => {
            const option = document.createElement('option');

            if (sumber === 'rabs') {
                option.value = item.id_rab;
                option.textContent = `${item.nama_pekerjaan ?? 'RAB Tanpa Nama'} (ID: ${item.id_rab})`;
            } 
            else if (sumber === 'penjualan') {

                // 🔥 PERBAIKAN PENTING: WAJIB SELARAS DENGAN CONTROLLER
                option.value = item.id_barang;
                option.textContent = `${item.nama_barang ?? 'Penjualan Tanpa Nama'} (ID: ${item.id_barang})`;
            }

            if (prefillId && option.value == prefillId && sumber === prefillTable) {
                option.selected = true;
                setTimeout(() => isiTotalOtomatis(), 100);
            }
            idSumberSelect.appendChild(option);
        });
    }

    async function isiTotalOtomatis() {
        const sumber = sumberSelect.value;
        const id = idSumberSelect.value;
        if (!sumber || !id) return;

        totalInput.value = 'Mengambil total...';

        try {
            const response = await fetch(`/kwitansi/getTotalSumber?sumber=${encodeURIComponent(sumber)}&id=${encodeURIComponent(id)}`);
            const data = await response.json();

            if (data && data.success && data.total !== undefined) {
                totalInput.value = data.total;
            } else {
                totalInput.value = '';
                alert(data.message ?? 'Total tidak ditemukan untuk sumber ini.');
            }
        } catch (error) {
            console.error('Gagal mengambil total:', error);
            totalInput.value = '';
            alert('Terjadi kesalahan saat mengambil total dari server.');
        }
    }

    sumberSelect.addEventListener('change', updateIdSumberOptions);
    idSumberSelect.addEventListener('change', isiTotalOtomatis);

    if (prefillTable) {
        sumberSelect.value = prefillTable;
        updateIdSumberOptions();
    }
</script>
@endsection
