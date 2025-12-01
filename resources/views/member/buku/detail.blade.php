@extends('layouts.member')

@section('content')
    <div class="px-4 md:px-24 py-10">

        <h2 class="text-lg font-semibold text-gray-700 mb-6">Detail Buku</h2>

        <div class="bg-white rounded-2xl shadow p-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div class="flex justify-center">
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}"
                        class="w-60 rounded-xl shadow">
                </div>

                <div class="md:col-span-2 flex flex-col justify-center">
                    <h1 class="text-3xl font-bold">{{ $book->judul }}</h1>

                    <p class="text-blue-600 font-semibold mt-1">
                        {{ $book->penulis }}
                    </p>

                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400 text-xl">
                            ★★★★★
                        </div>
                        <span class="ml-2 font-semibold text-gray-700">5.0</span>
                    </div>

                    <div class="mt-3 flex gap-2">
                        @if($book->hasEbook())
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                E-Book Tersedia
                            </span>
                        @endif
                        @if($book->stok > 0)
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                 Buku Fisik ({{ $book->stok }} tersedia)
                            </span>
                        @endif
                    </div>

                    @if($book->isAvailable())
                        <button onclick="openPinjamModal()"
                            class="bg-teal-500 hover:bg-teal-600 text-white px-7 py-2 rounded-full mt-4 w-fit font-semibold transition">
                            Pinjam
                        </button>
                    @else
                        <button disabled
                            class="bg-gray-400 text-white px-7 py-2 rounded-full mt-4 w-fit font-semibold cursor-not-allowed">
                            Tidak Tersedia
                        </button>
                    @endif
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 text-center bg-gray-50 rounded-xl p-6 mt-10">

                <div class="flex flex-col">
                    <span class="font-semibold">{{ $book->hasEbook() ? 'File Size' : 'Jumlah Halaman' }}</span>
                    <span class="text-gray-600">
                        {{ $book->hasEbook() ? '8.5 MB' : $book->jumlah_halaman . ' halaman' }}
                    </span>
                </div>

                <div>
                    <span class="font-semibold block">Total Copy</span>
                    <span class="text-gray-600">{{ $stats['total_copy'] }}</span>
                </div>

                <div>
                    <span class="font-semibold block">Tersedia Copy</span>
                    <span class="text-gray-600">{{ $stats['tersedia_copy'] }}</span>
                </div>

            </div>

            <div class="grid grid-cols-3 mt-8 text-center">

                <div class="flex flex-col items-center">
                    <div class="bg-blue-100 text-blue-500 p-3 rounded-full mb-2">
                        <i class="fas fa-eye text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-700">Telah Dibaca Oleh</p>
                    <p class="text-xs text-gray-500">{{ $stats['telah_dibaca'] }} Pengguna</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-blue-100 text-blue-500 p-3 rounded-full mb-2">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-700">Atrian</p>
                    <p class="text-xs text-gray-500">{{ $stats['atrian'] }} Pengguna</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-blue-100 text-blue-500 p-3 rounded-full mb-2">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <p class="text-sm text-gray-700">Sedang Dipinjam Oleh</p>
                    <p class="text-xs text-gray-500">{{ $stats['sedang_dipinjam'] }} Pengguna</p>
                </div>

            </div>

            <div class="mt-10 border-b">
                <ul class="flex gap-6 text-sm font-semibold">
                    <li class="tab-btn py-2 border-b-2 border-teal-500 text-teal-600 cursor-pointer" data-tab="deskripsi">
                        Deskripsi
                    </li>
                    <li class="tab-btn py-2 text-gray-600 cursor-pointer hover:text-black" data-tab="detail">
                        Detail
                    </li>
                    <li class="tab-btn py-2 text-gray-600 cursor-pointer hover:text-black" data-tab="ulasan">
                        Ulasan
                    </li>
                </ul>
            </div>

            <div class="mt-6">
                <div id="tab-deskripsi" class="tab-content">
                    <div class="text-gray-700 leading-relaxed text-sm">
                        <div id="deskripsi-short">
                            <p>{{ Str::limit($book->deskripsi, 300) }}</p>

                            @if(strlen($book->deskripsi) > 300)
                                <button onclick="toggleDeskripsi()" class="text-teal-600 font-semibold mt-3">
                                    Baca Selengkapnya
                                </button>
                            @endif
                        </div>

                        <div id="deskripsi-full" class="hidden">
                            <p>{{ $book->deskripsi }}</p>
                            <button onclick="toggleDeskripsi()" class="text-teal-600 font-semibold mt-3">
                                Baca Lebih Sedikit
                            </button>
                        </div>
                    </div>
                </div>

                <div id="tab-detail" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Penerbit</p>
                            <p class="font-semibold text-gray-800">{{ $book->penerbit }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tahun Terbit</p>
                            <p class="font-semibold text-gray-800">{{ $book->tahun_terbit }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">ISBN</p>
                            <p class="font-semibold text-gray-800">{{ $book->isbn }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Jumlah Halaman</p>
                            <p class="font-semibold text-gray-800">{{ $book->jumlah_halaman }} halaman</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Kategori</p>
                            <p class="font-semibold text-gray-800">{{ $book->kategori->nama_kategori }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Status Buku</p>
                            <p class="font-semibold text-gray-800">{{ ucfirst($book->status_buku) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Stok</p>
                            <p class="font-semibold text-gray-800">{{ $book->stok }} unit</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">Tipe Buku</p>
                            <p class="font-semibold text-gray-800">{{ ucfirst($book->getTipeBuku()) }}</p>
                        </div>
                    </div>
                </div>

                <div id="tab-ulasan" class="tab-content hidden">
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-5xl mb-4">💬</div>
                        <p class="text-gray-500">Belum ada ulasan untuk buku ini</p>
                        <button
                            class="mt-4 bg-teal-500 hover:bg-teal-600 text-white px-6 py-2 rounded-full text-sm font-semibold">
                            Tulis Ulasan Pertama
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div id="pinjamModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            <div class="bg-primary-blue px-6 py-4 rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Pinjam Buku</h3>
            </div>

            <form action="{{ route('pinjam') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="buku_id" value="{{ $book->id }}">

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Buku yang dipinjam:</p>
                    <p class="font-semibold text-gray-800">{{ $book->judul }}</p>
                </div>

                @if($book->hasEbook())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <span class="text-primary-blue text-xl mr-2">✓</span>
                            <div>
                                <p class="text-sm font-medium text-primary-blue">E-Book Tersedia</p>
                                <p class="text-xs text-primary-blue mt-1">Peminjaman akan disetujui otomatis dan Anda dapat
                                    langsung membaca buku.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <span class="text-yellow-600 text-xl mr-2">⚠</span>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Buku Fisik</p>
                                <p class="text-xs text-yellow-700 mt-1">Peminjaman memerlukan persetujuan admin terlebih dahulu.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pengembalian
                    </label>
                    <input type="date" name="tanggal_pengembalian" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        max="{{ date('Y-m-d', strtotime('+14 days')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 14 hari dari hari ini</p>
                </div>

                @if(!$book->hasEbook())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        <p class="text-xs text-red-700">
                            ⚠️ <strong>Perhatian:</strong> Keterlambatan pengembalian akan dikenakan denda Rp 1.000 per hari.
                        </p>
                    </div>
                @endif

                <div class="flex gap-3">
                    <button type="button" onclick="closePinjamModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-medium">
                        Konfirmasi Pinjam
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', function () {
                const tabName = this.getAttribute('data-tab');

                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('border-teal-500', 'text-teal-600');
                    btn.classList.add('text-gray-600');
                });

                this.classList.add('border-teal-500', 'text-teal-600');
                this.classList.remove('text-gray-600');

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                document.getElementById('tab-' + tabName).classList.remove('hidden');
            });
        });

        function toggleDeskripsi() {
            const shortDesc = document.getElementById('deskripsi-short');
            const fullDesc = document.getElementById('deskripsi-full');

            if (shortDesc.classList.contains('hidden')) {
                shortDesc.classList.remove('hidden');
                fullDesc.classList.add('hidden');
            } else {
                shortDesc.classList.add('hidden');
                fullDesc.classList.remove('hidden');
            }
        }

        function openPinjamModal() {
            document.getElementById('pinjamModal').classList.remove('hidden');
        }

        function closePinjamModal() {
            document.getElementById('pinjamModal').classList.add('hidden');
        }

        document.getElementById('pinjamModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closePinjamModal();
            }
        });
    </script>
@endsection