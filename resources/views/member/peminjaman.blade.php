@extends('layouts.member')

@section('content')
<div class="min-h-screen bg-gray-50 px-4 md:px-24 py-10">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Peminjaman Saya</h1>
            <p class="text-gray-600 mt-2">Kelola dan pantau buku yang Anda pinjam</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm mb-6" x-data="{ tab: 'aktif' }">
            <div class="border-b">
                <div class="flex space-x-8 px-6">
                    <button @click="tab = 'aktif'" 
                            :class="tab === 'aktif' ? 'border-primary-blue text-primary-blue' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                        Aktif
                    </button>
                    <button @click="tab = 'pending'" 
                            :class="tab === 'pending' ? 'border-primary-blue text-primary-blue' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                        Menunggu Approval
                    </button>
                    <button @click="tab = 'selesai'" 
                            :class="tab === 'selesai' ? 'border-primary-blue text-primary-blue' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium text-sm transition">
                        Selesai
                    </button>
                </div>
            </div>

            <div x-show="tab === 'aktif'" class="p-6">
                @php
                    $aktif = $peminjaman->where('status_peminjaman', 'dipinjam');
                @endphp
                
                @forelse($aktif as $loan)
                    <div class="flex gap-6 p-4 bg-gray-50 rounded-lg mb-4 hover:shadow-md transition">
                        <img src="{{ $loan->buku->cover ? asset('storage/'.$loan->buku->cover) : 'https://via.placeholder.com/120x180' }}" 
                             alt="{{ $loan->buku->judul }}"
                             class="w-24 h-36 object-cover rounded shadow-md">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $loan->buku->judul }}</h3>
                            <p class="text-sm text-gray-600">{{ $loan->buku->penulis }}</p>
                            
                            <div class="mt-3 space-y-1 text-sm">
                                <p class="text-gray-700">
                                    <span class="font-medium">Dipinjam:</span> 
                                    {{ $loan->tanggal_peminjaman->format('d M Y') }}
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Kembali:</span> 
                                    {{ $loan->tanggal_pengembalian->format('d M Y') }}
                                </p>
                                
                                @if($loan->isOverdue())
                                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        ⚠️ Terlambat {{ $loan->tanggal_pengembalian->diffInDays(now()) }} hari
                                    </div>
                                @else
                                    <p class="text-sm text-green-600">
                                        Sisa {{ now()->diffInDays($loan->tanggal_pengembalian) }} hari
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col justify-center gap-2">
                            @if($loan->isEbook())
                                <a href="{{ route('ebook.read', $loan->id) }}" 
                                   class="px-4 py-2 bg-primary-blue text-white rounded-lg hover:bg-primary-blue/80 transition text-center text-sm font-medium">
                                    📖 Baca
                                </a>
                                <form action="{{ route('peminjaman.return-ebook', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-medium"
                                            onclick="return confirm('Yakin ingin mengembalikan e-book ini?')">
                                        ↩️ Kembalikan
                                    </button>
                                </form>
                            @else
                                <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg text-center text-sm font-medium">
                                    📚 Buku Fisik
                                </span>
                                <p class="text-xs text-gray-500 text-center">Kembalikan ke perpustakaan</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500">Tidak ada peminjaman aktif</p>
                    </div>
                @endforelse
            </div>

            <div x-show="tab === 'pending'" class="p-6">
                @php
                    $pending = $peminjaman->where('status_peminjaman', 'pending');
                @endphp
                
                @forelse($pending as $loan)
                    <div class="flex gap-6 p-4 bg-yellow-50 rounded-lg mb-4">
                        <img src="{{ $loan->buku->cover ? asset('storage/'.$loan->buku->cover) : 'https://via.placeholder.com/120x180' }}" 
                             alt="{{ $loan->buku->judul }}"
                             class="w-24 h-36 object-cover rounded shadow-md">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $loan->buku->judul }}</h3>
                            <p class="text-sm text-gray-600">{{ $loan->buku->penulis }}</p>
                            
                            <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                                ⏳ Menunggu persetujuan admin
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-2">
                                Diajukan: {{ $loan->tanggal_peminjaman->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500">Tidak ada pengajuan yang menunggu approval</p>
                    </div>
                @endforelse
            </div>

            <div x-show="tab === 'selesai'" class="p-6">
                @php
                    $selesai = $peminjaman->whereIn('status_peminjaman', ['dikembalikan', 'selesai', 'rejected']);
                @endphp
                
                @forelse($selesai as $loan)
                    <div class="flex gap-6 p-4 bg-gray-50 rounded-lg mb-4">
                        <img src="{{ $loan->buku->cover ? asset('storage/'.$loan->buku->cover) : 'https://via.placeholder.com/120x180' }}" 
                             alt="{{ $loan->buku->judul }}"
                             class="w-24 h-36 object-cover rounded shadow-md opacity-75">
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-700">{{ $loan->buku->judul }}</h3>
                            <p class="text-sm text-gray-500">{{ $loan->buku->penulis }}</p>
                            
                            <div class="mt-3 space-y-1 text-sm">
                                <p class="text-gray-600">
                                    Dipinjam: {{ $loan->tanggal_peminjaman->format('d M Y') }} - 
                                    @if($loan->tanggal_kembali_actual)
                                        Dikembalikan: {{ $loan->tanggal_kembali_actual->format('d M Y') }}
                                    @endif
                                </p>
                                
                                @if($loan->status_peminjaman === 'rejected')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        ❌ Ditolak
                                    </span>
                                @elseif($loan->denda > 0)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                     {{ $loan->denda_lunas ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $loan->denda_lunas ? '✅ Denda Lunas' : '⚠️ Denda Belum Lunas' }}
                                        </span>
                                        <p class="text-sm text-gray-700 mt-1">
                                            Denda: Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                        </p>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-500">Belum ada riwayat peminjaman selesai</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
