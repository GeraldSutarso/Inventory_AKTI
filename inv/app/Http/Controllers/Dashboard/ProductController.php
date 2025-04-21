<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSupplies;
use App\Models\ProductActivity;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category'); // Eager load the related category

        //  Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%")
                ->orWhere('unit', 'like', "%$search%")
                ->orWhereHas('category', function ($catQ) use ($search) {
                    $catQ->where('name', 'like', "%$search%")
                        ->orWhere('room', 'like', "%$search%");
                });
            });
        }

        // 🎯 Filters
        if ($request->filled('room')) {
            $query->whereHas('category', fn($q) => $q->where('room', $request->room));
        }

        if ($request->filled('position')) {
            $query->whereHas('category', fn($q) => $q->where('name', $request->position));
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        // 🔃 Sorting
        $sortable = ['name', 'price', 'stock', 'stock_min', 'stock_max', 'unit', 'room', 'category'];
        $sortBy = in_array($request->get('sort_by'), $sortable) ? $request->get('sort_by') : 'created_at';
        $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

        if (in_array($sortBy, ['room', 'category'])) {
            // Only join if sorting by room or category name
            $query->join('categories', 'products.category_id', '=', 'categories.id');

            if ($sortBy === 'room') {
                $query->orderBy('categories.room', $sortDir);
            } elseif ($sortBy === 'category') {
                $query->orderBy('categories.name', $sortDir);
            }

            // Need to explicitly select all product fields to avoid missing columns
            $query->select('products.*');
        } else {
            $query->orderBy("products.$sortBy", $sortDir);
        }


        // 📦 Paginate
        $products = $query->paginate(10)->withQueryString();

        // 📌 Filter Options
        $rooms = Category::select('room')->distinct()->pluck('room');
        $positions = Category::select('name')->distinct()->pluck('name');
        $units = Product::select('unit')->distinct()->pluck('unit');

        return view('dashboard.products.index', compact('products', 'rooms', 'positions', 'units'));
    }

    

    public function delete(Product $product)
{
    $productName = $product->name;
    $productId = $product->id;
    
    Storage::delete($product->image);
    $deletedProduct = $product->delete();
    
    if ($deletedProduct) {
        ProductActivity::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'product_id' => $productId,
            'product_name' => $productName,
            'action' => 'delete',
            'description' => 'Menghapus produk: ' . $productName,
        ]);

        session()->flash('message', 'berhasil hapus data');
        return response()->json(['message'=> 'success delete data'], 200);
    }  
}


    public function create()
{
    $categories = Category::all();
    return view('dashboard.products.form', [
        'categories' => $categories,
        'product' => null
    ]);
}

public function store(Request $request)
{
    $validated = $this->validate($request, [
        'name' => ['required'],
        'price' => ['required'],
        'image' => ['required', 'image', 'max:1024'],
        'category_id' => ['required'],
        'stock_min' => ['required', 'integer', 'min:0'],
        'stock_max' => ['required', 'integer', 'min:0', 'gt:stock_min'],
        'unit' => ['required', 'string', 'max:50'],
    ]);

    // Create the product first to get its ID
    $product = Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'stock' => $request->stock ?? 0,
        'stock_min' => $request->stock_min,
        'stock_max' => $request->stock_max,
        'image' => '', // Temporary placeholder
        'unit' => $request->unit,
    ]);

    // Define paths
    $productDir = 'products/' . $product->id;
    $imageDir = public_path('../public_html/' . $productDir . '/product-img'); // Use public_html directory

    // Create directory if it doesn't exist
    if (!file_exists($imageDir)) {
        mkdir($imageDir, 0755, true);
    }

    // Move the uploaded image
    $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
    $request->file('image')->move($imageDir, $imageName);

    // Update the product's image path
    $product->image = 'products/' . $product->id . '/product-img/' . $imageName;
    $product->save();

    return redirect()->route('barang.index')->with('message', 'Berhasil menambahkan data');
}


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('dashboard.products.form', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

public function update(Request $request, $id)
{
    $validated = $this->validate($request, [
        'name' => ['required'],
        'price' => ['required'],
        'category_id' => ['required'],
        'stock_min' => ['required', 'integer', 'min:0'],
        'stock_max' => ['required', 'integer', 'min:0', 'gt:stock_min'],
        'unit' => ['required'],
        'image' => ['nullable', 'image', 'max:1024']
    ]);

    $product = Product::findOrFail($id);

    if ($request->hasFile('image')) {
        // Delete old image
        if (file_exists(public_path('../public_html/' . $product->image))) {
            unlink(public_path('../public_html/' . $product->image));
        }

        // Define new paths
        $productDir = 'products/' . $product->id;
        $imageDir = public_path('../public_html/' . $productDir . '/product-img');
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

        // Create directory if it doesn't exist
        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        // Move new image
        $request->file('image')->move($imageDir, $imageName);

        // Update image path
        $product->image = 'products/' . $product->id . '/product-img/' . $imageName;
    }

    // Update other product fields
    $product->name = $request->name;
    $product->price = $request->price;
    $product->category_id = $request->category_id;
    $product->stock_min = $request->stock_min;
    $product->stock_max = $request->stock_max;
    $product->unit = $request->unit;
    $product->save();

    return redirect()->route('barang.index')->with('message', 'Berhasil memperbarui data');
}


    public function updateStock(Request $request, $id)
    {
        // ✅ Validate input and store it in $validated
        $validated = $request->validate([
            'action_type' => 'required|in:tambah,kurang',
            'stock_value' => 'required|integer|min:1',
        ]);
    
        // ✅ Get the product
        $product = Product::findOrFail($id);
        $actionType = $validated['action_type'];
        $quantity = $validated['stock_value'];
    
        // ✅ Determine new stock for record-keeping
        $newStock = $actionType === 'tambah'
            ? $product->stock + $quantity
            : $product->stock - $quantity;
    
        // ❌ Prevent negative stock
        if ($actionType === 'kurang' && $quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi untuk barang keluar!');
        }
    
        // ✅ Update stock & check thresholds
        if ($actionType === 'tambah') {
            $product->stock = $newStock;
    
            if (!is_null($product->stock_max) && $product->stock > $product->stock_max) {
                session()->flash('warning', 'Stok melebihi batas maksimum (' . $product->stock_max . ')');
            }
        } else {
            $product->stock = $newStock;
    
            if (!is_null($product->stock_min) && $product->stock < $product->stock_min && $product->stock > 0) {
                session()->flash('warning', 'Stok berada di bawah batas minimum (' . $product->stock_min . ')');
            } elseif ($product->stock <= 0) {
                session()->flash('danger', 'Stok habis atau negatif');
            }
        }
    
        $product->save();
    
        // ✅ Log the supply action
        ProductSupplies::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'quantity' => $quantity,
            'type' => $actionType,
            'stock' => $newStock,
            'date' => now(),
        ]);
    
        // ✅ Set success message if no warning/danger
        if (!session()->has('warning') && !session()->has('danger')) {
            session()->flash('success', 'Stok berhasil diperbarui!');
        }
    
        return back();
    }
    

    public function getAllProducts () {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    public function exportExcel () {
        return Excel::download(new ProductExport, 'product.xlsx');
    }

public function generateQR($id)
{
    $product = Product::findOrFail($id);

    // Define relative and full path
    $qrCodeRelativePath = 'products/' . $product->id . '/qrcodes';
    $fileName = 'product-' . $product->id . '.png';
    $fullPath = base_path('public_html/' . $qrCodeRelativePath . '/' . $fileName); // <-- Adjusted here

    // Create directory if it doesn't exist
    if (!file_exists(dirname($fullPath))) {
        mkdir(dirname($fullPath), 0755, true);
    }

    // Generate QR code
    $qrCode = \QrCode::format('png')
        ->size(200)
        ->generate(route('hasil.show', $product->id));

    // Save the QR code as a file
    file_put_contents($fullPath, $qrCode);

    // Save relative path to database (relative from public_html root)
    $product->qr_code = $qrCodeRelativePath . '/' . $fileName;
    $product->save();

    return back()->with('message', 'QR Code berhasil dibuat!');
}


        public function showHasil($id)
        {
            $product = Product::findOrFail($id);
            return view('hasil.show', compact('product'));
        }

public function downloadQR($id)
{
    $product = Product::findOrFail($id);

    // Check if QR Code path is stored
    if (!$product->qr_code) {
        return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
    }

    // Adjust to point to public_html instead of default public/
    $filePath = base_path('public_html/' . $product->qr_code);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File QR Code tidak ditemukan.');
    }

    return response()->download($filePath, 'QR_' . $product->name . '.png');
}




    // Method to check stock status for color coding
    public function getStockStatus($product)
    {
        if ($product->stock <= 0) {
            return 'critical'; // Out of stock or negative
        } elseif ($product->stock < $product->stock_min) {
            return 'warning'; // Below minimum
        } elseif ($product->stock > $product->stock_max) {
            return 'excess'; // Above maximum
        } else {
            return 'normal'; // Between min and max
        }
    }

    public function exportPdf($id)
    {
        $product = Product::findOrFail($id);
        
        // Karena view expects $products (plural), kita bungkus jadi array
        $pdf = Pdf::loadView('dashboard.products.pdf', ['products' => [$product]])
        ->setPaper('a5', 'landscape');

        return $pdf->download('produk-' . Str::slug($product->name) . '.pdf');

    }
}