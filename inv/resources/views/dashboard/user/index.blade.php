@extends('layouts.main')

@section('container')

@if (session('message'))
    <div id="toast-container" class="fixed z-50 right-5 top-5 flex items-center w-full max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg opacity-0 transition-opacity duration-300" role="alert">
        <span class="font-semibold">{{ session('message') }}</span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast-container');
            toast.classList.remove('opacity-0');
            setTimeout(() => toast.classList.add('opacity-0'), 3000);
        });
    </script>
@endif

<div class="container mx-auto px-4">
    <div class="bg-white shadow-md rounded-lg p-6 mt-5">
        <div class="flex justify-between items-center mb-4 flex-col sm:flex-row">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-lg font-bold text-gray-700">User Management</h2>
                <a href="{{ route('users.create') }}" class="mt-2 inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition">Tambah User</a>
            </div>
            <form method="get" action="{{ route('users.index') }}" class="flex items-center w-full sm:w-auto gap-2">
                <input type="text" name="search" placeholder="Search users..." value="{{ request('search') }}"
                    class="border p-2 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-64">
                <select name="role" class="border p-2 text-sm rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Search</button>
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
                    @forelse ($users as $index => $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $users->firstItem() + $index }}</td>
                            <td class="p-3">{{ $user->name }}</td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">{{ ucfirst($user->role) }}</td>
                            <td class="p-3 flex justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 transition">
                                    <i class="ri-edit-box-line"></i>
                                </a>
                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 transition">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                                <button 
                                    class="btn-upload-ttd bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded shadow transition"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                >
                                    <i class="ri-sketching"></i> Upload TTD
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-3 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5 flex justify-between items-center">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Upload TTD Modal -->
<div id="modalUploadTTDUser" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-lg font-semibold mb-4">
            Upload Tanda Tangan <span id="userName" class="font-bold"></span>
        </h3>
        <form id="formUploadTTDUser" action="{{ route('users.upload.ttd') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" id="userId">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">File TTD (image):</label>
                <input type="file" name="ttd" accept="image/*" class="w-full border px-3 py-2 rounded" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModalTTDUser()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modalTTD = document.getElementById('modalUploadTTDUser');
    const userNameSpan = document.getElementById('userName');
    const userIdInput = document.getElementById('userId');

    document.querySelectorAll('.btn-upload-ttd').forEach(button => {
        button.addEventListener('click', function () {
            userIdInput.value = this.dataset.id;
            userNameSpan.textContent = this.dataset.name;
            modalTTD.classList.remove('hidden');
        });
    });

    function closeModalTTDUser() {
        modalTTD.classList.add('hidden');
    }

    document.addEventListener('keydown', event => {
        if (event.key === "Escape") closeModalTTDUser();
    });
</script>

@endsection
