<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Order - {{ $product->name }}</title>
</head>
<body>
    <h1>Review Order - {{ $product->name }}</h1>

    @if($orders->isEmpty())
        <p>Belum ada order untuk produk ini.</p>
    @else
        <table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Tanggal Order</th>
                    <th>Jumlah</th>
                    <th>Vendor TMMIN</th>
                    <th>Vendor AKTI</th>
                    <th>Keterangan</th>
                    <th>User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->vendor_tmmin ?? '-' }}</td>
                        <td>{{ $order->vendor_akti ?? '-' }}</td>
                        <td>{{ $order->keterangan ?? '-' }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
