<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 10px 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .logo {
            width: 130px;
            height: auto;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            flex: 1;
            padding-left: 10px;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 4px 6px;
            text-align: center;
        }

        th {
            background-color: #f3f4f6;
            font-size: 11px;
        }

        td {
            font-size: 11px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        .footer {
            text-align: left;
            margin-top: 10px;
            font-size: 10px;
            color: #00000;
        }

        .ttd-table {
            width: 100%;
            margin-top: 10px;
            font-size: 10px;
            border-collapse: collapse;
        }

        .ttd-table td {
            border: 1px solid #000;
            height: 60px;
            vertical-align: top;
            text-align: center;
            padding-top: 10px;
        }

        .ttd-empty {
            border: none !important;
            width: 2%;
        }

        .contact-info {
            padding-top: 10px;
            font-size: 10px;
            border: none !important;
        }

        .note-list {
            font-size: 9.5px;
            margin-top: 10px;
            padding-left: 16px;
        }

        .note-list li {
            margin-bottom: 3px;
        }
        
        .signature-container {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: 20px;
        }
        
        .signature-box {
            border-collapse: collapse;
            width: 140px;
            height: 100px;
            border: 1px solid black;
            text-align: center;
        }
        
        .signature-line {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            border-top: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('img/Logo (3295x1171).png') }}" alt="Logo" class="logo">
            <div class="title">FORM PERMINTAAN BARANG</div>
        </div>

        <table style="width: 100%; table-layout: fixed; margin-top: 5px;">
            <thead>
                <tr>
                    <th style="width: 5%;">NO</th>
                    <th style="width: 30%;">NAMA BARANG</th>
                    <th style="width: 10%;">QTY</th>
                    <th style="width: 15%;">Vendor TMMIN</th>
                    <th style="width: 15%;">Vendor AKTI</th>
                    <th style="width: 30%;">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $order)
                    <tr>
                        <td style="height: 50px;">{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>  
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->vendor_tmmin ?? '-' }}</td>
                        <td>{{ $order->vendor_akti ?? '-' }}</td>
                        <td>{{ $order->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            
        </table>
        

        <div class="footer">
            Dicetak pada: {{ now()->format('d F Y H:i') }}
        </div>
        

        <table style="width: 50%; margin-top: 10px; margin-bottom: 15px;">
            <thead>
                <tr>
                    <th style="width: 30%; text-align: center;">Mengetahui</th>
                    <th style="width: 30%; text-align: center;">Disetujui</th>
                    <th style="width: 30%; text-align: center;">Pemohon</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <!-- TTD SARPRAS -->
                    <td style="height: 40px; text-align: center; vertical-align: bottom;"></td>

                    <!-- TTD KAUNIT -->
                    <td style="text-align: center; vertical-align: bottom;">
                        @if($order->kaunit->ttd)
                            <img src="{{ storage_path('app/public/' . $order->kaunit->ttd) }}" style="max-height: 40px;">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    </td>

                    <!-- TTD PEMOHON -->
                    <td style="text-align: center; vertical-align: bottom;">
                        @if($order->user->ttd)
                            <img src="{{ storage_path('app/public/' . $order->user->ttd) }}" style="max-height: 40px;">
                        @else
                            <div style="height: 60px;"></div>
                        @endif
                    </td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th style="width: 30%; text-align: center;">Sarpras</th>
                    <th style="width: 30%; text-align: center;">{{ $order->kaunit->name ?? '-' }}</th>
                    <th style="width: 30%; text-align: center;">{{ $order->user->name ?? '-' }}</th>
                </tr>
            </thead>
        </table>

        <div style="font-size: 10px; margin-top: 10px; margin-bottom: 20px;">
            Ext. : <br>
            HP. No :
        </div>

        <ul class="note-list">
            <li>Disetujui oleh Ka. Unit</li>
            <li>Barang Konsumable dapat diterima selama 1 minggu dari pengajuan</li>
            <li>Barang Non Stock dapat diterima selama 2 minggu dari pengajuan</li>
            <li>Barang Impor dapat diterima selama 3 bulan dari pengajuan</li>
            <li>Barang yang datang harus diambil sesuai jumlah permintaan</li>
        </ul>

        <div style="font-size: 9px; text-align: center; margin-top: 15px;">
            Dibuat oleh Website Inventori AKTI
        </div>

    </div>
</body>
</html>