@extends('layouts.app')

@section('title', 'Edit Proyek')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/proyek-style.css') }}">
<style>
    /* ========== EDIT PROYEK SPECIFIC STYLES ========== */
    
    /* Main Content */
    .proyek-main {
        margin-left: 0;
        padding: 2rem;
        padding-top: 66px;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    /* Dark Theme Main */
    body.dark-theme .proyek-main {
        background-color: #0f172a;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    /* Dark Theme Form Container */
    body.dark-theme .form-container {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Form Title */
    .form-title {
        color: #ffffff;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 40px;
        text-transform: uppercase;
        text-align: center;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    /* Dark Theme Title - White with enhanced shadow */
    body.dark-theme .form-title {
        color: #ffffff;
        text-shadow: 0 2px 12px rgba(96, 165, 250, 0.4);
    }

    /* Form Row - 4 Kolom untuk Edit (termasuk Status) */
    .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Form Row - Full Width */
    .form-group-full {
        grid-column: 1 / -1;
    }

    /* Input Styles */
    .custom-input,
    .custom-select,
    .custom-textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #212529;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        outline: none;
        border-color: #1a1f71;
        background: white;
        box-shadow: 0 0 0 3px rgba(26, 31, 113, 0.1);
    }

    .custom-input::placeholder,
    .custom-textarea::placeholder {
        color: #6c757d;
    }

    /* Dark Theme Inputs */
    body.dark-theme .custom-input,
    body.dark-theme .custom-select,
    body.dark-theme .custom-textarea {
        background: #0f172a;
        border-color: #475569;
        color: #e2e8f0;
    }

    body.dark-theme .custom-input:focus,
    body.dark-theme .custom-select:focus,
    body.dark-theme .custom-textarea:focus {
        border-color: #3b82f6;
        background: #1e293b;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    body.dark-theme .custom-input::placeholder,
    body.dark-theme .custom-textarea::placeholder {
        color: #64748b;
    }

    .custom-select {
        cursor: pointer;
    }

    .custom-select option {
        padding: 10px;
        background: white;
        color: #212529;
    }

    /* Dark Theme Select Options */
    body.dark-theme .custom-select option {
        background: #1e293b;
        color: #e2e8f0;
    }

    .custom-textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        gap: 15px;
    }

    /* Button Styles */
    .btn-batal,
    .btn-simpan {
        padding: 15px 40px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
    }

    .btn-batal {
        background: #6c757d;
        color: white;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
    }

    .btn-batal:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        text-decoration: none;
    }

    /* Dark Theme Batal Button */
    body.dark-theme .btn-batal {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        box-shadow: 0 2px 8px rgba(74, 85, 104, 0.3);
    }

    body.dark-theme .btn-batal:hover {
        background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        box-shadow: 0 4px 12px rgba(74, 85, 104, 0.5);
    }

    .btn-simpan {
        background: linear-gradient(135deg, #1a1f71 0%, #2d3f8f 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(26, 31, 113, 0.2);
    }

    .btn-simpan:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 31, 113, 0.4);
    }

    /* Dark Theme Simpan Button */
    body.dark-theme .btn-simpan {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    body.dark-theme .btn-simpan:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
    }

    /* Validation Alert */
    .alert-validation {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border: 2px solid #f5c2c7;
        border-left: 4px solid #dc3545;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        color: #842029;
        transition: all 0.3s ease;
    }

    .alert-validation strong {
        display: block;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .alert-validation ul {
        margin: 10px 0 0 0;
        padding-left: 20px;
    }

    .alert-validation li {
        margin-bottom: 5px;
    }

    /* Dark Theme Validation Alert */
    body.dark-theme .alert-validation {
        background: linear-gradient(135deg, #7f1d1d 0%, #991b1b 100%);
        border-color: #991b1b;
        border-left-color: #ef4444;
        color: #fca5a5;
    }

    body.dark-theme .alert-validation strong {
        color: #fecaca;
    }

    /* Validation Errors */
    .is-invalid {
        border-color: #dc3545 !important;
    }

    /* Dark Theme Invalid */
    body.dark-theme .is-invalid {
        border-color: #ef4444 !important;
        background: #1e293b !important;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
        display: block;
        transition: color 0.3s ease;
    }

    /* Dark Theme Invalid Feedback */
    body.dark-theme .invalid-feedback {
        color: #fca5a5;
    }

    /* Status Select Styling */
    .custom-select option[value=""] {
        color: #6c757d;
    }

    body.dark-theme .custom-select option[value=""] {
        color: #64748b;
    }

    .custom-select:invalid {
        color: #6c757d;
    }

    body.dark-theme .custom-select:invalid {
        color: #64748b;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1200px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .proyek-main {
            padding: 1.5rem;
        }

        .form-container {
            padding: 30px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-title {
            font-size: 28px;
        }
    }

    @media (max-width: 768px) {
        .proyek-main {
            padding: 1rem;
        }

        .form-container {
            padding: 20px;
        }

        .form-title {
            font-size: 24px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-batal,
        .btn-simpan {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .custom-input,
        .custom-select,
        .custom-textarea {
            font-size: 14px;
            padding: 12px 15px;
        }

        .btn-batal,
        .btn-simpan {
            font-size: 14px;
            padding: 12px 30px;
        }
    }

    /* ========== SMOOTH TRANSITIONS ========== */
    .proyek-main,
    .form-container,
    .form-title,
    .custom-input,
    .custom-select,
    .custom-textarea,
    .btn-batal,
    .btn-simpan,
    .alert-validation,
    .invalid-feedback {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<main class="proyek-main">
    <div class="form-container">
        <h1 class="form-title">EDIT PROYEK</h1>

        {{-- Pesan error validasi --}}
        @if ($errors->any())
            <div class="alert-validation">
                <strong><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit Proyek --}}
        <form action="{{ route('proyek.update', $proyek->id_proyek) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Baris 1: Nama Proyek, Nama Owner, Nomor HP, Status (4 Kolom) --}}
            <div class="form-row">
                <div>
                    <input type="text" 
                           name="nama_proyek" 
                           class="custom-input @error('nama_proyek') is-invalid @enderror"
                           placeholder="Masukkan Nama Proyek" 
                           value="{{ old('nama_proyek', $proyek->nama_proyek) }}" 
                           required>
                    @error('nama_proyek')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="text" 
                           name="nama_owner" 
                           class="custom-input @error('nama_owner') is-invalid @enderror"
                           placeholder="Masukkan Nama Owner" 
                           value="{{ old('nama_owner', $proyek->nama_owner) }}">
                    @error('nama_owner')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <input type="text" 
                           name="nomor_hp" 
                           class="custom-input @error('nomor_hp') is-invalid @enderror"
                           placeholder="Masukkan Nomor Hp" 
                           value="{{ old('nomor_hp', $proyek->nomor_hp) }}">
                    @error('nomor_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <select name="status" 
                            class="custom-select @error('status') is-invalid @enderror" 
                            required>
                        <option value="" disabled {{ old('status', $proyek->status) == '' ? 'selected' : '' }}>
                            Pilih Status
                        </option>
                        <option value="RAB Belum Dibuat" {{ old('status', $proyek->status) == 'RAB Belum Dibuat' ? 'selected' : '' }}>
                            RAB Belum Dibuat
                        </option>
                        <option value="RAB Telah Dibuat" {{ old('status', $proyek->status) == 'RAB Telah Dibuat' ? 'selected' : '' }}>
                            RAB Telah Dibuat
                        </option>
                        <option value="Proyek Dikerjakan" {{ old('status', $proyek->status) == 'Proyek Dikerjakan' ? 'selected' : '' }}>
                            Proyek Dikerjakan
                        </option>
                        <option value="Proyek Selesai Dikerjakan" {{ old('status', $proyek->status) == 'Proyek Selesai Dikerjakan' ? 'selected' : '' }}>
                            Proyek Selesai Dikerjakan
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Baris 2: Deskripsi (Full Width) --}}
            <div class="form-row">
                <div class="form-group-full">
                    <textarea name="deskripsi" 
                              class="custom-textarea @error('deskripsi') is-invalid @enderror"
                              placeholder="Masukkan Deskripsi Proyek">{{ old('deskripsi', $proyek->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="form-actions">
                <a href="{{ route('proyek.index') }}" class="btn-batal">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-simpan">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    console.log('✅ Edit Proyek loaded successfully with Dark Theme Support!');
    
    // Auto dismiss validation alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-validation');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 8000);
        });
    });
</script>
@endsection