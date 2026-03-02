<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        // Mengambil semua data barang dari database PostgreSQL kita
        $barang = Barang::all(); 
        
        // Melempar data tersebut ke file view bernama 'index' di dalam folder 'barang'
        return view('barang.index', compact('barang'));
    }
    
    /**
     * Cetak Label Harga untuk barang yang dipilih
     * Format: Label TnJ 108 (5 kolom x 8 baris = 40 label per sheet A4)
     */
    public function cetakLabel(Request $request)
    {
        // Validasi input
        $request->validate([
            'cetak' => 'required|array|min:1',
            'cetak.*' => 'required|string',
            'koordinat_x' => 'nullable|integer|min:1|max:5',
            'koordinat_y' => 'nullable|integer|min:1|max:8',
        ]);
        
        // Ambil data barang yang dipilih
        $idBarang = $request->cetak;
        $barangDipilih = Barang::whereIn('id_barang', $idBarang)->get();
        
        // Ambil koordinat awal (default 1,1 jika tidak diisi)
        $koordinatX = $request->koordinat_x ?? 1;
        $koordinatY = $request->koordinat_y ?? 1;
        
        // Generate PDF dengan ukuran A4 Portrait (kertas label TnJ 108)
        // 1 sheet A4 = 5 kolom x 8 baris = 40 label
        $pdf = Pdf::loadView('pdf.label-barang', compact('barangDipilih', 'koordinatX', 'koordinatY'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download('label-harga-' . date('Y-m-d-His') . '.pdf');
    }
}