<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanController extends Controller
{

    private $MAX_HARI = 14;
    private $TARIF_DENDA_PER_HARI = 1000;
    public function pinjam(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pengembalian' => 'required|date|after:today'
        ]);
        $buku = Buku::findOrFail($request->buku_id);

        $hari = Carbon::now()->diffInDays(Carbon::parse($request->tanggal_pengembalian));
        if ($hari > $this->MAX_HARI) {
            return back()->withErrors([
                'tanggal_pengembalian' => "maksimal peminjaman adalah {$this->MAX_HARI} hari."
            ]);
        }

        $hasEbook = !empty($buku->isi_buku);

        if ($hasEbook) {
            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id,
                'tanggal_peminjaman' => Carbon::now(),
                'tanggal_pengembalian' => Carbon::parse($request->tanggal_pengembalian),
                'status_peminjaman' => 'dipinjam',
                'denda' => 0,
                'denda_lunas' => true
            ]);
        } else {
            if ($buku->stok < 1) {
                return back()->withErrors(['stok' => 'Stok buku fisik habis.']);
            }

            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id,
                'tanggal_peminjaman' => Carbon::now(),
                'tanggal_pengembalian' => Carbon::parse($request->tanggal_pengembalian),
                'status_peminjaman' => 'pending', // menunggu approval
                'denda' => 0,
                'denda_lunas' => true
            ]);

            return redirect()->back()->with('success', 'Request peminjaman menunggu persetujuan admin.');
            // nanti returnnya ganti ke redirect ke halaman user pemekndskjbjfklvbu;hkjwbefjkldbiopfuohjksfvdbdvilohfksbdnhfhkxbnm cbjkshvfx
        }
    }

 public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($peminjaman->buku_id);

        if ($peminjaman->status_peminjaman !== 'pending') {
            return back()->withErrors(['status' => 'Peminjaman sudah diproses.']);
        }

        if ($buku->stok < 1) {
            return back()->withErrors(['stok' => 'Stok buku habis.']);
        }

        // Kurangi stok buku
        $buku->decrement('stok');

        // Update status peminjaman
        $peminjaman->update([
            'status_peminjaman' => 'dipinjam'
        ]);

        return back()->with('success', 'Peminjaman telah disetujui.');
    }



    public function reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status_peminjaman !== 'pending') {
            return back()->withErrors(['status' => 'Peminjaman sudah diproses.']);
        }

        $peminjaman->update([
            'status_peminjaman' => 'rejected'
        ]);

        return back()->with('success', 'Peminjaman telah ditolak.');
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($peminjaman->buku_id);

        if ($peminjaman->status_peminjaman !== 'dipinjam') {
            return back()->withErrors(['status' => 'Buku tidak sedang dipinjam.']);
        }

        // Hitung denda jika terlambat
        $tanggalKembali = Carbon::now();
        $denda = 0;
        $dendaLunas = true;

        if ($tanggalKembali->gt($peminjaman->tanggal_pengembalian)) {
            $hariTerlambat = $peminjaman->tanggal_pengembalian->diffInDays($tanggalKembali);
            $denda = $hariTerlambat * $this->TARIF_DENDA_PER_HARI;
            $dendaLunas = false;
        }

        $peminjaman->update([
            'status_peminjaman' => 'dikembalikan',
            'tanggal_kembali_actual' => $tanggalKembali,
            'denda' => $denda,
            'denda_lunas' => $dendaLunas
        ]);

        if (!$buku->hasEbook()) {
            $buku->increment('stok');
        }

        if ($denda > 0) {
            return back()->with('warning', "Buku dikembalikan dengan denda Rp " . number_format($denda, 0, ',', '.') . " (terlambat {$hariTerlambat} hari)");
        }
        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    public function konfirmasiDenda($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda_lunas) {
            return back()->withErrors(['denda' => 'Denda sudah lunas.']);
        }

        $peminjaman->update([
            'denda_lunas' => true,
            'status_peminjaman' => 'selesai'
        ]);

        return back()->with('success', 'Pembayaran denda telah dikonfirmasi.');
    }

        public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('peminjaman'));
    }


    public function userPeminjaman()
    {
        $peminjaman = Peminjaman::where('user_id', auth()->id())
            ->with('buku')
            ->latest()
            ->get();

        return view('member.peminjaman', compact('peminjaman'));
    }
}

