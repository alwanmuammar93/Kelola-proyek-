<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>RAB - {{ $rab->no_rab }}</title>
    <style>
        @page {
            margin: 2cm 1.5cm;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 9pt;
            margin-bottom: 3px;
        }
        
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 20px 0 15px 0;
            text-decoration: underline;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 100px;
        }
        
        .info-table td:nth-child(2) {
            width: 10px;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        
        .detail-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .detail-table td {
            border: 1px solid #000;
            padding: 6px 5px;
            vertical-align: top;
        }
        
        .detail-table td:nth-child(1) {
            text-align: center;
            width: 30px;
        }
        
        .detail-table td:nth-child(2) {
            width: 40%;
        }
        
        .detail-table td:nth-child(3) {
            text-align: center;
            width: 80px;
        }
        
        .detail-table td:nth-child(4) {
            text-align: center;
            width: 60px;
        }
        
        .detail-table td:nth-child(5) {
            text-align: right;
            width: 100px;
        }
        
        .detail-table td:nth-child(6) {
            text-align: right;
            width: 100px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        
        .keterangan {
            margin: 20px 0;
            font-size: 10pt;
        }
        
        .keterangan ul {
            margin: 5px 0;
            padding-left: 20px;
        }
        
        .keterangan li {
            margin-bottom: 5px;
        }
        
        .payment-info {
            margin: 20px 0;
            font-size: 10pt;
        }
        
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    {{-- Header Perusahaan --}}
    <div class="header">
        <div class="company-name">CV. SURABAYA LAS</div>
        <div class="company-info">
            Jalan poros maros, bandara lama KM 22 makassar. Hp 082188637771 - 082188637778
        </div>
        <div class="company-info">
            Email: surabayalas55@gmail.com
        </div>
    </div>
    
    {{-- Judul --}}
    <div class="title">RENCANA ANGGARAN BIAYA</div>
    
    {{-- Info RAB --}}
    <table class="info-table">
        <tr>
            <td>No</td>
            <td>:</td>
            <td>{{ $rab->no_rab }}</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td>{{ $rab->perihal }}</td>
        </tr>
        <tr>
            <td>Owner</td>
            <td>:</td>
            <td>{{ $rab->owner }}</td>
        </tr>
    </table>
    
    <p style="margin-bottom: 15px; font-size: 10pt;">
        Kami CV. Surabaya Las selaku kontraktor besi dan bangunan mengajukan rencana anggaran biaya<br>
        <strong>{{ $rab->nama_pekerjaan }}</strong>
    </p>
    
    {{-- Tabel Detail Pekerjaan --}}
    <table class="detail-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Rincian pekerjaan</th>
                <th>Ukuran</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rincian as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['rincian'] ?? '' }}</td>
                <td>{{ $item['jumlah'] ?? 0 }} {{ $item['satuan'] ?? '' }}</td>
                <td>{{ $item['satuan'] ?? '' }}</td>
                <td>Rp {{ number_format($item['biaya_material'] ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            
            {{-- Total Row --}}
            <tr class="total-row">
                <td colspan="5" style="text-align: right; padding-right: 10px;">Total</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    {{-- Keterangan --}}
    <div class="keterangan">
        <strong>Keterangan:</strong>
        <ul>
            <li>Jika ada pekerjaan tambahan diluar dari RAB ini maka kami harus mengajukan RAB untuk pekerjaan tambahan terlebih dahulu sebelum dikerjakan.</li>
            <li>DP 50% dari total keseluruhan anggaran</li>
            <li>Pengerjaan terhitung pada saat DP diterima</li>
            <li>Lama pengerjaan ±3 minggu</li>
        </ul>
    </div>
    
    {{-- Info Pembayaran --}}
    <div class="payment-info">
        <strong>Pembayaran dapat melalui via transfer Bank</strong><br>
        BCA 7325394595<br>
        BRI 022401012790530<br>
        <strong>MOCHAMMAD HASANUDDIN</strong>
    </div>
    
    {{-- Tanda Tangan --}}
    <div class="signature">
        <div class="signature-box">
            Makassar, {{ $tanggal }}
            <div class="signature-line">
                MOCHAMMAD HASANUDDIN<br>
                PIMPINAN
            </div>
        </div>
    </div>
</body>
</html>