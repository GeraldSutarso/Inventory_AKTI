@extends('layouts.main')

@section('container')
   <div class="container px-4">
    <div class="p-6 mt-5 rounded-lg bg-white shadow-lg">
        <div class="text-left border-b pb-3 mb-5">
            <h1 class="text-gray-700 font-bold text-xl">📊 Dashboard Overview</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg text-center p-10 shadow-md transform hover:scale-105 transition duration-300">
                <h2 class="font-bold text-4xl">{{$countProducts}}</h2>
                <p class="text-sm mt-2">Jumlah Data Barang</p>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-700 text-white rounded-lg text-center p-10 shadow-md transform hover:scale-105 transition duration-300">
                <h2 class="font-bold text-4xl">{{$countProductOutcome}}</h2>
                <p class="text-sm mt-2">Jumlah Data Barang Keluar</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg text-center p-10 shadow-md transform hover:scale-105 transition duration-300">
                <h2 class="font-bold text-4xl">{{$countProductIncome}}</h2>
                <p class="text-sm mt-2">Jumlah Data Barang Masuk</p>
            </div>
        </div>
    </div>
   </div>
@endsection
