<?php

namespace App\Console\Commands;

use App\Models\AssessmentTrack;
use App\Services\AssessmentContentTranslator;
use Illuminate\Console\Command;

/**
 * Isi versi English jalur & soal asesmen dengan AI (hanya kolom *_en yang kosong).
 * Contoh: php artisan assessment:translate-content
 *         php artisan assessment:translate-content --track=pariwisata --force
 */
class AssessmentTranslateContent extends Command
{
    protected $signature = 'assessment:translate-content
        {--track= : Slug jalur (default semua jalur)}
        {--force : Timpa terjemahan English yang sudah ada}';

    protected $description = 'Terjemahkan nama jalur, dimensi, soal & teks bantuan asesmen ke English dengan AI';

    public function handle(): int
    {
        $tracks = AssessmentTrack::query()
            ->when($this->option('track'), fn ($q, $slug) => $q->where('slug', $slug))
            ->orderBy('sort_order')
            ->get();

        if ($tracks->isEmpty()) {
            $this->error('Jalur tidak ditemukan.');

            return self::FAILURE;
        }

        $failed = false;
        foreach ($tracks as $track) {
            $this->line("→ {$track->name} ({$track->slug})");
            $out = AssessmentContentTranslator::translateTrack($track, (bool) $this->option('force'));
            $this->line("  {$out['filled']} kolom English diisi");
            if (! $out['ok']) {
                $failed = true;
                $this->error('  Gagal: '.$out['error']);
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
