<?php

namespace App\Services;

use App\Mail\AssessmentMail;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Kirim email asesmen ke guest & catat hasilnya di assessment_results.email_log.
 * Gagal kirim tidak boleh menggagalkan alur pembayaran / laporan.
 */
class AssessmentMailer
{
    public static function send(AssessmentResult $result, string $type, ?AssessmentOrder $order = null): bool
    {
        $to = trim((string) $result->email);
        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $entry = ['type' => $type, 'to' => $to, 'order' => $order?->code, 'at' => now()->toIso8601String(), 'mailer' => config('mail.default')];
        // Mailer log/array hanya mencatat, tidak benar-benar mengirim ke inbox.
        if (self::isSimulated()) {
            $entry['simulated'] = true;
        }

        try {
            $result->loadMissing('track');
            Mail::to($to)->send(new AssessmentMail($type, $result, $order));
            $entry['ok'] = true;
        } catch (\Throwable $e) {
            Log::warning('Email asesmen gagal: '.$e->getMessage(), ['uuid' => $result->uuid, 'type' => $type]);
            $entry['ok'] = false;
            $entry['error'] = mb_substr($e->getMessage(), 0, 300);
        }

        $log = $result->fresh()->email_log ?? [];
        $log[] = $entry;
        $result->forceFill(['email_log' => $log])->saveQuietly();

        AssessmentNotifier::email($result, $type, $order, $entry);

        return $entry['ok'];
    }

    /** True bila mailer aktif tidak mengirim email sungguhan (MAIL_MAILER=log/array). */
    public static function isSimulated(): bool
    {
        return in_array(config('mail.default'), ['log', 'array'], true);
    }
}
