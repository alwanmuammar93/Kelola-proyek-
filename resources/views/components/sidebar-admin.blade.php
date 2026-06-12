<style>
    /* ========== SIDEBAR ADMIN STYLES - WITH DARK THEME SUPPORT ========== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .rab-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 270px;
        height: 100vh;
        background: #0A0E4F;
        color: white;
        padding: 0;
        z-index: 1030;
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    body.dark-theme .rab-sidebar {
        background: linear-gradient(180deg, #e2e8f0 0%, #cbd5e1 100%);
        color: #1e293b;
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.4);
        border-right: 1px solid #94a3b8;
    }

    .rab-sidebar.collapsed {
        width: 80px;
    }

    .rab-sidebar.collapsed .company-logo {
        width: 48px;
        height: 48px;
        margin: 0 auto;
        display: block;
        padding: 4px;
        background: white;
        border-radius: 50%;
    }

    body.dark-theme .rab-sidebar.collapsed .company-logo {
        background: #1e293b;
        border: 2px solid #64748b;
    }

    .rab-sidebar.collapsed .sidebar-logo h2 {
        display: none;
    }

    .rab-sidebar.collapsed .menu-text {
        display: none;
    }

    .rab-sidebar.collapsed .sidebar-header {
        padding: 20px 10px;
    }

    .rab-sidebar.collapsed .sidebar-menu a {
        justify-content: center;
        padding: 18px 0;
        border-left: none;
        border-bottom: 3px solid transparent;
    }

    .rab-sidebar.collapsed .sidebar-menu a:hover {
        background: rgba(255, 255, 255, 0.15);
        border-bottom-color: #ffc107;
    }

    body.dark-theme .rab-sidebar.collapsed .sidebar-menu a:hover {
        background: rgba(10, 14, 79, 0.08);
        border-bottom-color: #3b82f6;
    }

    .rab-sidebar.collapsed .sidebar-menu a.active {
        background: rgba(255, 255, 255, 0.2);
        border-bottom-color: #ffc107;
    }

    body.dark-theme .rab-sidebar.collapsed .sidebar-menu a.active {
        background: rgba(59, 130, 246, 0.15);
        border-bottom-color: #3b82f6;
    }

    .rab-sidebar.collapsed .menu-icon {
        margin: 0;
        font-size: 26px;
        width: auto;
    }

    .rab-sidebar.collapsed .sidebar-footer {
        padding: 15px 8px;
    }

    .rab-sidebar.collapsed .logout-btn {
        padding: 14px 0;
        justify-content: center;
        border-radius: 8px;
    }

    .rab-sidebar.collapsed .logout-btn span:not(.logout-icon) {
        display: none;
    }

    .rab-sidebar.collapsed .logout-icon {
        font-size: 24px;
    }

    .sidebar-header {
        padding: 20px;
        text-align: center;
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    body.dark-theme .sidebar-header {
        background: rgba(30, 41, 59, 0.08);
        border-bottom: 2px solid #94a3b8;
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.3s ease;
    }

    .rab-sidebar.collapsed .sidebar-logo {
        flex-direction: column;
        gap: 0;
    }

    .company-logo {
        width: 50px;
        height: 50px;
        object-fit: contain;
        background: white;
        border-radius: 50%;
        padding: 5px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    body.dark-theme .company-logo {
        background: #1e293b;
        border: 2px solid #64748b;
    }

    .sidebar-logo h2 {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.3;
        text-align: left;
        transition: all 0.3s ease;
        white-space: nowrap;
        color: white;
    }

    body.dark-theme .sidebar-logo h2 {
        color: #0f172a;
        font-weight: 700;
    }

    .sidebar-menu {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu li {
        margin: 0;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 25px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        position: relative;
    }

    body.dark-theme .sidebar-menu a {
        color: #334155;
        font-weight: 500;
    }

    .sidebar-menu a:hover {
        background: rgba(255, 255, 255, 0.15);
        border-left-color: #ffc107;
        color: white;
    }

    body.dark-theme .sidebar-menu a:hover {
        background: rgba(59, 130, 246, 0.12);
        border-left-color: #3b82f6;
        color: #0f172a;
        box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.3);
    }

    .sidebar-menu a.active {
        background: rgba(255, 255, 255, 0.2);
        border-left-color: #ffc107;
        color: white;
        font-weight: 600;
    }

    body.dark-theme .sidebar-menu a.active {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.08) 100%);
        border-left-color: #3b82f6;
        color: #0f172a;
        font-weight: 700;
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.25);
    }

    .menu-icon {
        font-size: 22px;
        width: 28px;
        text-align: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    body.dark-theme .menu-icon {
        filter: grayscale(0.3);
    }

    body.dark-theme .sidebar-menu a:hover .menu-icon,
    body.dark-theme .sidebar-menu a.active .menu-icon {
        filter: grayscale(0);
        transform: scale(1.1);
    }

    .menu-text {
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    body.dark-theme .sidebar-footer {
        border-top: 1px solid #94a3b8;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 12px 20px;
        background: white;
        color: #0A0E4F;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .logout-btn:hover {
        background: #ff3333;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 51, 51, 0.3);
    }

    body.dark-theme .logout-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: 2px solid #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    body.dark-theme .logout-btn:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
        transform: translateY(-2px);
        border-color: #b91c1c;
    }

    .logout-icon {
        font-size: 20px;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .rab-sidebar.collapsed .sidebar-menu a::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 85px;
        top: 50%;
        transform: translateY(-50%);
        background: #1a237e;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 1100;
        pointer-events: none;
        font-size: 14px;
        font-weight: 600;
    }

    body.dark-theme .rab-sidebar.collapsed .sidebar-menu a::after {
        background: #0f172a;
        color: white;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.5);
    }

    .rab-sidebar.collapsed .sidebar-menu a:hover::after {
        opacity: 1;
        visibility: visible;
        left: 90px;
    }

    .rab-sidebar.collapsed .sidebar-menu a::before {
        content: '';
        position: absolute;
        left: 80px;
        top: 50%;
        transform: translateY(-50%);
        border-width: 6px;
        border-style: solid;
        border-color: transparent #1a237e transparent transparent;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1100;
        pointer-events: none;
    }

    body.dark-theme .rab-sidebar.collapsed .sidebar-menu a::before {
        border-color: transparent #0f172a transparent transparent;
    }

    .rab-sidebar.collapsed .sidebar-menu a:hover::before {
        opacity: 1;
        visibility: visible;
        left: 85px;
    }

    .sidebar-menu::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    body.dark-theme .sidebar-menu::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
    }

    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    body.dark-theme .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }

    .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    body.dark-theme .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    @media (max-width: 992px) {
        .rab-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 270px;
            height: 100vh;
            height: 100dvh;
            transform: translateX(-100%);
            transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 1050 !important;
            box-shadow: none;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .rab-sidebar.mobile-open {
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.5);
        }

        body.dark-theme .rab-sidebar.mobile-open {
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.4);
        }

        body.sidebar-mobile-open::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            height: 100dvh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(2px);
            z-index: 1045;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        body.sidebar-mobile-open {
            overflow: hidden;
        }

        .rab-sidebar.collapsed {
            width: 270px;
        }

        .rab-sidebar.collapsed .menu-text {
            display: inline;
        }

        .rab-sidebar.collapsed .sidebar-logo h2 {
            display: block;
        }

        .rab-sidebar.collapsed .sidebar-menu a {
            justify-content: flex-start;
            padding: 15px 25px;
            border-left: 4px solid transparent;
            border-bottom: none;
        }

        .rab-sidebar.collapsed .menu-icon {
            font-size: 22px;
            width: 28px;
        }

        .rab-sidebar.collapsed .logout-btn span:not(.logout-icon) {
            display: inline;
        }

        .rab-sidebar.collapsed .logout-btn {
            padding: 12px 20px;
            justify-content: center;
        }

        .rab-sidebar.collapsed .sidebar-menu a::after,
        .rab-sidebar.collapsed .sidebar-menu a::before {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .sidebar-logo h2 {
            font-size: 14px;
        }

        .company-logo {
            width: 45px;
            height: 45px;
        }

        .sidebar-menu a {
            padding: 12px 20px;
            font-size: 14px;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sidebar-menu li {
        animation: slideIn 0.3s ease-out;
        animation-fill-mode: both;
    }

    .sidebar-menu li:nth-child(1) { animation-delay: 0.1s; }
    .sidebar-menu li:nth-child(2) { animation-delay: 0.15s; }
    .sidebar-menu li:nth-child(3) { animation-delay: 0.2s; }
    .sidebar-menu li:nth-child(4) { animation-delay: 0.25s; }
    .sidebar-menu li:nth-child(5) { animation-delay: 0.3s; }
    .sidebar-menu li:nth-child(6) { animation-delay: 0.35s; }

    .sidebar-menu a:focus,
    .logout-btn:focus {
        outline: 2px solid #ffc107;
        outline-offset: -2px;
    }

    body.dark-theme .sidebar-menu a:focus,
    body.dark-theme .logout-btn:focus {
        outline: 2px solid #3b82f6;
        outline-offset: -2px;
    }

    @media (prefers-contrast: high) {
        .sidebar-menu a {
            border: 1px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            border-color: #ffc107;
        }

        body.dark-theme .sidebar-menu a:hover,
        body.dark-theme .sidebar-menu a.active {
            border-color: #3b82f6;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .rab-sidebar,
        .sidebar-menu a,
        .sidebar-menu li,
        .company-logo,
        .menu-text,
        .logout-btn,
        .menu-icon,
        .logout-icon {
            transition: none !important;
            animation: none !important;
        }
    }

    .rab-sidebar,
    .sidebar-header,
    .company-logo,
    .sidebar-logo h2,
    .sidebar-menu a,
    .menu-icon,
    .menu-text,
    .sidebar-footer,
    .logout-btn {
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .rab-sidebar.collapsed {
        transition: width 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }
</style>

<!-- ========== SIDEBAR COMPONENT ========== -->
<div class="rab-sidebar" id="rabSidebar">
    <!-- Logo Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('images/SURABAYA LAS INTI (2).png') }}" 
                 alt="PT Surabaya Las" 
                 class="company-logo"
                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=PT+Surabaya+Las&background=0A0E4F&color=fff';">
            <h2>PT Surabaya Las</h2>
        </div>
    </div>

    <!-- Menu Navigation -->
    <div class="sidebar-menu">
        <ul>
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.index') }}" 
                   class="{{ Request::is('admin') || Request::is('admin/index') || Request::is('admin/dashboard') ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <span class="menu-icon">🏠</span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <!-- 🔥 FIXED: Kelola Konten (CMS + Katalog) - Route name diperbaiki -->
            <li>
                <a href="{{ route('admin.cms.index') }}" 
                   class="{{ Request::is('admin/konten*') ? 'active' : '' }}"
                   data-tooltip="Kelola Konten">
                    <span class="menu-icon">🌐</span>
                    <span class="menu-text">Kelola Konten</span>
                </a>
            </li>

            <!-- Kelola Proyek -->
            <li>
                <a href="{{ route('proyek.index') }}" 
                   class="{{ Request::is('proyek*') ? 'active' : '' }}"
                   data-tooltip="Kelola Proyek">
                    <span class="menu-icon">📁</span>
                    <span class="menu-text">Kelola Proyek</span>
                </a>
            </li>

            <!-- Kelola Kwitansi -->
            <li>
                <a href="{{ route('admin.kwitansi.index') }}" 
                   class="{{ Request::is('*kwitansi*') ? 'active' : '' }}"
                   data-tooltip="Kelola Kwitansi">
                    <span class="menu-icon">📄</span>
                    <span class="menu-text">Kelola Kwitansi</span>
                </a>
            </li>

            <!-- Kelola RAB -->
            <li>
                <a href="{{ route('rab.index') }}" 
                   class="{{ Request::is('rab*') ? 'active' : '' }}"
                   data-tooltip="Kelola RAB">
                    <span class="menu-icon">💰</span>
                    <span class="menu-text">Kelola RAB</span>
                </a>
            </li>

            <!-- Kelola Laporan -->
            <li>
                <a href="{{ route('laporan.index') }}" 
                   class="{{ Request::is('laporan*') ? 'active' : '' }}"
                   data-tooltip="Kelola Laporan">
                    <span class="menu-icon">📊</span>
                    <span class="menu-text">Kelola Laporan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Logout Button Footer -->
    <div class="sidebar-footer">
        <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-admin').submit();">
            <span class="logout-icon">🚪</span>
            <span>Log Out</span>
        </a>
        <form id="logout-form-sidebar-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<!-- ========== SIDEBAR JAVASCRIPT ========== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('rabSidebar');
        const body = document.body;
        
        if (sidebar && !sidebar.dataset.initialized) {
            sidebar.dataset.initialized = 'true';
            
            console.log('✅ Admin sidebar loaded (Fixed: admin.cms.* routes)');
            
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
            if (sidebarCollapsed === 'true' && window.innerWidth > 992) {
                sidebar.classList.add('collapsed');
                body.classList.add('sidebar-collapsed');
            }

            const menuLinks = sidebar.querySelectorAll('.sidebar-menu a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 992 && sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    const isClickInsideSidebar = sidebar.contains(e.target);
                    const isClickOnToggleButton = e.target.closest('#sidebarToggle');
                    
                    if (!isClickInsideSidebar && !isClickOnToggleButton && sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                    }
                }
            });

            const currentPath = window.location.pathname;
            menuLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href.replace(window.location.origin, '')) && href !== '/') {
                    menuLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            });

            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 992) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                        
                        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
                        if (sidebarCollapsed === 'true') {
                            sidebar.classList.add('collapsed');
                            body.classList.add('sidebar-collapsed');
                        }
                    } else {
                        sidebar.classList.remove('collapsed');
                        body.classList.remove('sidebar-collapsed');
                    }
                }, 250);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth <= 992) {
                    if (sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        body.classList.remove('sidebar-mobile-open');
                    }
                }
            });

            console.log('✅ Admin sidebar fully initialized');
        }
    });
</script>