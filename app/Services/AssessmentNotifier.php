<?php

namespace App\Services;

use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Notifikasi Telegram untuk tim: status email asesmen (invoice/lunas/hasil) dan error.
 * Gagal kirim Telegram tidak boleh mengganggu alur guest maupun admin.
 */
class AssessmentNotifier
{
    public static function telegram(string $text): bool
    {
        $token = config('telegram.token');
        $chatId = config('telegram.chat_id');
        if (blank($token) || blank($chatId)) {
            return false;
        }

        try {
            // Dikirim sebagai form body (bukan query string) agar teks dengan &, # dan baris baru utuh.
            return Http::asForm()->timeout(5)
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => mb_substr($text, 0, 4000),
                    'disable_web_page_preview' => 'true',
                ])->successful();
        } catch (\Throwable $e) {
            Log::warning('Telegram asesmen gagal: '.$e->getMessage());

            return false;
        }
    }

    /** Status pengiriman email asesmen (dipanggil AssessmentMailer). */
    public static function email(AssessmentResult $result, string $type, ?AssessmentOrder $order, array $entry): void
    {
        $label = AssessmentResult::EMAIL_TYPES[$type] ?? $type;
        [$icon, $status] = match (true) {
            ! empty($entry['simulated']) => ['⚠️', 'TIDAK TERKIRIM — mailer '.($entry['mailer'] ?? 'log').' (hanya log)'],
            ! empty($entry['ok']) => [['invoice' => '🧾', 'paid' => '✅', 'report' => '📊'][$type] ?? '✉️', 'terkirim'],
            default => ['❌', 'GAGAL'],
        };

        $lines = [
            "{$icon} Email {$label} {$status}",
            self::summary($result),
            'Ke: '.($entry['to'] ?? '-'),
        ];
        if ($order) {
            $lines[] = 'Invoice: '.$order->code.' · Rp '.number_format((int) $order->amount, 0, ',', '.').' · '.strtoupper($order->status === 'paid' ? 'LUNAS' : $order->status);
        }
        if (! empty($entry['error'])) {
            $lines[] = 'Error: '.$entry['error'];
        }
        $lines[] = route('assessment-results.show', $result->id);

        self::telegram(implode("\n", $lines));
    }

    /** Laporan AI gagal dibuat. */
    public static function reportFailed(AssessmentResult $result, string $error): void
    {
        self::telegram(implode("\n", [
            '❌ Laporan AI asesmen GAGAL',
            self::summary($result),
            'Error: '.$error,
            route('assessment-results.show', $result->id),
        ]));
    }

    protected static function summary(AssessmentResult $result): string
    {
        $result->loadMissing('track');

        return '#'.$result->id.' '.($result->organization ?: $result->name).' — '.($result->track->name ?? '-')
            .($result->regency ? ' ('.$result->regency.')' : '');
    }
}
