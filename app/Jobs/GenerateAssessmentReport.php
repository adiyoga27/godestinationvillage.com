<?php

namespace App\Jobs;

use App\Models\AssessmentResult;
use App\Services\AiReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAssessmentReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public string $resultUuid) {}

    public function handle(): void
    {
        $result = AssessmentResult::with('track')->where('uuid', $this->resultUuid)->first();
        if (! $result) {
            return;
        }
        // Idempoten: satu order = satu laporan; jangan buat ulang.
        if (! $result->is_unlocked || ! empty($result->ai_report)) {
            return;
        }
        // Kunci agar webhook ganda / view ganda tidak memicu dobel.
        if ($result->report_status === 'generating') {
            return;
        }
        $result->update(['report_status' => 'generating', 'report_error' => null]);

        $computed = [
            'dimensions' => $result->dimension_scores ?? [],
            'total' => (float) $result->total_score,
            'band' => $result->band,
        ];
        if ($result->track->slug === 'daya-saing-destinasi') {
            $computed['subindexes'] = \App\Services\AssessmentService::subindexScores($computed['dimensions']);
        }

        $out = AiReportService::generate($result, $computed);

        if ($out['ok']) {
            $result->update(['ai_report' => $out['report'], 'report_status' => 'done']);
        } else {
            $result->update(['report_status' => 'failed', 'report_error' => $out['error'] ?? 'unknown']);
            Log::error('GenerateAssessmentReport gagal', ['uuid' => $result->uuid, 'error' => $out['error'] ?? null]);
        }
    }
}
