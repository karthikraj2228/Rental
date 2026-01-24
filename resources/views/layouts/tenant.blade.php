<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Home - Rental Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen pb-20">
    
    <!-- Mobile Header -->
    <header class="glass-nav fixed top-0 w-full z-10 border-b border-gray-200 px-4 h-16 flex items-center justify-between">
        <h1 class="text-lg font-bold text-gray-800">My Home</h1>
        <div class="flex items-center gap-3">
             <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 h-16 flex items-center justify-around z-10 pb-safe">
        <a href="{{ route('tenant.dashboard') }}" class="flex flex-col items-center gap-1 text-xs {{ request()->routeIs('tenant.dashboard') ? 'text-blue-600' : 'text-gray-400' }}">
            <i class="bi bi-house-door text-xl"></i>
            Home
        </a>
        <a href="#" class="flex flex-col items-center gap-1 text-xs text-gray-400">
            <i class="bi bi-receipt text-xl"></i>
            History
        </a>
        <form action="{{ route('logout') }}" method="POST" class="flex flex-col items-center gap-1 text-xs text-gray-400 cursor-pointer">
            @csrf
            <button type="submit" class="flex flex-col items-center">
                 <i class="bi bi-person text-xl"></i>
                Profile
            </button>
        </form>
    </nav>

</body>
</html>
