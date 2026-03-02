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

    /**
     * Generate Sertifikat PDF (Landscape A4)
     */
    public function exportSertifikat()
    {
        $data = [
            'nama' => auth()->user()->name,
            'tanggal' => date('d F Y'),
            'nomor_sertifikat' => 'CERT/' . date('Y') . '/' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        ];
        
        $pdf = Pdf::loadView('pdf.sertifikat', $data)
                  ->setPaper('a4', 'landscape'); // Set ke landscape
        
        return $pdf->download('sertifikat-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Generate Undangan PDF (Portrait A4 dengan Header)
     */
    public function exportUndangan()
    {
        $data = [
            'penerima' => auth()->user()->name,
            'tanggal_acara' => date('d F Y', strtotime('+7 days')),
            'waktu' => '09:00 - 12:00 WIB',
            'tempat' => 'Aula Fakultas Ilmu Komputer',
            'nomor_surat' => 'UND/FIK/' . date('Y') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
            'tanggal_surat' => date('d F Y'),
        ];
        
        $pdf = Pdf::loadView('pdf.undangan', $data)
                  ->setPaper('a4', 'portrait'); // Set ke portrait
        
        return $pdf->download('undangan-' . date('Y-m-d') . '.pdf');
    }
}
