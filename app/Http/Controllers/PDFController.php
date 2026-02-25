<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    /**
     * Export semua data buku ke PDF
     */
    public function exportBuku()
    {
        $bukus = Buku::with('kategori')->get();
        
        $pdf = Pdf::loadView('pdf.buku', compact('bukus'));
        
        return $pdf->download('laporan-buku-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export detail buku ke PDF
     */
    public function exportBukuDetail($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.buku-detail', compact('buku'));
        
        return $pdf->download('detail-buku-' . $buku->judul . '.pdf');
    }

    /**
     * Export semua data kategori ke PDF
     */
    public function exportKategori()
    {
        $kategoris = Kategori::withCount('buku')->get();
        
        $pdf = Pdf::loadView('pdf.kategori', compact('kategoris'));
        
        return $pdf->download('laporan-kategori-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export kategori dengan daftar buku ke PDF
     */
    public function exportKategoriDetail($id)
    {
        $kategori = Kategori::with('buku')->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.kategori-detail', compact('kategori'));
        
        return $pdf->download('kategori-' . $kategori->nama_kategori . '.pdf');
    }
}
