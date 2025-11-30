@props(['category' => null])

@php
    $isEdit = !is_null($category);
    $actionUrl = $isEdit ? route('kategori.update', $category->id) : route('kategori.store');
@endphp

<form action="{{ $actionUrl }}" method="POST" class="space-y-6">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Nama Kategori *
        </label>
        <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}"
            required placeholder="Contoh: Fiksi, Non-Fiksi, Teknologi, dll."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg">
        @error('nama_kategori')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if($isEdit && $category->bukus->count() > 0)
        <!-- Category Info -->
        <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-indigo-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-indigo-800">Informasi Kategori</p>
                    <div class="mt-2 text-sm text-indigo-700">
                        <p><strong>Jumlah buku:</strong> {{ $category->bukus->count() }} buku</p>
                        <p class="mt-1 text-xs">Mengubah nama kategori tidak akan mempengaruhi buku yang sudah ada.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!$isEdit)
        <!-- Tips untuk Create -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
    @endif

    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-4 pt-6 border-t">
        <button type="button" @click="$dispatch('close-modal')"
            class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
            Batal
        </button>
        <button type="submit"
            class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-lg hover:shadow-xl">
            💾 {{ $isEdit ? 'Update' : 'Simpan' }} Kategori
        </button>
    </div>
</form>