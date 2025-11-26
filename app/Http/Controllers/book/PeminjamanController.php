<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use function Laravel\Prompts\error;

class PeminjamanController extends Controller
{
    //
    private $MAX_HARI = 14;
    public function Pinjam(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pengembalian' => 'required|date|after:today'
        ]);

        $hari = Carbon::now()->diffInDays(Carbon::parse($request->tanggal_pengembalian));

        if ($hari > $this->MAX_HARI) {
            return back()->withErrors([
                'tanggal_pengembalian' => "maksimal peminjaman adalah {$this->MAX_HARI} hari."
            ]);
        }

        Peminjaman::create([
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::parse($request->tanggal_pengembalian),
            'status_peminjaman' => 'dipinjam',
        ]);

        return redirect()->back()->with('success', 'Request peminjaman menunggu persetujuan admin.');
        // nanti returnnya ganti ke redirect ke halaman user pemekndskjbjfklvbu;hkjwbefjkldbiopfuohjksfvdbdvilohfksbdnhfhkxbnm cbjkshvfx
    }

    public function Approve($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = Buku::findOrFail($pinjam->buku_id);

        if ($buku->stok < 1) {
            return back()->withErrors(['stok' => 'Stok buku habis.']);
        }

        $buku->stok -= 1;
        $buku->save();

        $pinjam->status_pengembalian = 'dipinjam';
        $pinjam->save();

        return back()->with('success', 'Peminjaman telah disetujui.');
    }

    
}

