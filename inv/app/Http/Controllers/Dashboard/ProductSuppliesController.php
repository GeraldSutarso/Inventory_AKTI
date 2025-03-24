<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSuppliesController extends Controller
{
    public function index()
    {
        $activities = ProductSupplies::with(['product', 'user'])
            ->latest()
            ->paginate(10);
    
        return view('dashboard.supplies.index', compact('activities'));
    }
    


    public function create()
    {
        $products = Product::all();
        return view('dashboard.supplies.form', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:tambah,kurang',
            'date' => 'required|date'
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $newStock = $validated['type'] === 'tambah' 
            ? $product->stock + $validated['quantity'] 
            : $product->stock - $validated['quantity'];

        ProductSupplies::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'quantity' => $validated['quantity'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'stock' => $newStock
        ]);

        $this->updateProductStock($validated['product_id']);

        return redirect($request->input('redirect_to', route('supplies.index')))
            ->with('success', 'Aktivitas berhasil diperbarui');
    }


    public function update(Request $request, ProductSupplies $supply)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:tambah,kurang',
            'date' => 'required|date'
        ]);

        $originalProductId = $supply->product_id;
        $product = Product::findOrFail($validated['product_id']);
        
        $newStock = $validated['type'] === 'tambah' 
            ? $product->stock + $validated['quantity'] 
            : $product->stock - $validated['quantity'];

        $supply->update([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'stock' => $newStock
        ]);

        $this->updateProductStock($originalProductId);
        if ($originalProductId != $validated['product_id']) {
            $this->updateProductStock($validated['product_id']);
        }

        return redirect($request->input('redirect_to', route('supplies.index')))
            ->with('success', 'Aktivitas berhasil diperbarui');
    }



    public function edit(ProductSupplies $supply)
    {
        $products = Product::all();
        return view('dashboard.supplies.form', compact('supply', 'products'));
    }

    // public function update(Request $request, ProductSupplies $supply)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'quantity' => 'required|integer|min:1',
    //         'type' => 'required|in:tambah,kurang',
    //         'date' => 'required|date'
    //     ]);

    //     $originalProductId = $supply->product_id;
        
    //     $supply->update($request->only(['product_id', 'quantity', 'type', 'date']));
        
    //     // Update both old and new product stocks
    //     $this->updateProductStock($originalProductId);
    //     if ($originalProductId != $request->product_id) {
    //         $this->updateProductStock($request->product_id);
    //     }

    //     return redirect()->route('supplies.index')
    //         ->with('success', 'Aktivitas berhasil diperbarui');
    // }

    public function destroy(ProductSupplies $supply)
    {
        $productId = $supply->product_id;
        $supply->delete();
        
        $this->updateProductStock($productId);

        return response()->json(['message' => 'Aktivitas berhasil dihapus']);
    }

    private function updateProductStock($productId)
    {
        $latestStock = ProductSupplies::where('product_id', $productId)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc') // Ensures most recent transaction is used
            ->value('stock');

        Product::where('id', $productId)->update([
            'stock' => $latestStock ?? 0 // If no supplies exist, set to 0
        ]);
    }

}