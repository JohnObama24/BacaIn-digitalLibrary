@extends('layouts.member')

@section('content')
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Peminjaman Saya</h1>
            <a href="{{ route('member.profile') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Profil
            </a>
        </div>

        {{-- Tab Buttons --}}
        <div class="flex gap-3 mb-6">
            <a href="{{ route('member.history') }}"
                class="px-6 py-2 rounded-full font-semibold transition {{ request('tab') !== 'history' ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                Sedang Dipinjam
            </a>
            <a href="{{ route('member.history') }}?tab=history"
                class="px-6 py-2 rounded-full font-semibold transition {{ request('tab') === 'history' ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                Riwayat
            </a>
        </div>

        {{-- Active Borrowings --}}
        @if(request('tab') !== 'history')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($activePeminjaman as $item)
                    <x-book-card :item="$item" mode="active" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Tidak ada buku yang sedang dipinjam</p>
                    </div>
                @endforelse
            </div>
        @else
            {{-- History --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($historyPeminjaman as $item)
                    <x-book-card :item="$item" mode="history" />
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Belum ada riwayat peminjaman</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $historyPeminjaman->links() }}
            </div>
        @endif
    </div>
@endsection