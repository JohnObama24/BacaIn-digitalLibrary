<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
class BookController extends Controller
{
    public function index()
    {
        $books = Buku::with('kategori')->latest()->get();
        $categories = Kategori::all();

        return view('admin.buku.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('admin.buku.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'isbn' => 'required|string|unique:bukus,isbn',
            'stok' => 'required|integer|min:1',
            'jumlah_halaman' => 'required|integer|min:1',
            'deskripsi' => 'required|string',
            'status_buku' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi_buku' => 'nullable|mimes:pdf|max:20000',
        ]);

        $coverPath = $request->file('cover')
            ? $request->file('cover')->store('uploads/cover', 'public')
            : null;

        $pdfPath = $request->file('isi_buku')
            ? $request->file('isi_buku')->store('uploads/buku', 'public')
            : null;

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori_id' => $request->kategori_id,
            'isbn' => $request->isbn,
            'stok' => $request->stok,
            'jumlah_halaman' => $request->jumlah_halaman,
            'deskripsi' => $request->deskripsi,
            'status_buku' => $request->status_buku,
            'cover' => $coverPath,
            'isi_buku' => $pdfPath,
        ]);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit(Buku $buku)
    {
        $categories = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'categories'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'isbn' => 'required|string|unique:bukus,isbn,' . $buku->id,
            'stok' => 'required|integer|min:1',
            'jumlah_halaman' => 'required|integer',
            'deskripsi' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi_buku' => 'nullable|file|mimes:pdf|max:20000',
            'status_buku' => 'required|string'
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = $request->file('cover')->store('uploads/cover', 'public');
        }

        if ($request->hasFile('isi_buku')) {
            if ($buku->isi_buku) {
                Storage::disk('public')->delete($buku->isi_buku);
            }
            $validated['isi_buku'] = $request->file('isi_buku')->store('uploads/buku', 'public');
        }

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }
        if ($buku->isi_buku) {
            Storage::disk('public')->delete($buku->isi_buku);
        }
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }

    public function showDetail($id)
    {
        $book = Buku::with('kategori')->findOrFail($id);

        return view('member.buku.detail', compact('book'));
    }

public function show($id)
{
    $book = Buku::with('kategori')->findOrFail($id);

    // Hitung data statistik
    $total_copy = $book->stok;
    $sedang_dipinjam = Peminjaman::where('buku_id', $id)
                        ->where('status', 'dipinjam')
                        ->count();
    $atrian = Peminjaman::where('buku_id', $id)
                        ->where('status', 'menunggu')
                        ->count();
    $telah_dibaca = Peminjaman::where('buku_id', $id)
                        ->whereNotNull('tanggal_pengembalian')
                        ->count();

    $tersedia_copy = $total_copy - $sedang_dipinjam;

    $stats = [
        'total_copy'      => $total_copy,
        'tersedia_copy'   => $tersedia_copy,
        'telah_dibaca'    => $telah_dibaca,
        'atrian'          => $atrian,
        'sedang_dipinjam' => $sedang_dipinjam,
    ];

    return view('member.book-detail', compact('book', 'stats'));
}



    public function readEbook($id)
    {
        $buku = Buku::findOrFail($id);

        if (!$buku->hasEbook()) {
            return redirect()->back()->with('error', 'Buku ini tidak memiliki versi e-book.');
        }

        $peminjaman = Peminjaman::where('user_id', auth()->id())
            ->where('buku_id', $id)
            ->where('status_peminjaman', 'dipinjam')
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Anda harus meminjam buku ini terlebih dahulu untuk membacanya.');
        }

        return view('member.read-ebook', compact('buku', 'peminjaman'));
    }

}
