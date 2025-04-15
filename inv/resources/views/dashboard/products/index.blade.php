@extends('layouts.main')

@section('container')
@if (session('message'))
   <div id="toast-container" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('message') }}</span>
   </div>
@endif

@if (session('success'))
   <div id="success-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-green-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('success') }}</span>
   </div>
@endif

@if (session('warning'))
   <div id="warning-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-yellow-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('warning') }}</span>
   </div>
@endif

@if (session('danger'))
   <div id="danger-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-red-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('danger') }}</span>
   </div>
@endif

@if (session('error'))
   <div id="error-toast" class="fixed top-5 right-5 z-50 flex items-center max-w-xs p-4 text-sm text-white bg-red-500 rounded-lg shadow-lg animate-fade-in">
       <span class="font-semibold">{{ session()->get('error') }}</span>
   </div>
@endif

<div class="container mx-auto px-6">
    <div class="bg-white mt-6 p-8 rounded-xl shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h2 class="text-gray-900 font-bold text-3xl">Data Barang</h2>
                <div class="mt-3 flex gap-4">
                    <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">+ Tambah Barang</a>
                    <a href="/excel/products" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow-md text-sm font-semibold">⬇ Export Excel</a>
                    <button id="openOrderModal" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                        Order Stok
                    </button>
                </div>
            </div>
            <form method="GET" class="mb-4 flex flex-wrap gap-4">
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="border p-2">
            
                <select name="room" class="border p-2">
                    <option value="">-- Ruangan --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room }}" {{ request('room') == $room ? 'selected' : '' }}>{{ $room }}</option>
                    @endforeach
                </select>
            
                <select name="position" class="border p-2">
                    <option value="">-- Posisi --</option>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                    @endforeach
                </select>
            
                <select name="unit" class="border p-2">
                    <option value="">-- Satuan --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
            </form>
            
            
        </div>

        <div class="mb-4">
            <div class="flex items-center justify-start space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-100 border border-green-400 rounded mr-2"></div>
                    <span>Stok Normal</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-100 border border-yellow-400 rounded mr-2"></div>
                    <span>Stok Di Bawah Minimum</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-100 border border-red-400 rounded mr-2"></div>
                    <span>Stok Habis</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-400 rounded mr-2"></div>
                    <span>Stok Melebihi Maksimum</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200 text-black">
                    <tr>
                        <th class="p-2 border text-center">No</th>
                
                        {{-- Nama Barang --}}
                        <th class="p-2 border text-left text-black">
                            {!! sortLink('Nama Barang', 'name') !!}
                        </th>
                
                        {{-- Harga --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Harga', 'price') !!}
                        </th>
                
                        {{-- Stok --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Stok', 'stock') !!}
                        </th>
                
                        {{-- Min Stok --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Min Stok', 'min_stock') !!}
                        </th>
                
                        {{-- Max Stok --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Max Stok', 'max_stock') !!}
                        </th>
                
                        {{-- Satuan/Unit --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Satuan/Unit', 'unit') !!}
                        </th>
                
                        {{-- Ruangan --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Ruangan', 'room') !!}
                        </th>
                
                        {{-- Posisi --}}
                        <th class="p-2 border text-center">
                            {!! sortLink('Posisi', 'position') !!}
                        </th>
                
                        <th class="p-2 border text-center">Gambar</th>
                        <th class="p-2 border text-center">QR Code</th>
                        <th class="p-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-300">
                    @foreach ($products as $index => $product)
                        @php
                            $rowClass = '';
                            if ($product->stock <= 0) {
                                $rowClass = 'bg-red-100';
                            } elseif ($product->stock < $product->stock_min) {
                                $rowClass = 'bg-yellow-100';
                            } elseif ($product->stock > $product->stock_max) {
                                $rowClass = 'bg-blue-100';
                            } else {
                                $rowClass = 'bg-green-100';
                            }
                        @endphp
                        <tr class="{{ $rowClass }} hover:bg-opacity-80">
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $index + 1 }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-left break-words">{{ $product->name }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center text-green-600 font-semibold">Rp.{{ number_format($product->price, 0) }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center font-bold">{{ $product->stock }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->stock_min }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->stock_max }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->unit }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">{{ $product->category->room }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center break-words">{{ $product->category->name }}</td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                    class="w-20 h-20 md:w-24 md:h-24 mx-auto object-cover rounded-lg shadow-md">
                            </td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                @if ($product->qr_code)
                                    <img src="{{ asset('storage/' . $product->qr_code) }}" 
                                        alt="QR Code for {{ $product->name }}"
                                        class="w-24 h-24 md:w-28 md:h-28 mx-auto object-contain lg shadow-md">
                                @else
                                    <a href="{{ route('products.qr', $product->id) }}" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">Generate QR</a>
                                @endif
                            </td>
                            <td class="p-2 md:p-3 border border-gray-300 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('barang.edit', $product->id) }}"
                                       class="inline-flex items-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        ✏️ Edit
                                    </a>
                            
                                    <button data-id="{{ $product->id }}"
                                            class="btn-delete-product inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        🗑️ Hapus
                                    </button>
                            
                                    <a href="{{ route('products.qr.download', $product->id) }}"
                                       class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        ⬇️ QR
                                    </a>
                            
                                    <a href="{{ route('supplies.create') }}?redirect_to={{ url()->full() }}"
                                       class="inline-flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        📦 Stok
                                    </a>
                            
                                    <a href="{{ route('orders.review', $product->id) }}"
                                       class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        ✅ Review Order
                                    </a>
                            
                                    {{-- <a href="{{ route('orders.previewPdf', $product->id) }}"
                                       class="inline-flex items-center gap-1 bg-indigo-500 hover:bg-indigo-600 text-white font-medium text-sm px-3 py-1.5 rounded-lg shadow">
                                        📄 PDF Order
                                    </a> --}}
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Modal -->
<div id="orderModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <h2 class="text-xl font-semibold text-center mb-4">Order Stok Barang</h2>
        
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <!-- Produk -->
            <div class="mb-4">
                <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Barang</label>
                <select name="product_id" id="product_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah -->
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah yang Dipesan</label>
                <input type="number" name="quantity" id="quantity" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" min="1" required>
            </div>

            <!-- Vendor TMMIN -->
            <div class="mb-4">
                <label for="vendor_tmmin" class="block text-sm font-medium text-gray-700">Vendor TMMIN</label>
                <input type="text" name="vendor_tmmin" id="vendor_tmmin" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Vendor AKTI -->
            <div class="mb-4">
                <label for="vendor_akti" class="block text-sm font-medium text-gray-700">Vendor AKTI</label>
                <input type="text" name="vendor_akti" id="vendor_akti" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Keterangan -->
            <div class="mb-4">
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <!-- Disetujui -->
            <div class="mb-4">
                <label for="kaunit_id" class="block text-sm font-medium text-gray-700">Disetujui</label>
                <select name="kaunit_id" id="kaunit_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @foreach(\App\Models\User::where('role', 'head')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md" id="closeModal">Batal</button>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Order</button>
            </div>
        </form>
    </div>
</div>




<script>
    // Toast notifications auto-hide
    setTimeout(() => {
        const toasts = document.querySelectorAll('#toast-container, #success-toast, #warning-toast, #danger-toast, #error-toast');
        toasts.forEach(toast => {
            if (toast) {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 300);
            }
        });
    }, 3000);

    // Delete product functionality
// SweetAlert2 delete confirmation
document.querySelectorAll('.btn-delete-product').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        Swal.fire({
            title: 'Yakin ingin menghapus barang ini?',
            text: 'Data barang akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/barang/${id}`, {
  method: 'DELETE',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Accept': 'application/json'
  }
})

                .then(response => response.json())
                .then(data => {
                    if (data.message === 'success delete data') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Barang berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal menghapus barang.',
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus.',
                    });
                });
            }
        });
    });
});


</script>
<script>
    document.getElementById('openOrderModal').addEventListener('click', function () {
        document.getElementById('orderModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('orderModal').classList.add('hidden');
    });
</script>

@endsection