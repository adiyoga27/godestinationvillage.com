<?php

namespace App\Console\Commands;

use App\Models\AssessmentResult;
use App\Services\AssessmentService;
use Illuminate\Console\Command;

class AssessmentRecompute extends Command
{
    protected $signature = 'assessment:recompute {--all : hitung ulang semua hasil, bukan hanya yang skornya 0} {--dry-run : tampilkan perubahan tanpa menyimpan}';

    protected $description = 'Hitung ulang skor total & kategori hasil asesmen dari jawaban tersimpan (memperbaiki skor 0/100)';

    public function handle(): int
    {
        $query = AssessmentResult::with('track')->whereNotNull('answers');
        if (! $this->option('all')) {
            $query->where('total_score', 0);
        }

        $changed = 0;
        foreach ($query->cursor() as $result) {
            $answers = collect($result->answers)->map(fn ($v) => (int) $v)->all();
            $questions = $result->track->activeQuestions()->get()->whereIn('id', array_keys($answers))->values();
            if ($questions->isEmpty()) {
                $this->warn("#{$result->id}: pertanyaan tidak ditemukan, dilewati");

                continue;
            }

            $computed = AssessmentService::compute($result->track, $questions, $answers);
            if ((float) $result->total_score === (float) $computed['total'] && $result->band === $computed['band']) {
                continue;
            }

            $this->line("#{$result->id} {$result->organization}: {$result->total_score} ({$result->band}) → {$computed['total']} ({$computed['band']})");
            if (! $this->option('dry-run')) {
                $result->update([
                    'dimension_scores' => $computed['dimensions'],
                    'total_score' => $computed['total'],
                    'band' => $computed['band'],
                    'computed_result' => $computed,
                ]);
            }
            $changed++;
        }

        $this->info(($this->option('dry-run') ? '[dry-run] ' : '')."{$changed} hasil diperbarui.");

        return self::SUCCESS;
    }
}
