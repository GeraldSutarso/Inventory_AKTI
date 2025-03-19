@extends('layouts.main')

@section('container')

@if (session('message'))
<div id="toast-container" class="fixed top-5 right-5 z-50 p-4 flex items-center text-white bg-green-500 rounded-lg shadow-lg transition-transform transform translate-x-5 opacity-0"
    role="alert">
    <div class="text-sm font-bold">{{ session()->get('message') }}</div>
</div>
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-container');
        toast.classList.remove('translate-x-5', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-x-5', 'opacity-0');
        }, 3000);
    }, 300);
</script>
@endif

<div class="container mx-auto px-4">
    <div class="bg-white mt-5 p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-center mb-4">
            <div class="text-left mb-4 md:mb-0">
                <h2 class="text-lg font-bold text-gray-700">Data Barang Masuk</h2>
                <a href="/input-barang-masuk" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-block mt-2 shadow">
                    + Input Barang Masuk
                </a>
            </div>
            <form method="get" action="/supplier" class="w-full md:w-auto">
                <div class="relative">
                    <input id="search" name="search" class="w-full md:w-64 px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm" type="text" placeholder="Cari barang...">
                    <button type="submit" class="absolute top-1/2 right-3 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">
                        <i class="ri-search-line text-lg"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 bg-white border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-100">
                    <tr class="text-left border-b border-gray-300">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Nama Admin</th>
                        <th class="p-3">Jumlah Barang Masuk</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productsIncome as $index => $productIncome)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3 font-semibold">{{ $productIncome->product->name }}</td>
                        <td class="p-3">{{ $productIncome->user->name }}</td>
                        <td class="p-3">{{ $productIncome->quantity }}</td>
                        <td class="p-3">{{ $productIncome->date }}</td>
                        <td class="p-3 flex justify-center space-x-2">
                            <button data-id="{{ $productIncome->id }}" class="btn-delete-product-income bg-red-500 hover:bg-red-600 py-1 px-4 rounded text-white transition shadow-md">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <a href="/ubah-barang-masuk/{{ $productIncome->id }}" class="bg-yellow-400 hover:bg-yellow-500 py-1 px-4 rounded text-white transition shadow-md">
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-center">
            {{ $productsIncome->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection
