You said:
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

<div class="container mx-auto px-2 sm:px-6">
    <div class="bg-white mt-3 sm:mt-6 p-4 sm:p-8 rounded-xl shadow-lg">
        <div class="flex flex-col justify-between mb-6">
            <div class="mb-4">
                <h2 class="text-gray-900 font-bold text-2xl sm:text-3xl">Data Barang</h2>
                <div class="mt-3 flex flex-wrap gap-2 sm:gap-4">
                    <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 sm:px-5 sm:py-2 rounded-lg shadow-md text-xs sm:text-sm font-semibold">+ Tambah Barang</a>
                    <a href="/excel/products" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 sm:px-5 sm:py-2 rounded-lg shadow-md text-xs sm:text-sm font-semibold">⬇ Export Excel</a>
                    <button id="openOrderModal" class="px-3 py-1 sm:px-4 sm:py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-xs sm:text-sm">
                        Order Stok
                    </button>
                </div>
            </div>
            <form method="GET" class="mb-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:flex gap-2 sm:gap-4">
                <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="border p-2 text-sm rounded">
            
                <select name="room" class="border p-2 text-sm rounded">
                    <option value="">-- Ruangan --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room }}" {{ request('room') == $room ? 'selected' : '' }}>{{ $room }}</option>
                    @endforeach
                </select>
            
                <select name="position" class="border p-2 text-sm rounded">
                    <option value="">-- Posisi --</option>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" {{ request('position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                    @endforeach
                </select>
            
                <select name="unit" class="border p-2 text-sm rounded">
                    <option value="">-- Satuan --</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit }}" {{ request('unit') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                    @endforeach
                </select>
            
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">Filter</button>
            </form>
        </div>

        <div class="mb-4">
            <div class="flex flex-wrap items-center justify-start gap-2 sm:gap-4 text-xs sm:text-sm">
                <div class="flex items-center">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-100 border border-green-400 rounded mr-1 sm:mr-2"></div>
                    <span>Stok Normal</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-100 border border-yellow-400 rounded mr-1 sm:mr-2"></div>
                    <span>Stok Rendah</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-100 border border-red-400 rounded mr-1 sm:mr-2"></div>
                    <span>Stok Habis</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-100 border border-blue-400 rounded mr-1 sm:mr-2"></div>
                    <span>Stok Lebih</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <table class="w-full text-xs sm:text-sm text-gray-700 border border-gray-300 rounded-lg shadow-md">
                    <thead class="bg-gray-200 text-black">
                        <tr>
                            <th class="p-1 sm:p-2 border text-center">No</th>
                    
                            {{-- Nama Barang --}}
                            <th class="p-1 sm:p-2 border text-left text-black">
                                {!! sortLink('Nama', 'name') !!}
                            </th>
                    
                            {{-- Harga --}}
                            <th class="p-1 sm:p-2 border text-center hidden sm:table-cell">
                                {!! sortLink('Harga', 'price') !!}
                            </th>
                    
                            {{-- Stok --}}
                            <th class="p-1 sm:p-2 border text-center">
                                {!! sortLink('Stok', 'stock') !!}
                            </th>
                    
                            {{-- Min Stok --}}
                            <th class="p-1 sm:p-2 border text-center hidden md:table-cell">
                                {!! sortLink('Min', 'min_stock') !!}
                            </th>
                    
                            {{-- Max Stok --}}
                            <th class="p-1 sm:p-2 border text-center hidden md:table-cell">
                                {!! sortLink('Max', 'max_stock') !!}
                            </th>
                    
                            {{-- Satuan/Unit --}}
                            <th class="p-1 sm:p-2 border text-center hidden sm:table-cell">
                                {!! sortLink('Unit', 'unit') !!}
                            </th>
                    
                            {{-- Ruangan --}}
                            <th class="p-1 sm:p-2 border text-center">
                                {!! sortLink('Ruangan', 'room') !!}
                            </th>
                    
                            {{-- Posisi --}}
                            <th class="p-1 sm:p-2 border text-center">
                                {!! sortLink('Posisi', 'position') !!}
                            </th>
                    
                            <th class="p-1 sm:p-2 border text-center hidden sm:table-cell">Gambar</th>
                            <th class="p-1 sm:p-2 border text-center hidden sm:table-cell">QR Code</th>
                            <th class="p-1 sm:p-2 border text-center">Aksi</th>
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
                                <td class="p-1 sm:p-2 border border-gray-300 text-center">{{ $index + 1 }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-left break-words max-w-[100px] sm:max-w-none truncate sm:whitespace-normal">{{ $product->name }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center text-green-600 font-semibold hidden sm:table-cell">Rp.{{ number_format($product->price, 0) }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center font-bold">{{ $product->stock }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center hidden md:table-cell">{{ $product->stock_min }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center hidden md:table-cell">{{ $product->stock_max }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center hidden sm:table-cell">{{ $product->unit }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center">{{ $product->category->room }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center break-words">{{ $product->category->name }}</td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center hidden sm:table-cell">
                                    <img src="{{ asset('products/' . $product->id . '/product-img/' . basename($product->image)) }}" alt="{{ $product->name }}" 
                                        class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 mx-auto object-cover rounded-lg shadow">
                                </td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center hidden sm:table-cell">
                                    @if ($product->qr_code)
									<img src="{{ asset($product->qr_code) }}"
                                    alt="QR Code for {{ $product->name }}"
                                    class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 mx-auto object-contain shadow">
                                    @else
                                        <a href="{{ route('products.qr', $product->id) }}" 
                                            class="text-blue-600 hover:text-blue-800 text-xs">Generate QR</a>
                                    @endif
                                </td>
                                <td class="p-1 sm:p-2 border border-gray-300 text-center">
                                    <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-1 sm:gap-2">
                                        <a href="{{ route('barang.edit', $product->id) }}"
                                           class="inline-flex items-center justify-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-white font-medium text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow">
                                            ✏️
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                
                                        <button
                                            data-id="{{ $product->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDeleteProduct"
                                            class="btn-delete-product inline-flex items-center justify-center gap-1 bg-red-500 hover:bg-red-600 text-white font-medium text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow">
                                            🗑️
                                            <span class="hidden sm:inline">Hapus</span>
                                        </button>

                                
                                        <a href="{{ route('products.qr.download', $product->id) }}"
                                           class="inline-flex items-center justify-center gap-1 bg-blue-500 hover:bg-blue-600 text-white font-medium text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow">
                                            ⬇
                                            <span class="hidden sm:inline">QR</span>
                                        </a>
                                
                                        <a href="{{ route('supplies.create') }}?redirect_to={{ url()->full() }}"
                                           class="inline-flex items-center justify-center gap-1 bg-purple-500 hover:bg-purple-600 text-white font-medium text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow">
                                            
                                            <span class="hidden sm:inline">Stok</span>
                                        </a>
                                
                                        <a href="{{ route('orders.review', $product->id) }}"
                                           class="inline-flex items-center justify-center gap-1 bg-green-500 hover:bg-green-600 text-white font-medium text-xs sm:text-sm px-2 py-1 sm:px-3 sm:py-1.5 rounded-lg shadow">
                                            ✅
                                            <span class="hidden sm:inline">Review</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 sm:mt-6 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Modal -->
<div id="orderModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden px-2 sm:px-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-2 sm:mx-auto p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-semibold text-center mb-4">Order Stok Barang</h2>
        
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <!-- Produk -->
            <div class="mb-3 sm:mb-4">
                <label for="product_id" class="block text-xs sm:text-sm font-medium text-gray-700">Pilih Barang</label>
                <select name="product_id" id="product_id" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jumlah -->
            <div class="mb-3 sm:mb-4">
                <label for="quantity" class="block text-xs sm:text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="quantity" id="quantity" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm" min="1" required>
            </div>

            <!-- Vendor TMMIN -->
            <div class="mb-3 sm:mb-4">
                <label for="vendor_tmmin" class="block text-xs sm:text-sm font-medium text-gray-700">Vendor TMMIN</label>
                <input type="text" name="vendor_tmmin" id="vendor_tmmin" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm">
            </div>

            <!-- Vendor AKTI -->
            <div class="mb-3 sm:mb-4">
                <label for="vendor_akti" class="block text-xs sm:text-sm font-medium text-gray-700">Vendor AKTI</label>
                <input type="text" name="vendor_akti" id="vendor_akti" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm">
            </div>

            <!-- Keterangan -->
            <div class="mb-3 sm:mb-4">
                <label for="keterangan" class="block text-xs sm:text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="2" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm"></textarea>
            </div>

            <!-- Disetujui -->
            <div class="mb-3 sm:mb-4">
                <label for="kaunit_id" class="block text-xs sm:text-sm font-medium text-gray-700">Disetujui</label>
                <select name="kaunit_id" id="kaunit_id" class="mt-1 block w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-xs sm:text-sm" required>
                    @foreach(\App\Models\User::where('role', 'head')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-500 text-white rounded-md text-xs sm:text-sm" id="closeModal">Batal</button>
                <button type="submit" class="px-3 py-1 sm:px-4 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs sm:text-sm">Order</button>
            </div>
        </form>
    </div>
</div>
  
  <script>
    let productIdToDelete = null;

    document.querySelectorAll('.btn-delete-product').forEach(button => {
        button.addEventListener('click', function () {
            productIdToDelete = this.getAttribute('data-id');
        });
    });

    document.getElementById('btnConfirmDelete').addEventListener('click', function () {
        if (productIdToDelete) {
            fetch(/barang/${productIdToDelete}, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'success delete data') {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDeleteProduct'));
                    modal.hide();
                    setTimeout(() => location.reload(), 500);
                } else {
                    alert('Gagal menghapus barang.');
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat menghapus.');
            });
        }
    });
</script>



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

    // Modal functionality
    document.getElementById('openOrderModal').addEventListener('click', function () {
        document.getElementById('orderModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('orderModal').classList.add('hidden');
    });
</script>

@endsection