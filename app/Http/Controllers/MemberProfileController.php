<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Calculate stats
        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();
        $totalDenda = Peminjaman::where('user_id', $user->id)
            ->where('denda_lunas', false)
            ->sum('denda');
        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status_peminjaman', 'dipinjam')
            ->count();
        $bukuDikembalikan = Peminjaman::where('user_id', $user->id)
            ->where('status_peminjaman', 'dikembalikan')
            ->count();

        return view('member.profile.index', compact('user', 'totalPeminjaman', 'totalDenda', 'bukuDipinjam', 'bukuDikembalikan'));
    }

    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('buku')->latest()->paginate(12);

        return view('member.profile.favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $user = Auth::user();
        $bukuId = $request->buku_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('buku_id', $bukuId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'removed';
            $message = 'Buku dihapus dari favorit';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'buku_id' => $bukuId,
            ]);
            $status = 'added';
            $message = 'Buku ditambahkan ke favorit';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function history()
    {
        $user = Auth::user();
        $peminjaman = Peminjaman::where('user_id', $user->id)
            ->with('buku')
            ->latest()
            ->paginate(10);

        return view('member.profile.history', compact('peminjaman'));
    }
}
