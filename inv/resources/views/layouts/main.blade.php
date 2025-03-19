<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/base.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite('resources/css/app.css')
    <title>Dashboard Inventory</title>
</head>
<body class="bg-gray-100 text-black">
    
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 h-screen p-5 fixed top-0 left-0 shadow-lg">
        <a href="#" class="flex items-center pb-4 border-b border-gray-800">
            <img src="/img/Favicon akti.png" alt="logo" class="w-10 h-10 rounded-full mr-3 object-cover" />
            <span class="text-lg font-bold text-white">Inventory AKTI</span>
        </a>
        <ul class="mt-6 space-y-3">
            <li><a href="/" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-dashboard-line mr-3 text-lg"></i>Overview</a></li>
            <li><a href="/barang" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-archive-2-line mr-3 text-lg"></i>Data Barang</a></li>
            <li><a href="/kategori" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-book-line mr-3 text-lg"></i>Data Kategori</a></li>
            <li><a href="/barang-masuk" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-add-box-line mr-3 text-lg"></i>Barang Masuk</a></li>
            <li><a href="/barang-keluar" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-folder-reduce-line mr-3 text-lg"></i>Barang Keluar</a></li>
            @if(Auth::user()->role === 'admin')
            <li><a href="/petugas" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-user-line mr-3 text-lg"></i>Data Petugas</a></li>
            <li><a href="/admin" class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 rounded-md transition"><i class="ri-admin-line mr-3 text-lg"></i>Data Admin</a></li>
            @endif
            <li><a href="/logout" class="flex items-center px-4 py-2 text-red-400 hover:bg-gray-700 rounded-md transition"><i class="ri-logout-circle-line mr-3 text-lg"></i>Logout</a></li>
        </ul>
    </div>
    <!-- Sidebar End -->
    
    <main class="md:w-[calc(100%-256px)] md:ml-64 min-h-screen">
        <!-- Navbar -->
        <div class="bg-white py-3 px-6 flex items-center justify-between shadow-md fixed top-0 left-0 w-full md:w-[calc(100%-256px)] md:ml-64 z-30">
            <h2 class="text-lg font-semibold text-gray-700">Dashboard</h2>
            <div class="flex items-center">
                <p class="text-sm text-gray-600 mr-2">{{ Auth::user()->name }}</p>
                <img src="https://placehold.co/40x40" alt="User" class="w-10 h-10 rounded-full border border-gray-300" />
            </div>
        </div>
        <!-- Navbar End -->
        
        <!-- Main Content -->
        <div class="mt-16 p-6">
            @yield('container')
        </div>
        <!-- Main Content End -->
    </main>
    
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>
</html>