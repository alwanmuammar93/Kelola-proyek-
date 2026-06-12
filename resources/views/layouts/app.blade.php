<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Kelola Proyek - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Font Awesome (UNTUK MODAL BARU) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        /* ========== CRITICAL: BASE LAYOUT - FIXED VERSION ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding-left: 0 !important;
        }

        /* Prevent transition on page load */
        body.no-transition,
        body.no-transition * {
            transition: none !important;
        }

        /* ========== CONTENT WRAPPER - ULTRA SMOOTH TRANSITION ========== */
        .content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 100vh;
            background-color: #f8f9fa;
            width: 100%;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); /* 🔥 Smooth natural easing */
            margin-left: 0;
        }

        /* Content wrapper dengan sidebar */
        body.has-sidebar .content-wrapper {
            margin-left: 270px;
        }

        body.has-sidebar.sidebar-collapsed .content-wrapper {
            margin-left: 80px;
        }

        /* ========== MAIN - FIXED ========== */
        main {
            flex: 1;
            padding: 0;
            margin: 0;
            background-color: #f8f9fa;
            width: 100%;
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding-top: 66px;
            padding-bottom: 40px;
        }

        /* ========== FOOTER - DIHILANGKAN ========== */
        footer {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ========== NAVBAR STYLING - ULTRA SMOOTH TRANSITION ========== */
        .top-navbar {
            background: #ffffff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1031;
            transition: left 0.6s cubic-bezier(0.23, 1, 0.32, 1), background 0.3s ease; /* 🔥 Smooth slide */
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            height: 66px;
            display: flex;
            align-items: center;
        }

        body.has-sidebar .top-navbar {
            left: 270px;
        }

        body.has-sidebar.sidebar-collapsed .top-navbar {
            left: 80px;
        }

        .navbar-left-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 200px;
        }

        /* ============================================
           🔥 SMOOTH HAMBURGER BUTTON - ANIMATED
           ============================================ */
        .sidebar-toggle {
            background: transparent;
            border: 1px solid #ddd;
            color: #0A0E4F;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            position: relative;
            width: 44px;
            height: 44px;
            overflow: hidden;
        }

        .sidebar-toggle:hover {
            background: #f8f9fa;
            border-color: #0A0E4F;
            transform: scale(1.05);
        }

        .sidebar-toggle:active {
            transform: scale(0.95);
        }

        /* 🔥 HAMBURGER ICON - CUSTOM ANIMATED */
        .hamburger-icon {
            width: 24px;
            height: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hamburger-line {
            width: 100%;
            height: 3px;
            background-color: #0A0E4F;
            border-radius: 3px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        /* 🔥 COLLAPSED STATE - X SHAPE */
        .sidebar-toggle.active .hamburger-line:nth-child(1) {
            transform: translateY(8.5px) rotate(45deg);
        }

        .sidebar-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: translateX(-20px);
        }

        .sidebar-toggle.active .hamburger-line:nth-child(3) {
            transform: translateY(-8.5px) rotate(-45deg);
        }

        /* 🔥 DARK THEME - Hamburger */
        body.dark-theme .hamburger-line {
            background-color: #e2e8f0;
        }

        body.dark-theme .sidebar-toggle {
            border-color: rgba(255, 255, 255, 0.2);
            color: #e2e8f0;
        }

        body.dark-theme .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Logo/Brand */
        .navbar-brand-text {
            font-weight: 700;
            color: #0A0E4F;
            font-size: 1.1rem;
            display: none;
        }

        /* Profile dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            background: transparent;
            border: 2px solid #e0e0e0;
            color: #0A0E4F;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .profile-btn:hover {
            background: #f8f9fa;
            border-color: #0A0E4F;
            transform: translateY(-2px);
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
            color: white;
        }

        .user-profile-photo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }

        .profile-name,
        .user-display-name {
            font-weight: 500;
            font-size: 0.95rem;
            color: #0A0E4F;
            transition: color 0.3s ease;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 200px;
            margin-top: 0.5rem;
            z-index: 1040;
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 0.6rem 1.2rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 0.5rem;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        .role-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .role-kasir {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .profile-btn i.bi-chevron-down {
            color: #0A0E4F;
            transition: transform 0.3s ease;
        }

        .profile-btn[aria-expanded="true"] i.bi-chevron-down {
            transform: rotate(180deg);
        }

        body.dark-theme .profile-btn i.bi-chevron-down {
            color: #e2e8f0;
        }

        .container {
            max-width: 1400px;
        }

        /* ========== DARK THEME SUPPORT ========== */
        body.dark-theme {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        body.dark-theme .content-wrapper {
            background-color: #0f172a;
        }

        body.dark-theme main {
            background-color: #0f172a;
        }

        body.dark-theme .top-navbar {
            background: #1e293b !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        body.dark-theme .profile-btn {
            border-color: rgba(255, 255, 255, 0.2);
            color: #e2e8f0;
        }

        body.dark-theme .profile-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
        }

        body.dark-theme .profile-name,
        body.dark-theme .user-display-name {
            color: #e2e8f0;
        }

        body.dark-theme .user-profile-photo {
            border-color: rgba(255, 255, 255, 0.2);
        }

        body.dark-theme .dropdown-menu {
            background: #1a1a2e;
            border: 1px solid #16213e;
        }

        body.dark-theme .dropdown-item {
            color: #e9ecef;
        }

        body.dark-theme .dropdown-item:hover {
            background: #16213e;
        }

        body.dark-theme .navbar-brand-text {
            color: #e2e8f0;
        }

        /* ========== MODAL & SIDEBAR Z-INDEX ========== */
        .modal-overlay {
            z-index: 10050 !important;
        }

        .modal-proyek,
        .modal {
            z-index: 10055 !important;
        }

        .rab-sidebar {
            z-index: 1030 !important;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            body.has-sidebar .content-wrapper {
                margin-left: 0 !important;
            }

            body.has-sidebar.sidebar-collapsed .content-wrapper {
                margin-left: 0 !important;
            }

            .top-navbar {
                left: 0 !important;
            }

            body.has-sidebar .top-navbar,
            body.has-sidebar.sidebar-collapsed .top-navbar {
                left: 0 !important;
            }

            .profile-name,
            .user-display-name {
                display: none;
            }

            .navbar-brand-text {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .profile-name,
            .user-display-name {
                display: none;
            }
        }

        /* ========== SCROLL PREVENTION ========== */
        body.modal-open {
            overflow: hidden;
        }

        body:not(.modal-open):not(.sidebar-mobile-open) {
            overflow-x: hidden;
            overflow-y: auto;
        }

        body.sidebar-mobile-open {
            overflow: hidden !important;
            position: fixed !important;
            width: 100% !important;
        }

        /* ========== SMOOTH SCROLL ========== */
        html {
            scroll-behavior: smooth;
        }
    </style>
    
    @yield('styles')
</head>
<body class="{{ auth()->check() && in_array(auth()->user()->role, ['admin', 'kasir']) ? 'has-sidebar' : '' }} no-transition">
    
    <!-- ========== SIDEBAR BERDASARKAN ROLE ========== -->
    @auth
        @if(auth()->user()->role == 'admin')
            @include('components.sidebar-admin')
        @elseif(auth()->user()->role == 'kasir')
            @include('components.sidebar-kasir')
        @endif
    @endauth

    <!-- Content Wrapper (Navbar + Main) -->
    <div class="content-wrapper">
        
        <!-- ========== NAVBAR UNTUK SEMUA USER ========== -->
        @auth
        <nav class="navbar navbar-expand-lg top-navbar">
            <div class="container-fluid px-4">
                <!-- LEFT SECTION - Hamburger + Logo -->
                <div class="navbar-left-section">
                    <!-- 🔥 SMOOTH HAMBURGER BUTTON -->
                    @if(in_array(auth()->user()->role, ['admin', 'kasir']))
                    <button class="sidebar-toggle" id="sidebarToggle" type="button" title="Toggle Sidebar">
                        <div class="hamburger-icon">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </div>
                    </button>
                    @endif
                    
                    <!-- Brand/Logo (Opsional) -->
                    <span class="navbar-brand-text">PT Surabaya Las</span>
                </div>

                <!-- RIGHT SECTION - Profile Dropdown -->
                <div class="dropdown profile-dropdown ms-auto">
                    <button class="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->profile_photo_url }}" 
                             alt="Profile" 
                             class="user-profile-photo"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="profile-avatar" style="display: none;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-display-name">
                            {{ auth()->user()->name }}
                        </div>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="px-3 py-2">
                                <div class="fw-bold user-display-name">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                <div class="mt-2">
                                    <span class="role-badge role-{{ auth()->user()->role }}">
                                        {{ strtoupper(auth()->user()->role) }}
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.index') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="openUserSettings(); return false;">
                                <i class="bi bi-gear"></i> Pengaturan
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="openEditProfileModal(); return false;">
                                <i class="bi bi-person-circle"></i> Edit Profil
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin')
                        <li>
                            <a class="dropdown-item" href="#" onclick="openPasswordRoleSelectionModal(); return false;">
                                <i class="bi bi-key"></i> Ubah Password
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event)">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

    </div>

    <!-- ========== USER SETTINGS & MODALS ========== -->
    @auth
        @include('components.user-settings')
    @endauth

    @yield('modals')

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>
    <script src="{{ asset('js/firebase-config.js') }}"></script>
    <script src="{{ asset('js/push-notification-manager.js') }}"></script>
    
    <!-- Custom Scripts -->
    <script>
        // ========== INITIALIZE THEME ==========
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.body.classList.remove('no-transition');
            }, 100);
            
            @auth
            const currentTheme = '{{ auth()->user()->theme_preference ?? "light" }}';
            if (currentTheme === 'dark') {
                document.body.classList.add('dark-theme');
            }
            @endauth

            console.log('✅ Page loaded with smooth animations');
        });

        // ========== CONFIRM LOGOUT ==========
        function confirmLogout(event) {
            event.preventDefault();
            if (confirm('Apakah Anda yakin ingin logout?')) {
                document.getElementById('logout-form').submit();
            }
        }

        // ========== 🔥 SMOOTH SIDEBAR TOGGLE - ENHANCED VERSION ==========
        window.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            
            if (sidebarToggle) {
                console.log('✅ Smooth hamburger toggle initialized');
                
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const sidebar = document.querySelector('.rab-sidebar');
                    
                    if (sidebar) {
                        // Desktop: Toggle collapsed
                        if (window.innerWidth > 992) {
                            const isCollapsed = sidebar.classList.toggle('collapsed');
                            body.classList.toggle('sidebar-collapsed');
                            
                            // 🔥 Toggle hamburger animation
                            sidebarToggle.classList.toggle('active', isCollapsed);
                            
                            localStorage.setItem('sidebarCollapsed', isCollapsed);
                            console.log(`🖥️ Sidebar ${isCollapsed ? 'collapsed' : 'expanded'}`);
                        } 
                        // Mobile: Toggle open/close
                        else {
                            const isOpen = sidebar.classList.toggle('mobile-open');
                            body.classList.toggle('sidebar-mobile-open');
                            
                            // 🔥 Toggle hamburger animation
                            sidebarToggle.classList.toggle('active', isOpen);
                            
                            console.log(`📱 Sidebar ${isOpen ? 'opened' : 'closed'}`);
                        }
                    }
                });

                // Restore sidebar state
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
                if (sidebarCollapsed === 'true' && window.innerWidth > 992) {
                    const sidebar = document.querySelector('.rab-sidebar');
                    if (sidebar) {
                        sidebar.classList.add('collapsed');
                        body.classList.add('sidebar-collapsed');
                        sidebarToggle.classList.add('active');
                        console.log('🔄 Restored collapsed state');
                    }
                }
            }

            // Close sidebar on mobile outside click
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    const sidebar = document.querySelector('.rab-sidebar');
                    const sidebarToggleBtn = document.getElementById('sidebarToggle');
                    
                    if (sidebar && 
                        sidebar.classList.contains('mobile-open') && 
                        !sidebar.contains(e.target) && 
                        (!sidebarToggleBtn || !sidebarToggleBtn.contains(e.target))) {
                        
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                        
                        if (sidebarToggleBtn) {
                            sidebarToggleBtn.classList.remove('active');
                        }
                        
                        console.log('📱 Sidebar closed by outside click');
                    }
                }
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const sidebar = document.querySelector('.rab-sidebar');
                    const toggleBtn = document.getElementById('sidebarToggle');
                    
                    if (window.innerWidth > 992) {
                        if (sidebar) sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                        
                        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
                        if (sidebarCollapsed === 'true' && sidebar) {
                            sidebar.classList.add('collapsed');
                            body.classList.add('sidebar-collapsed');
                            if (toggleBtn) toggleBtn.classList.add('active');
                        }
                    } else {
                        if (sidebar) sidebar.classList.remove('collapsed');
                        body.classList.remove('sidebar-collapsed');
                        if (toggleBtn) toggleBtn.classList.remove('active');
                    }
                }, 250);
            });
        });

        // ========== AUTO-HIDE ALERTS ==========
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // ========== KEYBOARD SHORTCUTS ==========
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                @auth
                openUserSettings();
                @endauth
            }
        });

        console.log('🎉 Smooth App System Loaded!');
        console.log('🔥 Smooth hamburger animation ready');
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>