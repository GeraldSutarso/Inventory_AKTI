@extends('layouts.main')

@section('container')
<div class="container mt-5">
    <h2>QR Scanner</h2>
    <div id="qr-reader" style="width: 100%; max-width: 500px;"></div>
</div>

<!-- Tambahkan library scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        alert("QR Code Detected: " + decodedText);
    }

    function onScanError(errorMessage) {
        console.warn(`QR Scan Error: ${errorMessage}`);
    }

    let scanner = new Html5QrcodeScanner("qr-reader", {
        fps: 10,                // Kecepatan scan (frame per detik)
        qrbox: { width: 250, height: 250 } // Ukuran area scan
    });

    scanner.render(onScanSuccess, onScanError);
</script>
@endsection
