<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - PT Surabaya Las</title>
    
    {{-- CSS Login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="login-wrapper">
        
        {{-- ===========================
            BAGIAN KIRI - FORM LOGIN
            =========================== --}}
        <div class="login-left">
            <div class="login-container">
                
                {{-- Logo dan Judul --}}
                <div class="login-header">
                    <div class="logo-circle">
                        {{-- ✅ DIPERBAIKI: Gunakan tag <img> bukan <span> --}}
                        <img 
                            src="{{ asset('images/SURABAYA LAS INTI (2).png') }}" 
                            alt="Logo PT Surabaya Las" 
                            class="logo-img"
                        >
                    </div>
                    <h1 class="login-title">LOGIN PT SURABAYA LAS</h1>
                </div>

                {{-- Alert Error --}}
                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="bi bi-exclamation-circle"></i>
                        @if($errors->has('login'))
                            {{ $errors->first('login') }}
                        @elseif($errors->has('username'))
                            {{ $errors->first('username') }}
                        @else
                            {{ $errors->first() }}
                        @endif
                    </div>
                @endif

                {{-- Alert Success (untuk logout) --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Login --}}
                <form method="POST" action="{{ route('login.post') }}" class="login-form">
                    @csrf

                    {{-- Input Username --}}
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <input 
                            type="text" 
                            name="username" 
                            class="form-input" 
                            placeholder="Username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                        >
                    </div>

                    {{-- Input Password --}}
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Password"
                            required
                        >
                    </div>

                    {{-- Remember Me (Opsional) --}}
                    <div class="remember-group">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" class="remember-checkbox">
                            <span>Ingat Saya</span>
                        </label>
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit" class="btn-login">
                        Login
                        <i class="bi bi-arrow-right-circle"></i>
                    </button>

                </form>

                {{-- Footer - DIPERBAIKI: Ganti Copyright dengan Link Kembali ke Beranda --}}
                <div class="login-footer">
                    <a href="{{ url('/') }}" class="back-to-home">
                        <i class="bi bi-arrow-left-circle"></i>
                        Kembali kehalaman Beranda
                    </a>
                </div>

            </div>
        </div>

        {{-- ===========================
            BAGIAN KANAN - ILUSTRASI
            =========================== --}}
        <div class="login-right">
            <div class="illustration-container">
                {{-- Gambar Ilustrasi --}}
                <img 
                    src="{{ asset('images/login-illustration.png') }}" 
                    alt="Login Illustration" 
                    class="illustration-img"
                    onerror="this.style.display='none'"
                >
            </div>
        </div>

    </div>

    {{-- JavaScript untuk auto hide alert --}}
    <script>
        // Auto hide alert setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>