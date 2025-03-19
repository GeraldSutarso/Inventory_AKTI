@extends('layouts.main')

@section('container')

@if (session('message'))
   <div id="toast-container" class="fixed z-50 top-5 right-5 flex items-center max-w-xs p-4 text-sm text-green-600 bg-white border border-green-400 rounded-lg shadow-md animate-fade-in-out">
       <i class="ri-check-line mr-2"></i>
       <span class="font-semibold">{{ session('message') }}</span>
   </div>
@endif

<div class="container mx-auto px-4">
    <div class="bg-white mt-5 p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-gray-700 text-lg font-bold">Data Barang Keluar</h2>
                <a href="/input-barang-keluar" class="mt-2 inline-block px-4 py-2 text-white text-sm font-medium bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    + Input Barang Keluar
                </a>
            </div>
            <form method="get" action="/supplier" class="flex items-center border rounded-lg overflow-hidden">
                <input id="search" name="search" class="p-2 text-sm text-gray-700 focus:outline-none" type="text" placeholder="Cari Barang">
                <button type="submit" class="bg-gray-700 p-2 text-white text-sm hover:bg-gray-800 transition">
                    <i class="ri-search-line"></i>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border-collapse shadow-sm">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Barang</th>
                        <th class="p-3 text-left">Nama Admin</th>
                        <th class="p-3 text-left">Jumlah</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productsOutcome as $index => $productOutcome)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $productOutcome->product->name }}</td>
                        <td class="p-3">{{ $productOutcome->user->name }}</td>
                        <td class="p-3">{{ $productOutcome->quantity }}</td>
                        <td class="p-3">{{ $productOutcome->date }}</td>
                        <td class="p-3 flex justify-center gap-2">
                            <a href="/ubah-barang-keluar/{{ $productOutcome->id }}" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600 transition">
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <button data-id="{{ $productOutcome->id }}" class="btn-delete-product-outcome bg-red-500 p-2 text-white rounded hover:bg-red-600 transition">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-center">
            {{ $productsOutcome->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection
