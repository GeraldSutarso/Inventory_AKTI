@extends('layouts.main')

@section('container')

@if (session('message'))
   <div id="toast-container" class="fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
    <div class="text-green-400 text-sm font-bold capitalize">{{ session()->get('message') }}</div>
</div>
@endif

<div class="container px-4">
    <div class="bg-white mt-5 p-5 rounded-lg shadow">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-gray-600 font-bold text-lg">Data Barang</h2>
                <a href="/input-barang" class="text-sm inline-block bg-gray-700 text-white mt-2 px-4 py-2 rounded-lg">Input Barang</a>
                <a href="/excel/products" class="text-sm bg-gray-700 text-white inline-block mt-2 px-4 py-2 rounded-lg">Export Excel</a>
            </div>
            <form method="get" action="/barang" class="flex">
                <input id="search" name="search" class="border p-2 rounded-l text-sm focus:outline-none" type="text" placeholder="Cari barang...">
                <button type="submit" class="bg-gray-700 p-2 rounded-r text-white text-sm">Cari</button>
            </form>
        </div>

        <table class="w-full mt-5 text-sm text-gray-600 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-2 border">No</th>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border">Harga</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Kategori</th>
                    <th class="p-2 border">Gambar</th>
                    <th class="p-2 border">QR Code</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $noProduct = 1;
                @endphp
                @foreach ($products as $product)
                    <tr class="border-b">
                        <td class="p-2 border text-center">{{ $noProduct }}</td>
                        <td class="p-2 border">{{ $product->name }}</td>
                        <td class="p-2 border">Rp.{{ number_format($product->price, 0) }}</td>
                        <td class="p-2 border">{{ $product->stock }}</td>
                        <td class="p-2 border">{{ $product->category }}</td>
                        <td class="p-2 border text-center">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="100">
                        </td>
                        <td class="p-2 border text-center">
                            @if ($product->qr_code)
                                <!-- Remove "public/" from the asset path -->
                                <img src="{{ asset('storage/' . $product->qr_code) }}" class="w-16 h-16 rounded">
                            @else
                                <a href="{{ route('products.qr', $product->id) }}" class="...">
                                    Generate QR
                                </a>
                            @endif
                        </td>
                        <td class="p-2 border flex gap-2 justify-center">
                            <button data-id="{{ $product->id }}" class="btn-delete-product bg-red-500 py-1 px-4 rounded text-white text-sm">
                                <i class="ri-delete-bin-line"></i> Hapus
                            </button>
                            <a href="/ubah-barang/{{ $product->id }}" class="bg-yellow-400 py-1 px-4 rounded text-white text-sm">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @php
                        $noProduct++;
                    @endphp
                @endforeach
            </tbody>
        </table>

        <div class="mt-5">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection
