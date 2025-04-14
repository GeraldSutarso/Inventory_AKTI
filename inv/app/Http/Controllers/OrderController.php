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
}
