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

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg p-6 mt-5">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-700">Data Kepala Unit</h2>
                <a href="/input-kepala" class="mt-2 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">Tambah Kepala</a>
            </div>
            <form method="get" action="/kepala-unit" class="flex items-center mt-4 sm:mt-0">
                <input id="search" name="search" class="border p-2 rounded-l text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-auto" type="text" placeholder="Cari kepala...">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700 transition">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($heads as $index => $head)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $head->name }}</td>
                            <td class="p-3">{{ $head->email }}</td>
                            <td class="p-3">{{ $head->role }}</td>
                            <td class="p-3 flex justify-center gap-2">
                                <a href="/ubah-kepala/{{$head->id}}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                                <button data-id="{{$head->id}}" class="btn-delete-admin bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 transition">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <button
                                    data-id="{{ $head->id }}"
                                    data-name="{{ $head->name }}"
                                    class="btn-upload-ttd bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded shadow transition"
                                >
                                    <i class="ri-sketching"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-between items-center">
            <div>
                {{ $heads->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload TTD Kepala -->
<div id="modalUploadTTD" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Upload TTD Kepala Unit</h2>
        <form method="POST" action="{{ route('kepala.upload.ttd') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kepala_id" id="kepalaIdInput">
            <div class="mb-4">
                <label for="ttd" class="block text-sm font-medium text-gray-600">Upload TTD (PNG/JPG)</label>
                <input type="file" name="ttd" accept="image/*" class="mt-1 block w-full border rounded p-2">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalTTD" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalUploadTTD = document.getElementById('modalUploadTTD');
    const btnUploadTTD = document.querySelectorAll('.btn-upload-ttd');
    const closeModalTTD = document.getElementById('closeModalTTD');
    const kepalaIdInput = document.getElementById('kepalaIdInput');

    btnUploadTTD.forEach(btn => {
        btn.addEventListener('click', function () {
            const kepalaId = this.getAttribute('data-id');
            kepalaIdInput.value = kepalaId;
            modalUploadTTD.classList.remove('hidden');
            modalUploadTTD.classList.add('flex');
        });
    });

    closeModalTTD.addEventListener('click', function () {
        modalUploadTTD.classList.remove('flex');
        modalUploadTTD.classList.add('hidden');
    });
</script>

@endsection
