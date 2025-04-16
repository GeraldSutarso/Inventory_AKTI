@extends('layouts.main')

@section('container')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with Back Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">Review Order</h1>
            <p class="text-base sm:text-lg text-gray-600 mt-1">{{ $product->name }}</p>
        </div>
        <a href="{{ route('barang.index') }}"
           class="inline-flex items-center gap-2 px-3 py-2 bg-gray-200 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-gray-700 transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            <span class="hidden sm:inline">Kembali ke Daftar Barang</span>
            <span class="sm:hidden">Kembali</span>
        </a>     
    </div>

    <!-- Order Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($orders->isEmpty())
            <div class="p-6 text-center text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-3 text-lg font-medium">Belum ada order untuk produk ini</p>
                <p class="mt-1 text-sm">Silahkan buat order baru melalui menu Order Stok</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs sm:text-sm">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Vendor TMMIN</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Vendor AKTI</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ $order->quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $order->vendor_tmmin ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $order->vendor_akti ?: '-' }}</td>
                            <td class="px-4 py-3 max-w-[150px] sm:max-w-xs text-gray-500 text-sm">
                                <p class="truncate">{{ $order->keterangan ?: '-' }}</p>
                                @if($order->keterangan && strlen($order->keterangan) > 50)
                                    <span class="text-xs text-blue-600 cursor-pointer hover:underline" onclick="toggleFullText(this)">
                                        Lihat lebih
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 text-sm font-medium">
                                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('orders.downloadPdf', $order->id) }}" 
                                   class="inline-flex items-center px-3 py-1 text-xs sm:text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleFullText(element) {
        element.previousElementSibling.classList.toggle('truncate');
        element.textContent = element.textContent === 'Lihat lebih' ? 'Lihat kurang' : 'Lihat lebih';
    }
</script>

<style>
    .truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection
