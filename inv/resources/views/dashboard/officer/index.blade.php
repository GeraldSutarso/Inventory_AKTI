@extends('layouts.main')

@section('container')

@if (session('message'))
    <div id="toast-container" class="fixed z-50 right-5 top-5 flex items-center max-w-xs p-4 space-x-4 bg-green-500 text-white rounded-lg shadow-md transition-opacity duration-300 ease-in-out">
        <span class="text-sm font-bold">{{ session('message') }}</span>
    </div>
@endif

<div class="container px-4">
    <div class="bg-white mt-5 p-5 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-left mb-4 md:mb-0">
                <h2 class="text-gray-700 font-bold text-xl">Data Petugas</h2></br>
                <a href="/input-petugas" class="mt-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition">Tambah Petugas</a>
            </div>

            <!-- Search Form -->
            <form method="get" action="/petugas" class="w-full md:w-auto">
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <span class="px-3 text-gray-500"><i class="ri-search-line"></i></span>
                    <input id="search" name="search" class="p-2 w-full focus:outline-none text-sm" type="text" placeholder="Cari petugas...">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 transition">Cari</button>
                </div>
            </form>
        </div>

        <!-- Tabel Petugas -->
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
                                <button 
                                    data-id="{{ $officer->id }}" 
                                    data-name="{{ $officer->name }}"
                                    class="btn-upload-ttd bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded shadow transition"
                                >
                                    <i class="ri-sketching"></i>
                                </button>
                            </td>
                        </tr>
                        @php $no++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-5 flex justify-center">
            {{ $officers->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Modal Upload TTD Officer-->
<div id="modalUploadTTD" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-lg font-semibold mb-4">
            Upload Tanda Tangan <span id="officerName" class="font-bold"></span>
        </h3>
        <form id="formUploadTTD" action="/upload-ttd" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="officer_id" id="officerId">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">File TTD (gambar):</label>
                <input type="file" name="ttd" accept="image/*" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script>
    const modal = document.getElementById('modalUploadTTD');
    const officerNameSpan = document.getElementById('officerName');
    const officerIdInput = document.getElementById('officerId');

    document.querySelectorAll('.btn-upload-ttd').forEach(button => {
        button.addEventListener('click', function () {
            const officerId = this.getAttribute('data-id');
            const officerName = this.getAttribute('data-name');

            officerIdInput.value = officerId;
            officerNameSpan.textContent = officerName;
            modal.classList.remove('hidden');
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
</script>

@endsection
