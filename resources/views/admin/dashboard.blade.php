<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f2f2f2;
        }
        h1 {
            color: #333;
        }
        .menu-box {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.2);
        }
        ul {
            list-style: none;
            padding-left: 0;
        }
        ul li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            margin-top: 20px;
            display: inline-block;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Selamat datang, Admin!</h1>
    <p>Ini adalah halaman utama admin.</p>

    <div class="menu-box">
        <h3>Menu Admin:</h3>
        <ul>
            <li><a href="{{ route('proyek.index') }}">Kelola Proyek</a></li>
            <li><a href="{{ route('penjualan.index') }}">Kelola Penjualan</a></li>

            <!-- PERBAIKAN DISINI -->
            <li><a href="{{ route('rab.select_project') }}">Kelola RAB</a></li>

            <li><a href="{{ route('admin.kwitansi.index') }}">Kelola Kwitansi</a></li>
        </ul>
    </div>

    <a href="{{ route('logout') }}" class="logout">Logout</a>

</body>
</html>
