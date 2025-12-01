<div x-data="{ openMenu: false, openCategory: false }" class="fixed top-0 w-full z-50">
    <nav class="w-full bg-primary-blue text-white shadow-md relative">
        <div class="px-6 py-4 flex justify-between items-center gap-4">
            <h1 class="text-2xl font-bold tracking-wide whitespace-nowrap">Baca<span class="text-blue-200">In</span>
            </h1>

            <div class="flex-1 max-w-2xl">
                <form action="{{ route('member.home') }}" method="GET"
                    class="flex items-center bg-[#f3eee7] rounded-full shadow overflow-hidden">

                    <div class="relative" x-data="{ openCategory: false }">
                        <button type="button" @click="openCategory = !openCategory"
                            class="px-4 py-2 border-r border-gray-300 text-gray-700 hover:bg-gray-100 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span class="text-sm hidden sm:inline">Kategori</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openCategory" @click.away="openCategory = false" x-transition
                            class="absolute left-0 top-full mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
                            style="display: none;">
                            <a href="{{ route('member.home', ['search' => request('search')]) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                Semua Kategori
                            </a>
                            @php
                                $categories = \App\Models\Kategori::all();
                            @endphp
                            @foreach($categories as $category)
                                <a href="{{ route('member.home', ['kategori' => $category->id, 'search' => request('search')]) }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                    {{ $category->nama_kategori }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif

                    <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}"
                        class="flex-1 px-4 py-2 bg-transparent text-sm text-gray-700 placeholder-gray-500 focus:outline-none min-w-0">

                    <button type="submit" class="px-4 py-2 text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

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

                <a href="{{ route('member.profile') }}" class="block px-6 py-3 hover:bg-gray-100 font-medium">Profile</a>

                <a href="{{ route('member.home') }}" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Home
                </a>

                <a href="#" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Koleksi Buku
                </a>

                <a href="{{ route('member.favorites') }}" class="block px-6 py-3 hover:bg-gray-100 font-medium">
                    Favorit
                </a>

                <a href="{{ route('member.history') }}" class="block px-6 py-3 hover:bg-gray-100 font-medium">
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