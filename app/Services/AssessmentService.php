<?php

namespace App\Services;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;
use Illuminate\Support\Collection;

class AssessmentService
{
    public const SCALE_LABELS = [
        1 => 'Sangat belum siap',
        2 => 'Belum siap',
        3 => 'Cukup',
        4 => 'Siap',
        5 => 'Sangat siap',
    ];

    public static function bandsFor(AssessmentTrack $track): array
    {
        if ($track->slug === 'regeneratif') {
            return [
                ['min' => 75, 'label' => 'Regeneratif Matang', 'desc' => 'Usahamu memulihkan alam, menguatkan komunitas, dan memutar ekonomi lokal. Saatnya menjadi teladan dan mentor bagi pelaku lain.'],
                ['min' => 50, 'label' => 'Berkembang ke Regeneratif', 'desc' => 'Fondasi baik sudah ada. Fokus pada 2–3 tantangan terbesar agar naik ke level matang.'],
                ['min' => 25, 'label' => 'Transisi', 'desc' => 'Usahamu mulai bergerak keluar dari pola ekstraktif. Perkuat tata kelola lokal dan rantai pasok.'],
                ['min' => 0, 'label' => 'Ekstraktif', 'desc' => 'Model usaha masih mengambil lebih banyak dari alam dan komunitas daripada yang dikembalikan. Mulai dari langkah prioritas di bawah.'],
            ];
        }

        return [
            ['min' => 80, 'label' => 'Mandiri', 'desc' => 'Sangat siap untuk naik kelas: ekspansi pasar, sertifikasi, dan kemitraan strategis.'],
            ['min' => 60, 'label' => 'Maju', 'desc' => 'Fondasi kuat. Fokus pada 2–3 tantangan terbesar untuk mencapai kemandirian.'],
            ['min' => 40, 'label' => 'Berkembang', 'desc' => 'Potensi jelas, sistem perlu dirapikan. Jalankan draf strategi prioritas di bawah secara bertahap.'],
            ['min' => 0, 'label' => 'Rintisan', 'desc' => 'Masih tahap awal. Mulai dari fondasi: kelembagaan, produk unggulan, dan pencatatan dasar.'],
        ];
    }

    public static function bandFor(AssessmentTrack $track, float $score): array
    {
        foreach (self::bandsFor($track) as $band) {
            if ($score >= $band['min']) {
                return $band;
            }
        }

        return ['min' => 0, 'label' => 'Rintisan', 'desc' => ''];
    }

    public static function dimensionAdvice(string $dimension, float $score): string
    {
        if ($score >= 80) {
            return "Pertahankan dan dokumentasikan praktik baik pada dimensi {$dimension} sebagai keunggulan untuk promosi dan kemitraan.";
        }
        if ($score >= 60) {
            return "Dimensi {$dimension} sudah berjalan — standarkan prosedurnya agar konsisten meski pengelola berganti.";
        }
        if ($score >= 40) {
            return "Dimensi {$dimension} perlu penguatan bertahap: tetapkan penanggung jawab, target 3 bulan, dan evaluasi rutin.";
        }

        return "Dimensi {$dimension} adalah prioritas utama: mulai dari langkah terkecil yang bisa dikerjakan minggu ini dengan sumber daya yang ada.";
    }

    /**
     * @param  Collection<int, AssessmentQuestion>  $questions
     * @param  array<int, int>  $answers  [question_id => 1..5]
     */
    public static function compute(AssessmentTrack $track, $questions, array $answers): array
    {
        $byDimension = [];
        foreach ($questions as $q) {
            $value = (int) ($answers[$q->id] ?? 3);
            $value = max(1, min(5, $value));
            $byDimension[$q->dimension]['weighted'] = ($byDimension[$q->dimension]['weighted'] ?? 0) + $value * $q->weight;
            $byDimension[$q->dimension]['weight'] = ($byDimension[$q->dimension]['weight'] ?? 0) + $q->weight;
            $byDimension[$q->dimension]['questions'][] = [
                'id' => $q->id,
                'question' => $q->question,
                'score' => $value,
            ];
        }

        $dimensions = [];
        foreach ($byDimension as $name => $agg) {
            $avg = $agg['weighted'] / max(1, $agg['weight']);
            $score = round(($avg - 1) / 4 * 100, 2);
            $dimensions[$name] = [
                'score' => $score,
                'average' => round($avg, 2),
                'advice' => self::dimensionAdvice($name, $score),
                'questions' => $agg['questions'],
            ];
        }

        uasort($dimensions, fn ($a, $b) => $b['score'] <=> $a['score']);

        $total = count($dimensions) > 0 ? round(array_sum(array_column($dimensions, 'score')) / count($dimensions), 2) : 0;
        $band = self::bandFor($track, $total);

        $names = array_keys($dimensions);
        $strengths = array_slice($names, 0, 2);
        $challenges = array_slice(array_reverse($names), 0, 2);

        $priorityActions = [];
        foreach ($dimensions as $name => $dim) {
            foreach ($dim['questions'] as $item) {
                if ($item['score'] <= 2) {
                    $priorityActions[] = ['dimension' => $name, 'action' => 'Perkuat: '.$item['question']];
                }
            }
            if (count($priorityActions) >= 7) {
                break;
            }
        }

        return [
            'dimensions' => $dimensions,
            'total' => $total,
            'band' => $band['label'],
            'band_desc' => $band['desc'],
            'strengths' => $strengths,
            'challenges' => $challenges,
            'priority_actions' => $priorityActions,
        ];
    }
}
