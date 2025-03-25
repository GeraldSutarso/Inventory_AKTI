<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\User;


class OverviewController extends Controller
{
    public function index()
    {
        // Basic counts
        $countProducts = Product::count();
        $countProductIncome = ProductSupplies::where('type', 'tambah')->sum('quantity');
        $countProductOutcome = ProductSupplies::where('type', 'kurang')->sum('quantity');
    
        // 1️⃣ Stock Trends Over Time (Line Chart)
        $movementTrends = ProductSupplies::selectRaw("
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as date_time,
                SUM(CASE WHEN type = 'tambah' THEN quantity ELSE 0 END) as incoming,
                SUM(CASE WHEN type = 'kurang' THEN quantity ELSE 0 END) as outgoing
            ")
            ->where('created_at', '>=', now()->subDays(30))
            ->groupByRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i')")
            ->orderBy('date_time')
            ->get();

    
        // Fetch supply type distribution for the selected product
        $supplyTypesDistribution = ProductSupplies::selectRaw("
                product_id,
                SUM(CASE WHEN type = 'tambah' THEN quantity ELSE 0 END) as incoming,
                SUM(CASE WHEN type = 'kurang' THEN quantity ELSE 0 END) as outgoing
            ")
            ->groupBy('product_id')
            ->get();

    
        // 3️⃣ Category-wise Product Stock (Pie Chart)
        $categoryDistribution = Category::withSum('products', 'stock')
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category->name,
                    'stock' => (int) $category->products_sum_stock ?? 0
                ];
            })->values();

    
        // 4️⃣ User Product Supply Contributions (Bar Chart)
        $userContributions = ProductSupplies::selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->with('user') // Ensure user data is loaded
            ->get()
            ->map(function ($supply) {
                return [
                    'user' => $supply->user->name ?? 'Unknown',
                    'total' => $supply->total
                ];
            });

    
        // 5️⃣ Product Price Distribution (Bar Chart)
        $priceDistribution = Product::select('name', 'price')
            ->orderBy('price', 'desc')
            ->get();

    
        // ✅ Stock Categorization
        $outOfStockProducts = Product::where('stock', '<=', 0)->get();
        $lowStockProducts = Product::whereColumn('stock', '<', 'stock_min')
            ->where('stock', '>', 0)
            ->get();
        $overStockProducts = Product::whereColumn('stock', '>', 'stock_max')->get();
        $normalStockProducts = Product::whereBetween('stock', ['stock_min', 'stock_max'])
            ->where('stock', '>', 0)
            ->get();
    
            return view('dashboard.overview.index', [
                // Basic counts
                'countProducts' => $countProducts,
                'countProductIncome' => $countProductIncome,
                'countProductOutcome' => $countProductOutcome,
            
                // Chart data
                'movementTrends' => $movementTrends,
                'supplyTypesDistribution' => $supplyTypesDistribution,
                'categoryDistribution' => $categoryDistribution,
                'userContributions' => $userContributions,
                'priceDistribution' => $priceDistribution,
            
                // Stock categorization
                'outOfStockProducts' => $outOfStockProducts,
                'lowStockProducts' => $lowStockProducts,
                'overStockProducts' => $overStockProducts,
                'normalStockProducts' => $normalStockProducts,
            
                // product selection
                'products' => Product::select('id', 'name')->orderBy('name')->get(),
                'selectedProductId' => request('product_id', null),
            ]);
            
    }
    

}
