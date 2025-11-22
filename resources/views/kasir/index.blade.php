<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
</head>
<body>
    <h1>Dashboard Kasir</h1>
    <p>Selamat datang, Kasir!</p>

    <ul>
        <li><a href="{{ route('kasir.penjualan') }}">Kelola Penjualan</a></li>
        <li><a href="{{ route('laporan.index') }}">Lihat Laporan</a></li>
        <li><a href="{{ route('logout') }}">Logout</a></li>
    </ul>
</body>
</html>
