<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PT Surabaya Las</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            overflow-x: hidden;
        }

        /* ========== HEADER ========== */
        .header {
            position: fixed;
            top: 0;
            left: 270px;
            right: 0;
            height: 70px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
            transition: left 0.3s ease;
        }

        .header.full-width {
            left: 0;
        }

        .hamburger {
            width: 40px;
            height: 40px;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 6px;
            padding: 8px;
        }

        .hamburger span {
            width: 100%;
            height: 3px;
            background: #0A0E4F;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger:hover span {
            background: #ff3333;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #0A0E4F;
            font-weight: 600;
            font-size: 16px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #0A0E4F;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 270px;
            margin-top: 70px;
            padding: 40px 30px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }

        .main-content.full-width {
            margin-left: 0;
        }

        .welcome-section {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .welcome-section h1 {
            color: #ff3333;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
            margin-top: 10px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .welcome-section h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    {{-- ========== SIDEBAR COMPONENT ========== --}}
    @include('components.sidebar-admin')

    {{-- ========== HEADER ========== --}}
    <div class="header" id="header">
        <button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="user-info">
            <div class="user-avatar">👤</div>
            <span>{{ Auth::user()->name ?? 'USERNAME' }}</span>
        </div>
    </div>

    {{-- ========== MAIN CONTENT ========== --}}
    <div class="main-content" id="mainContent">
        <div class="welcome-section">
            <h1>SELAMAT DATANG, ADMIN</h1>
            <p>Silakan pilih menu di sidebar untuk mengelola sistem.</p>
        </div>
    </div>

    {{-- ========== JAVASCRIPT ========== --}}
    <script>
        // Toggle Sidebar
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const header = document.getElementById('header');
        const mainContent = document.getElementById('mainContent');

        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
            header.classList.toggle('full-width');
            mainContent.classList.toggle('full-width');
        });

        // Responsive - Auto hide sidebar on mobile
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('hidden');
                header.classList.add('full-width');
                mainContent.classList.add('full-width');
            } else {
                sidebar.classList.remove('hidden');
                header.classList.remove('full-width');
                mainContent.classList.remove('full-width');
            }
        }

        // Check on load
        checkScreenSize();

        // Check on resize
        window.addEventListener('resize', checkScreenSize);

        console.log('✅ Dashboard Admin loaded successfully!');
    </script>

</body>
</html>