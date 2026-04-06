<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('nama')->get(['id_barang', 'nama', 'harga']);
        return view('checkout.marketplace', compact('barang'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|string',
            'items.*.nama_barang' => 'required|string',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.subtotal' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:qris,va',
            'bank_va' => 'nullable|in:BCA,BNI,BRI,MANDIRI',
            'total' => 'required|numeric|min:1',
        ]);

        if ($request->metode_pembayaran === 'va' && empty($request->bank_va)) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'Pilih bank untuk Virtual Account.',
                'data' => null,
            ], 422);
        }

        DB::beginTransaction();
        try {
            $expiresAt = now()->addMinutes(15);
            $method = $request->metode_pembayaran;
            $bankVa = $method === 'va' ? $request->bank_va : null;

            $transaksi = Transaksi::create([
                'tanggal' => now()->toDateString(),
                'total' => $request->total,
                'status_order' => 'pending',
                'metode_pembayaran' => $method,
                'bank_va' => $bankVa,
                'expires_at' => $expiresAt,
            ]);

            $kodeTransaksi = 'ORD-' . now()->format('Ymd') . '-' . str_pad((string) $transaksi->id, 6, '0', STR_PAD_LEFT);
            $transaksi->update(['kode_transaksi' => $kodeTransaksi]);

            $isSimulator = !$this->isMidtransConfigured();
            if ($isSimulator) {
                $paymentCode = $method === 'qris'
                    ? $this->generateQrisCode()
                    : $this->generateVirtualAccountNumber();

                $paymentPayload = $method === 'qris'
                    ? $this->buildQrisPayload($paymentCode, (float) $request->total)
                    : null;

                $transaksi->update([
                    'payment_code' => $paymentCode,
                    'payment_payload' => $paymentPayload,
                ]);
            } else {
                $chargeResponse = $this->chargeMidtrans(
                    orderId: $kodeTransaksi,
                    total: (float) $request->total,
                    method: $method,
                    bankVa: $bankVa,
                    expiresMinutes: 15,
                    items: $request->items
                );

                $paymentCode = $this->extractPaymentCode($chargeResponse, $method);
                $paymentPayload = json_encode($chargeResponse);
                $expiresAt = !empty($chargeResponse['expiry_time'])
                    ? \Carbon\Carbon::parse($chargeResponse['expiry_time'])
                    : $expiresAt;

                $transaksi->update([
                    'payment_code' => $paymentCode,
                    'payment_payload' => $paymentPayload,
                    'expires_at' => $expiresAt,
                ]);
            }

            foreach ($request->items as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id,
                    'id_barang' => $item['id_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'harga' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => $isSimulator
                    ? 'Checkout dibuat dalam mode simulator (Midtrans belum dikonfigurasi).'
                    : 'Checkout berhasil dibuat via Midtrans. Lanjutkan pembayaran.',
                'data' => [
                    'id_transaksi' => $transaksi->id,
                    'kode_transaksi' => $kodeTransaksi,
                    'metode_pembayaran' => $method,
                    'bank_va' => $bankVa,
                    'payment_code' => $paymentCode,
                    'payment_payload' => $paymentPayload,
                    'qr_url' => !$isSimulator ? $this->extractQrisUrl(json_decode((string) $paymentPayload, true) ?? []) : null,
                    'is_simulator' => $isSimulator,
                    'total' => (float) $transaksi->total,
                    'status_order' => $transaksi->status_order,
                    'expires_at' => $expiresAt->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'status' => 'error',
                'message' => 'Gagal memproses checkout: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function status(Transaksi $transaksi)
    {
        if ($this->isMidtransConfigured() && $transaksi->status_order === 'pending' && !empty($transaksi->kode_transaksi)) {
            $this->syncStatusFromMidtrans($transaksi);
            $transaksi->refresh();
        }

        if ($transaksi->status_order === 'pending' && $transaksi->expires_at && now()->greaterThan($transaksi->expires_at)) {
            $transaksi->update(['status_order' => 'expired']);
            $transaksi->refresh();
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Status transaksi berhasil diambil',
            'data' => [
                'id_transaksi' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status_order' => $transaksi->status_order,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'bank_va' => $transaksi->bank_va,
                'payment_code' => $transaksi->payment_code,
                'total' => (float) $transaksi->total,
                'expires_at' => optional($transaksi->expires_at)->toIso8601String(),
                'paid_at' => optional($transaksi->paid_at)->toIso8601String(),
                'is_simulator' => !$this->isMidtransConfigured(),
            ],
        ]);
    }

    public function midtransCallback(Request $request)
    {
        $payload = $request->all();
        $orderId = (string) ($payload['order_id'] ?? '');

        if ($orderId === '') {
            return response()->json(['message' => 'Invalid callback payload'], 400);
        }

        if (!$this->isValidMidtransSignature($payload)) {
            Log::warning('Midtrans callback signature invalid', ['payload' => $payload]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = Transaksi::where('kode_transaksi', $orderId)->first();
        if (!$transaksi) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $mappedStatus = $this->mapMidtransStatus(
            (string) ($payload['transaction_status'] ?? ''),
            (string) ($payload['fraud_status'] ?? '')
        );

        $update = [
            'status_order' => $mappedStatus,
            'payment_payload' => json_encode($payload),
        ];

        if ($mappedStatus === 'paid') {
            $update['paid_at'] = now();
        }

        $transaksi->update($update);

        return response()->json(['message' => 'ok']);
    }

    public function simulatePaid(Transaksi $transaksi)
    {
        if ($transaksi->status_order === 'paid') {
            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Transaksi sudah dibayar sebelumnya.',
                'data' => ['status_order' => 'paid'],
            ]);
        }

        if ($transaksi->expires_at && now()->greaterThan($transaksi->expires_at)) {
            $transaksi->update(['status_order' => 'expired']);
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'Transaksi sudah expired. Tidak bisa dibayar.',
                'data' => ['status_order' => 'expired'],
            ], 422);
        }

        $transaksi->update([
            'status_order' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Simulasi pembayaran berhasil. Status menjadi PAID.',
            'data' => [
                'status_order' => 'paid',
                'paid_at' => now()->toIso8601String(),
            ],
        ]);
    }

    private function generateQrisCode(): string
    {
        return 'QRIS-' . strtoupper(Str::random(12));
    }

    private function generateVirtualAccountNumber(): string
    {
        return '88' . now()->format('ymdHis') . random_int(10, 99);
    }

    private function buildQrisPayload(string $qrisCode, float $total): string
    {
        return '00020101021126670016COM.NOBUBANK.WWW0118936000000000000' . $qrisCode . '5204549953033605802ID5920TOKO WORKSHOP FRAMEWORK6013KOTA BANDUNG5406' . (int) $total . '6304ABCD';
    }

    private function isMidtransConfigured(): bool
    {
        return !empty(config('services.midtrans.server_key'));
    }

    private function midtransBaseUrl(): string
    {
        return config('services.midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    private function chargeMidtrans(string $orderId, float $total, string $method, ?string $bankVa, int $expiresMinutes, array $items): array
    {
        $grossAmount = (int) round($total);
        $itemDetails = array_map(function ($item) {
            return [
                'id' => (string) $item['id_barang'],
                'price' => (int) round((float) $item['harga']),
                'quantity' => (int) $item['jumlah'],
                'name' => substr((string) $item['nama_barang'], 0, 50),
            ];
        }, $items);

        $requestBody = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'custom_expiry' => [
                'order_time' => now()->format('Y-m-d H:i:s O'),
                'expiry_duration' => $expiresMinutes,
                'unit' => 'minute',
            ],
        ];

        if ($method === 'qris') {
            $requestBody['payment_type'] = 'qris';
            $requestBody['qris'] = ['acquirer' => 'gopay'];
        } else {
            $requestBody['payment_type'] = 'bank_transfer';
            $requestBody['bank_transfer'] = [
                'bank' => strtolower((string) $bankVa),
            ];
        }

        $response = Http::withBasicAuth((string) config('services.midtrans.server_key'), '')
            ->acceptJson()
            ->post($this->midtransBaseUrl() . '/v2/charge', $requestBody);

        if (!$response->ok()) {
            throw new \RuntimeException('Midtrans charge gagal: ' . $response->body());
        }

        $json = $response->json();
        if (!is_array($json)) {
            throw new \RuntimeException('Response Midtrans tidak valid');
        }

        return $json;
    }

    private function extractPaymentCode(array $response, string $method): string
    {
        if ($method === 'qris') {
            return (string) ($response['transaction_id'] ?? $response['order_id'] ?? 'QRIS');
        }

        if (!empty($response['va_numbers'][0]['va_number'])) {
            return (string) $response['va_numbers'][0]['va_number'];
        }

        if (!empty($response['permata_va_number'])) {
            return (string) $response['permata_va_number'];
        }

        if (!empty($response['bill_key']) && !empty($response['biller_code'])) {
            return (string) $response['biller_code'] . $response['bill_key'];
        }

        return (string) ($response['transaction_id'] ?? $response['order_id'] ?? 'VA');
    }

    private function extractQrisUrl(array $response): ?string
    {
        if (empty($response['actions']) || !is_array($response['actions'])) {
            return null;
        }

        foreach ($response['actions'] as $action) {
            if (!is_array($action)) {
                continue;
            }

            $name = (string) ($action['name'] ?? '');
            if ($name === 'generate-qr-code' || $name === 'deeplink-redirect') {
                return (string) ($action['url'] ?? null);
            }
        }

        return null;
    }

    private function syncStatusFromMidtrans(Transaksi $transaksi): void
    {
        try {
            $response = Http::withBasicAuth((string) config('services.midtrans.server_key'), '')
                ->acceptJson()
                ->get($this->midtransBaseUrl() . '/v2/' . $transaksi->kode_transaksi . '/status');

            if (!$response->ok()) {
                return;
            }

            $payload = $response->json();
            if (!is_array($payload)) {
                return;
            }

            $mappedStatus = $this->mapMidtransStatus(
                (string) ($payload['transaction_status'] ?? ''),
                (string) ($payload['fraud_status'] ?? '')
            );

            $update = [
                'status_order' => $mappedStatus,
                'payment_payload' => json_encode($payload),
            ];

            if ($mappedStatus === 'paid' && empty($transaksi->paid_at)) {
                $update['paid_at'] = now();
            }

            $transaksi->update($update);
        } catch (\Throwable $e) {
            Log::warning('Gagal sinkron status Midtrans', [
                'order_id' => $transaksi->kode_transaksi,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function mapMidtransStatus(string $transactionStatus, string $fraudStatus = ''): string
    {
        $status = strtolower($transactionStatus);
        if (in_array($status, ['settlement', 'capture'], true)) {
            if ($status === 'capture' && strtolower($fraudStatus) === 'challenge') {
                return 'pending';
            }
            return 'paid';
        }

        if (in_array($status, ['deny', 'cancel', 'failure'], true)) {
            return 'failed';
        }

        if ($status === 'expire') {
            return 'expired';
        }

        return 'pending';
    }

    private function isValidMidtransSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');
        if ($serverKey === '') {
            return false;
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '' || $statusCode === '' || $grossAmount === '' || $signature === '') {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expected, $signature);
    }
}
