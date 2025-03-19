@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4 mt-5">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Produk</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg shadow-md">
            </div>
            <div>
                <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                <p class="text-gray-600 mt-2">Kategori: <span class="font-bold">{{ $product->category }}</span></p>
                <p class="text-gray-600 mt-2">Harga: <span class="font-bold text-green-600">Rp.{{ number_format($product->price, 0) }}</span></p>
                <p class="text-gray-600 mt-2">Jumlah Stok: <span class="font-bold">{{ $product->stock }}</span></p>
                <div class="mt-4">
                    <h4 class="text-lg font-semibold">QR Code:</h4>
                    <img src="{{ asset('storage/' . $product->qr_code) }}" alt="QR Code" class="w-32 h-32 mt-2 rounded shadow-md">
                </div>
                <a href="/" class="mt-5 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
