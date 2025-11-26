<?php

namespace App\Http\Controllers\book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Support\Facades\Storage;
use App\Models\Kategori;

class BookController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string',
            'penerbit' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'isbn' => 'required|string',
            'stok' => 'required|integer|min:1',
            'jumlah_halaman' => 'required|integer|min:1',
            'deskripsi' => 'required',
            'status_buku' => 'required|string',
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'isi_buku' => 'required|mimes:pdf|max:20000',
        ]);

        $coverPath = $request->file('cover')->store('uploads/cover', 'public');
        $pdfPath = $request->file('isi_buku')->store('uploads/buku', 'public');

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
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'isbn' => 'required|string|unique:bukus,isbn,' . $buku->id,
            'stok' => 'required|integer|min:1',
            'jumlah_halaman' => 'required|integer',
            'deskripsi' => 'required|string',

            // optional file
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'isi_buku' => 'nullable|file|mimes:pdf|max:10000',

            'status_buku' => 'required|string'
        ]);

        // replace file if uploaded
        if ($request->hasFile('cover')) {
            Storage::delete($buku->cover);
            $validated['cover'] = $request->file('cover')->store('covers');
        }

        if ($request->hasFile('isi_buku')) {
            Storage::delete($buku->isi_buku);
            $validated['isi_buku'] = $request->file('isi_buku')->store('files');
        }

        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui');
    }

    public function destroy(Buku $buku)
    {
        Storage::delete([$buku->cover, $buku->isi_buku]);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }

    public function index() {
        
    }



}
