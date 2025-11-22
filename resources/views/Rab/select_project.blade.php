{{-- resources/views/rab/select_project.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Pilih Proyek untuk Membuat RAB</h2>
            <small class="text-muted">
                Klik tombol <strong>Buat RAB</strong> pada proyek yang diinginkan.
            </small>
        </div>
        <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Proyek
        </a>
    </div>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Proyek</th>
                    <th style="width:160px">Status</th>
                    <th style="width:140px">Tanggal Mulai</th>
                    <th style="width:140px">Tanggal Selesai</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($proyeks as $index => $proyek)
                    @php
                        // pastikan id proyek benar
                        $idProyek = $proyek->id_proyek;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>

                        <td>
                            <strong>{{ $proyek->nama_proyek }}</strong>
                            @if(!empty($proyek->deskripsi))
                                <div class="text-muted small mt-1">
                                    {{ \Illuminate\Support\Str::limit($proyek->deskripsi, 80) }}
                                </div>
                            @endif
                        </td>

                        <td class="text-center">
                            @php $status = $proyek->status ?? 'Belum Dimulai'; @endphp

                            @switch(true)
                                @case(in_array($status, ['Belum Dimulai', 'Perencanaan']))
                                    <span class="badge bg-secondary">Belum Dimulai</span>
                                    @break

                                @case(in_array($status, ['Sedang Berjalan', 'Berjalan', 'Disetujui']))
                                    <span class="badge bg-primary">Sedang Berjalan</span>
                                    @break

                                @case(in_array($status, ['Selesai', 'Selesai Dimulai']))
                                    <span class="badge bg-success">Selesai</span>
                                    @break

                                @default
                                    <span class="badge bg-info">{{ $status }}</span>
                            @endswitch
                        </td>

                        <td class="text-center">
                            {{ $proyek->tanggal_mulai 
                                ? \Carbon\Carbon::parse($proyek->tanggal_mulai)->format('d-m-Y') 
                                : '-' }}
                        </td>

                        <td class="text-center">
                            {{ $proyek->tanggal_selesai 
                                ? \Carbon\Carbon::parse($proyek->tanggal_selesai)->format('d-m-Y') 
                                : '-' }}
                        </td>

                        <td class="text-center">
                            <a href="{{ route('rab.create', ['id_proyek' => $idProyek]) }}" 
                               class="btn btn-sm btn-primary" 
                               title="Buat RAB untuk {{ $proyek->nama_proyek }}">
                                <i class="bi bi-file-earmark-plus"></i> Buat RAB
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">
                            Belum ada proyek yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
