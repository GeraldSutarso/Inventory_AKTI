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
    <link rel="icon" type="image/png" href="{{ asset('img/Favicon akti.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/css/app.css')
    <title>Dashboard Inventory</title>
    
    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -256px;
            width: 256px;
            height: 100%;
            background-color: #1f2937;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease-in-out;
            z-index: 100;
            overflow-y: auto;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #374151;
            padding-bottom: 15px;
        }
        
        .sidebar-logo img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }
        
        .sidebar-logo span {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        
        .sidebar ul li {
            margin: 10px 0;
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: #d1d5db;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        
        .sidebar ul li a:hover {
            background-color: #374151;
            color: white;
        }
        
        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .sidebar .logout {
            margin-top: auto;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background-color: #991b1b;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        
        .sidebar .logout:hover {
            background-color: #b91c1c;
        }
        
        .sidebar .logout i {
            margin-right: 10px;
        }
        
        .sidebar.active {
            left: 0;
        }
        
        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 97;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Close Sidebar Button */
        .close-sidebar-button {
            position: fixed;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #991b1b;
            color: white;
            border: none;
            z-index: 103;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            opacity: 0;
            visibility: hidden;
        }
        
        .sidebar.active + .close-sidebar-button,
        .sidebar.active ~ .close-sidebar-button {
            left: 230px;
            opacity: 1;
            visibility: visible;
        }
        
        .close-sidebar-button:hover {
            background-color: #7f1d1d;
        }
        
        /* Scroll to Top Button */
        .scroll-to-top {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #991b1b;
            color: white;
            border: none;
            outline: none;
            cursor: pointer;
            z-index: 104;
            transition: background-color 0.3s, transform 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .scroll-to-top:hover {
            background-color: #b91c1c;
            transform: translateY(-5px);
        }
        
        .scroll-to-top:active {
            transform: translateY(0);
        }
        
        /* Main Content Area */
        .main-content {
            transition: margin-left 0.3s ease-in-out;
            padding-top: 60px;
            min-height: 100vh;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .main-content.sidebar-active {
                margin-left: 256px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-black">
    <!-- Navbar (Always Visible) -->
    <div class="bg-white py-3 px-6 flex items-center justify-between shadow-md fixed top-0 left-0 w-full z-30">
        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle" class="text-gray-600 flex items-center">
            <i class="ri-menu-line text-2xl"></i>
        </button>
        <h2 class="text-lg font-semibold text-gray-700">Dashboard Inventory</h2>
        <div class="flex items-center">
            <p class="text-sm text-gray-600 mr-2">{{ Auth::user()->name }}</p>
            <img 
                src="{{ Auth::user()->role == 'admin' ? 'https://static.vecteezy.com/system/resources/previews/020/429/953/original/admin-icon-vector.jpg' : 'https://th.bing.com/th/id/OIP.KEwFWztwZ37-ZKTMcxuZuAHaHa?w=512&h=512&rs=1&pid=ImgDetMain' }}" 
                alt="User" 
                class="w-10 h-10 rounded-full border border-gray-300"
            />
        </div>        
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Favicon akti.png') }}" alt="logo" class="object-cover" />
            <span>Inventory AKTI</span>
        </div>
        <ul class="mt-6 space-y-3">
            <li><a href="/"><i class="ri-dashboard-line"></i>Overview</a></li>
            <li><a href="/barang"><i class="ri-archive-2-line"></i>Data Barang</a></li>
            <li><a href="/kategori"><i class="ri-book-line"></i>Data Lokasi</a></li>
            <li><a href="/supplies"><i class="ri-archive-line"></i>Riwayat Stok</a></li>
            <li><a href="/qr-scanner"><i class="ri-qr-code-line"></i>QR Scanner</a></li>
            @if(Auth::user()->role === 'admin')
            <li><a href="/petugas"><i class="ri-user-line"></i>Data Petugas</a></li>
            <li><a href="/admin"><i class="ri-admin-line"></i>Data Admin</a></li>
            @endif
            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#bugReportModal">
                    <i class="ri-bug-line"></i>Laporkan Bug
                </a>
            </li>
        </ul>
        <!-- Logout Button -->
        <a href="/logout" class="logout">
            <i class="ri-logout-circle-line"></i>Logout
        </a>
    </div>
    
    <!-- Close Sidebar Button -->
    <button id="closeSidebarButton" class="close-sidebar-button">&lt;</button>

    <!-- Main Content -->
    <main id="main-content" class="main-content p-6">
        @yield('container')
    </main>

    <!-- Scroll to Top Button -->
    <button id="scrollToTopBtn" class="scroll-to-top">
        <i class="ri-arrow-up-line text-xl"></i>
    </button>

    <!-- Bug Report Modal -->
    <div style="z-index: 105" id="bugReportModal" class="fixed inset-0 z-102 hidden">
        <!-- Modal Backdrop/Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50" id="modalOverlay"></div>
        
        <!-- Modal Content -->
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white max-w-md">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Laporkan Bug</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" id="closeModalBtn">
                        <span class="sr-only">Close</span>
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="mt-4 mb-6">
                    <p class="text-sm text-gray-600 mb-3">Kalau terdapat error atau bug, silahkan hubungi lewat email atau nomor whatsapp berikut:</p>
                    <p class="text-sm text-gray-600 flex items-center mb-2">
                        <i class="ri-mail-line mr-2"></i> Email: 
                        <a href="mailto:eomverif@gmail.com" class="ml-1 text-blue-600 hover:underline">eomverif@gmail.com</a>
                    </p>
                    <p class="text-sm text-gray-600 flex items-center">
                        <i class="ri-whatsapp-line mr-2"></i> Telepon/WA: 
                        <a href="https://wa.me/6282311921842" class="ml-1 text-blue-600 hover:underline">+62 823-1192-1842</a>
                    </p>
                </div>
                
                <!-- Modal Footer -->
                <div class="mt-4 pt-3 border-t">
                    <p class="text-sm text-gray-600">Kalau ada foto/video keterangannya, akan sangat membantu.<b> Jangan lupa untuk memberikan nama website yang bermasalah</b></p>
                    <p class="text-sm text-gray-600 italic">~Terima kasih</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Modal functionality
            const bugReportLink = document.querySelector('a[data-bs-toggle="modal"][data-bs-target="#bugReportModal"]');
            const bugReportModal = document.getElementById('bugReportModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const modalOverlay = document.getElementById('modalOverlay');

            // Update the bug report link to use our new modal
            if (bugReportLink) {
                bugReportLink.removeAttribute('data-bs-toggle');
                bugReportLink.removeAttribute('data-bs-target');
                
                bugReportLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    bugReportModal.classList.remove('hidden');
                });
            }

            // Close modal when the close button is clicked
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    bugReportModal.classList.add('hidden');
                });
            }

            // Close modal when clicking on the overlay
            if (modalOverlay) {
                modalOverlay.addEventListener('click', function() {
                    bugReportModal.classList.add('hidden');
                });
            }

            // Close modal when pressing ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !bugReportModal.classList.contains('hidden')) {
                    bugReportModal.classList.add('hidden');
                }
            });

            const sidebarToggle = document.getElementById("sidebarToggle");
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");
            const closeSidebarButton = document.getElementById("closeSidebarButton");
            const mainContent = document.getElementById("main-content");
            
            // Function to toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle("active");
                overlay.classList.toggle("active");
                
                // On desktop, adjust main content margin
                if (window.innerWidth >= 768) {
                    mainContent.classList.toggle("sidebar-active");
                }
            }

            // Toggle sidebar visibility
            sidebarToggle.addEventListener("click", toggleSidebar);

            // Hide sidebar when close button is clicked
            closeSidebarButton.addEventListener("click", function() {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.remove("sidebar-active");
                }
            });

            // Hide sidebar when overlay is clicked
            overlay.addEventListener("click", function() {
                sidebar.classList.remove("active");
                overlay.classList.remove("active");
                
                if (window.innerWidth >= 768) {
                    mainContent.classList.remove("sidebar-active");
                }
            });
            
            // Scroll to top functionality
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');

            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 10) {
                    scrollToTopBtn.style.display = 'flex';
                } else {
                    scrollToTopBtn.style.display = 'none';
                }
            });

            scrollToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Check modal functionality
            if (typeof bootstrap !== 'undefined') {
                // If Bootstrap is available
                var myModal = new bootstrap.Modal(document.getElementById('bugReportModal'));
            }
        });
    </script>

    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>
</html>