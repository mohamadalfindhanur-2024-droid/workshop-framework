<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private string $apiBase = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    public function ajax()
    {
        $provinsi = $this->getProvinsiData();
        return view('wilayah.ajax', compact('provinsi'));
    }

    public function axios()
    {
        $provinsi = $this->getProvinsiData();
        return view('wilayah.axios', compact('provinsi'));
    }

    public function getProvinsi()
    {
        $data = $this->getProvinsiData();
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data provinsi berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKota(Request $request)
    {
        $data = $this->getKotaData((string) $request->id_provinsi);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kota berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $data = $this->getKecamatanData((string) $request->id_kota);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kecamatan berhasil diambil',
            'data'    => $data,
        ]);
    }

    public function getKelurahan(Request $request)
    {
        $data = $this->getKelurahanData((string) $request->id_kecamatan);
        return response()->json([
            'code'    => 200,
            'status'  => 'success',
            'message' => 'Data kelurahan berhasil diambil',
            'data'    => $data,
        ]);
    }

    private function getProvinsiData()
    {
        $apiData = $this->fetchApiList($this->apiBase . '/provinces.json', function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama')->values()->map(fn ($row) => (object) $row);
        }

        return Provinsi::orderBy('nama')->get(['id', 'nama']);
    }

    private function getKotaData(string $idProvinsi)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/regencies/' . $idProvinsi . '.json', function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama')->values()->map(fn ($row) => (object) $row);
        }

        return Kota::where('id_provinsi', $idProvinsi)
            ->orderBy('nama')
            ->get(['id', 'nama']);
    }

    private function getKecamatanData(string $idKota)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/districts/' . $idKota . '.json', function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama')->values()->map(fn ($row) => (object) $row);
        }

        return Kecamatan::where('id_kota', $idKota)
            ->orderBy('nama')
            ->get(['id', 'nama']);
    }

    private function getKelurahanData(string $idKecamatan)
    {
        $apiData = $this->fetchApiList($this->apiBase . '/villages/' . $idKecamatan . '.json', function ($item) {
            return [
                'id' => (string) ($item['id'] ?? ''),
                'nama' => (string) ($item['name'] ?? ''),
            ];
        });

        if ($apiData !== null) {
            return collect($apiData)->sortBy('nama')->values()->map(fn ($row) => (object) $row);
        }

        return Kelurahan::where('id_kecamatan', $idKecamatan)
            ->orderBy('nama')
            ->get(['id', 'nama']);
    }

    private function fetchApiList(string $url, callable $transformer): ?array
    {
        try {
            $response = Http::timeout(8)->acceptJson()->get($url);
            if (!$response->ok()) {
                return null;
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                return null;
            }

            $mapped = [];
            foreach ($payload as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $normalized = $transformer($item);
                if (!empty($normalized['id']) && !empty($normalized['nama'])) {
                    $mapped[] = $normalized;
                }
            }

            return $mapped;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
