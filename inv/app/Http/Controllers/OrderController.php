<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'vendor_tmmin' => 'nullable|string',
            'vendor_akti' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'kaunit_id' => 'required|exists:users,id',
        ]);
    
        $validated['user_id'] = auth()->id(); // Ambil user yang login
    
        Order::create($validated);
    
        return redirect()->back()->with('success', 'Order berhasil dibuat.');
    }

    public function review(Product $product)
    {
        $orders = Order::with('user') // kalau kamu ingin tahu siapa yang pesan
            ->where('product_id', $product->id)
            ->latest()
            ->get();

        return view('orders.review', compact('product', 'orders'));
    }

    public function previewPdf($productId)
    {
        $product = Product::findOrFail($productId);  // Mendapatkan data produk
        $orders = Order::where('product_id', $productId)->get();  // Mendapatkan semua order untuk produk tersebut
    
        // Mengonversi tampilan ke PDF
        $pdf = PDF\Pdf::loadView('orders.pdf_preview', compact('product', 'orders'));
    
        // Menyajikan PDF untuk di-download atau dilihat di browser
        return $pdf->stream('order_preview.pdf');
    }

    public function downloadPdf(Order $order)
    {
        $data = [
            'order' => $order,
            'product' => $order->product, // Asumsi ada relasi product
            'orders' => collect([$order]), // Membuat collection dengan 1 order untuk kompatibilitas dengan loop
            'date' => now()->format('d F Y'),
        ];
    
        $pdf = PDF\Pdf::loadView('orders.pdf', $data);
        return $pdf->download('order-'.$order->id.'.pdf');
    }

    public function approve(Order $order)
{
    $order->update(['is_approved' => true, 'is_rejected' => false]);
    return back()->with('success', 'Order approved.');
}

public function reject(Order $order)
{
    $order->update(['is_approved' => false, 'is_rejected' => true]);
    return back()->with('success', 'Order rejected.');
}

public function acknowledge(Order $order)
{
    $order->update(['is_acknowledged' => true]);
    return back()->with('success', 'Order acknowledged.');
}

    
}
