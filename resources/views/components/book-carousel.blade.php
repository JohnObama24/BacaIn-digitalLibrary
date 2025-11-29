@props(['books'])

@if ($books->count() > 0)
    <div class="px-4 md:px-16 mt-6">
        <div class="flex justify-between">
            <h2 class="text-lg font-bold">Book Recommendations</h2>
            <a href="#" class="text-sm text-blue-600">Explore More →</a>
        </div>

        <div class="mt-4 overflow-x-auto flex space-x-4 pb-4">
            @foreach ($books as $book)
                <div class="min-w-[140px] bg-white shadow rounded-xl p-3">
                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-40 object-cover rounded">

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $book->kategori?->nama_kategori ?? '-' }}
                    </p>

                    <p class="font-semibold">{{ $book->judul }}</p>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="px-4 md:px-16 mt-6">
        <h2 class="text-lg font-bold">Book Recommendations</h2>
        <div class="mt-3 bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-xl">
            Belum ada rekomendasi buku.
        </div>
    </div>
@endif
