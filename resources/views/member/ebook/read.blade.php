@extends('layouts.member')

@section('content')
    <div class="min-h-screen bg-gray-900">
        <div class="bg-gray-800 shadow-lg sticky top-0 z-10">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('user.peminjaman') }}" class="text-gray-300 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-white font-bold text-lg">{{ $peminjaman->buku->judul }}</h1>
                        <p class="text-gray-400 text-sm">{{ $peminjaman->buku->penulis }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <form action="{{ route('peminjaman.return-ebook', $peminjaman->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Yakin ingin mengembalikan e-book ini?')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm font-medium">
                            Kembalikan
                        </button>
                    </form>

                    <div class="text-sm text-gray-300">
                        <p>Kembali: {{ $peminjaman->tanggal_pengembalian->format('d M Y') }}</p>
                        <p class="{{ $peminjaman->isOverdue() ? 'text-red-400' : 'text-green-400' }}">
                            @if($peminjaman->isOverdue())
                                ⚠️ Terlambat {{ $peminjaman->tanggal_pengembalian->diffInDays(now()) }} hari
                            @else
                                Sisa {{ now()->diffInDays($peminjaman->tanggal_pengembalian) }} hari
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-6">
            @if($peminjaman->buku->isi_buku)
                <div class="bg-white rounded-lg shadow-2xl overflow-hidden" style="height: calc(100vh - 120px);">
                    <iframe src="{{ asset('storage/' . $peminjaman->buku->isi_buku) }}" class="w-full h-full" frameborder="0"
                        type="application/pdf">
                        <p class="p-4">
                            Browser Anda tidak mendukung PDF viewer.
                            <a href="{{ asset('storage/' . $peminjaman->buku->isi_buku) }}" download
                                class="text-primary-blue hover:underline">
                                Download PDF
                            </a>
                        </p>
                    </iframe>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-2xl p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-600 text-lg">File PDF tidak tersedia</p>
                    <a href="{{ route('user.peminjaman') }}" class="mt-4 inline-block text-primary-blue hover:underline">
                        Kembali ke Peminjaman
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection