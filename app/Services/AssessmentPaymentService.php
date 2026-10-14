<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Jobs\GenerateAssessmentReport;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use App\Models\AssessmentVoucher;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\Midtrans;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class AssessmentPaymentService
{
    /**
     * Samakan format nomor WA agar pencarian status konsisten (62812… / +62 812… → 0812…).
     */
    public static function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        } elseif ($digits !== '' && ! str_starts_with($digits, '0')) {
            $digits = '0'.$digits;
        }

        return $digits;
    }

    /**
     * Cek status order langsung ke API Midtrans (server-to-server, pakai server key),
     * untuk berjaga bila webhook terlambat / tidak sampai (mis. di lokal).
     */
    public static function sync(AssessmentOrder $order): AssessmentOrder
    {
        if ($order->status !== 'pending' || ! $order->gateway_ref) {
            return $order;
        }

        try {
            new Midtrans;
            $status = (array) Transaction::status($order->code);
        } catch (\Throwable $e) {
            // 404 = belum ada transaksi (guest belum memilih metode bayar).
            if (! str_contains($e->getMessage(), '404')) {
                Log::warning('Cek status Midtrans asesmen gagal: '.$e->getMessage(), ['code' => $order->code]);
            }

            return $order;
        }

        $transactionStatus = $status['transaction_status'] ?? '';
        $paid = $transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && ($status['fraud_status'] ?? null) !== 'challenge');

        if ($paid && (int) ($status['gross_amount'] ?? 0) >= $order->amount) {
            self::markPaid($order, $status['payment_type'] ?? null);
        } elseif ($transactionStatus === 'expire') {
            $order->update(['status' => 'expired']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'failure'], true)) {
            $order->update(['status' => 'failed']);
        }

        return $order->refresh();
    }

    /**
     * Tandai lunas, buka hasil, dan antrekan laporan AI. Idempoten.
     */
    public static function markPaid(AssessmentOrder $order, ?string $paymentType = null): void
    {
        if ($order->status === 'paid') {
            return;
        }

        DB::transaction(function () use ($order, $paymentType) {
            $order->update(['status' => 'paid', 'payment_type' => $paymentType, 'paid_at' => now()]);

            $result = $order->result;
            if ($result && ! $result->is_unlocked) {
                $result->update(['is_unlocked' => true, 'unlocked_at' => now()]);
                // Email "lunas" dikirim sebelum commit agar tiba lebih dulu dari email hasil (job jalan setelah commit).
                AssessmentMailer::send($result, 'paid', $order);
                GenerateAssessmentReport::dispatch($result->uuid)->afterCommit();
            }
        });

        try {
            $voucher = $order->voucher_code ? "\nVoucher : {$order->voucher_code} (-{$order->discount_amount})" : '';
            BotHelper::sendTelegram("Godevi - Payment Asesmen Success, \n\nInvoice : {$order->code} \nNominal : {$order->amount}.{$voucher}\n");
        } catch (\Throwable $e) {
            Log::error('Telegram ASM error: '.$e->getMessage());
        }
    }

    /**
     * Approve pembayaran secara manual oleh admin (mis. transfer langsung / pembayaran di luar Midtrans).
     * Memakai order pending terakhir bila ada; bila belum ada, dibuatkan invoice manual.
     */
    public static function approveManually(AssessmentResult $result, int $adminId, ?string $note = null): AssessmentOrder
    {
        $order = $result->orders()->where('status', 'pending')->latest('id')->first()
            ?? $result->orders()->create([
                'code' => AssessmentOrder::generateCode(),
                'amount' => (int) ($result->track->price ?? 0),
                'gateway' => 'manual',
                'status' => 'pending',
            ]);

        $result->update([
            'approved_by' => $adminId,
            'approved_at' => now(),
            'approval_note' => $note,
        ]);

        self::markPaid($order, 'manual');

        return $order->refresh();
    }

    /**
     * Invoice (order pending) untuk hasil berbayar yang belum lunas. Dipakai ulang bila sudah ada,
     * sehingga nomor invoice tetap sama dari email pertama sampai checkout.
     */
    public static function ensureInvoice(AssessmentResult $result): ?AssessmentOrder
    {
        $result->loadMissing('track');
        $amount = (int) ($result->track->price ?? 0);

        if ($result->is_unlocked || $amount <= 0) {
            return null;
        }

        return $result->orders()->where('status', 'pending')->latest('id')->first()
            ?? $result->orders()->create([
                'code' => AssessmentOrder::generateCode(),
                'amount' => $amount,
                'original_amount' => $amount,
                'gateway' => 'midtrans',
                'status' => 'pending',
            ]);
    }

    /**
     * Pakai kode voucher pada invoice hasil ini. Voucher 100% → langsung lunas & hasil terbuka.
     *
     * @return array{ok: bool, message: string}
     */
    public static function applyVoucher(AssessmentResult $result, ?string $code): array
    {
        $result->loadMissing('track');
        $price = (int) ($result->track->price ?? 0);
        $code = AssessmentVoucher::normalizeCode($code);

        if ($result->is_unlocked || $price <= 0) {
            return ['ok' => false, 'message' => __('Hasil asesmen ini sudah terbuka.')];
        }
        if ($code === '') {
            return ['ok' => false, 'message' => __('Masukkan kode voucher.')];
        }

        return DB::transaction(function () use ($result, $code, $price) {
            // Kunci baris voucher agar kuota tidak terlampaui oleh pemakaian bersamaan.
            $voucher = AssessmentVoucher::where('code', $code)->lockForUpdate()->first();
            if (! $voucher) {
                return ['ok' => false, 'message' => __('Kode voucher tidak ditemukan.')];
            }
            if ($reason = $voucher->rejectionFor($result->track)) {
                return ['ok' => false, 'message' => $reason];
            }

            $discount = $voucher->discountFor($price);
            $order = self::replacePendingOrder($result, [
                'amount' => $price - $discount,
                'original_amount' => $price,
                'discount_amount' => $discount,
                'voucher_id' => $voucher->id,
                'voucher_code' => $voucher->code,
                'gateway' => $price - $discount > 0 ? 'midtrans' : 'voucher',
            ]);
            if (! $order) {
                return ['ok' => false, 'message' => __('Pembayaran untuk asesmen ini sudah diterima.')];
            }

            if ($order->amount === 0) {
                self::markPaid($order, 'voucher');

                return ['ok' => true, 'message' => __('Voucher :code dipakai — asesmen gratis, hasil sudah terbuka.', ['code' => $voucher->code])];
            }

            return ['ok' => true, 'message' => __('Voucher :code dipakai — hemat Rp :amount.', ['code' => $voucher->code, 'amount' => number_format($discount, 0, ',', '.')])];
        });
    }

    /** Lepas voucher dari invoice yang belum dibayar → kembali ke harga normal. */
    public static function removeVoucher(AssessmentResult $result): void
    {
        $result->loadMissing('track');
        $pending = $result->orders()->where('status', 'pending')->latest('id')->first();
        if ($result->is_unlocked || ! $pending?->voucher_id) {
            return;
        }

        $price = (int) ($result->track->price ?? 0);
        self::replacePendingOrder($result, [
            'amount' => $price,
            'original_amount' => $price,
            'discount_amount' => 0,
            'voucher_id' => null,
            'voucher_code' => null,
            'gateway' => 'midtrans',
        ]);
    }

    /**
     * Ubah nominal invoice yang belum dibayar. Snap token Midtrans terikat pada nominal lama,
     * jadi invoice yang sudah punya token dibatalkan dan diganti invoice baru.
     * Mengembalikan null bila ternyata invoice lama sudah lunas.
     */
    protected static function replacePendingOrder(AssessmentResult $result, array $attributes): ?AssessmentOrder
    {
        $pending = $result->orders()->where('status', 'pending')->latest('id')->first();

        if ($pending?->gateway_ref) {
            // Guest mungkin sudah membayar invoice lama → cek dulu ke Midtrans.
            if (self::sync($pending)->status === 'paid') {
                return null;
            }
            if ($pending->status === 'pending') {
                $pending->update(['status' => 'cancelled']);
            }
            $pending = null;
        }

        if ($pending) {
            $pending->update($attributes);

            return $pending;
        }

        return $result->orders()->create($attributes + [
            'code' => AssessmentOrder::generateCode(),
            'status' => 'pending',
        ]);
    }

    /**
     * Pastikan order punya Snap token Midtrans (dibuat saat guest membuka halaman bayar).
     *
     * @throws \Throwable bila Midtrans menolak / tidak bisa dihubungi
     */
    public static function ensureSnapToken(AssessmentOrder $order): AssessmentOrder
    {
        if ($order->gateway_ref) {
            return $order;
        }

        $result = $order->result()->with('track')->first();
        $params = [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => (int) $order->amount,
            ],
            'item_details' => [[
                'id' => 'asesmen-'.$result->track->slug,
                'price' => (int) $order->amount,
                'quantity' => 1,
                'name' => 'Laporan Asesmen: '.$result->track->name,
            ]],
            'customer_details' => array_filter([
                'first_name' => $result->name,
                'email' => $result->email,
                'phone' => $result->phone,
            ]),
            'credit_card' => ['secure' => true],
            'expiry' => ['unit' => 'hour', 'duration' => 24],
        ];

        $order->gateway_ref = (new CreateSnapTokenService($order))->getSnapToken($params);
        $order->save();

        return $order;
    }

    /** Sudahkah email invoice untuk order ini pernah terkirim? */
    public static function invoiceEmailed(AssessmentResult $result, AssessmentOrder $order): bool
    {
        return collect($result->email_log ?? [])
            ->contains(fn ($e) => ($e['type'] ?? null) === 'invoice' && ($e['order'] ?? null) === $order->code && ! empty($e['ok']));
    }
}
