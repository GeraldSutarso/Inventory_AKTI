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
use Illuminate\Support\Facades\Process;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    public function index (Request $request) {
        if($request->has('search')){
            $products = DB::table('products')
            ->join('categories', 'products.category_id', '=' , 'categories.id')
            ->where('products.name', "LIKE","%{$request->search}%")
            ->select('products.*','categories.name as category')
            ->orderBy('products.created_at')
            ->paginate(10);
        } else {
             $products = DB::table('products')
            ->join('categories', 'products.category_id', '=' , 'categories.id')
            ->select('products.*','categories.name as category')
            ->orderBy('products.created_at')
            ->paginate(10);
        }

        return view('dashboard.products.index', ['products'=>$products]);
    }

    public function delete ($id) {
        $product = Product::findOrFail($id);
        Storage::delete($product->image);
        $deletedProduct = $product->delete();

        if($deletedProduct){
            session()->flash('message', 'berhasil hapus data');
            return response()->json(['message'=> 'success delete data'],200);
        }
    }

    public function create () {
        $category = Category::all();
        return view('dashboard.products.input', ['categories'=> $category]);
    }

    public function store(Request $request) {
        $validated = $this->validate($request, [
            'name' => ['required'],
            'price' => ['required'],
            'image' => ['required', 'image', 'max:1024'],
            'category_id' => ['required'],
        ]);
    
        // Simpan gambar di public/products
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('products'), $imageName);
    
        $created = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => 'products/' . $imageName, // Simpan path relatif
            'category_id' => $request->category_id,
        ]);
    
        if ($created) {
            return redirect('/barang')->with('message', 'Berhasil menambahkan data');
        }
    }
    

    public function edit ($id) {
        $product = Product::findOrFail($id);
        $category = Category::all();
        return view('dashboard.products.update', ["product"=>$product, 'categories'=>$category]);
    }

    public function update(Request $request, $id) {
        $validated = $this->validate($request, [
            'name' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'image' => ['nullable', 'image', 'max:1024']
        ]);
    
        $product = Product::findOrFail($id);
    
        // Hapus gambar lama jika ada yang baru diunggah
        if ($request->hasFile('image')) {
            if (file_exists(public_path($product->image))) {
                unlink(public_path($product->image)); // Hapus gambar lama
            }
    
            // Simpan gambar baru di public/products
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('products'), $imageName);
            $product->image = 'products/' . $imageName;
        }
    
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->save();
    
        return redirect('/barang')->with('message', 'Berhasil update data');
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
    try {
        $product = Product::findOrFail($id);
        $qrCodePath = 'qrcodes/product-' . $product->id . '.png';

        // Explicitly create the directory
        Storage::disk('public')->makeDirectory('qrcodes');

        // Generate QR
        $qrCode = QrCode::format('png')->size(200)->generate(route('products.show', $product->id));

        // Save to storage
        Storage::disk('public')->put($qrCodePath, $qrCode);

        // Update database
        $product->qr_code = $qrCodePath;
        $product->save();

        return back()->with('message', 'QR Code berhasil dibuat!');
    } catch (Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}


}
