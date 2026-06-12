<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Kwitansi - {{ $no_kwitansi }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            color: #000;
            background: #fff;
            position: relative;
        }
        
        .page-wrapper {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
        }
        
        .page-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 240mm;
            height: 130mm;
            padding: 15px 22px 15px 25px;
            border: 1px solid #e0e0e0;
            background: white;
        }
        
        /* SIDEBAR */
        .sidebar {
            position: absolute;
            right: 0;
            top: 0;
            width: 45px;
            height: 130mm;
            background-color: #1a1a4d;
        }
        
        .sidebar-text-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-90deg);
            width: 130mm;
            text-align: center;
            color: white;
            white-space: nowrap;
        }
        
        .sidebar-kwitansi {
            font-size: 10pt;
            letter-spacing: 10px;
            font-weight: normal;
        }
        
        .sidebar-spacing {
            display: inline-block;
            width: 100px;
        }
        
        .sidebar-no {
            font-size: 7.5pt;
            letter-spacing: 3px;
        }
        
        /* CONTENT WRAPPER - untuk avoid sidebar */
        .content-wrapper {
            margin-right: 50px;
        }
        
        /* HEADER */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }
        
        .logo-section {
            display: table;
        }
        
        .logo {
            display: table-cell;
            width: 50px;
            vertical-align: middle;
        }
        
        .logo img {
            width: 42px;
            height: 42px;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 8px;
        }
        
        .company-name {
            font-size: 9pt;
            font-weight: bold;
            line-height: 1.1;
        }
        
        .company-location {
            font-size: 6.5pt;
            color: #333;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            position: relative;
            height: 45px;
        }
        
        .decorative-circles {
            position: absolute;
            right: 0;
            top: 0;
            width: 110px;
            height: 45px;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
        }
        
        .circle-red-1 {
            width: 40px;
            height: 40px;
            background-color: #dc3545;
            top: 2px;
            right: 60px;
        }
        
        .circle-yellow-1 {
            width: 32px;
            height: 32px;
            background-color: #ffc107;
            top: 9px;
            right: 30px;
        }
        
        .circle-red-2 {
            width: 38px;
            height: 38px;
            background-color: #dc3545;
            top: 3px;
            right: 0;
            opacity: 0.9;
        }
        
        /* TITLE */
        .title-section {
            text-align: center;
            margin: 8px 0 10px 0;
        }
        
        .main-title {
            font-size: 22pt;
            font-weight: bold;
            letter-spacing: 16px;
            color: #000;
        }
        
        /* CONTENT BOX */
        .content-box {
            background-color: #1a1a4d;
            color: white;
            padding: 12px 18px;
            margin: 10px 0;
        }
        
        .content-row {
            margin-bottom: 6px;
            font-size: 7.5pt;
            line-height: 1.5;
        }
        
        .content-row:last-child {
            margin-bottom: 0;
        }
        
        .content-label {
            display: inline-block;
            width: 140px;
            font-weight: bold;
            vertical-align: top;
        }
        
        .content-value {
            display: inline;
            vertical-align: top;
        }
        
        .content-value-large {
            font-size: 8.5pt;
            font-weight: bold;
        }
        
        /* INFO SECTION */
        .info-section {
            margin-top: 12px;
            text-align: right;
            padding-right: 5px;
        }
        
        .date {
            font-size: 6.5pt;
            margin-bottom: 2px;
            color: #333;
        }
        
        .position-label {
            font-size: 7pt;
            font-weight: bold;
            color: #1a1a4d;
            margin-bottom: 30px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 7pt;
            border-bottom: 1.5px dotted #333;
            display: inline-block;
            padding-bottom: 2px;
            min-width: 170px;
            text-align: center;
        }
        
        .address-info {
            margin-top: 10px;
            font-size: 5.5pt;
            line-height: 1.5;
            color: #333;
        }
        
        .address-row {
            margin-bottom: 1px;
        }
        
        /* FOOTER */
        .footer-quote {
            position: absolute;
            bottom: 12px;
            left: 25px;
            right: 70px;
            text-align: center;
            font-style: italic;
            color: #666;
            font-size: 6.5pt;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="page-container">
            <!-- SIDEBAR -->
            <div class="sidebar">
                <div class="sidebar-text-wrapper">
                    <span class="sidebar-kwitansi">KWITANSI</span>
                    <span class="sidebar-spacing"></span>
                    <span class="sidebar-no">NO.: {{ $no_kwitansi }}</span>
                </div>
            </div>
            
            <!-- CONTENT WRAPPER (avoid sidebar) -->
            <div class="content-wrapper">
                <!-- HEADER -->
                <div class="header">
                    <div class="header-left">
                        <div class="logo-section">
                            <div class="logo">
                                <img src="{{ public_path('images/logo-surabaya-las.png') }}" alt="Logo">
                            </div>
                            <div class="company-info">
                                <div class="company-name">PT SURABAYA LAS</div>
                                <div class="company-location">Marusu, Kab Maros</div>
                            </div>
                        </div>
                    </div>
                    <div class="header-right">
                        <div class="decorative-circles">
                            <div class="circle circle-red-1"></div>
                            <div class="circle circle-yellow-1"></div>
                            <div class="circle circle-red-2"></div>
                        </div>
                    </div>
                </div>

                <!-- TITLE -->
                <div class="title-section">
                    <h1 class="main-title">KWITANSI</h1>
                </div>

                <!-- CONTENT BOX -->
                <div class="content-box">
                    <div class="content-row">
                        <span class="content-label">TELAH DITERIMA DARI:</span>
                        <span class="content-value">
                            @if($kwitansi->Sumber_Tabel === 'penjualan' || $kwitansi->Sumber_Tabel === 'penjualans')
                                {{ $sumber->nama_sales ?? 'N/A' }}
                            @elseif($kwitansi->Sumber_Tabel === 'rabs' || $kwitansi->Sumber_Tabel === 'rab')
                                {{ $sumber->nama_pekerjaan ?? 'N/A' }}
                            @else
                                {{ $kwitansi->Sales ?? 'N/A' }}
                            @endif
                        </span>
                    </div>
                    
                    <div class="content-row">
                        <span class="content-label">UANG SEJUMLAH:</span>
                        <span class="content-value">Rp {{ number_format($kwitansi->Total_Pembayaran ?? 0, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="content-row">
                        <span class="content-label">UNTUK:</span>
                        <span class="content-value">{{ $kwitansi->Untuk_Pembayaran ?? 'Pembayaran ' . ucfirst($kwitansi->Sumber_Tabel) }}</span>
                    </div>
                    
                    <div class="content-row">
                        <span class="content-label">CATATAN:</span>
                        <span class="content-value">Metode Pembayaran: {{ $kwitansi->Metode_Pembayaran ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="content-row">
                        <span class="content-label"></span>
                        <span class="content-value content-value-large">Status: {{ $kwitansi->Status ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="content-row">
                        <span class="content-label"></span>
                        <span class="content-value content-value-large">Total Tagihan: Rp {{ number_format($kwitansi->Total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- INFO SECTION -->
                <div class="info-section">
                    <div class="date">{{ $tanggal }}</div>
                    <div class="position-label">PIMPINAN</div>
                    <div class="signature-name">MOCHAMMAD HASANUDDIN</div>
                    
                    <div class="address-info">
                        <div class="address-row">📍 Jl. Bandara lama, Marumpa, Kec marusu, Kabupaten maros</div>
                        <div class="address-row">📱 082 188 637 778</div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="footer-quote">
                    "Kualitas & Kepuasan Pelanggan adalah Prioritas Kami"
                </div>
            </div>
        </div>
    </div>
</body>
</html>