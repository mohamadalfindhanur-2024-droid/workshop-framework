<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function ajax()
    {
        return view('kasir.ajax');
    }

    public function axios()
    {
        return view('kasir.axios');
    }

    public function cariBarang(Request $request)
    {
        $kode = $request->kode;
        $barang = Barang::where('id_barang', $kode)->first();

        if (!$barang) {
            return response()->json([
                'code'    => 404,
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan',
                'data'    => null,
            ]);
        }

        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Barang ditemukan',
            'data'    => [
                'id_barang' => $barang->id_barang,
                'nama'      => $barang->nama,
                'harga'     => $barang->harga,
            ],
        ]);
    }

    public function bayar(Request $request)
    {
        $request->validate([
            'items'   => 'required|array|min:1',
            'items.*.id_barang'  => 'required|string',
            'items.*.nama_barang'=> 'required|string',
            'items.*.harga'      => 'required|numeric|min:0',
            'items.*.jumlah'     => 'required|integer|min:1',
            'items.*.subtotal'   => 'required|numeric|min:0',
            'total'              => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'tanggal' => now()->toDateString(),
                'total'   => $request->total,
            ]);

            foreach ($request->items as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang'    => $item['id_barang'],
                    'nama_barang'  => $item['nama_barang'],
                    'harga'        => $item['harga'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'code'    => 200,
                'status'  => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'data'    => ['id_transaksi' => $transaksi->id],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code'    => 500,
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data'    => null,
            ], 500);
        }
    }
}
