@extends('layouts.main')

@section('container')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Review Order - {{ $product->name }}</h1>

    @if($orders->isEmpty())
        <div class="text-gray-500">Belum ada order untuk produk ini.</div>
    @else
        <table class="min-w-full bg-white border rounded shadow">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal Order</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Vendor TMMIN</th>
                    <th class="px-4 py-2 text-left">Vendor AKTI</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-left">User</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-2">{{ $order->quantity }}</td>
                    <td class="px-4 py-2">{{ $order->vendor_tmmin }}</td>
                    <td class="px-4 py-2">{{ $order->vendor_akti }}</td>
                    <td class="px-4 py-2">{{ $order->keterangan }}</td>
                    <td class="px-4 py-2">{{ $order->user->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
