@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-6 mt-6 rounded-lg shadow-lg">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h2 class="text-gray-700 font-bold text-lg">Input Data Barang Keluar</h2>
            <a href="/barang-keluar" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600">Kembali</a>
        </div>

        <form action="/input-barang-keluar" method="POST" class="w-full md:w-2/3 lg:w-1/2 mx-auto">
            @csrf
            <div class="mt-4">
                <label class="block text-sm text-gray-700 font-semibold" for="name">Nama Barang</label>
                <div class="border rounded-lg overflow-hidden">
                    <select name="product_id" class="w-full p-2 text-gray-700 border-none focus:ring focus:ring-gray-300" id="">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm text-gray-700 font-semibold" for="quantity">Jumlah Barang Keluar</label>
                <div class="border rounded-lg p-2 focus-within:ring focus-within:ring-gray-300">
                    <input name="quantity" autocomplete="off" class="w-full text-gray-700 focus:outline-none" id="quantity" type="number">
                </div>
                @error('quantity')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-4">
                <label class="block text-sm text-gray-700 font-semibold" for="date">Tanggal</label>
                <div class="border rounded-lg p-2 focus-within:ring focus-within:ring-gray-300">
                    <input type="date" name="date" class="w-full text-gray-700 focus:outline-none" id="date">
                </div>
                @error('date')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-6">
                <button class="bg-gray-700 text-white w-full p-3 rounded-lg text-sm font-semibold hover:bg-gray-800">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/supplies/input.js') }}"></script>
@endsection
