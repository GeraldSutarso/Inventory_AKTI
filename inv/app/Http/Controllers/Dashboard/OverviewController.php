<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupplies;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function index () {
        $countProducts = Product::count();
        $countProductIncome = ProductSupplies::where('type', 'tambah')->count();
        $countProductOutcome = ProductSupplies::where('type', 'kurang')->count();
        return view('dashboard.overview.index',
         [
            'countProducts'=>$countProducts,
            'countProductIncome'=>$countProductIncome,
            'countProductOutcome'=>$countProductOutcome
        ]);
    }
}
