<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Jobs\GenerateAssessmentReport;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
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
            BotHelper::sendTelegram("Godevi - Payment Asesmen Success, \n\nInvoice : {$order->code} \nNominal : {$order->amount}.\n");
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
}
