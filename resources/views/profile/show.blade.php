@extends('layouts.app') 
{{-- Asumsi: Anda menggunakan 'layouts.app' sebagai layout utama --}}

@section('title', 'Profile Saya')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Pesan Sukses atau Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 1. KARTU DETAIL & EDIT PROFILE --}}
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white p-3">
                    <h5 class="mb-0">👤 Informasi Profil</h5>
                </div>
                <div class="card-body">
                    
                    {{-- Form untuk Update Profile --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Laravel menggunakan POST, tapi kita kirim PUT/PATCH secara implisit untuk UPDATE --}}
                        @method('POST') 

                        <div class="row">
                            {{-- Kolom Foto Profil --}}
                            <div class="col-md-3 text-center">
                                <div class="mb-3">
                                    {{-- Menggunakan Accessor getProfilePhotoUrlAttribute dari Model User --}}
                                    <img src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->display_name }}" 
                                         class="img-fluid rounded-circle mb-2" 
                                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f8f9fa;">
                                </div>
                                <div class="mb-3">
                                    <label for="profile_photo" class="btn btn-sm btn-outline-primary">Ubah Foto</label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
                                    @error('profile_photo')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($user->profile_photo)
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePhotoModal">
                                            Hapus Foto
                                        </button>
                                    </div>
                                @endif
                            </div>

                            {{-- Kolom Detail User --}}
                            <div class="col-md-9">
                                {{-- Role (Hanya Display) --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Role</label>
                                    <p class="form-control-plaintext text-capitalize">{{ $user->role }}</p>
                                </div>
                                
                                {{-- Username (Hanya Display) --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Username</label>
                                    <p class="form-control-plaintext">{{ $user->username }}</p>
                                </div>

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Alamat Email</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. KARTU UBAH PASSWORD --}}
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark p-3">
                    <h5 class="mb-0">🔐 Ubah Kata Sandi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT') 
                        
                        {{-- Password Lama --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-warning text-dark">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL UNTUK HAPUS FOTO --}}
@if ($user->profile_photo)
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-labelledby="deletePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePhotoModalLabel">Konfirmasi Hapus Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus foto profil Anda?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('profile.delete-photo') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection