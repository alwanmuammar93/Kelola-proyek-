<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, Admin!</p>

    <ul>
        {{-- MENU PROYEK --}}
        <li><a href="{{ route('proyek.index') }}">Kelola Proyek</a></li>

        {{-- MENU PENJUALAN --}}
        <li><a href="{{ route('penjualan.index') }}">Kelola Penjualan</a></li>

        {{-- MENU LAPORAN --}}
        <li><a href="{{ route('laporan.index') }}">Laporan</a></li>

        <li><a href="{{ route('rab.select_project') }}">Kelola RAB</a></li>
        <li><a href="{{ route('admin.kwitansi.index') }}">Kelola Kwitansi</a></li>

        {{-- LOGOUT --}}
        <li><a href="{{ route('logout') }}">Logout</a></li>
    </ul>
</body>
</html>

