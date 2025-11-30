@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')
@section('subheader', 'Perbarui informasi kategori')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori *
                </label>
                <input type="text" 
                       name="nama_kategori" 
                       value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                       required
                       placeholder="Contoh: Fiksi, Non-Fiksi, Teknologi, dll."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                @error('nama_kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Info -->
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-indigo-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-800">Informasi Kategori</p>
                        <div class="mt-2 text-sm text-indigo-700">
                            <p><strong>Jumlah buku:</strong> {{ $kategori->bukus->count() }} buku</p>
                            <p class="mt-1 text-xs">
                                @if($kategori->bukus->count() > 0)
                                    Mengubah nama kategori tidak akan mempengaruhi buku yang sudah ada.
                                @else
                                    Kategori ini belum memiliki buku.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books in this category -->
            @if($kategori->bukus->count() > 0)
            <div class="border rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3">Buku dalam kategori ini:</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($kategori->bukus as $book)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                        <img src="{{ $book->cover ? asset('storage/'.$book->cover) : 'https://via.placeholder.com/40x60/4F46E5/FFFFFF?text=?' }}" 
                             alt="{{ $book->judul }}" 
                             class="w-8 h-12 object-cover rounded shadow-sm">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $book->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $book->penulis }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('kategori.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-lg hover:shadow-xl">
                    💾 Update Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
