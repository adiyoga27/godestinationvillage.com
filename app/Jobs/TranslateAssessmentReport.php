<?php

namespace App\Jobs;

use App\Models\AssessmentResult;
use App\Services\AiReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Terjemahkan laporan AI (Bahasa Indonesia) ke English → assessment_results.ai_report_en.
 * Dipicu setelah laporan jadi (responden English) atau saat halaman hasil dibuka dalam English.
 */
class TranslateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(public string $resultUuid) {}

    /**
     * Dispatch sekali per 5 menit per hasil (halaman hasil memuat ulang berkala).
     * Antrean sync → jalan setelah respons terkirim agar halaman tidak menunggu AI.
     */
    public static function dispatchOnce(AssessmentResult $result): void
    {
        if (! Cache::add('assessment-translate:'.$result->uuid, true, now()->addMinutes(5))) {
            return;
        }

        $pending = self::dispatch($result->uuid);
        if (config('queue.default') === 'sync') {
            $pending->afterResponse();
        }
    }

    public function handle(): void
    {
        $result = AssessmentResult::with('track')->where('uuid', $this->resultUuid)->first();
        if (! $result || empty($result->ai_report) || ! empty($result->ai_report_en)) {
            return;
        }

        $out = AiReportService::translateReport($result->ai_report, $result->track->slug);

        $meta = $result->ai_meta ?? [];
        $meta['translation_en'] = $out['meta'] + ['ok' => $out['ok'], 'error' => $out['error'] ?? null];

        if ($out['ok']) {
            $result->forceFill(['ai_report_en' => $out['report'], 'ai_meta' => $meta])->saveQuietly();
        } else {
            $result->forceFill(['ai_meta' => $meta])->saveQuietly();
            Log::warning('TranslateAssessmentReport gagal', ['uuid' => $result->uuid, 'error' => $out['error'] ?? null]);
        }
    }
}
