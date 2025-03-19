@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Input Data Barang Masuk</h2>
        </div>

        <form action="/input-barang-masuk" method="POST" class="w-1/2 mt-5">
            @csrf
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="product_id">Nama Barang</label>
                <div class="border @error('product_id') border-red-400 @enderror p-1">
                    <select name="product_id" id="product_id" class="select-product text-black w-full focus:outline-none" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('product_id')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="quantity">Jumlah Barang Masuk</label>
                <div class="border-2 p-1 @error('quantity') border-red-400 @enderror">
                    <input name="quantity" id="quantity" type="number" class="text-sm text-black w-full focus:outline-none" autocomplete="off" required>
                </div>
                @error('quantity')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="date">Tanggal</label>
                <div class="border-2 p-1 @error('date') border-red-400 @enderror">
                    <input type="date" name="date" id="date" class="text-sm text-black w-full focus:outline-none" required>
                </div>
                @error('date')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3">
                <button type="submit" class="bg-gray-600 text-white w-full p-2 rounded text-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    {{-- Load Choice.js --}}
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Choices("#product_id", { 
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false
            });
        });
    </script>
@endsection
