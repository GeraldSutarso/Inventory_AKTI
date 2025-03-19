@extends('layouts.main')

@section('container')
@if (session('message'))
    <div id="toast-container" class="fixed z-50 right-5 top-5 flex items-center max-w-xs p-4 space-x-4 bg-green-500 text-white rounded-lg shadow-md transition-opacity duration-300 ease-in-out">
        <span class="text-sm font-bold">{{ session('message') }}</span>
    </div>
@endif

<div class="container px-4">
    <div class="bg-white mt-5 p-5 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-left mb-4 md:mb-0">
                <h2 class="text-gray-700 font-bold text-xl">Data Petugas</h2>
                <a href="/input-petugas" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition">Tambah Petugas</a>
            </div>
            <form method="get" action="/petugas" class="w-full md:w-auto">
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <span class="px-3 text-gray-500"><i class="ri-search-line"></i></span>
                    <input id="search" name="search" class="p-2 w-full focus:outline-none text-sm" type="text" placeholder="Cari petugas...">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 transition">Cari</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto mt-5">
            <table class="w-full text-sm text-gray-700 border rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Admin</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($officers as $officer)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $no }}</td>
                            <td class="p-3">{{ $officer->name }}</td>
                            <td class="p-3">{{ $officer->email }}</td>
                            <td class="p-3">{{ ucfirst($officer->role) }}</td>
                            <td class="p-3 flex justify-center space-x-2">
                                <a href="/ubah-petugas/{{ $officer->id }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded shadow transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                                <button data-id="{{ $officer->id }}" class="btn-delete-officer bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow transition">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @php $no++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5 flex justify-center">
            {{ $officers->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
