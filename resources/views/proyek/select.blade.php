@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pilih Proyek untuk Membuat RAB</h2>

    <form action="{{ route('rab.create') }}" method="GET">
        <div class="mb-3">
            <label for="id_proyek" class="form-label">Pilih Proyek</label>
            <select name="id_proyek" id="id_proyek" class="form-control" required>
                <option value="">-- Pilih Proyek --</option>
                @foreach ($proyeks as $proyek)
                    <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Buat RAB</button>
        <a href="{{ route('proyek.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
