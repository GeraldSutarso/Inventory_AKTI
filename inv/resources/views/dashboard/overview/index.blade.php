@extends('layouts.main')

@section('container')
<div class="container px-4">

    {{-- 🚨 Stock Alerts Section --}}
    <div class="mt-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4 text-xl">🚨 Peringatan Jumlah Stok</h2>

            @php
                $lowStockIds = $lowStockProducts->pluck('id')->toArray();
                $outOfStockIds = $outOfStockProducts->pluck('id')->toArray();
            @endphp

            @if($outOfStockProducts->isEmpty() && $lowStockProducts->isEmpty() && $overStockProducts->isEmpty())
                <p class="text-gray-500 text-sm">✅ Tidak ada masalah pada jumlah stok.</p>
            @else
                <ul class="space-y-3">
                    {{-- 🔴 Out of Stock Items --}}
                    @foreach ($outOfStockProducts as $product)
                        <li class="p-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md">
                            Stok barang <i>{{ $product->name }}</i> <strong>HABIS!</strong> 
                            (Jumlah Stok: {{ $product->stock }})
                        </li>
                    @endforeach

                    {{-- 🟡 Low Stock Items --}}
                    @foreach ($lowStockProducts as $product)
                        @if (!in_array($product->id, $outOfStockIds))
                            <li class="p-3 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded-md">
                                Stok barang <i>{{ $product->name }}</i> <strong>TERLALU SEDIKIT!</strong> 
                                (Jumlah Stok: {{ $product->stock }} | Min Stok: {{ $product->stock_min }})
                            </li>
                        @endif
                    @endforeach

                    {{-- 🔵 Overstock Items --}}
                    @foreach ($overStockProducts as $product)
                        <li class="p-3 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-md">
                            Stok barang <i>{{ $product->name }}</i> <strong>TERLALU BANYAK!</strong> 
                            (Jumlah Stok: {{ $product->stock }} | Max Stok: {{ $product->stock_max }})
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    {{-- 📊 Stock Status Overview --}}
    <div class="p-6 mt-5 rounded-lg bg-white shadow-lg">
        <div class="text-left border-b pb-3 mb-5">
            <h1 class="text-gray-700 font-bold text-xl">📊 Status Barang</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            {{-- 📦 Total Products --}}
            <div class="bg-gradient-to-r from-gray-500 to-gray-700 text-white rounded-lg text-center p-8 shadow-md">
                <h2 class="font-bold text-3xl">{{ $countProducts }}</h2>
                <p class="text-sm mt-2">Total Barang</p>
            </div>

            {{-- 🔴 Out of Stock --}}
            <div class="bg-gradient-to-r from-red-500 to-red-700 text-white rounded-lg text-center p-8 shadow-md">
                <h2 class="font-bold text-3xl">{{ count($outOfStockProducts) }}</h2>
                <p class="text-sm mt-2">Barang Habis</p>
            </div>

            {{-- 🟡 Low Stock (Excludes out of stock) --}}
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 text-white rounded-lg text-center p-8 shadow-md">
                <h2 class="font-bold text-3xl">{{ count($lowStockProducts) }}</h2>
                <p class="text-sm mt-2">Stok Rendah</p>
            </div>

            {{-- 🟢 Normal Stock (Excludes low stock & overstock) --}}
            <div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg text-center p-8 shadow-md">
                <h2 class="font-bold text-3xl">{{ count($normalStockProducts) }}</h2>
                <p class="text-sm mt-2">Stok Normal</p>
            </div>

            {{-- 🔵 Overstocked --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg text-center p-8 shadow-md">
                <h2 class="font-bold text-3xl">{{ count($overStockProducts) }}</h2>
                <p class="text-sm mt-2">Barang Berlebih</p>
            </div>
        </div>
    </div>
    {{-- 📈 Charts Section --}}
    <div class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-6">
        {{-- 📊 Charts Container (3/5 width) --}}
        <div class="md:col-span-3 bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-5">
                <h1 class="text-gray-700 font-bold text-xl">📊 Analisis Produk</h1>
    
                <div class="flex gap-3">
                    {{-- Product Selection Dropdown --}}
                    <select id="productSelector" class="form-select w-48 border rounded-md px-2 py-1 hidden">
                        <option value="">Semua Produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
    
                    {{-- Chart Type Dropdown --}}
                    <select id="chartSelector" class="form-select w-48 border rounded-md px-2 py-1">
                        <option value="stockTrends">Pergerakan Stok</option>
                        <option value="supplyTypesDistribution">Distribusi Pasokan</option>
                        <option value="categoryStock">Distribusi Kategori</option>
                        <option value="userContributions">Kontribusi Pengguna</option>
                        <option value="priceDistribution">Distribusi Harga</option>
                    </select>
                </div>
            </div>
    
            {{-- Chart Canvas --}}
            <div class="w-full h-96">
                <canvas id="mainChart"></canvas>
            </div>
        </div>
    
        {{-- 📋 Activity Log Container (2/5 width) --}}
        <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6 flex flex-col" style="height: 24rem;">
            <div class="flex items-center justify-between cursor-pointer" onclick="toggleSort()">
                <h2 class="text-gray-700 font-bold text-xl">📋 Aktivitas Produk</h2>
                <span id="sort-icon" class="text-gray-500 text-sm">▼</span>
            </div>
    
            <hr class="my-3 border-gray-300">
    
            <div id="activity-log-container"
                class="space-y-4 overflow-y-auto pr-2"
                style="flex-grow: 1; min-height: 0;">
                @foreach ($activityLogs as $log)
                    <div class="text-sm text-gray-700 activity-item" data-created="{{ $log['created_at'] }}">
                        <span class="font-semibold">{{ $log['user_name'] }}</span>
                        {{ ucfirst($log['description']) }}
                        <span class="text-xs text-gray-400 block">
                            {{ \Carbon\Carbon::parse($log['created_at'])->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    
    

</div>

<script>
    // console.log("Stock Trends Data:", @json($movementTrends, JSON_NUMERIC_CHECK));
    document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("mainChart").getContext("2d");
    let chart;

    // 🟢 Get Chart Data from Blade
    const chartData = {
        stockTrends: {
            type: 'line',
            labels: @json($movementTrends->pluck('date_time')->toArray()),
            datasets: [
                {
                    label: 'Barang Masuk',
                    data: @json($movementTrends->pluck('incoming')->toArray(), JSON_NUMERIC_CHECK),
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 255, 0, 0.2)',
                    fill: true
                },
                {
                    label: 'Barang Keluar',
                    data: @json($movementTrends->pluck('outgoing')->toArray(), JSON_NUMERIC_CHECK),
                    borderColor: 'red',
                    backgroundColor: 'rgba(255, 0, 0, 0.2)',
                    fill: true
                }
            ]
        },
        stockTrendsByProduct: @json($stockTrendsByProduct, JSON_NUMERIC_CHECK),
        supplyTypesDistribution: @json($supplyTypesDistribution),
        categoryStock: {
            type: 'pie',
            labels: @json($categoryDistribution->pluck('category')),
            datasets: [{
                data: @json($categoryDistribution->pluck('stock'), JSON_NUMERIC_CHECK),
                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)']
            }]
        },
        userContributions: {
            type: 'bar',
            labels: @json($userContributions->pluck('user')),
            datasets: [{
                label: 'Total Kontribusi',
                data: @json($userContributions->pluck('total'), JSON_NUMERIC_CHECK),
                backgroundColor: 'rgba(153, 102, 255, 0.6)'
            }]
        },
        priceDistribution: {
            type: 'bar',
            labels: @json($priceDistribution->pluck('name')->toArray()),
            datasets: [{
                label: 'Harga Produk',
                data: @json($priceDistribution->pluck('price')->toArray(), JSON_NUMERIC_CHECK),
                backgroundColor: 'rgba(255, 159, 64, 0.6)'
            }]
        }
    };

    // 🟡 Function to Create or Update Chart
    function updateChart(chartType, productId = null) {
        let data;
        
        if (chartType === "supplyTypesDistribution") {
            if (productId) {
                // 🔵 Filter data for the selected product
                const filtered = chartData.supplyTypesDistribution.find(item => item.product_id == productId);
                data = {
                    type: "pie",
                    labels: ["Barang Masuk", "Barang Keluar"],
                    datasets: [{
                        data: filtered ? [filtered.incoming, filtered.outgoing] : [0, 0],
                        backgroundColor: ["rgba(75, 192, 192, 0.6)", "rgba(255, 159, 64, 0.6)"]
                    }]
                };
            } else {
                // 🔴 Show aggregated data for all products
                data = {
                    type: "pie",
                    labels: ["Barang Masuk", "Barang Keluar"],
                    datasets: [{
                        data: [
                            chartData.supplyTypesDistribution.reduce((sum, item) => sum + Number(item.incoming || 0), 0),
                            chartData.supplyTypesDistribution.reduce((sum, item) => sum + Number(item.outgoing || 0), 0)
                        ],

                        backgroundColor: ["rgba(75, 192, 192, 0.6)", "rgba(255, 159, 64, 0.6)"]
                    }]
                };
            }
        } else if (chartType === "stockTrends") {
            if (productId && chartData.stockTrendsByProduct && chartData.stockTrendsByProduct[productId]) {
                data = chartData.stockTrendsByProduct[productId];
            } else {
                data = chartData.stockTrends;
            }
        } else {
            data = chartData[chartType];
        }


        if (!data) return;

        if (chart) {
            chart.destroy(); // Destroy previous instance to prevent duplicate data
        }

        chart = new Chart(ctx, {
            type: data.type,
            data: {
                labels: data.labels,
                datasets: data.datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "top"
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    }

    // 🟠 Chart Selection Change Event
    const chartSelector = document.getElementById("chartSelector");
    const productSelector = document.getElementById("productSelector");

    chartSelector.addEventListener("change", function () {
        if (["stockTrends", "supplyTypesDistribution"].includes(this.value)) {
                productSelector.classList.remove("hidden");
            } 
        else {
                productSelector.classList.add("hidden");
            }
        updateChart(this.value, productSelector.value);
    });
    

    // 🟢 Product Selection Change Event
    productSelector.addEventListener("change", function () {
        updateChart(chartSelector.value, this.value);
    });

    // 🔵 Initial Chart Load
    updateChart("stockTrends");
    productSelector.classList.remove("hidden");

});

</script>
    
{{-- For activity feed --}}
<script>
    let ascending = false;

    function toggleSort() {
        ascending = !ascending;
        const container = document.getElementById('activity-log-container');
        const icon = document.getElementById('sort-icon');
        icon.textContent = ascending ? '▲' : '▼';

        const items = Array.from(container.querySelectorAll('.activity-item'));

        items.sort((a, b) => {
            const timeA = new Date(a.dataset.created);
            const timeB = new Date(b.dataset.created);
            return ascending ? timeA - timeB : timeB - timeA;
        });

        // Remove and re-append sorted elements
        items.forEach(item => container.appendChild(item));
    }
</script>

@endsection
