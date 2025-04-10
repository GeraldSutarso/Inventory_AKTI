@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg max-w-lg mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-xl">
                {{ isset($supply) ? 'Ubah' : 'Tambah' }} Aktivitas Stok
            </h2>
            <a href="{{ url()->previous() }}" 
                class="flex items-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm shadow-md transition">
                 <i class="ri-arrow-left-line mr-2"></i> Kembali
            </a>             
        </div>
        
        <form action="{{ isset($supply) ? route('supplies.update', $supply) : route('supplies.store') }}" method="POST" class="w-full">
            @csrf
            @isset($supply) @method('PUT') @endisset

            <!-- Product Selection -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="product_id">Nama Barang</label>
                <select name="product_id" class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                            {{ (isset($supply) && $supply->product_id == $product->id) ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Activity Type -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="type">Jenis Aktivitas</label>
                <select name="type" class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" required>
                    <option value="tambah" {{ (isset($supply) && $supply->type === 'tambah') ? 'selected' : '' }}>Penambahan Stok</option>
                    <option value="kurang" {{ (isset($supply) && $supply->type === 'kurang') ? 'selected' : '' }}>Pengurangan Stok</option>
                </select>
            </div>

            <!-- Quantity Input -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="quantity">Jumlah</label>
                <div class="flex items-center gap-2">
                    <input name="quantity" 
                        value="{{ old('quantity', $supply->quantity ?? '') }}"
                        class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" 
                        type="number" 
                        min="1" 
                        required>
                    <span id="unit-label" class="text-gray-600 text-sm whitespace-nowrap"></span>
                </div>
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Activity Date Picker -->
            <div class="mb-4">
                <label class="text-sm text-gray-700 font-semibold" for="date">
                    Tanggal Aktual Aktivitas
                </label>
                <input type="datetime-local" 
                       name="date" 
                       value="{{ old('date', isset($supply) ? $supply->date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                       class="border border-gray-300 rounded-md w-full p-2 focus:ring-2 focus:ring-blue-500 transition" 
                       required>
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white w-full p-2 rounded-md shadow-md text-sm transition">
                    {{ isset($supply) ? 'Perbarui' : 'Simpan' }} Data
                </button>
            </div>
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to', url()->previous()) }}">

        </form>
    </div>
</div>

<script>
    const products = @json($products->mapWithKeys(fn($p) => [$p->id => $p->unit]));

    function updateUnitLabel() {
        const selectedProductId = document.querySelector('select[name="product_id"]').value;
        const unitLabel = products[selectedProductId] || '';
        document.getElementById('unit-label').textContent = unitLabel;
    }

    // Update on load and on change
    document.addEventListener('DOMContentLoaded', updateUnitLabel);
    document.querySelector('select[name="product_id"]').addEventListener('change', updateUnitLabel);
</script>

@endsection