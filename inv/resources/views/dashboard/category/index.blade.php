@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4 py-6">
    <!-- Toast Notification -->
    @if (session('message'))
        <div id="toast-container" class="fixed top-5 right-5 z-50 bg-green-500 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-700">Data Lokasi</h2>
                <div class="mt-2 flex gap-2">
                    <a href="/input-kategori" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">Tambah Lokasi</a>
                    <a href="/excel/kategori" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">Export Excel</a>
                </div>
            </div>
            <form method="get" action="/kategori" class="flex gap-2 items-center flex-wrap">
                {{-- Room Filter --}}
                <select name="room" class="border rounded px-3 py-2 text-sm text-gray-600">
                    <option value="">Semua Ruangan</option>
                    @foreach ($allRooms as $room)
                        <option value="{{ $room }}" {{ request('room') === $room ? 'selected' : '' }}>
                            {{ $room }}
                        </option>
                    @endforeach
                </select>
            
                {{-- Search by Name --}}
                <input name="search" class="px-4 py-2 border rounded text-gray-600 focus:outline-none" placeholder="Cari nama posisi..." value="{{ request('search') }}">
            
                {{-- Sort --}}
                <select name="sort" class="border rounded px-3 py-2 text-sm text-gray-600">
                    <option value="">Urutkan</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            
                {{-- Submit --}}
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                    <i class="ri-search-line"></i> Filter
                </button>
            </form>
            
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100 text-left font-semibold">
                        <th class="p-3">No</th>
                        <th class="p-3">Ruangan</th>
                        <th class="p-3">Posisi</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $noCategory = 1; @endphp
                    @foreach ($categories as $category)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-3">{{ $noCategory }}</td>
                            <td class="p-3">{{ $category->room }}</td>
                            <td class="p-3">{{ $category->name }}</td>
                            <td class="p-3 flex justify-center gap-3">
                                <button data-id="{{ $category->id }}" class="btn-delete-category bg-red-500 text-white px-4 py-1 rounded shadow hover:bg-red-600 transition">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <a href="/ubah-kategori/{{ $category->id }}" class="bg-yellow-400 text-white px-4 py-1 rounded shadow hover:bg-yellow-500 transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                            </td>
                        </tr>
                        @php $noCategory++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="mt-5 flex justify-center">
            {{ $categories->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection