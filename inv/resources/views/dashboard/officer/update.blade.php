@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-6 rounded-lg shadow-lg max-w-lg mx-auto">
        <div class="flex justify-between items-center">
            <h2 class="text-gray-700 font-bold text-lg">Ubah Data Petugas</h2>
            <a href="/petugas" class="text-sm bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 transition">Kembali</a>
        </div>

        <form action="/ubah-petugas/{{$officer->id}}" method="POST" class="mt-5 space-y-4">
            @csrf
            
            <div>
                <label class="text-sm text-gray-600 font-medium" for="name">Nama Petugas</label>
                <input name="name" value="{{$officer->name}}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" id="name" type="text" autocomplete="off">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div>
                <label class="text-sm text-gray-600 font-medium" for="email">Email Petugas</label>
                <input name="email" value="{{$officer->email}}" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" id="email" type="email" autocomplete="off">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div>
                <label class="text-sm text-gray-600 font-medium" for="password">Password Baru (Opsional)</label>
                <input name="password" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" id="password" type="password" autocomplete="off">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div class="flex justify-between items-center">
                <button class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection