@extends('layouts.app') 
{{-- Asumsi: Anda menggunakan 'layouts.app' sebagai layout utama --}}

@section('title', 'Ubah Password')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        
        {{-- Pesan Sukses atau Error (Meskipun di Controller di-redirect ke profile.show, ini berjaga-jaga jika ada error validasi di sini) --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="col-lg-6 offset-lg-3">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark p-3">
                    <h5 class="mb-0">🔐 Form Ubah Kata Sandi</h5>
                </div>
                <div class="card-body">
                    
                    {{-- Form ini akan mengirimkan PUT request ke profile.update-password --}}
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        {{-- Menggunakan PUT method sesuai dengan definisi route di web.php --}}
                        @method('PUT') 
                        
                        {{-- Password Lama (current_password) --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama Anda</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   required 
                                   autocomplete="current-password">
                            
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        
                        {{-- Password Baru (new_password) --}}
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   required 
                                   autocomplete="new-password">
                            
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                <div class="form-text">Minimal 8 karakter.</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru (new_password_confirmation) --}}
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation" 
                                   class="form-control" 
                                   required 
                                   autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-warning text-dark">Ubah Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection