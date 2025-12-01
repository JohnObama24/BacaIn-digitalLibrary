<div x-data="{ openMenu: false }" class="fixed top-0 w-full">
    <nav class="w-full bg-primary-blue text-white shadow-md relative">
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-wide">Baca<span class="text-blue-200">In</span></h1>

            <button @click="openMenu = !openMenu" class="text-white hover:bg-blue-900 p-2 rounded-lg transition">
                <svg x-show="!openMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <svg x-show="openMenu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="openMenu" x-transition
            class="absolute right-6 top-20 w-64 bg-white text-gray-700 rounded-xl shadow-2xl border border-gray-200 z-50"
            style="display: none;">

            <div class="py-2">

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">Profile</a>

                <a href="{{ route('member.home') }}" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Home
                </a>

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Koleksi Buku
                </a>

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Favorit
                </a>

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Peminjaman Saya
                </a>

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Riwayat
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
</div>