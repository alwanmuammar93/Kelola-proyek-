@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Data RAB untuk Proyek: <strong>{{ $proyek->nama_proyek }}</strong></h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('rab.update', $rab->id_rab) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">No RAB</label>
                <input type="text" name="no_rab" class="form-control" value="{{ old('no_rab', $rab->no_rab) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Perihal</label>
                <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $rab->perihal) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Owner</label>
            <input type="text" name="owner" class="form-control" value="{{ old('owner', $rab->owner) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Pekerjaan</label>
            <input type="text" name="nama_pekerjaan" class="form-control" value="{{ old('nama_pekerjaan', $rab->nama_pekerjaan) }}" required>
        </div>

        {{-- ================= RINCIAN ================= --}}
        <div class="mb-3">
            <label class="form-label">Rincian Pekerjaan</label>
            <div id="rincian-container">

                @php
                    // ==== FUNGSI PERBAIKAN KHUSUS: aman untuk PHP 8 ====
                    $decodeAman = function($val) {
                        if (is_array($val)) return $val;
                        if (is_object($val)) return (array) $val;
                        if (is_null($val) || $val === "") return [];

                        if (is_string($val)) {
                            $tmp = json_decode($val, true);
                            return (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) 
                                ? $tmp 
                                : [$val];
                        }

                        return [$val];
                    };

                    // ambil old input
                    $oldRincian = old('rincian_pekerjaan');
                    $oldSatuan = old('satuan');
                    $oldJumlah = old('jumlah');
                    $oldBiaya = old('biaya_material_rincian');

                    $rincianList = [];
                    $satuanList  = [];
                    $jumlahList  = [];
                    $biayaList   = [];

                    if (is_array($oldRincian) || is_array($oldSatuan) || is_array($oldJumlah) || is_array($oldBiaya)) {
                        // old input
                        $maxOld = max(
                            count($oldRincian ?: []),
                            count($oldSatuan ?: []),
                            count($oldJumlah ?: []),
                            count($oldBiaya ?: [])
                        );
                        for ($i = 0; $i < max(1, $maxOld); $i++) {
                            $rincianList[] = $oldRincian[$i] ?? '';
                            $satuanList[]  = $oldSatuan[$i] ?? '';
                            $jumlahList[]  = $oldJumlah[$i] ?? 0;
                            $biayaList[]   = $oldBiaya[$i] ?? 0;
                        }
                    } else {

                        // ==== KUATKAN DENGAN decodeAman (DI SINI ERROR MUNCUL) ====
                        $decoded = $decodeAman($rab->rincian_pekerjaan);

                        if (is_array($decoded)) {
                            foreach ($decoded as $row) {
                                if (is_object($row)) $row = (array)$row;

                                $rincianList[] = $row['rincian'] ?? '';
                                $satuanList[]  = $row['satuan'] ?? '';
                                $jumlahList[]  = $row['jumlah'] ?? 0;
                                $biayaList[]   = $row['biaya_material'] ?? 0;
                            }
                        } 
                        else {
                            $satuanList = $decodeAman($rab->satuan);
                            $jumlahList = $decodeAman($rab->jumlah);
                            $biayaList  = $decodeAman($rab->biaya_material_rincian);

                            $maxCount = max(1, count($satuanList), count($jumlahList), count($biayaList));
                            for ($i = 0; $i < $maxCount; $i++) {
                                $rincianList[] = '';
                                $satuanList[$i] = $satuanList[$i] ?? '';
                                $jumlahList[$i] = $jumlahList[$i] ?? 0;
                                $biayaList[$i]  = $biayaList[$i] ?? 0;
                            }
                        }
                    }

                    $count = max(1, count($rincianList));
                @endphp

                @for ($i = 0; $i < $count; $i++)
                <div class="card mb-2 rincian-row">
                    <div class="card-body p-2">
                        <div class="row g-2 align-items-start">

                            <div class="col-12 mb-2">
                                <textarea name="rincian_pekerjaan[]" class="form-control" rows="2">{{ $rincianList[$i] }}</textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="small">Satuan</label>
                                <input type="text" name="satuan[]" class="form-control" value="{{ $satuanList[$i] }}">
                            </div>

                            <div class="col-md-4">
                                <label class="small">Jumlah</label>
                                <input type="number" name="jumlah[]" class="form-control" min="0" value="{{ $jumlahList[$i] }}">
                            </div>

                            <div class="col-md-3">
                                <label class="small">Biaya Material (Rp)</label>
                                <input type="number" name="biaya_material_rincian[]" class="form-control biaya-rincian"
                                       min="0" step="0.01" value="{{ $biayaList[$i] }}" oninput="hitungTotalRAB()">
                            </div>

                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100"
                                        onclick="hapusRincian(this)">Hapus</button>
                            </div>

                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <button type="button" class="btn btn-outline-primary" onclick="tambahRincian()">+ Tambah Rincian</button>
        </div>

        {{-- TOTAL --}}
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Total RAB (Rp)</label>
                <input type="number" id="total_rab" name="total_rab"
                       class="form-control" value="{{ old('total_rab', $rab->total_rab ?? $rab->total ?? 0) }}" readonly>
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option {{ $rab->status=="Perencanaan" ? "selected" : "" }}>Perencanaan</option>
                <option {{ $rab->status=="Disetujui" ? "selected" : "" }}>Disetujui</option>
                <option {{ $rab->status=="Selesai" ? "selected" : "" }}>Selesai</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('rab.index',['id_proyek'=>$proyek->id_proyek]) }}" class="btn btn-secondary">← Kembali</a>
            <button class="btn btn-success">💾 Simpan Perubahan</button>
        </div>

    </form>
</div>

<script>
function tambahRincian() {
    let div = document.createElement("div");
    div.className = "card mb-2 rincian-row";
    div.innerHTML = `
        <div class="card-body p-2">
            <div class="row g-2">
                <div class="col-12 mb-2">
                    <textarea name="rincian_pekerjaan[]" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-4">
                    <input type="text" name="satuan[]" class="form-control" value="Unit">
                </div>
                <div class="col-md-4">
                    <input type="number" name="jumlah[]" class="form-control" min="1" value="1">
                </div>
                <div class="col-md-3">
                    <input type="number" name="biaya_material_rincian[]" class="form-control biaya-rincian"
                           min="0" step="0.01" value="0" oninput="hitungTotalRAB()">
                </div>
                <div class="col-md-1">
                    <button type="button" onclick="hapusRincian(this)" class="btn btn-outline-danger w-100">X</button>
                </div>
            </div>
        </div>
    `;
    document.getElementById("rincian-container").appendChild(div);
    hitungTotalRAB();
}

function hapusRincian(btn) {
    const rows = document.querySelectorAll(".rincian-row");
    if (rows.length <= 1) {
        alert("Minimal satu rincian harus ada!");
        return;
    }
    btn.closest(".rincian-row").remove();
    hitungTotalRAB();
}

function hitungTotalRAB() {
    let total = 0;
    document.querySelectorAll(".biaya-rincian").forEach(e => {
        total += parseFloat(e.value) || 0;
    });
    document.getElementById("total_rab").value = total;
}

document.addEventListener("DOMContentLoaded", function() {
    hitungTotalRAB();
});
</script>

@endsection
