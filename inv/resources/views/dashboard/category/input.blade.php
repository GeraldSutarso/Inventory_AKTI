@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-6 mt-5 rounded-lg shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-gray-700 font-bold text-lg">Input Data Lokasi</h2>
            <a href="/kategori" class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition">Back</a>
        </div>

        <form action="/input-kategori" method="POST" class="w-full max-w-md mx-auto">
            @csrf

            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-semibold" for="room">Ruangan</label>
                <div class="border-2 p-2 rounded-lg @error('room') border-red-400 @enderror">
                    <input name="room" value="{{ old('room') }}" class="w-full focus:outline-none text-sm p-1" id="room" type="text" placeholder="Masukkan nama ruangan">
                </div>
                @error('room')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-700 font-semibold" for="name">Posisi</label>
                <div class="border-2 p-2 rounded-lg @error('name') border-red-400 @enderror">
                    <input name="name" value="{{old('name')}}" class="w-full focus:outline-none text-sm p-1" id="name" type="text" placeholder="Masukkan posisi">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-4">
                <button class="bg-blue-600 text-white w-full py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
