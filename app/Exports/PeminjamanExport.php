<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Peminjaman::with(['user', 'buku'])
            ->whereBetween('tanggal_peminjaman', [$this->startDate, $this->endDate]);

        if ($this->status !== 'all') {
            $query->where('status_peminjaman', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Peminjam',
            'Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali (Jadwal)',
            'Tanggal Kembali (Aktual)',
            'Status',
            'Denda',
            'Status Denda',
        ];
    }

    public function map($peminjaman): array
    {
        return [
            $peminjaman->id,
            $peminjaman->user->name,
            $peminjaman->buku->judul,
            $peminjaman->tanggal_peminjaman->format('Y-m-d'),
            $peminjaman->tanggal_pengembalian->format('Y-m-d'),
            $peminjaman->tanggal_kembali_actual ? $peminjaman->tanggal_kembali_actual->format('Y-m-d') : '-',
            ucfirst($peminjaman->status_peminjaman),
            $peminjaman->denda,
            $peminjaman->denda > 0 ? ($peminjaman->denda_lunas ? 'Lunas' : 'Belum Lunas') : '-',
        ];
    }
}
