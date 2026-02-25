<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')
                        ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.show', compact('kategori'));
    }

    public function edit(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')
                        ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')
                        ->with('success', 'Kategori berhasil dihapus');
    }
}
