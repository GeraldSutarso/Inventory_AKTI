@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-lg mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-xl">Input Data Sarpras</h2>
            <a href="/sarpras" class="bg-gray-500 text-white px-4 py-2 rounded-md text-sm hover:bg-gray-600 transition">Kembali</a>
        </div>

        <form action="/input-sarpras" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-gray-700" for="name">Nama Sarpras</label>
                <input autocomplete="off" name="name" value="{{ old('name') }}" 
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('name') border-red-400 @enderror" id="name" type="text">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-sm text-gray-700" for="email">Email Sarpras</label>
                <input autocomplete="off" type="email" value="{{ old('email') }}" name="email"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('email') border-red-400 @enderror" id="email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-sm text-gray-700" for="password">Password</label>
                <input autocomplete="off" type="password" value="{{ old('password') }}" name="password"
                    class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none @error('password') border-red-400 @enderror" id="password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
