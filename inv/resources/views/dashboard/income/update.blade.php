@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg">
        <div class="flex justify-between items-center">
            <h2 class="text-gray-700 font-bold text-lg">Ubah Data Barang Masuk</h2>
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-700 transition">
                <i class="ri-arrow-left-line"></i> Kembali
            </a>
        </div>

        <form action="/barang-masuk" method="POST" class="mt-5">
            @csrf
            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-600" for="product_id">Nama Barang</label>
                <select name="product_id" class="border rounded-lg w-full p-2 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $productIncome->product_id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-600" for="quantity">Jumlah Barang Masuk</label>
                <input value="{{$productIncome->quantity}}" name="quantity" type="number" id="quantity" class="border rounded-lg w-full p-2 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none @error('quantity') border-red-400 @enderror">
                @error('quantity')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="text-sm font-semibold text-gray-600" for="date">Tanggal</label>
                <input value="{{$productIncome->date}}" type="date" name="date" id="date" class="border rounded-lg w-full p-2 text-gray-700 focus:ring-2 focus:ring-gray-400 focus:outline-none @error('date') border-red-400 @enderror">
                @error('date')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-5 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                    <i class="ri-save-3-line"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/supplies/update.js') }}"></script>
@endsection
