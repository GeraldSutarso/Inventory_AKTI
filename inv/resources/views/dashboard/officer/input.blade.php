@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4 flex justify-center">
    <div class="bg-white p-6 mt-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-xl">Input Data Petugas</h2>
            <a href="/petugas" class="text-sm text-gray-500 hover:text-gray-700 transition">&larr; Kembali</a>
        </div>

        <form action="/input-petugas" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="text-sm text-gray-600" for="name">Nama Petugas</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-gray-400 focus:outline-none @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm text-gray-600" for="email">Email Petugas</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-gray-400 focus:outline-none @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm text-gray-600" for="password">Password Petugas</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-gray-400 focus:outline-none @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <a href="/petugas" class="w-1/2 bg-gray-300 text-gray-700 text-center py-2 rounded-md hover:bg-gray-400 transition">Kembali</a>
                <button type="submit" class="w-1/2 bg-gray-700 text-white py-2 rounded-md hover:bg-gray-800 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection