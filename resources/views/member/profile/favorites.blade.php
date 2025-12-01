@extends('layouts.member')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Buku Favorit Saya</h1>
            <a href="{{ route('member.profile') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        @if($favorites->isEmpty())
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada buku favorit</h3>
                <p class="mt-2 text-gray-500">Anda belum menambahkan buku apapun ke daftar favorit.</p>
                <a href="{{ route('member.home') }}"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                    Jelajahi Buku
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @foreach($favorites as $fav)
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
                        <a href="{{ route('book.detail', $fav->buku->id) }}" class="block relative group shrink-0">
                            <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden bg-gray-200">
                                <img src="{{ $fav->buku->cover_url }}" alt="{{ $fav->buku->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        </a>
                        <div class="p-4 flex flex-col grow">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2 mb-1">
                                <a href="{{ route('book.detail', $fav->buku->id) }}" class="hover:text-blue-600">
                                    {{ $fav->buku->judul }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $fav->buku->penulis }}</p>
                            <div class="mt-auto pt-2 flex justify-between items-center">
                                <span
                                    class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $fav->buku->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $fav->buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                                <button onclick="toggleFavorite({{ $fav->buku->id }}, this)"
                                    class="text-pink-600 hover:text-pink-800 focus:outline-none p-1 rounded-full hover:bg-pink-50 transition-colors"
                                    title="Hapus dari favorit">
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleFavorite(bookId, btn) {
            if (!confirm('Hapus buku ini dari favorit?')) return;

            fetch('{{ route("member.favorites.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ buku_id: bookId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'removed') {
                        // Reload page to reflect changes
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection