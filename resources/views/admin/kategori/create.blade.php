@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('header', 'Tambah Kategori')
@section('subheader', 'Buat kategori buku baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('kategori.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori *
                </label>
                <input type="text" 
                       name="nama_kategori" 
                       value="{{ old('nama_kategori') }}" 
                       required
                       placeholder="Contoh: Fiksi, Non-Fiksi, Teknologi, dll."
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
                @error('nama_kategori')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Tips:</p>
                        <ul class="mt-1 text-sm text-blue-700 list-disc list-inside">
                            <li>Gunakan nama yang jelas dan mudah dipahami</li>
                            <li>Hindari nama yang terlalu panjang</li>
                            <li>Pastikan kategori belum ada sebelumnya</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('kategori.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-lg hover:shadow-xl">
                    💾 Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Preview -->
    <div class="mt-6 bg-linear-to-br from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
        <h4 class="font-semibold text-gray-800 mb-3">Kategori Populer:</h4>
        <div class="flex flex-wrap gap-2">
            @php
                $suggestions = ['Fiksi', 'Non-Fiksi', 'Teknologi', 'Bisnis', 'Sejarah', 'Biografi', 'Sains', 'Religi', 'Anak-anak', 'Komik', 'Novel', 'Ensiklopedia'];
            @endphp
            @foreach($suggestions as $suggestion)
                <span class="px-3 py-1.5 bg-white text-gray-700 rounded-full text-sm shadow-sm border border-gray-200 hover:shadow-md transition cursor-pointer"
                      onclick="document.querySelector('input[name=nama_kategori]').value='{{ $suggestion }}'">
                    {{ $suggestion }}
                </span>
            @endforeach
        </div>
        <p class="text-xs text-gray-600 mt-3">Klik salah satu untuk mengisi otomatis</p>
    </div>
</div>
@endsection
