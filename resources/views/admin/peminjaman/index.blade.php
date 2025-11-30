@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('header', 'Kelola Peminjaman')
@section('subheader', 'Kelola semua peminjaman buku')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm mb-1">Pending</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $peminjaman->where('status_peminjaman', 'pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm mb-1">Dipinjam</p>
            <p class="text-3xl font-bold text-blue-600">{{ $peminjaman->where('status_peminjaman', 'dipinjam')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
            <p class="text-gray-500 text-sm mb-1">Dikembalikan</p>
            <p class="text-3xl font-bold text-green-600">{{ $peminjaman->where('status_peminjaman', 'dikembalikan')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-red-500">
            <p class="text-gray-500 text-sm mb-1">Ditolak</p>
            <p class="text-3xl font-bold text-red-600">{{ $peminjaman->where('status_peminjaman', 'rejected')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500">
            <p class="text-gray-500 text-sm mb-1">Total Denda</p>
            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($peminjaman->where('denda_lunas', false)->sum('denda'), 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow-md p-1 flex space-x-1" x-data="{ tab: 'all' }">
        <button @click="tab = 'all'" 
                :class="tab === 'all' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            Semua ({{ $peminjaman->count() }})
        </button>
        <button @click="tab = 'pending'" 
                :class="tab === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            Pending ({{ $peminjaman->where('status_peminjaman', 'pending')->count() }})
        </button>
        <button @click="tab = 'dipinjam'" 
                :class="tab === 'dipinjam' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            Dipinjam ({{ $peminjaman->where('status_peminjaman', 'dipinjam')->count() }})
        </button>
        <button @click="tab = 'dikembalikan'" 
                :class="tab === 'dikembalikan' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="flex-1 px-4 py-2 rounded-lg font-medium transition">
            Dikembalikan ({{ $peminjaman->where('status_peminjaman', 'dikembalikan')->count() }})
        </button>
    </div>

    <!-- Peminjaman List -->
    <div class="space-y-4">
        @forelse($peminjaman as $item)
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all overflow-hidden peminjaman-item"
             data-status="{{ $item->status_peminjaman }}"
             x-show="tab === 'all' || tab === '{{ $item->status_peminjaman }}'">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Left: Book & User Info -->
                    <div class="flex space-x-4 flex-1">
                        <img src="{{ $item->buku->cover ? asset('storage/'.$item->buku->cover) : 'https://via.placeholder.com/80x120/4F46E5/FFFFFF?text=No+Cover' }}" 
                             alt="{{ $item->buku->judul }}" 
                             class="w-16 h-24 object-cover rounded-lg shadow-md">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item->buku->judul }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ $item->buku->penulis }}</p>
                            
                            <div class="flex items-center space-x-4 text-sm">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $item->user->name }}
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $item->user->email }}
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                <div class="flex items-center bg-blue-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-blue-700">
                                        Pinjam: {{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center bg-purple-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-purple-700">
                                        Kembali: {{ \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') }}
                                    </span>
                                </div>
                                @if($item->denda > 0)
                                <div class="flex items-center bg-red-50 px-3 py-1 rounded-full">
                                    <svg class="w-4 h-4 mr-1 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-red-700 font-medium">
                                        Denda: Rp {{ number_format($item->denda, 0, ',', '.') }} 
                                        @if($item->denda_lunas)
                                            <span class="text-green-600">(Lunas)</span>
                                        @else
                                            <span class="text-red-600">(Belum Lunas)</span>
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Status & Actions -->
                    <div class="ml-4 flex flex-col items-end space-y-3">
                        <!-- Status Badge -->
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-yellow-100 text-yellow-700', 'icon' => '⏳', 'text' => 'Pending'],
                                'dipinjam' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => '📖', 'text' => 'Dipinjam'],
                                'dikembalikan' => ['class' => 'bg-green-100 text-green-700', 'icon' => '✅', 'text' => 'Dikembalikan'],
                                'rejected' => ['class' => 'bg-red-100 text-red-700', 'icon' => '❌', 'text' => 'Ditolak'],
                                'selesai' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => '🏁', 'text' => 'Selesai'],
                            ];
                            $config = $statusConfig[$item->status_peminjaman] ?? ['class' => 'bg-gray-100 text-gray-700', 'icon' => '❓', 'text' => ucfirst($item->status_peminjaman)];
                        @endphp
                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $config['class'] }}">
                            {{ $config['icon'] }} {{ $config['text'] }}
                        </span>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2 justify-end">
                            @if($item->status_peminjaman === 'pending')
                                <form action="{{ route('peminjaman.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form action="{{ route('peminjaman.reject', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Yakin ingin menolak peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                        ✗ Tolak
                                    </button>
                                </form>
                            @endif

                            @if($item->status_peminjaman === 'dipinjam')
                                <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition text-sm font-medium">
                                        🔄 Kembalikan
                                    </button>
                                </form>
                            @endif

                            @if($item->status_peminjaman === 'dikembalikan' && $item->denda > 0 && !$item->denda_lunas)
                                <form action="{{ route('peminjaman.konfirmasi-denda', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium">
                                        💰 Konfirmasi Denda
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-500 text-lg">Belum ada peminjaman</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
