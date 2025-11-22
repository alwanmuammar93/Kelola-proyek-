<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Publik - Surabaya Las</title>
    <style>
        body {
            font-family: Arial;
            background: #f7f7f7;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 3px 10px rgba(0,0,0,0.1);
        }
        a.btn {
            padding: 10px 16px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a.btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Selamat Datang di PT Surabaya Las</h2>
    <p>Ini adalah halaman publik yang dapat dilihat oleh semua orang.</p>

    <a href="{{ route('login') }}" class="btn">Login Admin / Kasir</a>
</div>

</body>
</html>
