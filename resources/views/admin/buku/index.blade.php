@extends('layouts.admin')

@section('title', 'Kelola Buku')
@section('header', 'Kelola Buku')
@section('subheader', 'Daftar semua buku di perpustakaan')

@section('page_title', 'Kelola Buku')
@section('content')
    <div class="space-y-6">
        <div class="md:flex md:justify-between gap-5 items-center">
            <div class="relative w-full">
                <input type="text" id="searchInput" placeholder="Cari buku..."
                    class="w-full pl-10 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="button" @click="$dispatch('open-modal', 'create-book')" class="md:w-96 px-6 py-4 bg-primary-blue text-white rounded-lg hover:bg-primary-blue/80 transition flex items-center shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Buku Baru
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full" id="booksTable">
                    <thead class="bg-secondary-gray text-black">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Cover</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Judul</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Penulis</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kategori</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">ISBN</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Stok</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tipe</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($books as $book)
                            <tr class="hover:bg-gray-50 transition book-row">
                                <td class="px-6 py-4">
                                    <img src="{{ $book->cover ? asset('storage/' . $book->cover) : 'https://via.placeholder.com/60x90/4F46E5/FFFFFF?text=No+Cover' }}"
                                        alt="{{ $book->judul }}" class="w-12 h-16 object-cover rounded shadow">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $book->judul }}</div>
                                    <div class="text-sm text-gray-500">{{ $book->penerbit }} ({{ $book->tahun_terbit }})</div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $book->penulis }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">
                                        {{ $book->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-sm font-mono">{{ $book->isbn }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-bold {{ $book->stok > 5 ? 'text-green-600' : ($book->stok > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $book->stok }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($book->getTipeBuku() == 'hybrid')
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">📚
                                            Hybrid</span>
                                    @elseif($book->getTipeBuku() == 'digital')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">💻 Digital</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">📖 Fisik</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $book->status_buku == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($book->status_buku) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button" @click="$dispatch('open-modal', 'edit-book-{{ $book->id }}')"
                                            class="px-3 py-1.5 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition text-sm font-medium">
                                            Edit
                                        </button>
                                        <form action="{{ route('buku.destroy', $book->id) }}" method="POST" class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-500 text-white rounded hover:bg-red-600 transition text-sm font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <p class="text-gray-500 text-lg">Belum ada buku</p>
                                        <button type="button" @click="$dispatch('open-modal', 'create-book')"
                                            class="mt-4 text-indigo-600 hover:text-indigo-700 font-medium">
                                            Tambah buku pertama →
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <x-modal name="create-book" title="Tambah Buku Baru" max-width="4xl">
            <x-book-form :categories="$categories" />
        </x-modal>

        @foreach($books as $book)
            <x-modal name="edit-book-{{ $book->id }}" title="Edit Buku: {{ $book->judul }}" max-width="4xl">
                <x-book-form :categories="$categories" :book="$book" />
            </x-modal>
        @endforeach

        <script>
            document.getElementById('searchInput').addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.book-row');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        </script>
@endsection