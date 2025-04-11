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

    // Filter by room
    if ($request->filled('room')) {
        $query->where('room', $request->input('room'));
    }

    // Search by name/position
    if ($request->filled('search')) {
        $searchTerms = explode(' ', $request->input('search'));
        $query->where(function ($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->where('name', 'LIKE', "%{$term}%");
            }
        });
    }

    // Sorting
    if ($request->filled('sort')) {
        switch ($request->input('sort')) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
        }
    }

    $categories = $query->paginate(10);
    $allRooms = Category::select('room')->distinct()->pluck('room');

    return view('dashboard.category.index', [
        'categories' => $categories,
        'allRooms' => $allRooms,
    ]);
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
