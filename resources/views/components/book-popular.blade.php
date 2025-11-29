@props(['popularBooks'])    

@if ($popularBooks->count() > 0)
    <div class="px-4 md:px-16 mt-10">
        <h2 class="text-lg font-bold mb-4">Popular Books In The Week</h2>

        <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
            @foreach ($popularBooks as $b)
                <div class="bg-white shadow rounded-xl p-3 text-center">
                    <img src="{{ asset('storage/' . $b->cover) }}" class="w-full h-28 object-cover rounded">

                    <p class="text-sm font-semibold mt-2 truncate">{{ $b->judul }}</p>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="px-4 md:px-16 mt-10">
        <h2 class="text-lg font-bold mb-4">Popular Books In The Week</h2>
        <div class="bg-gray-100 text-gray-600 p-4 rounded-xl">
            Belum ada buku populer minggu ini.
        </div>
    </div>
@endif
