<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')
                        ->with('success', 'Buku berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kode' => 'required|string|max:20',
            'judul' => 'required|string|max:500',
            'pengarang' => 'required|string|max:200',
            'idkategori' => 'required|exists:kategori,idkategori',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('buku.index')
                        ->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')
                        ->with('success', 'Buku berhasil dihapus');
    }
}
