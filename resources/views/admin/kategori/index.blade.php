@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('header', 'Kelola Kategori')
@section('subheader', 'Daftar semua kategori buku')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div class="bg-white px-6 py-3 rounded-lg shadow-md">
                <p class="text-sm text-gray-500">Total Kategori</p>
                <p class="text-3xl font-bold text-primary-blue">{{ $categories->count() }}</p>
            </div>
            <button type="button" @click="$dispatch('open-modal', 'create-category')"
                class="px-6 py-2.5 bg-primary-blue text-white rounded-lg hover:bg-indigo-700 transition flex items-center shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Kategori
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div
                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-primary-blue">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-12 h-12 bg-primary-blue rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $category->nama_kategori }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $category->bukus->count() }} buku
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if($category->bukus->count() > 0)
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-2">Buku terbaru:</p>
                                <div class="flex -space-x-2">
                                    @foreach($category->bukus->take(3) as $book)
                                        <img src="{{ $book->cover ? asset('storage/' . $book->cover) : 'https://via.placeholder.com/40x60/4F46E5/FFFFFF?text=?' }}"
                                            alt="{{ $book->judul }}" title="{{ $book->judul }}"
                                            class="w-10 h-14 object-cover rounded shadow-md border-2 border-white">
                                    @endforeach
                                    @if($category->bukus->count() > 3)
                                        <div
                                            class="w-10 h-14 bg-gray-200 rounded shadow-md border-2 border-white flex items-center justify-center">
                                            <span class="text-xs font-bold text-primary-blue">+{{ $category->bukus->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center space-x-2 pt-4 border-t">
                            <button type="button" @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')"
                                class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-primary-blue transition text-sm font-medium text-center">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('kategori.destroy', $category->id) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->nama_kategori }}? Buku dengan kategori ini akan tetap ada.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-primary-blue transition text-sm font-medium">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="text-gray-500 text-lg mb-4">Belum ada kategori</p>
                        <button type="button" @click="$dispatch('open-modal', 'create-category')"
                            class="inline-block text-primary-blue hover:text-indigo-700 font-medium">
                            Tambah kategori pertama →
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        @if($categories->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-primary-blue">
                    <h3 class="text-lg font-semibold text-white">Statistik Kategori</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                                Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Persentase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress
                                Bar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $totalBooks = $categories->sum(fn($c) => $c->bukus->count());
                        @endphp
                        @foreach($categories->sortByDesc(fn($c) => $c->bukus->count()) as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $category->nama_kategori }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $category->bukus->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $totalBooks > 0 ? number_format(($category->bukus->count() / $totalBooks) * 100, 1) : 0 }}%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-primary-blue h-2.5 rounded-full"
                                            style="width: {{ $totalBooks > 0 ? ($category->bukus->count() / $totalBooks) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Create Category Modal -->
    <x-modal name="create-category" title="Tambah Kategori Baru" max-width="lg">
        <x-category-form />
    </x-modal>

    <!-- Edit Category Modals -->
    @foreach($categories as $category)
        <x-modal name="edit-category-{{ $category->id }}" title="Edit Kategori: {{ $category->nama_kategori }}" max-width="lg">
            <x-category-form :category="$category" />
        </x-modal>
    @endforeach
@endsection