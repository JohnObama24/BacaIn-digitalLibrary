<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    //
    protected $table = [
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

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
