@extends('layouts.main')

@section('container')
@if (session('message'))
   <div id="toast-container" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('message') }}</span>
   </div>
@endif

<div class="container mx-auto px-6">
    <div class="bg-white mt-6 p-8 rounded-xl shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h2 class="text-gray-900 font-bold text-3xl">Data Barang</h2>
                <div class="mt-3 flex gap-4">
                    <a href="/input-barang" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">+ Tambah Barang</a>
                    <a href="/excel/products" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">⬇ Export Excel</a>
                </div>
            </div>
            <form method="get" action="/barang" class="flex w-full md:w-auto mt-3 md:mt-0">
                <input id="search" name="search" class="border border-gray-300 p-3 rounded-l-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full md:w-64" type="text" placeholder="Cari barang...">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-r-lg text-white text-sm">🔍</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border border-gray-300 rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-4 border">No</th>
                        <th class="p-4 border">Nama Barang</th>
                        <th class="p-4 border">Harga</th>
                        <th class="p-4 border">Jumlah</th>
                        <th class="p-4 border">Lokasi</th>
                        <th class="p-4 border">Gambar</th>
                        <th class="p-4 border">QR Code</th>
                        <th class="p-4 border">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach ($products as $index => $product)
                        <tr class="hover:bg-gray-100">
                            <td class="p-4 text-center">{{ $index + 1 }}</td>
                            <td class="p-4">{{ $product->name }}</td>
                            <td class="p-4 text-green-600 font-semibold">Rp.{{ number_format($product->price, 0) }}</td>
                            <td class="p-4 text-center">{{ $product->stock }}</td>
                            <td class="p-4 text-center">{{ $product->category }}</td>
                            <td class="p-4 text-center">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 rounded-lg shadow-md">
                            </td>
                            <td class="p-4 text-center">
                                @if ($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" class="w-16 h-16 rounded-lg shadow-md">
                                @else
                                    <a href="{{ route('products.qr', $product->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Generate QR</a>
                                @endif
                            </td>
                            <td class="p-4 text-center flex gap-2 justify-center">
                                <a href="/ubah-barang/{{ $product->id }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md text-sm shadow-md font-semibold">✏ Edit</a>
                                <button data-id="{{ $product->id }}" class="btn-delete-product bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md text-sm shadow-md font-semibold">🗑 Hapus</button>
                                <a href="{{ route('products.qr.download', $product->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-sm shadow-md font-semibold">⬇ Download QR</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
