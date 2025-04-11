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
    
        // Multi-keyword search (room or name)
        if ($request->filled('search')) {
            $searchTerms = explode(' ', $request->input('search'));
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('room', 'LIKE', "%{$term}%")
                             ->orWhere('name', 'LIKE', "%{$term}%");
                    });
                }
            });
        }
    
        // Sorting by header links (only room and name)
        $sortField = match ($request->input('sort')) {
            'room' => 'room',
            'name' => 'name',
            default => null,
        };

    
        $direction = $request->input('direction', 'asc');
    
        if ($sortField) {
            $query->orderBy($sortField, $direction);
        }
    
        $categories = $query->paginate(10);
        $allRooms = Category::select('room')->distinct()->pluck('room');
    
        return view('dashboard.category.index', compact('categories', 'allRooms'));
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
