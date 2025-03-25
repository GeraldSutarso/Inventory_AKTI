@extends('layouts.main')

@section('container')
<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-r from-blue-50 to-blue-100 p-4">
    <div class="bg-white shadow-2xl rounded-2xl p-6 w-full max-w-lg text-center transform transition duration-300 hover:scale-105">
        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-4">📷 QR Scanner</h2>

        <!-- Area Kamera QR Scanner -->
        <div id="qr-reader" class="w-full border-4 border-dashed border-blue-400 rounded-lg shadow-md p-2 bg-blue-50"></div>

        <!-- Hasil Scan -->
        <div class="mt-6 bg-gray-100 p-4 rounded-lg shadow-inner">
            <h4 class="text-lg font-semibold text-gray-700">🔍 Hasil Scan:</h4>
            <p id="qr-result" class="text-blue-600 font-medium break-words mt-2 p-2 bg-white rounded-md shadow-sm"></p>

            <!-- Tombol untuk membuka link jika hasilnya URL -->
            <a id="qr-link" href="#" target="_blank" class="hidden mt-4 px-5 py-3 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                🌐 Buka Link
            </a>
        </div>
    </div>
</div>

<!-- Tambahkan library scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        let resultElement = document.getElementById("qr-result");
        let linkElement = document.getElementById("qr-link");

        resultElement.innerText = decodedText; // Tampilkan hasil scan
        resultElement.classList.add("animate-pulse"); // Efek animasi hasil scan

        // Cek apakah hasil scan adalah URL
        if (decodedText.startsWith("http://") || decodedText.startsWith("https://")) {
            linkElement.href = decodedText; // Set link
            linkElement.classList.remove("hidden"); // Tampilkan tombol "Buka Link"
        } else {
            linkElement.classList.add("hidden"); // Sembunyikan tombol jika bukan URL
        }
    }

    function onScanError(errorMessage) {
        console.warn(`QR Scan Error: ${errorMessage}`);
    }

    // Menyesuaikan ukuran scanner berdasarkan ukuran layar
    function getQrboxSize() {
        let width = window.innerWidth;
        if (width < 600) {
            return { width: 200, height: 200 }; // Ukuran lebih kecil untuk HP
        } else {
            return { width: 300, height: 300 }; // Ukuran lebih besar untuk tablet/PC
        }
    }

    let scanner = new Html5QrcodeScanner("qr-reader", {
        fps: 10,
        qrbox: getQrboxSize() // Ukuran responsif
    });

    scanner.render(onScanSuccess, onScanError);
</script>
@endsection
