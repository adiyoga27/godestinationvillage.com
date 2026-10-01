<?php

namespace App\Services;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;
use Illuminate\Support\Collection;

class AssessmentService
{
    /**
     * Skala penilaian 1–5 (Brief §3).
     * 1 Belum ada, 2 Rintisan, 3 Cukup, 4 Baik, 5 Sangat matang.
     */
    public const SCALE_LABELS = [
        1 => 'Belum ada',
        2 => 'Rintisan',
        3 => 'Cukup',
        4 => 'Baik',
        5 => 'Sangat matang',
    ];

    /**
     * Bobot dimensi per jalur (persen, total 100) — Brief §3.1–§3.4.
     *
     * CATATAN: dimensi DB untuk jalur ekonomi-desa & regeneratif belum
     * dipetakan ulang ke 7 dimensi brief (butuh teks DIM_* dari prototype
     * godevi-readiness-impact-assistant.html). Selama belum dipetakan,
     * jalur tersebut memakai bobot setara (fallback equal weight).
     */
    public const DIMENSION_WEIGHTS = [
        'pariwisata' => [
            'Daya Tarik Alam & Budaya' => 25,
            'Aksesibilitas & Infrastruktur' => 20,
            'Amenitas & Akomodasi' => 15,
            'Kesiapan Komunitas & SDM' => 15,
            'Tata Kelola & Kelembagaan' => 15,
            'Kehadiran Digital Saat Ini' => 10,
        ],
        // TTDI: 17 pilar, bobot sama rata 1/17 (Brief §3.3).
        'daya-saing-destinasi' => [
            'Iklim Usaha' => 5.882,
            'Keamanan & Keselamatan' => 5.882,
            'Kesehatan & Kebersihan' => 5.882,
            'SDM & Pasar Tenaga Kerja' => 5.882,
            'Kesiapan TIK' => 5.882,
            'Prioritas Pariwisata' => 5.882,
            'Keterbukaan Internasional' => 5.882,
            'Daya Saing Harga' => 5.882,
            'Infrastruktur Transportasi Udara' => 5.882,
            'Infrastruktur Darat & Pelabuhan' => 5.882,
            'Infrastruktur Layanan Wisatawan' => 5.882,
            'Sumber Daya Alam' => 5.882,
            'Sumber Daya Budaya' => 5.882,
            'Sumber Daya Non-Leisure' => 5.882,
            'Keberlanjutan Lingkungan' => 5.882,
            'Ketahanan Sosial-Ekonomi' => 5.882,
            'Dampak Sosial-Ekonomi Pariwisata' => 5.882,
        ],
    ];

    /**
     * Pemetaan 17 pilar TTDI ke 5 subindeks resmi WEF (Brief §3.3).
     */
    public const TTDI_SUBINDEXES = [
        'A. Enabling Environment' => [
            'Iklim Usaha',
            'Keamanan & Keselamatan',
            'Kesehatan & Kebersihan',
            'SDM & Pasar Tenaga Kerja',
            'Kesiapan TIK',
        ],
        'B. T&T Policy & Enabling Conditions' => [
            'Prioritas Pariwisata',
            'Keterbukaan Internasional',
            'Daya Saing Harga',
        ],
        'C. Infrastructure' => [
            'Infrastruktur Transportasi Udara',
            'Infrastruktur Darat & Pelabuhan',
            'Infrastruktur Layanan Wisatawan',
        ],
        'D. T&T Demand Drivers' => [
            'Sumber Daya Alam',
            'Sumber Daya Budaya',
            'Sumber Daya Non-Leisure',
        ],
        'E. T&T Sustainability' => [
            'Keberlanjutan Lingkungan',
            'Ketahanan Sosial-Ekonomi',
            'Dampak Sosial-Ekonomi Pariwisata',
        ],
    ];

    public const TTDI_DISCLAIMER = 'Asesmen ini mengikuti struktur 17 pilar Travel & Tourism Development Index (WEF), diadaptasi menjadi penilaian mandiri skala kawasan/kabupaten dengan skor 1–5. Ini bukan replikasi 102 indikator data-keras resmi WEF dan bukan skor TTDI resmi.';

    public static function weightsFor(AssessmentTrack $track): array
    {
        return self::DIMENSION_WEIGHTS[$track->slug] ?? [];
    }

    public static function bandsFor(AssessmentTrack $track): array
    {
        // Brief §3: ≤40 / 41–60 / 61–80 / >80.
        if ($track->slug === 'regeneratif') {
            return [
                ['min' => 81, 'label' => 'Regeneratif Matang', 'desc' => 'Usahamu memulihkan alam, menguatkan komunitas, dan memutar ekonomi lokal. Saatnya menjadi teladan dan mentor bagi pelaku lain.'],
                ['min' => 61, 'label' => 'Regeneratif Awal', 'desc' => 'Praktik regeneratif mulai berjalan. Fokus pada 2–3 dimensi terendah agar naik ke level matang.'],
                ['min' => 41, 'label' => 'Berkelanjutan', 'desc' => 'Usaha sudah mengurangi dampak negatif. Saatnya beralih dari sekadar berkelanjutan menjadi memulihkan.'],
                ['min' => 0, 'label' => 'Ekstraktif', 'desc' => 'Model usaha masih mengambil lebih banyak dari alam dan komunitas daripada yang dikembalikan. Mulai dari langkah prioritas di bawah.'],
            ];
        }

        if ($track->slug === 'daya-saing-destinasi') {
            return [
                ['min' => 81, 'label' => 'Unggul', 'desc' => 'Destinasi sangat kompetitif: saatnya ekspansi pasar, sertifikasi, dan kemitraan strategis.'],
                ['min' => 61, 'label' => 'Kompetitif', 'desc' => 'Daya saing kuat. Fokus pada 2–3 pilar terendah untuk mencapai level unggul.'],
                ['min' => 41, 'label' => 'Berkembang', 'desc' => 'Potensi jelas, sistem perlu dirapikan. Jalankan prioritas kebijakan di bawah secara bertahap.'],
                ['min' => 0, 'label' => 'Rintisan', 'desc' => 'Masih tahap awal. Mulai dari fondasi: tata kelola, infrastruktur dasar, dan pendataan.'],
            ];
        }

        // pariwisata & ekonomi-desa: Rintisan / Berkembang / Siap / Unggul.
        return [
            ['min' => 81, 'label' => 'Unggul', 'desc' => 'Sangat siap untuk naik kelas: ekspansi pasar, sertifikasi, dan kemitraan strategis.'],
            ['min' => 61, 'label' => 'Siap', 'desc' => 'Fondasi kuat. Fokus pada 2–3 tantangan terbesar untuk mencapai level unggul.'],
            ['min' => 41, 'label' => 'Berkembang', 'desc' => 'Potensi jelas, sistem perlu dirapikan. Jalankan draf strategi prioritas di bawah secara bertahap.'],
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
     * Rumus Brief §3: overall = round((Σ skor_dimensi × bobot) / 5 × 100).
     * skor_dimensi 1–5 (rata-rata jawaban per dimensi), bobot dalam persen (Σ=100).
     * Jalur tanpa bobot eksplisit memakai bobot setara per dimensi.
     *
     * @param  Collection<int, AssessmentQuestion>  $questions
     * @param  array<int, int>  $answers  [question_id => 1..5]
     */
    public static function compute(AssessmentTrack $track, $questions, array $answers): array
    {
        $byDimension = [];
        foreach ($questions as $q) {
            $value = (int) ($answers[$q->id] ?? 3);
            $value = max(1, min(5, $value));
            $byDimension[$q->dimension]['total'] = ($byDimension[$q->dimension]['total'] ?? 0) + $value;
            $byDimension[$q->dimension]['count'] = ($byDimension[$q->dimension]['count'] ?? 0) + 1;
            $byDimension[$q->dimension]['questions'][] = [
                'id' => $q->id,
                'question' => $q->question,
                'score' => $value,
            ];
        }

        $configured = self::weightsFor($track);
        $useEqual = empty($configured);
        $equalWeight = count($byDimension) > 0 ? 100 / count($byDimension) : 0;

        $dimensions = [];
        $weightedSum = 0;
        foreach ($byDimension as $name => $agg) {
            $avg = $agg['total'] / max(1, $agg['count']);
            // Normalisasi linear 1–5 → 20–100 (konsisten dengan rumus overall).
            $score = round($avg / 5 * 100, 2);
            $weight = $useEqual ? $equalWeight : ($configured[$name] ?? 0);
            $weightedSum += $avg * $weight;
            $dimensions[$name] = [
                'score' => $score,
                'average' => round($avg, 2),
                'weight' => round($weight, 3),
                'advice' => self::dimensionAdvice($name, $score),
                'questions' => $agg['questions'],
            ];
        }

        uasort($dimensions, fn ($a, $b) => $b['score'] <=> $a['score']);

        $totalWeight = $useEqual ? 100 : array_sum(array_column($dimensions, 'weight'));
        $total = $totalWeight > 0 ? (int) round($weightedSum / $totalWeight / 5 * 100) : 0;
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

        $result = [
            'dimensions' => $dimensions,
            'total' => $total,
            'band' => $band['label'],
            'band_desc' => $band['desc'],
            'strengths' => $strengths,
            'challenges' => $challenges,
            'priority_actions' => $priorityActions,
        ];

        // Subskor per subindeks TTDI (rata-rata pilar dalam subindeks, 0–100).
        if ($track->slug === 'daya-saing-destinasi') {
            $result['subindexes'] = self::subindexScores($dimensions);
        }

        return $result;
    }

    /**
     * @param  array<string, array{average: float}>  $dimensions
     * @return array<string, float>
     */
    public static function subindexScores(array $dimensions): array
    {
        $out = [];
        foreach (self::TTDI_SUBINDEXES as $sub => $pillars) {
            $vals = [];
            foreach ($pillars as $p) {
                if (isset($dimensions[$p])) {
                    $vals[] = $dimensions[$p]['average'];
                }
            }
            $out[$sub] = count($vals) > 0 ? round(array_sum($vals) / count($vals) / 5 * 100, 2) : 0;
        }

        return $out;
    }
}
