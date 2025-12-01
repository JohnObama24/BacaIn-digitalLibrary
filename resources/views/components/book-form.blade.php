@props(['categories', 'book' => null])

@php
    $isEdit = !is_null($book);
    $actionUrl = $isEdit ? route('buku.update', $book->id) : route('buku.store');
@endphp

<form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="border-b pb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Informasi Dasar
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Buku *</label>
                <input type="text" name="judul" value="{{ old('judul', $book->judul ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Penulis *</label>
                <input type="text" name="penulis" value="{{ old('penulis', $book->penulis ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Penerbit *</label>
                <input type="text" name="penerbit" value="{{ old('penerbit', $book->penerbit ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ISBN *</label>
                <input type="text" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit ?? '') }}"
                    required min="1900" max="{{ date('Y') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="kategori_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('kategori_id', $book->kategori_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Halaman *</label>
                <input type="number" name="jumlah_halaman"
                    value="{{ old('jumlah_halaman', $book->jumlah_halaman ?? '') }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
    </div>

    <div class="border-b pb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Stok & Status
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                <input type="number" name="stok" value="{{ old('stok', $book->stok ?? 1) }}" required min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Jumlah buku fisik yang tersedia</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Buku *</label>
                <select name="status_buku" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="tersedia" {{ old('status_buku', $book->status_buku ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="tidak tersedia" {{ old('status_buku', $book->status_buku ?? '') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
        </div>
    </div>

    <div class="border-b pb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            Deskripsi
        </h3>

        <textarea name="deskripsi" rows="4" required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Tulis sinopsis atau deskripsi buku...">{{ old('deskripsi', $book->deskripsi ?? '') }}</textarea>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Upload File
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                @if($isEdit && $book->cover)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="Current Cover"
                            class="w-32 h-44 object-cover rounded-lg shadow-md">
                        <p class="text-xs text-gray-500 mt-1">Cover saat ini</p>
                    </div>
                @endif
                <input type="file" name="cover" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Max:
                    2MB{{ $isEdit ? '. Kosongkan jika tidak ingin mengubah.' : '' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">E-Book (PDF)</label>
                @if($isEdit && $book->isi_buku)
                    <div class="mb-3 flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">PDF tersedia</p>
                            <p class="text-xs text-gray-500">{{ basename($book->isi_buku) }}</p>
                        </div>
                    </div>
                @endif
                <input type="file" name="isi_buku" accept=".pdf"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-gray-500">Format: PDF. Max:
                    20MB{{ $isEdit ? '. Kosongkan jika tidak ingin mengubah.' : '' }}</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end space-x-4 pt-6 border-t">
        <button type="button" @click="$dispatch('close-modal')"
            class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
            Batal
        </button>
        <button type="submit"
            class="px-6 py-2.5 bg-primatext-primary-blue text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-lg hover:shadow-xl">
            💾 {{ $isEdit ? 'Update' : 'Simpan' }} Buku
        </button>
    </div>
</form>