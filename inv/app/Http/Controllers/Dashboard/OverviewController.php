<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductActivity;
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

        // 🔁 Movement Trends by Product
        $movementTrendsByProduct = Product::with(['supplies' => function ($query) {
            $query->selectRaw("
                    product_id,
                    DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as date_time,
                    SUM(CASE WHEN type = 'tambah' THEN quantity ELSE 0 END) as incoming,
                    SUM(CASE WHEN type = 'kurang' THEN quantity ELSE 0 END) as outgoing
                ")
                ->where('created_at', '>=', now()->subDays(30))
                ->groupByRaw("product_id, DATE_FORMAT(created_at, '%Y-%m-%d %H:%i')")
                ->orderBy('date_time');
        }])->get();

        $stockTrendsByProduct = [];

        foreach ($movementTrendsByProduct as $product) {
            if ($product->supplies->isEmpty()) continue;

            $stockTrendsByProduct[$product->id] = [
                'type' => 'line',
                'labels' => $product->supplies->pluck('date_time'),
                'datasets' => [
                    [
                        'label' => 'Barang Masuk',
                        'data' => $product->supplies->pluck('incoming'),
                        'borderColor' => 'green',
                        'backgroundColor' => 'rgba(0, 255, 0, 0.2)',
                        'fill' => true
                    ],
                    [
                        'label' => 'Barang Keluar',
                        'data' => $product->supplies->pluck('outgoing'),
                        'borderColor' => 'red',
                        'backgroundColor' => 'rgba(255, 0, 0, 0.2)',
                        'fill' => true
                    ]
                ]
            ];
        }

    
        // 2️⃣ Supply Types Distribution (pie Chart)
        $supplyTypesDistribution = ProductSupplies::selectRaw("
                product_id,
                CAST(SUM(CASE WHEN type = 'tambah' THEN quantity ELSE 0 END) AS UNSIGNED) as incoming,
                CAST(SUM(CASE WHEN type = 'kurang' THEN quantity ELSE 0 END) AS UNSIGNED) as outgoing
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



        

        // 🔄 (Activity Feed)
        // Get logs from the last 90 days
        $daysBack = 90; // Change to 180 for 6 months
        $cutoffDate = now()->subDays($daysBack);

        $activityLogs = ProductActivity::where('created_at', '>=', $cutoffDate)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($activity) {
                return [
                    'user_name' => $activity->user_name,
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                ];
            });


        // Get recent product supplies
        $supplies = ProductSupplies::with('user', 'product')->latest()->take(10)->get();
        foreach ($supplies as $supply) {
            $desc = $supply->type === 'tambah'
                ? "menambahkan {$supply->quantity} stok ke {$supply->product->name}"
                : "mengambil {$supply->quantity} stok dari {$supply->product->name}";

            $activityLogs->push([
                'user_name' => $supply->user->name ?? 'Unknown',
                'description' => $desc,
                'created_at' => $supply->created_at,
            ]);
        }

        // // Sort by time (newest first)
        // $activityLogs = $activityLogs->sortByDesc('created_at')->take(10)->values();

    
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

                'stockTrendsByProduct' => $stockTrendsByProduct,

                // Activity feed
                'activityLogs' => $activityLogs,


            ]);
            
    }
    

}
