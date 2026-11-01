<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} | Admin Panel</title>
    
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1f2937',
                        sidebar: '#111827',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Start -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-sky-800 text-gray-100 transition-transform transform -translate-x-full md:translate-x-0 md:static md:inset-0 flex flex-col">
            <div class="flex items-center justify-center h-16 bg-sky-700 border-b border-sky-800">
                <a href="{{ route('admin.index') }}" class="flex items-center gap-2 text-white font-bold text-xl tracking-wider uppercase">
                    <img src="{{ asset('assets/images/logo-w.png') }}" onerror="this.onerror=null; this.src='{{ asset('assets/images/logo.png') }}';" alt="Logo" class="h-10" />
                </a>
            </div>

            <div class="flex-1 overflow-y-auto py-4">
                <nav class="px-4 space-y-1">
                    <a href="{{ route('admin.index') }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.index') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <p class="px-4 text-xs font-semibold text-sky-200 uppercase mt-4 mb-2">Management</p>
                    
                    <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.products*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-box w-5 text-center"></i>
                        <span>Products</span>
                    </a>

                    <a href="{{ Route::has('admin.categories') ? route('admin.categories') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.categories*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-layer-group w-5 text-center"></i>
                        <span>Categories</span>
                    </a>

                    <a href="{{ Route::has('admin.brands') ? route('admin.brands') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.brands*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-tag w-5 text-center"></i>
                        <span>Brands</span>
                    </a>

                    <a href="{{ Route::has('admin.coupons') ? route('admin.coupons') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.coupons*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-ticket w-5 text-center"></i>
                        <span>Coupons</span>
                    </a>

                    <a href="{{ Route::has('admin.orders') ? route('admin.orders') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.orders*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-cart-shopping w-5 text-center"></i>
                        <span>Orders</span>
                    </a>

                    <a href="{{ Route::has('admin.slides') ? route('admin.slides') : '#' }}" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition {{ request()->routeIs('admin.slides*') ? 'bg-primary text-white shadow-md font-medium' : '' }}">
                        <i class="fa-solid fa-images w-5 text-center"></i>
                        <span>Slides</span>
                    </a>

                    <p class="px-4 text-xs font-semibold text-sky-200 uppercase mt-4 mb-2">System</p>

                    <a href="{{ route('home.index') }}" target="_blank" class="nav-link flex items-center gap-3 px-4 py-2.5 text-gray-100 hover:text-white hover:bg-sky-700 rounded-lg transition">
                        <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center"></i>
                        <span>View Website</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-sky-700">
                <form method="POST" action="{{ route('logout') }}" id="admin-logout-form">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-gray-100 hover:text-white hover:bg-red-600 rounded-lg transition text-left">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
        <!-- Sidebar End -->

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Header Start -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-30">
                <button id="mobile-menu-btn" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>

                <div class="hidden md:flex items-center bg-gray-100 rounded-lg px-3 py-2 w-96">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none ml-2 text-sm w-full text-gray-600">
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative text-gray-500 hover:text-primary transition">
                        <i class="fa-regular fa-bell text-xl"></i>
                    </button>
                    
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-sky-700 text-white font-bold flex items-center justify-center border border-gray-200">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>
            <!-- Header End -->

            <!-- Main Content Start -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
            <!-- Main Content End -->

        </div>
    </div>

    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (mobileMenuBtn && sidebar && overlay) {
            function toggleSidebar() {
                const isClosed = sidebar.classList.contains('-translate-x-full');
                if (isClosed) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }

            mobileMenuBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        }
    </script>
    @stack('scripts')
</body>
</html>

