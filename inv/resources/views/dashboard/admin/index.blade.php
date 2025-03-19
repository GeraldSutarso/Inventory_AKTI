@extends('layouts.main')

@section('container')

@if (session('message'))
    <div id="toast-container" class="fixed z-50 right-5 top-5 flex items-center w-full max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg opacity-0 transition-opacity duration-300" role="alert">
        <span class="font-semibold">{{ session()->get('message') }}</span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let toast = document.getElementById('toast-container');
            toast.classList.remove('opacity-0');
            setTimeout(() => toast.classList.add('opacity-0'), 3000);
        });
    </script>
@endif

<div class="container mx-auto px-4">
    <div class="bg-white shadow-md rounded-lg p-6 mt-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-700">Data Admin</h2>
                <a href="/input-admin" class="mt-2 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">Tambah Admin</a>
            </div>
            <form method="get" action="/admin" class="flex items-center">
                <input id="search" name="search" class="border p-2 rounded-l text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" type="text" placeholder="Cari admin...">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700 transition">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Admin</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $index => $admin)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $admin->name }}</td>
                            <td class="p-3">{{ $admin->email }}</td>
                            <td class="p-3">{{ $admin->role }}</td>
                            <td class="p-3 flex justify-center gap-2">
                                <a href="/ubah-admin/{{$admin->id}}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                                <button data-id="{{$admin->id}}" class="btn-delete-admin bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 transition">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-between items-center">

            <div>
                {{ $admins->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection