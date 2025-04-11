@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg max-w-lg mx-auto">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Ubah Data Sarpras</h2>
        
        <form action="/ubah-sarpras/{{$sarpras->id}}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-600" for="name">Nama Sarpras</label>
                <input type="text" id="name" name="name" value="{{$sarpras->name}}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 text-sm" autocomplete="off">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600" for="email">Email Sarpras</label>
                <input type="email" id="email" name="email" value="{{$sarpras->email}}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 text-sm" autocomplete="off">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-600" for="password">Password</label>
                <input type="password" id="password" name="password" value="{{old('password')}}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700 text-sm" autocomplete="off">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            
            <div class="flex justify-between mt-5">
                <a href="/sarpras" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition">Kembali</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
