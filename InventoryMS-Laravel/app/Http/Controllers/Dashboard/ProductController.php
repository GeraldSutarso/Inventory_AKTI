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

    public function store (Request $request) {
        $validated = $this->validate($request,[
            'name'=>['required'],
            'price'=>['required'],
            'image'=>['required', 'image','max:1024'],
            'category_id'=>['required'],
        ]);

        $imagePath = $request->file('image')->store('products');

        $created = Product::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'image'=>$imagePath,
            'category_id'=>$request->category_id,
        ]);
        if($created){
            return redirect('/barang')->with('message', 'berhasil menambahkan data');
        }
    }

    public function edit ($id) {
        $product = Product::findOrFail($id);
        $category = Category::all();
        return view('dashboard.products.update', ["product"=>$product, 'categories'=>$category]);
    }

    public function update (Request $request, $id) {
        $validated = $this->validate($request,[
            'name'=>['required'],
            'price'=>['required'],
            'category_id'=>['required'],
            'image'=>['required', 'image', 'max:1024']
        ]);

        $productWithId = Product::findOrFail($id);
        Storage::delete($productWithId->image);
        $imagePath = $request->file('image')->store('products');
        $updated = $productWithId->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'stock'=>$request->stock,
            'image'=>$imagePath,
            'category_id'=>$request->category_id,
        ]);

        if($updated){
            return redirect('/barang')->with('message', 'berhasil update data');
        }

    }

    public function getAllProducts () {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    public function exportExcel () {
        return Excel::download(new ProductExport, 'product.xlsx');
    }

    public function generateQr($id)
    {
        $product = Product::findOrFail($id);

        $qrCode = QrCode::format('png')->size(200)->generate(route('products.show', $product->id));

        // Simpan QR Code ke storage
        $filePath = 'qrcodes/' . $product->id . '.png';
        Storage::put('public/' . $filePath, $qrCode);

        $product->update(['qr_code' => $filePath]);

        return back()->with('message', 'QR Code berhasil dibuat!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

}
