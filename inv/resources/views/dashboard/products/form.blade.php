@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">
            {{ isset($product) ? 'Edit' : 'Tambah' }} Data Barang
        </h2>

        <form action="{{ isset($product) ? route('barang.update', $product->id) : route('barang.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="w-full max-w-lg mx-auto">
            @csrf
            @isset($product) @method('PUT') @endisset

            <!-- Nama Barang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="name">Nama Barang</label>
                <input name="name" 
                       value="{{ old('name', $product->name ?? '') }}" 
                       class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('name') border-red-400 @enderror" 
                       id="name" 
                       type="text"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga Barang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="price">Harga Barang</label>
                <input name="price" 
                       value="{{ old('price', $product->price ?? '') }}" 
                       class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('price') border-red-400 @enderror" 
                       id="price" 
                       type="number"
                       required>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gambar Barang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="image">
                    Gambar Barang {{ isset($product) ? '(Biarkan kosong jika tidak ingin mengubah)' : '' }}
                </label>
                <input type="file" 
                       name="image" 
                       class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('image') border-red-400 @enderror" 
                       id="image" 
                       {{ !isset($product) ? 'required' : '' }}
                       onchange="previewImage(event)">
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Preview Gambar -->
                <div class="mt-3">
                    @isset($product)
                        <img src="{{ asset($product->image) }}" 
                             class="w-32 h-32 object-cover rounded-lg shadow-md mb-2"
                             alt="Current Image">
                    @endisset
                    <img id="preview" 
                         src="" 
                         class="{{ isset($product) ? 'hidden' : 'hidden' }} w-32 h-32 object-cover rounded-lg shadow-md" 
                         alt="Preview Gambar">
                </div>
            </div>

            <!-- Lokasi Barang -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="category_id">Lokasi Barang</label>
                <select name="category_id" 
                        class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none"
                        required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ (old('category_id', $product->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Minimum Stock -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="stock_min">Stok Minimum</label>
                <input name="stock_min" 
                       value="{{ old('stock_min', $product->stock_min ?? '') }}" 
                       class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('stock_min') border-red-400 @enderror" 
                       id="stock_min" 
                       type="number"
                       min="0"
                       required>
                @error('stock_min')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Maximum Stock -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="stock_max">Stok Maksimum</label>
                <input name="stock_max" 
                       value="{{ old('stock_max', $product->stock_max ?? '') }}" 
                       class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('stock_max') border-red-400 @enderror" 
                       id="stock_max" 
                       type="number"
                       min="1"
                       required>
                @error('stock_max')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="w-full bg-gray-700 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-800 transition">
                    {{ isset($product) ? 'Update Data' : 'Simpan Data' }}
                </button>
                <a href="{{ route('barang.index') }}" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition text-center">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Preview Gambar -->
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const currentImage = document.querySelector('img[alt="Current Image"]');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if(currentImage) {
                    currentImage.classList.add('hidden');
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection