<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamen';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'tanggal_kembali_actual',
        'status_peminjaman',
        'denda',
        'denda_lunas',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Check if this is an e-book loan
     */
    public function isEbook()
    {
        return $this->buku && $this->buku->hasEbook();
    }

    /**
     * Check if this is a physical book loan
     */
    public function isPhysical()
    {
        return !$this->isEbook();
    }

    /**
     * Check if loan is overdue
     */
    public function isOverdue()
    {
        return now()->gt($this->tanggal_pengembalian) && $this->status_peminjaman === 'dipinjam';
    }

    /**
     * Check if book can be returned
     */
    public function canBeReturned()
    {
        return $this->status_peminjaman === 'dipinjam';
    }

    /**
     * Get casts for date fields
     */
    protected $casts = [
        'tanggal_peminjaman' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'tanggal_kembali_actual' => 'datetime',
        'denda_lunas' => 'boolean',
    ];
}
