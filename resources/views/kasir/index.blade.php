@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('styles')
<style>
    /* ========== DASHBOARD KASIR SPECIFIC STYLES ========== */
    
    /* Main Content */
    .dashboard-main {
        margin-left: 0;
        padding: 2rem;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
    }

    /* Welcome Card */
    .welcome-card {
        background: white;
        padding: 35px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border-left: 5px solid #ff3333;
        transition: all 0.3s ease;
    }

    .welcome-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .welcome-title {
        font-size: 32px;
        color: #d32f2f;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }

    .welcome-subtitle {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin: 0;
    }

    /* ========== DARK THEME SUPPORT ========== */
    body.dark-theme .welcome-card {
        background: #1e293b;
        border-left-color: #ff5555;
    }

    body.dark-theme .welcome-title {
        color: #ff5555;
    }

    body.dark-theme .welcome-subtitle {
        color: #cbd5e1;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .dashboard-main {
            padding: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .dashboard-main {
            padding: 1rem;
        }

        .welcome-card {
            padding: 25px;
        }

        .welcome-title {
            font-size: 24px;
        }

        .welcome-subtitle {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .welcome-title {
            font-size: 20px;
        }
    }

    /* ========== ANIMATIONS ========== */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .welcome-card {
        animation: fadeIn 0.5s ease-out;
        animation-fill-mode: both;
    }

    /* ========== ACCESSIBILITY ========== */
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection

@section('content')
<main class="dashboard-main">

    {{-- Welcome Card --}}
    <div class="welcome-card">
        <h1 class="welcome-title">SELAMAT DATANG, KASIR</h1>
        <p class="welcome-subtitle">Silakan pilih menu di sidebar untuk mengelola sistem.</p>
    </div>

</main>
@endsection

@section('scripts')
<script>
    console.log('✅ Dashboard Kasir loaded successfully!');
</script>
@endsection