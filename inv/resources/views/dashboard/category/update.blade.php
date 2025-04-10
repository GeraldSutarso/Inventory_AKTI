@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-md">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h2 class="text-gray-700 font-bold text-lg">Ubah Data Lokasi</h2>
            <a href="/kategori" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">Back</a>
        </div>

        <form action="/ubah-kategori/{{$category->id}}" method="POST" class="w-full md:w-1/2 mx-auto">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-semibold" for="room">Nama Ruangan</label>
                <div class="border-2 p-2 rounded-lg @error('room') border-red-400 @enderror">
                    <input name="room" value="{{ $category->room }}" class="w-full focus:outline-none text-sm p-1" id="room" type="text" placeholder="Masukkan nama ruangan">
                </div>
                @error('room')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700" for="name">Nama Lokasi</label>
                <div class="border-2 rounded-md p-2 @error('name') border-red-400 @enderror">
                    <input name="name" value="{{$category->name}}" class="w-full h-full focus:outline-none text-sm" id="name" type="text">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-5">
                <button class="bg-blue-600 hover:bg-blue-700 text-white w-full p-3 rounded-md text-sm font-medium">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
