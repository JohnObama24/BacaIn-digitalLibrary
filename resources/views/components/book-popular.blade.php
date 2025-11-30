@props(['popularBooks'])

@if ($popularBooks->count() > 0)
    <div class="px-4 md:px-16 mt-12">
        <h2 class="text-3xl font-semibold mb-4">Popular Books In The Week</h2>

        <div class="bg-[#0F6A8C] rounded-2xl p-6">
            <div class="grid grid-cols-4 md:grid-cols-6 gap-6 justify-items-center">
                @foreach ($popularBooks as $index => $b)
                    <div class="relative">
                        <div class="bg-white w-28 h-28 md:w-32 md:h-32 rounded-full p-3 shadow-md flex items-center justify-center">
                            <img src="{{ asset('storage/' . $b->cover) }}"
                                class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-full">
                        </div>

                        <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs
                                    rounded-full w-6 h-6 flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </div>

                        <p class="text-white text-center mt-3 text-sm truncate w-28">
                            {{ $b->judul }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="px-4 md:px-16 mt-12">
        <h2 class="text-lg font-bold mb-4">Popular Books In The Week</h2>
        <div class="bg-gray-100 text-gray-600 p-4 rounded-xl">
            Belum ada buku populer minggu ini.
        </div>
    </div>
@endif
