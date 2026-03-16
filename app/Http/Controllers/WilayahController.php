<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function ajax()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('wilayah.ajax', compact('provinsi'));
    }

    public function axios()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('wilayah.axios', compact('provinsi'));
    }

    public function getProvinsi()
    {
        $data = Provinsi::orderBy('nama')->get(['id', 'nama']);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data provinsi berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKota(Request $request)
    {
        $data = Kota::where('id_provinsi', $request->id_provinsi)
                    ->orderBy('nama')
                    ->get(['id', 'nama']);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kota berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $data = Kecamatan::where('id_kota', $request->id_kota)
                         ->orderBy('nama')
                         ->get(['id', 'nama']);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kecamatan berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKelurahan(Request $request)
    {
        $data = Kelurahan::where('id_kecamatan', $request->id_kecamatan)
                         ->orderBy('nama')
                         ->get(['id', 'nama']);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kelurahan berhasil diambil',
            'data'    => $data,
        ]);
    }
}
