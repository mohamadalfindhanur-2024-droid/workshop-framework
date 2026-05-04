<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->limit(10)->get();
        return view('customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'provinsi' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kodepos_kelurahan' => 'required|string|max:255',
            'foto_base64' => 'nullable|string',
            'qr_order_id' => 'nullable|string|max:255',
        ]);

        $fotoPath = null;
        if (!empty($request->foto_base64)) {
            $fotoPath = $this->saveBase64Image($request->foto_base64);
        }

        Customer::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'foto_path' => $fotoPath,
            'qr_order_id' => $request->qr_order_id,
        ]);

        return back()->with('success', 'Data customer berhasil disimpan.');
    }

    public function verifyOrderByQr(Request $request)
    {
        $request->validate([
            'qr_order_id' => 'required|string',
        ]);

        $digits = preg_replace('/\D+/', '', $request->qr_order_id);
        if (empty($digits)) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'QR tidak valid',
                'data' => null,
            ], 422);
        }

        $transaksi = Transaksi::with('detail')->find($digits);
        if (!$transaksi) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Order tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Order ditemukan',
            'data' => [
                'id' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status_order' => $transaksi->status_order,
                'status_bayar' => $transaksi->status_order === 'paid' ? 'LUNAS' : strtoupper($transaksi->status_order ?? 'pending'),
                'tanggal' => optional($transaksi->created_at)->format('d/m/Y H:i:s'),
                'total' => $transaksi->total,
                'items' => $transaksi->detail->map(function ($item) {
                    return [
                        'id_barang' => $item->id_barang,
                        'nama_barang' => $item->nama_barang,
                        'jumlah' => $item->jumlah,
                        'harga' => $item->harga,
                        'subtotal' => $item->subtotal,
                    ];
                })->values(),
            ],
        ]);
    }

    public function scan()
    {
        return view('customer.scan');
    }

    public function orderHistory()
    {
        $transaksi = Transaksi::with('detail')->latest()->paginate(10);
        return view('customer.order-history', compact('transaksi'));
    }

    private function saveBase64Image(string $base64): ?string
    {
        if (!str_contains($base64, 'base64,')) {
            return null;
        }

        [$meta, $content] = explode('base64,', $base64, 2);
        $binaryData = base64_decode($content);
        if ($binaryData === false) {
            return null;
        }

        $extension = str_contains($meta, 'image/png') ? 'png' : 'jpg';
        $filename = 'customer_' . now()->format('Ymd_His') . '_' . mt_rand(1000, 9999) . '.' . $extension;
        $path = 'customers/' . $filename;

        Storage::disk('public')->put($path, $binaryData);
        return $path;
    }
}
