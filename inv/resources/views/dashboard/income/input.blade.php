@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg max-w-lg mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-xl">Input Data Barang Masuk</h2>
            <a href="/barang-masuk" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm shadow-md transition">
                <i class="ri-arrow-left-line mr-2"></i> Kembali
            </a>
        </div>
        
        <form action="/input-barang-masuk" method="POST" class="w-full">
            @csrf
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="product_id">Nama Barang</label>
                <select name="product_id" class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="quantity">Jumlah Barang Masuk</label>
                <input name="quantity" autocomplete="off" class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" id="quantity" type="number" required>
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="date">Tanggal</label>
                <input type="date" name="date" class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" id="date" required>
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="flex gap-2">
                <button class="bg-blue-600 hover:bg-blue-700 text-white w-full p-2 rounded-md shadow-md text-sm transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/supplies/input.js') }}"></script>
@endsection
