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

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => ['required'],
            'price' => ['required'],
            'image' => ['required', 'image', 'max:1024'],
            'category_id' => ['required'],
        ]);

        // Create the product first to get its ID
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => '', // Temporary placeholder
        ]);

        // Define paths
        $productDir = 'products/' . $product->id;
        $imageDir = $productDir . '/product-img'; // e.g., public/products/1/product-img

        // Create directory if it doesn't exist
        if (!file_exists(public_path($imageDir))) {
            mkdir(public_path($imageDir), 0755, true);
        }

        // Move the uploaded image
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path($imageDir), $imageName);

        // Update the product's image path
        $product->image = $imageDir . '/' . $imageName;
        $product->save();

        return redirect('/barang')->with('message', 'Berhasil menambahkan data');
    }
    

    public function edit ($id) {
        $product = Product::findOrFail($id);
        $category = Category::all();
        return view('dashboard.products.update', ["product"=>$product, 'categories'=>$category]);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'name' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'image' => ['nullable', 'image', 'max:1024']
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image
            if (file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Define new paths
            $productDir = 'products/' . $product->id;
            $imageDir = $productDir . '/product-img';
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

            // Create directory if it doesn't exist
            if (!file_exists(public_path($imageDir))) {
                mkdir(public_path($imageDir), 0755, true);
            }

            // Move new image
            $request->file('image')->move(public_path($imageDir), $imageName);

            // Update image path
            $product->image = $imageDir . '/' . $imageName;
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
        $product = Product::findOrFail($id);

        // Define paths
        $qrCodeDir = 'products/' . $product->id . '/qr-codes'; // e.g., storage/app/public/products/1/qr-codes
        $qrCodePath = $qrCodeDir . '/product-' . $product->id . '.png';

        // Create directory if it doesn't exist
        Storage::disk('public')->makeDirectory($qrCodeDir);

        // Generate and save QR code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->generate(route('products.show', $product->id));

        Storage::disk('public')->put($qrCodePath, $qrCode);

        // Update the database
        $product->qr_code = $qrCodePath;
        $product->save();

        return back()->with('message', 'QR Code berhasil dibuat!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    

}
