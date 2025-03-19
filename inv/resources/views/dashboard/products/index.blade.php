@extends('layouts.main')

@section('container')

@if (session('message'))
   <div id="toast-container" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg">
       <span class="font-semibold">{{ session()->get('message') }}</span>
   </div>
@endif

<div class="container mx-auto px-4">
    <div class="bg-white mt-5 p-6 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <div>
                <h2 class="text-gray-800 font-bold text-2xl">Data Barang</h2>
                <div class="mt-2 flex gap-3">
                    <a href="/input-barang" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md text-sm">Input Barang</a>
                    <a href="/excel/products" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md text-sm">Export Excel</a>
                </div>
            </div>
            <form method="get" action="/barang" class="flex mt-3 md:mt-0">
                <input id="search" name="search" class="border border-gray-300 p-2 rounded-l text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" type="text" placeholder="Cari barang...">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 p-2 rounded-r text-white text-sm">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr class="text-gray-700">
                        <th class="p-3 border">No</th>
                        <th class="p-3 border">Nama Barang</th>
                        <th class="p-3 border">Harga</th>
                        <th class="p-3 border">Jumlah</th>
                        <th class="p-3 border">Kategori</th>
                        <th class="p-3 border">Gambar</th>
                        <th class="p-3 border">QR Code</th>
                        <th class="p-3 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 border text-center">{{ $index + 1 }}</td>
                            <td class="p-3 border">{{ $product->name }}</td>
                            <td class="p-3 border">Rp.{{ number_format($product->price, 0) }}</td>
                            <td class="p-3 border">{{ $product->stock }}</td>
                            <td class="p-3 border">{{ $product->category }}</td>
                            <td class="p-3 border text-center">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 rounded shadow-md">
                            </td>
                            <td class="p-3 border text-center">
                                @if ($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" class="w-16 h-16 rounded shadow-md">
                                @else
                                    <a href="{{ route('products.qr', $product->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Generate QR</a>
                                @endif
                            </td>
                            <td class="p-3 border text-center flex gap-2 justify-center">
                                <button data-id="{{ $product->id }}" class="btn-delete-product bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm shadow-md">
                                    <i class="ri-delete-bin-line"></i> Hapus
                                </button>
                                <a href="/ubah-barang/{{ $product->id }}" class="bg-yellow-400 hover:bg-yellow-500 text-white py-1 px-3 rounded text-sm shadow-md">
                                    <i class="ri-edit-box-line"></i> Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection