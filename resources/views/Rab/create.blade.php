{{-- resources/views/Rab/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Validasi proyek --}}
    @if (!isset($proyek))
        <div class="alert alert-danger">
            <strong>Data proyek tidak ditemukan.</strong>
            <a href="{{ route('rab.index') }}" class="btn btn-sm btn-secondary ms-2">Kembali</a>
        </div>
        @return
    @endif

    <h3 class="mb-4">RAB : {{ $proyek->nama_proyek }}</h3>

    {{-- Pesan error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form id="rab-form" action="{{ route('rab.store', ['id_proyek' => $proyek->id_proyek]) }}" method="POST">
        @csrf

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label fw-bold">No</label>
                <input type="text" name="no" class="form-control" value="{{ old('no', '1') }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">No RAB :</label>
                <input type="text" name="no_rab" class="form-control" value="{{ old('no_rab') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Perihal :</label>
                <input type="text" name="perihal" class="form-control" value="{{ old('perihal') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Owner :</label>
                <input type="text" name="owner" class="form-control" value="{{ old('owner') }}" required>
            </div>
        </div>

        {{-- Rincian --}}
        <div id="rincian-container">
            <div class="rincian-item border rounded p-3 mb-3 bg-light">

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Rincian Pekerjaan / Alat / Bahan</label>
                        <textarea name="rincian_pekerjaan[]" class="form-control" rows="2" required>{{ old('rincian_pekerjaan.0') }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan[]" class="form-control" value="{{ old('satuan.0','Unit') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah[]" min="1" class="form-control" value="{{ old('jumlah.0',1) }}" required>
                    </div>
                </div>

                <div class="row mb-3 text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="hapusRincian(this)">Hapus</button>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Biaya Material</label>
                        <input type="number" name="biaya_material_rincian[]" class="form-control biaya-material"
                               min="0" step="0.01" value="{{ old('biaya_material_rincian.0',0) }}" required>
                    </div>
                </div>

            </div>
        </div>

        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary" onclick="tambahRincian()">+ Tambah Rincian</button>
        </div>

        {{-- Footer --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Perencanaan">Perencanaan</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Total RAB (Rp)</label>
                <input type="number" id="total_rab" class="form-control bg-white" readonly value="0">
            </div>
        </div>

        {{-- FIELD TOTAL ASLI UNTUK CONTROLLER --}}
        <input type="hidden" name="total" id="total_hidden">

        <input type="hidden" name="nama_pekerjaan" value="{{ $proyek->nama_proyek }}">

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('rab.index', ['id_proyek' => $proyek->id_proyek]) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan RAB</button>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script>
(function() {

    window.tambahRincian = function() {
        const container = document.getElementById('rincian-container');

        const newItem = document.createElement('div');
        newItem.classList.add('rincian-item','border','rounded','p-3','mb-3','bg-light');

        newItem.innerHTML = `
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Rincian Pekerjaan / Alat / Bahan</label>
                    <textarea name="rincian_pekerjaan[]" class="form-control" rows="2" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan[]" value="Unit" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control" min="1" value="1" required>
                </div>
            </div>

            <div class="row mb-3 text-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="hapusRincian(this)">Hapus</button>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Biaya Material</label>
                    <input type="number" name="biaya_material_rincian[]" class="form-control biaya-material" min="0" step="0.01" value="0" required>
                </div>
            </div>
        `;

        container.appendChild(newItem);

        // Event hitung otomatis
        newItem.querySelector('.biaya-material').addEventListener('input', hitungTotal);
    };

    window.hapusRincian = function(btn) {
        const items = document.querySelectorAll('.rincian-item');
        if (items.length > 1) {
            btn.closest('.rincian-item').remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada satu rincian!');
        }
    };

    function safe(v){
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    function hitungTotal(){
        let total = 0;
        document.querySelectorAll('.biaya-material').forEach(el => {
            total += safe(el.value);
        });

        document.getElementById('total_rab').value = total;
        document.getElementById('total_hidden').value = total;
    }

    // Hitung ulang setelah load
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.biaya-material').forEach(el => {
            el.addEventListener('input', hitungTotal);
        });
        hitungTotal();
    });

})();
</script>

@endsection
