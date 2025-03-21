<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="{{ asset('img/Favicon akti.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showForm(action) {
            document.getElementById('formContainer').style.display = 'block';
            
            if (action === 'tambah') {
                document.getElementById('labelInput').innerText = 'Jumlah Barang Masuk:';
                document.getElementById('inputField').setAttribute('name', 'stock_value'); // FIXED
                document.getElementById('inputField').setAttribute('placeholder', 'Masukkan jumlah barang masuk');
                document.getElementById('inputField').setAttribute('min', '1');
                document.getElementById('inputField').value = '';
                document.getElementById('actionType').value = 'tambah';
            } else {
                document.getElementById('labelInput').innerText = 'Jumlah Barang Keluar:';
                document.getElementById('inputField').setAttribute('name', 'stock_value'); // FIXED
                document.getElementById('inputField').setAttribute('placeholder', 'Masukkan jumlah barang keluar');
                document.getElementById('inputField').setAttribute('min', '1');
                document.getElementById('inputField').value = '';
                document.getElementById('actionType').value = 'kurang';
            }
        }
    </script>
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
                    <p class="text-gray-600 mt-2">Jumlah Stok: <span class="font-bold" id="currentStock">{{ $product->stock }}</span></p>

                    <!-- Pilihan Tambah atau Kurangi Stok -->
                    <div class="mt-4">
                        <button onclick="showForm('tambah')" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-700">Tambah Stok</button>
                        <button onclick="showForm('kurang')" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 ml-2">Kurangi Stok</button>
                    </div>

                    <!-- Form Input Barang Masuk/Keluar (Hidden by Default) -->
                    <div id="formContainer" class="mt-4 hidden">
                        <form action="{{ route('updateStock', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" id="actionType" name="action_type" value="">
                        
                            <label id="labelInput" class="block text-gray-700"></label>
                            <input type="number" id="inputField" name="stock_value" class="w-full px-3 py-2 border rounded-lg shadow-sm" required>
                        
                            <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 w-full">
                                Simpan Perubahan
                            </button>
                        </form>
                        
                    </div>

                    <a href="/" class="mt-5 inline-block bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-700">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
