<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori_id',
        'isbn',
        'stok',
        'jumlah_halaman',
        'deskripsi',
        'cover',
        'isi_buku',
        'status_buku',
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // Helper: Cek apakah buku punya e-book
    public function hasEbook()
    {
        return !empty($this->isi_buku);
    }

    // Helper: Cek apakah buku tersedia (stok > 0 atau ada ebook)
    public function isAvailable()
    {
        return $this->hasEbook() || $this->stok > 0;
    }

    // Helper: Get tipe buku
    public function getTipeBuku()
    {
        if ($this->hasEbook() && $this->stok > 0) {
            return 'hybrid'; // ada fisik dan digital
        } elseif ($this->hasEbook()) {
            return 'digital'; // hanya digital
        } else {
            return 'fisik'; // hanya fisik
        }
    }

    // Accessor untuk URL cover
    public function getCoverUrlAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : asset('images/default-book-cover.png');
    }

    // Accessor untuk URL isi buku (PDF)
    public function getIsiBukuUrlAttribute()
    {
        return $this->isi_buku ? asset('storage/' . $this->isi_buku) : null;
    }
}
