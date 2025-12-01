@props(['books'])

@if ($books->count() > 0)
    <div class="px-4 md:px-16 mt-10">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-semibold">Book Recommendations</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">Explore More →</a>
        </div>

        <div class="mt-5 overflow-x-auto flex space-x-5 pb-4 scroll-smooth">
            @foreach ($books as $book)
                <a href="{{ route('book.detail', $book->id) }}"
                    class="min-w-[150px] bg-white shadow-md rounded-2xl p-4 hover:scale-105 transition block">
                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-44 object-cover rounded-xl">

                    <p class="text-xs text-gray-500 mt-2">
                        {{ $book->kategori?->nama_kategori ?? '-' }}
                    </p>

                    <p class="font-semibold text-sm truncate">{{ $book->judul }}</p>
                </a>
            @endforeach
        </div>
    </div>
@else
    <div class="px-4 md:px-16 mt-10">
        <h2 class="text-lg font-bold">Book Recommendations</h2>
        <div class="mt-3 bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-xl">
            Belum ada rekomendasi buku.
        </div>
    </div>
@endif
