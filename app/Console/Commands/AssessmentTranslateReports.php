<?php

namespace App\Console\Commands;

use App\Jobs\TranslateAssessmentReport;
use App\Models\AssessmentResult;
use Illuminate\Console\Command;

/**
 * Lengkapi laporan AI lama yang baru tersimpan dalam Bahasa Indonesia dengan versi English (ai_report_en).
 * Contoh: php artisan assessment:translate-reports
 *         php artisan assessment:translate-reports --uuid=873b3bfa-3f69-4b31-be1e-4a9e070cef97
 */
class AssessmentTranslateReports extends Command
{
    protected $signature = 'assessment:translate-reports
        {--uuid= : Hanya hasil dengan UUID ini}
        {--limit=50 : Maksimal jumlah hasil yang diterjemahkan}';

    protected $description = 'Terjemahkan laporan AI asesmen yang belum punya versi English';

    public function handle(): int
    {
        $results = AssessmentResult::query()
            ->whereNotNull('ai_report')
            ->whereNull('ai_report_en')
            ->when($this->option('uuid'), fn ($q, $uuid) => $q->where('uuid', $uuid))
            ->latest()
            ->limit((int) $this->option('limit'))
            ->pluck('uuid');

        if ($results->isEmpty()) {
            $this->info('Semua laporan sudah punya versi English.');

            return self::SUCCESS;
        }

        $ok = 0;
        foreach ($results as $uuid) {
            (new TranslateAssessmentReport($uuid))->handle();
            $done = AssessmentResult::where('uuid', $uuid)->whereNotNull('ai_report_en')->exists();
            $ok += (int) $done;
            $this->line(($done ? '✓ ' : '✗ ').$uuid);
        }

        $this->info("$ok / {$results->count()} laporan diterjemahkan.");

        return $ok === $results->count() ? self::SUCCESS : self::FAILURE;
    }
}
