@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Input Data Barang</h2>

        <form action="/input-barang" method="POST" enctype="multipart/form-data" class="w-full max-w-lg mx-auto">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="name">Nama Barang</label>
                <input name="name" value="{{ old('name') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('name') border-red-400 @enderror" id="name" type="text">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="price">Harga Barang</label>
                <input name="price" value="{{ old('price') }}" class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('price') border-red-400 @enderror" id="price" type="number">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="image">Gambar Barang</label>
                <input type="file" name="image" class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none @error('image') border-red-400 @enderror" id="image" onchange="previewImage(event)">
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Preview Gambar -->
                <div class="mt-3">
                    <img id="preview" src="" class="hidden w-32 h-32 object-cover rounded-lg shadow-md" alt="Preview Gambar">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="category">Posisi Barang</label>
                <select name="category_id" class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-600" for="category">Posisi Barang</label>
                <select name="category_id" class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-gray-300 focus:outline-none">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex space-x-4">
                <button class="w-full bg-gray-700 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-800 transition">Simpan Data</button>
                <a href="{{ url('/barang') }}" class="w-full bg-gray-500 text-white py-2 px-4 rounded-lg shadow-md hover:bg-gray-600 transition text-center">Back</a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Preview Gambar -->
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
