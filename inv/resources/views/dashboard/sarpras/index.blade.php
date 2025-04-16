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
        <div class="flex justify-between items-center mb-4 flex-col sm:flex-row">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-lg font-bold text-gray-700">Data Sarpras</h2>
                <a href="/input-sarpras" class="mt-2 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">Tambah Sarpras</a>
            </div>
            <form method="get" action="/sarpras" class="flex items-center w-full sm:w-auto">
                <input id="search" name="search" class="border p-2 rounded-l text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-64" type="text" placeholder="Cari sarpras...">
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
                    @foreach ($sarpras as $index => $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $item->name }}</td>
                            <td class="p-3">{{ $item->email }}</td>
                            <td class="p-3">{{ $item->role }}</td>
                            <td class="p-3 flex justify-center gap-2">
                                <a href="/ubah-sarpras/{{ $item->id }}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                                <button data-id="{{ $item->id }}" class="btn-delete-sarpras bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 transition">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                                <button 
                                    class="btn-upload-ttd-sarpras bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded shadow transition"
                                    data-id="{{ $item->id }}"
                                    data-name="{{ $item->name }}"
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
                {{ $sarpras->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload TTD Sarpras -->
<div id="modalUploadTTDSarpras" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-lg font-semibold mb-4">
            Upload Tanda Tangan <span id="sarprasName" class="font-bold"></span>
        </h3>
        <form id="formUploadTTDSarpras" action="{{ route('sarpras.upload.ttd') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="sarpras_id" id="sarprasId">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">File TTD (gambar):</label>
                <input type="file" name="ttd" accept="image/*" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModalTTDSarpras()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalTTD = document.getElementById('modalUploadTTDSarpras');
    const sarprasNameSpan = document.getElementById('sarprasName');
    const sarprasIdInput = document.getElementById('sarprasId');

    document.querySelectorAll('.btn-upload-ttd-sarpras').forEach(button => {
        button.addEventListener('click', function () {
            const sarprasId = this.getAttribute('data-id');
            const sarprasName = this.getAttribute('data-name');

            sarprasIdInput.value = sarprasId;
            sarprasNameSpan.textContent = sarprasName;
            modalTTD.classList.remove('hidden');
        });
    });

    function closeModalTTDSarpras() {
        modalTTD.classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            closeModalTTDSarpras();
        }
    });
</script>

@endsection
