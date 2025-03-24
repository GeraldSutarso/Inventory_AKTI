@extends('layouts.main')

@section('container')
@if (session('message'))
   <div id="toast-container" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('message') }}</span>
   </div>
@endif

@if (session('success'))
   <div id="success-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('success') }}</span>
   </div>
@endif

@if (session('warning'))
   <div id="warning-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-yellow-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('warning') }}</span>
   </div>
@endif

@if (session('danger'))
   <div id="danger-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-red-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('danger') }}</span>
   </div>
@endif

@if (session('error'))
   <div id="error-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-red-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('error') }}</span>
   </div>
@endif

<div class="container mx-auto px-6">
    <div class="bg-white mt-6 p-8 rounded-xl shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h2 class="text-gray-900 font-bold text-3xl">Data Barang</h2>
                <div class="mt-3 flex gap-4">
                    <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">+ Tambah Barang</a>
                    <a href="/excel/products" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">⬇ Export Excel</a>
                </div>
            </div>
            <form method="get" action="/barang" class="flex w-full md:w-auto mt-3 md:mt-0">
                <input id="search" name="search" class="border border-gray-300 p-3 rounded-l-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full md:w-64" type="text" placeholder="Cari barang...">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-r-lg text-white text-sm">
                    <i class="ri-search-line text-lg"></i>
                </button>
            </form>
        </div>

        <div class="mb-4">
            <div class="flex items-center justify-start space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-400 rounded mr-2"></div>
                    <span>Stok Normal</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-100 border border-yellow-400 rounded mr-2"></div>
                    <span>Stok Di Bawah Minimum</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-100 border border-red-400 rounded mr-2"></div>
                    <span>Stok Habis</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-400 rounded mr-2"></div>
                    <span>Stok Melebihi Maksimum</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">No</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-left">Nama Barang</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Harga</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Stok</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Min Stok</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Max Stok</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Lokasi</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Gambar</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">QR Code</th>
                        <th class="p-2 md:p-3 border border-gray-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach ($products as $index => $product)
                        @php
                            $rowClass = '';
                            if ($product->stock <= 0) {
                                $rowClass = 'bg-red-100';
                            } elseif ($product->stock < $product->stock_min) {
                                $rowClass = 'bg-yellow-100';
                            } elseif ($product->stock > $product->stock_max) {
                                $rowClass = 'bg-blue-100';
                            } else {
                                $rowClass = 'bg-green-100';
                            }
                        @endphp
                        <tr class="{{ $rowClass }} hover:bg-opacity-80">
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $index + 1 }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-left break-words">{{ $product->name }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center text-green-600 font-semibold">Rp.{{ number_format($product->price, 0) }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center font-bold">{{ $product->stock }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->stock_min }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->stock_max }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center break-words">{{ $product->category }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                    class="w-20 h-20 md:w-24 md:h-24 mx-auto object-cover rounded-lg shadow-md">
                            </td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                @if ($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" 
                                        alt="QR Code for {{ $product->name }}"
                                        class="w-24 h-24 md:w-28 md:h-28 mx-auto object-contain rounded-lg shadow-md">
                                @else
                                    <a href="{{ route('products.qr', $product->id) }}" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">Generate QR</a>
                                @endif
                            </td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                <div class="flex flex-wrap gap-1 md:gap-2 justify-center">
                                    <a href="{{ route('barang.edit', $product->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-2 md:py-2 md:px-3 rounded-md text-xs md:text-sm shadow-md font-semibold">✏ Edit</a>
                                    <button data-id="{{ $product->id }}" class="btn-delete-product bg-red-500 hover:bg-red-600 text-white py-1 px-2 md:py-2 md:px-3 rounded-md text-xs md:text-sm shadow-md font-semibold">🗑 Hapus</button>
                                    <a href="{{ route('products.qr.download', $product->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 md:py-2 md:px-3 rounded-md text-xs md:text-sm shadow-md font-semibold">⬇ Download QR</a>
                                    <a href="{{ route('supplies.create') }}?redirect_to={{ url()->full() }}" 
                                        class="bg-purple-500 hover:bg-purple-600 text-white py-1 px-2 md:py-2 md:px-3 rounded-md text-xs md:text-sm shadow-md font-semibold">📦 Update Stok</a>
                                </div>
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



<script>
    // Toast notifications auto-hide
    setTimeout(() => {
        const toasts = document.querySelectorAll('#toast-container, #success-toast, #warning-toast, #danger-toast, #error-toast');
        toasts.forEach(toast => {
            if (toast) {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        });
    }, 3000);

    // Delete product functionality
    document.querySelectorAll('.btn-delete-product').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                const id = this.getAttribute('data-id');
                fetch(`/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'success delete data') {
                        location.reload();
                    }
                });
            }
        });
    });

</script>
@endsection