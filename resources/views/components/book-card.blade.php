@props(['item', 'mode' => 'active'])

<div class="bg-white rounded-2xl shadow-md overflow-hidden w-full">
    {{-- Cover --}}
    <div class="h-52 w-full bg-gray-200 flex items-center justify-center">
        <img src="{{ $item->buku->cover_url }}" 
             class="h-full object-cover" 
             alt="{{ $item->buku->judul }}">
    </div>

    {{-- Content --}}
    <div class="p-4">
        <h3 class="text-lg font-bold">{{ $item->buku->judul }}</h3>
        <p class="text-sm text-gray-500">By {{ $item->buku->penulis }}</p>

        <div class="mt-3 text-sm">
            <p>
                <span class="font-semibold">Dipinjam:</span>
                {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
            </p>

            <p>
                <span class="font-semibold">Jatuh Tempo:</span>
                {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
            </p>
        </div>

        {{-- Status --}}
        <div class="mt-3">
            @if($mode === 'active')
                <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                    Sedang dipinjam
                </span>

                <div class="mt-2 flex items-center text-sm text-gray-600">
                    ⏳ {{ now()->diffInDays($item->tanggal_kembali, false) }} Hari lagi
                </div>

                {{-- Buttons --}}
                <div class="mt-4 space-y-2">
                    <button class="w-full bg-blue-600 text-white py-2 rounded-lg">Kembalikan</button>
                    <button class="w-full bg-gray-200 text-gray-800 py-2 rounded-lg">Beri Ulasan</button>
                    <button class="w-full bg-yellow-500 text-white py-2 rounded-lg">Perpanjang</button>
                    <button class="w-full border py-2 rounded-lg">Baca</button>
                </div>

            @else
                {{-- HISTORY --}}
                <div class="mt-3">
                    <p class="text-sm">
                        <span class="font-semibold">Tgl Kembali:</span>
                        {{ $item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') : '-' }}
                    </p>

                    <p class="mt-1 text-sm">
                        <span class="font-semibold">Status:</span>
                        {{ ucfirst($item->status_peminjaman) }}
                    </p>

                    @if($item->denda > 0)
                        <p class="mt-1 font-semibold text-red-600">
                            Denda: Rp {{ number_format($item->denda, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
