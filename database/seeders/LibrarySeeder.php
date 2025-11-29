<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Buku;
use App\Models\User;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        // Buat kategori
        $kategoriFiksi = Kategori::create(['nama_kategori' => 'Fiksi']);
        $kategoriNonFiksi = Kategori::create(['nama_kategori' => 'Non-Fiksi']);
        $kategoriTeknologi = Kategori::create(['nama_kategori' => 'Teknologi']);
        $kategoriSejarah = Kategori::create(['nama_kategori' => 'Sejarah']);

        // Buat buku dengan e-book (digital)
        Buku::create([
            'judul' => 'Belajar Laravel untuk Pemula',
            'penulis' => 'John Doe',
            'penerbit' => 'Tech Publisher',
            'tahun_terbit' => 2024,
            'kategori_id' => $kategoriTeknologi->id,
            'isbn' => '978-1234567890',
            'stok' => 0, // tidak ada stok fisik
            'jumlah_halaman' => 350,
            'deskripsi' => 'Panduan lengkap belajar Laravel dari dasar hingga mahir',
            'cover' => null,
            'isi_buku' => 'uploads/buku/sample.pdf', // ada e-book
            'status_buku' => 'tersedia',
        ]);

        // Buku fisik saja (tanpa e-book)
        Buku::create([
            'judul' => 'Sejarah Indonesia Modern',
            'penulis' => 'Soekarno',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2020,
            'kategori_id' => $kategoriSejarah->id,
            'isbn' => '978-0987654321',
            'stok' => 5, // ada stok fisik
            'jumlah_halaman' => 420,
            'deskripsi' => 'Perjalanan sejarah Indonesia dari masa ke masa',
            'cover' => null,
            'isi_buku' => null, // tidak ada e-book
            'status_buku' => 'tersedia',
        ]);

        // Buku hybrid (ada fisik dan digital)
        Buku::create([
            'judul' => 'Harry Potter dan Batu Bertuah',
            'penulis' => 'J.K. Rowling',
            'penerbit' => 'Gramedia Pustaka Utama',
            'tahun_terbit' => 2015,
            'kategori_id' => $kategoriFiksi->id,
            'isbn' => '978-1122334455',
            'stok' => 3, // ada stok fisik
            'jumlah_halaman' => 320,
            'deskripsi' => 'Petualangan Harry Potter di Hogwarts',
            'cover' => null,
            'isi_buku' => 'uploads/buku/harry-potter.pdf', // ada e-book juga
            'status_buku' => 'tersedia',
        ]);

        // Buat user untuk testing
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@library.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Member User',
            'email' => 'member@library.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->command->info('Library seeder completed!');
    }
}
