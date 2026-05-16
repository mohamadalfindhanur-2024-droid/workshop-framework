<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::orderBy('id', 'desc')->get();
        return view('kunjungan.index', compact('tokos'));
    }

    public function create()
    {
        return view('kunjungan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barcode' => 'required|unique:tokos,barcode',
            'nama' => 'nullable|string',
            'nama_toko' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'accuracy' => 'nullable|integer',
        ]);

        Toko::create([
            'barcode' => $data['barcode'],
            'nama' => $data['nama'] ?? $data['nama_toko'] ?? '-',
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'accuracy' => $data['accuracy'] ?? null,
        ]);

        return redirect()->route('kunjungan.index')->with('success', 'Data toko berhasil ditambahkan');
    }

    public function setPoint(Request $request, Toko $toko)
    {
        $data = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy' => 'required|integer',
        ]);

        $toko->update($data);
        return response()->json(['status' => 'success']);
    }

    public function verifyVisit(Request $request)
    {
        $req = $request->validate([
            'barcode' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'accuracy' => 'required|integer',
            'threshold' => 'nullable|integer',
        ]);

        $toko = Toko::where('barcode', $req['barcode'])->first();
        if (!$toko || !$toko->latitude || !$toko->longitude) {
            return response()->json(['status' => 'error', 'message' => 'Toko tidak ditemukan atau titik awal belum di-set'], 404);
        }

        $lat1 = (float) $toko->latitude;
        $lng1 = (float) $toko->longitude;
        $lat2 = (float) $req['lat'];
        $lng2 = (float) $req['lng'];

        $distance = $this->haversineDistance($lat1, $lng1, $lat2, $lng2);

        $threshold = isset($req['threshold']) ? (int)$req['threshold'] : 300; // default meters
        $effective = $threshold + (int)($toko->accuracy ?: 0) + (int)$req['accuracy'];

        $accepted = ($distance <= $effective);

        return response()->json([
            'status' => 'ok',
            'distance' => round($distance, 2),
            'effective_threshold' => $effective,
            'accepted' => $accepted,
        ]);
    }

    public function qrcode(Toko $toko)
    {
        return view('kunjungan.qrcode', compact('toko'));
    }

    public function scan()
    {
        return view('kunjungan.scan');
    }

    public function getToko($barcode)
    {
        $toko = Toko::where('barcode', $barcode)->first();
        if (!$toko) {
            return response()->json(null, 404);
        }

        return response()->json([
            'id' => $toko->id,
            'barcode' => $toko->barcode,
            'nama_toko' => $toko->nama,
            'latitude' => $toko->latitude,
            'longitude' => $toko->longitude,
            'accuracy' => $toko->accuracy,
        ]);
    }

    protected function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }
}
