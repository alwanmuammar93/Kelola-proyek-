@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Tambah Data Laporan</h2>

    <form action="{{ route('laporan.store') }}" method="POST" id="formLaporan">
        @csrf

        {{-- ROW 1 --}}
        <div class="d-flex gap-3 mb-3">

            <div class="flex-fill">
                <label>Sumber Data :</label>
                <select id="sumber" class="form-control">
                    <option value="">-- Pilih Sumber --</option>
                    <option value="RAB">RAB</option>
                    <option value="Penjualan">Penjualan</option>
                </select>
            </div>

            <div class="flex-fill">
                <label>Pilih Data :</label>
                <select id="pilihData" class="form-control">
                    <option value="">-- Pilih Data --</option>
                </select>
            </div>

            <div class="flex-fill">
                <label>Nama Laporan :</label>
                <input type="text" name="nama_laporan" class="form-control">
            </div>

        </div>

        {{-- ROW 2 --}}
        <div class="d-flex gap-3 mb-3">

            <div style="width:80px;">
                <label>No</label>
                <input type="text" class="form-control" value="1" readonly>
            </div>

            <div class="flex-fill">
                <label>Tanggal :</label>
                <input type="date" name="tanggal" class="form-control">
            </div>

            <div class="flex-fill">
                <label>No. Nota :</label>
                <input type="text" name="no_nota" class="form-control">
            </div>

            <div class="flex-fill">
                <label>Owner :</label>
                <input type="text" id="owner" name="owner" class="form-control">
            </div>

            <div class="flex-fill">
                <label>Hasil Profit :</label>
                <div id="hasilProfitBox" class="form-control bg-light">0</div>
            </div>

        </div>

        {{-- DETAIL --}}
        <div class="border p-3 rounded mb-3">

            <div class="d-flex gap-3">

                <div style="width:50px;">
                    <label>1</label>
                </div>

                <div class="flex-fill">
                    <label>Rincian</label>
                    <input type="text" name="detail[0][rincian]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Jumlah</label>
                    <input type="number" name="detail[0][jumlah]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Satuan</label>
                    <input type="text" name="detail[0][satuan]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Total</label>
                    <input type="number" name="detail[0][total]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Modal Satuan</label>
                    <input type="number" name="detail[0][modal_satuan]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Total Modal</label>
                    <input type="number" name="detail[0][total_modal]" class="form-control">
                </div>

                <div class="flex-fill">
                    <label>Profit</label>
                    <input type="number" name="detail[0][profit]" class="form-control">
                </div>

            </div>
        </div>

        {{-- BUTTON --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Keluar</a>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </div>

    </form>

</div>
@endsection

@section('scripts')
<script>

// ===============================
// 1. LOAD DATA DROPDOWN
// ===============================
document.getElementById('sumber').addEventListener('change', function () {

    let sumber = this.value;
    let pilih = document.getElementById('pilihData');
    let url = "{{ route('laporan.getData') }}";

    pilih.innerHTML = '<option value="">Loading...</option>';

    if (!sumber) {
        pilih.innerHTML = '<option value="">-- Pilih Data --</option>';
        return;
    }

    fetch(url + "?sumber=" + sumber)
        .then(res => res.json())
        .then(data => {

            pilih.innerHTML = '<option value="">-- Pilih Data --</option>';

            data.forEach(row => {
                pilih.innerHTML += `
                    <option value="${row.id}">
                        ${row.rincian}
                    </option>
                `;
            });

        });
});


// ===============================
// 2. LOAD DETAIL
// ===============================
document.getElementById('pilihData').addEventListener('change', function () {

    let id = this.value;
    let sumber = document.getElementById('sumber').value;

    if (!id) return;

    let url = "{{ url('/laporan/get-detail') }}/" + sumber + "/" + id;

    fetch(url)
        .then(res => res.json())
        .then(data => {

            document.querySelector('input[name="detail[0][rincian]"]').value     = data.rincian ?? '';
            document.querySelector('input[name="detail[0][jumlah]"]').value      = data.jumlah ?? '';
            document.querySelector('input[name="detail[0][satuan]"]').value      = data.satuan ?? '';
            document.querySelector('input[name="detail[0][total]"]').value       = data.total ?? '';
            document.querySelector('input[name="detail[0][modal_satuan]"]').value= data.modal_satuan ?? 0;
            document.querySelector('input[name="detail[0][total_modal]"]').value = data.total_modal ?? 0;

            let profit = (parseFloat(data.total) || 0) - (parseFloat(data.total_modal) || 0);
            document.querySelector('input[name="detail[0][profit]"]').value = profit;

            document.getElementById('hasilProfitBox').innerText = profit;

            document.getElementById('owner').value = data.owner ?? '';

        });
});
</script>
@endsection
