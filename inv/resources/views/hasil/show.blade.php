<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tambahkan Tailwind jika diperlukan -->
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 mt-5">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Produk</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>
                <div>
                    <h3 class="text-xl font-semibold">Barang: {{ $product->name }}</h3>
                    <p class="text-gray-600 mt-2">Kategori: <span class="font-bold">{{ $product->category->name }}</span></p>
                    <p class="text-gray-600 mt-2">Jumlah Stok: <span class="font-bold">{{ $product->stock }}</span></p>
                    <a href="/" class="mt-5 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
