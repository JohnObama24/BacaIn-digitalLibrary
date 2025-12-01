<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Bacaln</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100" x-data="{ openMenu: false }">

    <nav class="w-full bg-primary-blue text-white shadow-md relative">
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-wide">Baca<span class="text-blue-200">In</span></h1>

            <button @click="openMenu = !openMenu" class="text-white hover:bg-blue-900 p-2 rounded-lg transition">
                <svg x-show="!openMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                
                <svg x-show="openMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

  <div x-show="openMenu"
         x-transition
         class="absolute right-6 top-20 w-64 bg-white text-gray-700 rounded-xl shadow-2xl border border-gray-200 z-50"
         style="display: none;">

        <div class="py-2">

            <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">Profile</a>

            <a href="{{ route('admin.dashboard') }}"
               class="block px-6 py-3 hover:bg-gray-100 font-medium">
               Statistik Data
            </a>

            <a href="{{ route('buku.index') }}"
               class="block px-6 py-3 hover:bg-gray-100 font-medium">
               Manajemen Buku
            </a>

            <a href="{{ route('kategori.index') }}"
               class="block px-6 py-3 hover:bg-gray-100 font-medium">
               Kategori Buku
            </a>

            <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                Laporan Digital
            </a>

            <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                Manajemen Events
            </a>

            <a href="{{ route('peminjaman.index') }}"
               class="block px-6 py-3 hover:bg-gray-100 font-medium">
               Konfirmasi Peminjaman
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50 font-medium">
                    Logout
                </button>
            </form>
        </div>
    </div>
    </nav>
    
<div class="w-full bg-white shadow-sm border-b border-gray-200">
    <div class="px-6"> <div class="flex items-center h-14"> 
            <div class="flex items-center gap-2 h-full border-b-[3px] border-primary-blue text-primary-blue px-1">
                <h2 class="font-bold text-lg tracking-wide">
                    @yield('page_title', 'Dashboard') 
                </h2>
            </div>

        </div>
    </div>
</div>

    <main class="p-6 ">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-yellow-700 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-red-700 font-medium mb-2">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside text-red-600 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>
