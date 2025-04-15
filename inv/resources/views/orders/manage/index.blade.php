@extends('layouts.main')

@section('container')
<div class="container mx-auto px-4">
    <h1 class="text-lg font-bold mt-6 mb-4">Manajemen Order</h1>

    {{-- Filter Form --}}
    <form method="GET" class="flex flex-wrap gap-4 mb-6 bg-white p-4 rounded shadow">
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-medium mb-1">Produk</label>
            <select name="product_id" class="w-full border rounded px-3 py-2">
                <option value="">Semua Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(in_array($user->role, ['admin', 'sarpras']))
        <div class="w-full md:w-1/4">
            <label class="block text-sm font-medium mb-1">Ka. Unit</label>
            <select name="kaunit_id" class="w-full border rounded px-3 py-2">
                <option value="">Semua Ka. Unit</option>
                @foreach($kaunits as $kaunit)
                    <option value="{{ $kaunit->id }}" {{ request('kaunit_id') == $kaunit->id ? 'selected' : '' }}>
                        {{ $kaunit->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="w-full md:w-1/4">
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="filter" class="w-full border rounded px-3 py-2">
                <option value="">Semua</option>
                <option value="approved" {{ request('filter') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('filter') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                <option value="acknowledged" {{ request('filter') == 'acknowledged' ? 'selected' : '' }}>Diketahui Sarpras</option>
            </select>
        </div>

        <div class="w-full md:w-1/4 flex items-end">
            <button class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Terapkan Filter</button>
        </div>
    </form>

    {{-- Order Table --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Pemohon</th>
                    <th class="p-3 text-left">Produk</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Vendor TMMIN</th>
                    <th class="p-3 text-left">Vendor AKTI</th>
                    <th class="p-3 text-left">Kepala Unit</th>
                    <th class="p-3 text-center" colspan="3">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
                <tr class="bg-gray-50 text-xs text-gray-600">
                    <th colspan="7"></th>
                    <th class="p-2 text-center">Diketahui</th>
                    <th class="p-2 text-center">Disetujui</th>
                    <th class="p-2 text-center">Ditolak</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $order->user->name }}</td>
                        <td class="p-3">{{ $order->product->name }}</td>
                        <td class="p-3">{{ $order->quantity }}</td>
                        <td class="p-3">{{ $order->vendor_tmmin ?? '-' }}</td>
                        <td class="p-3">{{ $order->vendor_akti ?? '-' }}</td>
                        <td class="p-3">{{ $order->kaunit->name ?? '-' }}</td>

                        {{-- Status --}}
                        <td class="p-3 text-center">
                            @if($order->is_acknowledged)
                                ✅
                            @else
                                ❌
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($order->is_approved)
                                ✅
                            @else
                                ❌
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($order->is_rejected)
                                ✅
                            @else
                                ❌
                            @endif
                        </td>

                        {{-- Action Buttons --}}
                        <td class="p-3 text-center space-x-2">
                            {{-- Officer (can edit if their own and not acknowledged) --}}
                            @if(
                                ($user->role === 'officer' && $order->user_id === $user->id) ||
                                $user->role === 'admin'
                            )
                                @if(
                                    !$order->is_acknowledged &&
                                    !$order->is_approved &&
                                    !$order->is_rejected
                                )
                                    <a href="{{ route('orders.manage.edit', $order) }}" class="bg-yellow-400 px-3 py-1 rounded text-white hover:bg-yellow-500 transition">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                @endif
                            @endif
                            

                            {{-- Head or Admin: Approve / Reject --}}
                            @if(in_array($user->role, ['head', 'admin']) && !$order->is_acknowledged)
                                <form method="POST" action="{{ route('orders.manage.approve', $order) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 px-3 py-1 rounded text-white hover:bg-green-600 transition" onclick="return confirm('Setujui order ini?')">
                                        <i class="ri-check-line"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('orders.manage.reject', $order) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 px-3 py-1 rounded text-white hover:bg-red-600 transition" onclick="return confirm('Tolak order ini?')">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- Sarpras or Admin: Acknowledge --}}
                            @if(in_array($user->role, ['sarpras', 'admin']) && !$order->is_acknowledged)
                                <form method="POST" action="{{ route('orders.manage.acknowledge', $order) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 px-3 py-1 rounded text-white hover:bg-blue-600 transition" onclick="return confirm('Tandai sebagai selesai?')">
                                        <i class="ri-check-double-line"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center p-4 text-gray-500">Tidak ada order ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-5">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
