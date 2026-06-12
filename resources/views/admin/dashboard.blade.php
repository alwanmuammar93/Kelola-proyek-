@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
<style>
    /* ========== DASHBOARD ADMIN SPECIFIC STYLES ========== */
    
    /* Main Content */
    .dashboard-main {
        margin-left: 0;
        padding: 2rem;
        padding-top: 66px;
        min-height: calc(100vh - 66px);
        background-color: #f8f9fa;
        width: 100%;
    }

    /* Welcome Section */
    .welcome-section {
        background: white;
        padding: 25px 40px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .welcome-section h1 {
        color: #ff3333;
        font-size: 32px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 0;
    }

    .welcome-subtitle {
        color: #0A0E4F;
        font-size: 14px;
        margin-top: 10px;
        font-weight: 500;
        opacity: 0.85;
    }

    /* ========== PEMBERITAHUAN SECTION ========== */
    .notification-section {
        background: #E8E8F5;
        padding: 20px 30px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .notification-header {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0A0E4F;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .notification-header i {
        font-size: 24px;
        color: #0A0E4F;
    }

    .notification-box {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        min-height: 150px;
    }

    .notification-item {
        display: flex;
        align-items: start;
        gap: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 12px;
        border-left: 4px solid #0A0E4F;
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        background: #f0f0f0;
        transform: translateX(5px);
    }

    .notification-item:last-child {
        margin-bottom: 0;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        background: #0A0E4F;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-icon.warning {
        background: #ff9800;
    }

    .notification-icon.success {
        background: #4caf50;
    }

    .notification-icon.info {
        background: #2196f3;
    }

    .notification-content {
        flex: 1;
    }

    .notification-content h4 {
        color: #0A0E4F;
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .notification-content p {
        color: #666;
        font-size: 13px;
        margin: 0;
    }

    .notification-time {
        color: #999;
        font-size: 12px;
        margin-top: 5px;
    }

    .empty-notification {
        text-align: center;
        color: #999;
        padding: 40px 20px;
    }

    .empty-notification i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    /* ========== STATS CARDS ========== */
    .stats-container {
        background: #E8E8F5;
        padding: 30px;
        border-radius: 12px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .stat-card {
        background: white;
        padding: 30px 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: #0A0E4F;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-card.blue::before {
        background: #0A0E4F;
    }

    .stat-card.navy::before {
        background: #1a237e;
    }

    .stat-card.red::before {
        background: #ff3333;
    }

    .stat-card.white::before {
        background: #e0e0e0;
    }

    .stat-card.blue {
        background: white;
    }

    .stat-card.navy {
        background: #0A0E4F;
        color: white;
    }

    .stat-card.red {
        background: #ff3333;
        color: white;
    }

    .stat-card.white {
        background: white;
    }

    .stat-content h3 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .stat-content p {
        font-size: 14px;
        font-weight: 500;
        opacity: 0.9;
    }

    .stat-card.navy .stat-content p,
    .stat-card.red .stat-content p {
        opacity: 1;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        opacity: 0.2;
    }

    .stat-card.navy .stat-icon,
    .stat-card.red .stat-icon {
        opacity: 0.3;
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 992px) {
        .dashboard-main {
            padding: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .dashboard-main {
            padding: 1rem;
        }

        .welcome-section {
            padding: 20px 25px;
        }

        .welcome-section h1 {
            font-size: 24px;
        }

        .notification-section {
            padding: 15px 20px;
        }

        .stats-container {
            padding: 20px 15px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-content h3 {
            font-size: 28px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 28px;
        }
    }

    @media (max-width: 480px) {
        .notification-item {
            flex-direction: column;
            text-align: center;
        }

        .notification-icon {
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
<main class="dashboard-main">

    {{-- Welcome Section --}}
    <div class="welcome-section">
        <h1>SELAMAT DATANG, ADMIN</h1>
        <p class="welcome-subtitle">Silahkan pilih menu sidebar yang tersedia</p>
    </div>

    {{-- Pemberitahuan Section --}}
    <div class="notification-section">
        <div class="notification-header">
            <i class="bi bi-bell-fill"></i>
            <span>Pemberitahuan</span>
        </div>

        <div class="notification-box">
            {{-- Jika ada notifikasi --}}
            @if(isset($notifications) && count($notifications) > 0)
                @foreach($notifications as $notif)
                    <div class="notification-item">
                        <div class="notification-icon {{ $notif['type'] ?? '' }}">
                            <i class="bi {{ $notif['icon'] ?? 'bi-info-circle' }}"></i>
                        </div>
                        <div class="notification-content">
                            <h4>{{ $notif['title'] }}</h4>
                            <p>{{ $notif['message'] }}</p>
                            <div class="notification-time">{{ $notif['time'] ?? 'Baru saja' }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Jika tidak ada notifikasi --}}
                <div class="empty-notification">
                    <i class="bi bi-bell-slash"></i>
                    <p>Tidak ada pemberitahuan saat ini</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Stats Cards Section --}}
    <div class="stats-container">
        <div class="stats-grid">
            {{-- Card 1: Jumlah Proyek (Total Proyek) --}}
            <div class="stat-card blue">
                <div class="stat-content">
                    <h3>{{ $totalPenjualan ?? 0 }}</h3>
                    <p>Jumlah Proyek</p>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>

            {{-- Card 2: Status Proyek (PADAT/SEPI) --}}
            <div class="stat-card navy">
                <div class="stat-content">
                    <h3>{{ $statusProyek['status'] ?? 'N/A' }}</h3>
                    <p>Status Proyek</p>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person-workspace"></i>
                </div>
            </div>

            {{-- Card 3: Kwitansi Belum Lunas (🔥 UPDATED) --}}
            <div class="stat-card red">
                <div class="stat-content">
                    <h3>{{ $kwitansiLunas ?? 0 }}</h3>
                    <p>Kwitansi Belum Lunas</p>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>

            {{-- Card 4: Total Profit --}}
            <div class="stat-card white">
                <div class="stat-content">
                    <h3>Rp{{ number_format($totalProfit ?? 0, 0, ',', '.') }}</h3>
                    <p>Total Profit</p>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@section('scripts')
<script>
    console.log('✅ Dashboard Admin loaded successfully!');
    console.log('📊 Statistik Kwitansi Belum Lunas: {{ $kwitansiLunas ?? 0 }}');
</script>
@endsection