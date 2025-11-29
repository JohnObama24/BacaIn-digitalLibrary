<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Buku::with('kategori')->latest()->get();
        $categories = Kategori::all();
        $popularBooks = Buku::with('kategori')
            ->orderBy('jumlah_halaman', 'desc')
            ->take(5)
            ->get();


        return view('member.index', compact('books', 'categories', 'popularBooks'));
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

        return back()->with('success', 'Buku berhasil ditambahkan');
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
}
