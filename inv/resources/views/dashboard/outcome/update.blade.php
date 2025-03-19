@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-gray-700 font-bold text-lg flex items-center">
                <i class="ri-edit-box-line mr-2 text-blue-500"></i> Ubah Data Barang Keluar
            </h2>
            <a href="/barang-keluar" class="text-sm bg-gray-700 text-white px-3 py-2 rounded hover:bg-gray-800">
                <i class="ri-arrow-left-line"></i> Kembali
            </a>
        </div>

        <form action="/ubah-barang-keluar/{{$productOutcome->id}}" method="POST" class="w-full md:w-1/2">
            @csrf
            <div class="mb-4">
                <label class="text-sm text-gray-600 font-medium" for="product_id">Nama Barang</label>
                <select name="product_id" class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $productOutcome->product_id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-600 font-medium" for="quantity">Jumlah Barang Keluar</label>
                <input value="{{$productOutcome->quantity}}" name="quantity" autocomplete="off" class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" id="quantity" type="number">
                @error('quantity')
                    <p class="text-red-500 text-xs italic mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-600 font-medium" for="date">Tanggal</label>
                <input value="{{$productOutcome->date}}" type="date" name="date" class="w-full p-2 border rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" id="date">
                @error('date')
                    <p class="text-red-500 text-xs italic mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-5">
                <button class="bg-blue-600 hover:bg-blue-700 text-white w-full p-3 rounded-lg text-sm font-medium transition-all duration-300">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/supplies/update.js') }}"></script>
@endsection
