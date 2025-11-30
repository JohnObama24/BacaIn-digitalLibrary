<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanController extends Controller
{

    // Configuration loaded from config/borrowing.php
    public function pinjam(Request $request)
    {
        $maxDays = config('borrowing.max_borrowing_days');

        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pengembalian' => 'required|date|after:today'
        ]);

        $buku = Buku::findOrFail($request->buku_id);
        $hari = Carbon::now()->diffInDays(Carbon::parse($request->tanggal_pengembalian));

        if ($hari > $maxDays) {
            return back()->withErrors([
                'tanggal_pengembalian' => "Maksimal peminjaman adalah {$maxDays} hari."
            ]);
        }

        $hasEbook = !empty($buku->isi_buku);

        if ($hasEbook) {
            // E-book: Auto-approved, immediately available
            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id,
                'tanggal_peminjaman' => Carbon::now(),
                'tanggal_pengembalian' => Carbon::parse($request->tanggal_pengembalian),
                'status_peminjaman' => 'dipinjam',
                'denda' => 0,
                'denda_lunas' => true
            ]);

            return redirect()->route('user.peminjaman')->with('success', 'E-book berhasil dipinjam! Anda dapat langsung membacanya.');
        } else {
            // Physical book: Requires admin approval
            if ($buku->stok < 1) {
                return back()->withErrors(['stok' => 'Stok buku fisik habis.']);
            }

            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id,
                'tanggal_peminjaman' => Carbon::now(),
                'tanggal_pengembalian' => Carbon::parse($request->tanggal_pengembalian),
                'status_peminjaman' => 'pending',
                'denda' => 0,
                'denda_lunas' => true
            ]);

            return redirect()->route('user.peminjaman')->with('success', 'Request peminjaman buku fisik menunggu persetujuan admin.');
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

    /**
     * Admin confirms physical book return
     */
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($peminjaman->buku_id);

        if ($peminjaman->status_peminjaman !== 'dipinjam') {
            return back()->withErrors(['status' => 'Buku tidak sedang dipinjam.']);
        }

        // Only for physical books
        if ($peminjaman->isEbook()) {
            return back()->withErrors(['status' => 'E-book dikembalikan otomatis oleh sistem.']);
        }

        // Calculate fine if late
        $tanggalKembali = Carbon::now();
        $denda = 0;
        $dendaLunas = true;
        $fineRate = config('borrowing.fine_per_day');

        if ($tanggalKembali->gt($peminjaman->tanggal_pengembalian)) {
            $hariTerlambat = $peminjaman->tanggal_pengembalian->diffInDays($tanggalKembali);
            $denda = $hariTerlambat * $fineRate;
            $dendaLunas = false;
        }

        $peminjaman->update([
            'status_peminjaman' => 'dikembalikan',
            'tanggal_kembali_actual' => $tanggalKembali,
            'denda' => $denda,
            'denda_lunas' => $dendaLunas
        ]);

        // Return physical book to stock
        $buku->increment('stok');

        if ($denda > 0) {
            return back()->with('warning', "Buku dikembalikan dengan denda Rp " . number_format($denda, 0, ',', '.') . " (terlambat {$hariTerlambat} hari)");
        }
        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * User returns e-book manually
     */
    public function returnEbook($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Check ownership
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$peminjaman->canBeReturned()) {
            return back()->withErrors(['status' => 'Buku tidak dapat dikembalikan.']);
        }

        if (!$peminjaman->isEbook()) {
            return back()->withErrors(['status' => 'Hanya e-book yang dapat dikembalikan sendiri.']);
        }

        $peminjaman->update([
            'status_peminjaman' => 'dikembalikan',
            'tanggal_kembali_actual' => Carbon::now(),
        ]);

        return redirect()->route('user.peminjaman')->with('success', 'E-book berhasil dikembalikan.');
    }

    /**
     * Display e-book reader
     */
    public function readEbook($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        // Check ownership
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if still active and is e-book
        if ($peminjaman->status_peminjaman !== 'dipinjam' || !$peminjaman->isEbook()) {
            return redirect()->route('user.peminjaman')->withErrors(['status' => 'E-book tidak tersedia untuk dibaca.']);
        }

        // Auto-return if overdue
        if ($peminjaman->isOverdue() && config('borrowing.auto_return_ebooks')) {
            $peminjaman->update([
                'status_peminjaman' => 'dikembalikan',
                'tanggal_kembali_actual' => Carbon::now(),
            ]);
            return redirect()->route('user.peminjaman')->with('warning', 'E-book telah melewati batas waktu peminjaman dan dikembalikan otomatis.');
        }

        return view('member.ebook.read', compact('peminjaman'));
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

