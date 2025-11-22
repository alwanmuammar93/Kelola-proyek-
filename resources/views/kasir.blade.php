<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Kasir</title>
</head>
<body>
    <h2>Selamat Datang, {{ $user->username }}!</h2>
    <h3>Anda masuk sebagai Kasir</h3>

    <p>Ini halaman kasir, di sini kamu bisa mengelola transaksi.</p>

    <a href="{{ url('/logout') }}">Logout</a>
</body>
</html>
