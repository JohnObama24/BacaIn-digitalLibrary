<nav class="w-full bg-primary-blue px-4 py-3 flex items-center justify-between shadow-md">

    <a href="/" class="text-white font-bold text-2xl">
        Baca<span class="text-secondary-blue">In</span>
    </a>

    <div class="flex items-center bg-[#f3eee7] rounded-full shadow overflow-hidden mx-2 w-40">

        <button class="px-3 py-2 border-r border-gray-300 text-gray-700">
            <i class="fa-solid fa-bars"></i>
        </button>

        <input type="text" placeholder="Search..." class="px-2 py-2 bg-transparent text-sm focus:outline-none w-full">

        <button class="px-3 py-2 text-gray-600">
            <i class="fa-solid fa-search"></i>
        </button>
    </div>

    <div class="flex items-center gap-2">
        <button class="w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center">
            <i class="fa-regular fa-heart"></i>
        </button>

        <button class="w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center">
            <i class="fa-solid fa-books"></i>
        </button>

        <button id="mobileMenuButton"
            class="w-9 h-9 rounded-full bg-white/20 text-white flex items-center justify-center">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>


{{-- <div id="mobileMenu" class="hidden bg-[#1d5b7a] text-white p-4 space-y-3 md:hidden">
    <a href="#" class="block hover:text-teal-300">Home</a>
    <a href="#" class="block hover:text-teal-300">Buku Populer</a>
    <a href="#" class="block hover:text-teal-300">Favorit</a>
    <a href="#" class="block hover:text-teal-300">Rak Saya</a>
    <a href="#" class="block hover:text-teal-300">Akun</a>
</div> --}}

<script>
    const btn = document.getElementById('mobileMenuButton');
    const menu = document.getElementById('mobileMenu');

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
