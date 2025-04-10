<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoryExport;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('room', 'LIKE', "%{$search}%");
            });
        }

        $categories = $query->paginate(10);

        return view('dashboard.category.index', ['categories' => $categories]);
    }


    public function create () {
        return view('dashboard.category.input');
    }

    public function store (Request $request) {
        $this->validate($request, [
            'room'=> ['required'],
            'name'=> ['required']
        ]);

       $created = Category::create([
            'room'=>$request->room,
            'name'=>$request->name
       ]);

       if($created){
        return redirect('/kategori')->with('message', 'data berhasil ditambahkan');
       }
    }

    public function delete ($id) {
        $category = Category::findOrFail($id);
        $deleted = $category->delete();

        if($deleted) {
           session()->flash('message', 'berhasil hapus data');
           return response()->json(['message'=> 'success delete data'],200);
        }
    }

    public function edit ($id) {
        $category = Category::findOrFail($id);
        return view('dashboard.category.update', ['category'=>$category]);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'room'=> ['required'],
            'name'=> ['required']
        ]);

        $category = Category::findOrFail($id);
        $updated = $category->update([
            'room'=>$request->room,
            'name'=>$request->name
        ]);

        if($updated){
            return redirect('/kategori')->with('message', 'data berhasil diubah');
        }
    }

    public function exportExcel () {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }
}
