<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rental Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-active { @apply bg-blue-600 text-white; }
        .sidebar-item { @apply flex items-center px-6 py-3 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors duration-200; }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.3s ease-out; }
    </style>
</head>
<body class="bg-gray-50 h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }">
    
    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity 
         class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 border-r border-gray-800 transition-transform duration-300 transform lg:static lg:translate-x-0 flex flex-col pt-5 shadow-2xl">
        
        <div class="px-6 mb-8 text-white text-xl font-bold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                   <i class="bi bi-building"></i>
                </div>
                <span>RentalMgr</span>
            </div>
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto space-y-1 px-3">

    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
       class="sidebar-item flex items-center w-full px-4 py-3 rounded-xl
              transition-all duration-200
              {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white hover:bg-blue-500/20' }}">
        <i class="bi bi-speedometer2 mr-3"></i>
        Dashboard
    </a>

    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4 mb-2">
        Management
    </p>

    <!-- Properties -->
    <a href="{{ route('admin.houses.index') }}"
       class="sidebar-item flex items-center w-full px-4 py-3 rounded-xl
              transition-all duration-200
              {{ request()->routeIs('admin.houses*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white hover:bg-blue-500/20' }}">
        <i class="bi bi-house-door mr-3"></i>
        Properties
    </a>

    <!-- Tenants -->
    <a href="{{ route('admin.tenants.index') }}"
       class="sidebar-item flex items-center w-full px-4 py-3 rounded-xl
              transition-all duration-200
              {{ request()->routeIs('admin.tenants*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white hover:bg-blue-500/20' }}">
        <i class="bi bi-people mr-3"></i>
        Tenants
    </a>

    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mt-4 mb-2">
        Finance
    </p>

    <!-- Payments -->
    <a href="{{ route('admin.payments.index') }}"
       class="sidebar-item flex items-center w-full px-4 py-3 rounded-xl
              transition-all duration-200
              {{ request()->routeIs('admin.payments*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white hover:bg-blue-500/20' }}">
        <i class="bi bi-cash-stack mr-3"></i>
        Payments
    </a>

    <!-- Reports -->
    <a href="{{ route('admin.reports.index') }}"
       class="sidebar-item flex items-center w-full px-4 py-3 rounded-xl
              transition-all duration-200
              {{ request()->routeIs('admin.reports*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20' : 'text-white hover:bg-blue-500/20' }}">
        <i class="bi bi-file-earmark-bar-graph mr-3"></i>
        Reports
    </a>

</nav>


        <div class="p-4 border-t border-gray-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-red-400 hover:bg-red-500/10 hover:text-red-300 rounded-xl transition">
                    <i class="bi bi-box-arrow-right mr-3"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-8 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <!-- Hamburger Menu -->
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 lg:hidden focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">
                    @yield('header', 'Dashboard')
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Content Scrollable -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8 bg-gray-50">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center justify-between animate-fade-in-down" role="alert">
                    <p>{{ session('success') }}</p>
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r shadow-sm flex items-center justify-between animate-fade-in-down" role="alert">
                    <p>{{ session('error') }}</p>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
