<?php

namespace App\Services;

use App\Models\AssessmentResult;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Laporan strategi AI untuk asesmen (Brief §6).
 * Provider-agnostic: driver 'deepseek' (default) atau 'claude'.
 * Semua panggilan server-side; key tidak pernah ke frontend.
 * Skor dihitung server (AssessmentService), AI hanya menulis narasi.
 */
class AiReportService
{
    public static function requiredKeys(string $slug): array
    {
        $base = ['ringkasan', 'kekuatan', 'tantangan', 'langkah_prioritas', 'layanan_godevi_disarankan'];

        return match ($slug) {
            'pariwisata' => [...$base, 'segmen_pasar', 'strategi_pemasaran', 'opsi_branding'],
            'ekonomi-desa' => [...$base, 'produk_unggulan_potensial', 'segmen_pasar', 'strategi_pemasaran', 'model_bisnis_disarankan'],
            'daya-saing-destinasi' => [...$base, 'catatan_kategori', 'prioritas_kebijakan', 'catatan_ttdi'],
            'regeneratif' => [...$base, 'posisi_spektrum', 'rekomendasi_regeneratif', 'sertifikasi_relevan'],
            default => $base,
        };
    }

    public static function buildPrompt(AssessmentResult $result, array $computed): string
    {
        $track = $result->track;
        $lines = [];
        foreach ($computed['dimensions'] ?? [] as $name => $dim) {
            $weight = $dim['weight'] ?? null;
            $lines[] = '- '.$name.' (bobot '.($weight !== null ? $weight.'%' : '-').'): rata-rata '
                .($dim['average'] ?? '?').'/5 → skor '.($dim['score'] ?? '?').'/100';
        }
        $dims = implode("\n", $lines);

        $profile = implode("\n", array_filter([
            'Nama: '.$result->name,
            'Organisasi/Desa/Usaha: '.($result->organization ?? '-'),
            $result->business_type ? 'Jenis Badan Usaha: '.$result->business_type : null,
            $result->business_sector ? 'Sektor Usaha: '.$result->business_sector : null,
            $result->member_count ? 'Jumlah Anggota/Pelaku Usaha: '.$result->member_count : null,
            'Kabupaten/Kota: '.($result->regency ?? '-'),
            'Provinsi: '.($result->province ?? '-'),
            'Deskripsi: '.($result->profile_description ?? '-'),
        ]));

        $sub = '';
        if ($track->slug === 'daya-saing-destinasi' && ! empty($computed['subindexes'])) {
            $parts = [];
            foreach ($computed['subindexes'] as $name => $score) {
                $parts[] = '- '.$name.': '.$score.'/100';
            }
            $sub = "\nSubskor per subindeks:\n".implode("\n", $parts);
        }

        $extra = match ($track->slug) {
            'pariwisata' => "\nTambahan wajib: segmen_pasar[] (3 segmen paling cocok), strategi_pemasaran[] (3 strategi konkret), opsi_branding[3] (masing-masing: nama, tagline, alasan).",
            'ekonomi-desa' => "\nTambahan wajib: produk_unggulan_potensial[] (3 produk + alasan), segmen_pasar[] (3 segmen), strategi_pemasaran[] (3 strategi), model_bisnis_disarankan[] (2 model + alasan).",
            'daya-saing-destinasi' => "\nTambahan wajib: catatan_kategori[5] (satu catatan per subindeks A–E), prioritas_kebijakan[3] (rekomendasi kebijakan konkret untuk pemda), catatan_ttdi (wajib berisi disclaimer: ini penilaian mandiri skala kawasan/kabupaten, bukan replikasi 102 indikator resmi WEF dan bukan skor TTDI resmi; jangan pernah menyebutnya \"skor TTDI\").",
            'regeneratif' => "\nTambahan wajib: posisi_spektrum (satu dari: Ekstraktif/Berkelanjutan/Regeneratif Awal/Regeneratif Matang + penjelasan jujur), rekomendasi_regeneratif[4] (langkah menuju regeneratif matang), sertifikasi_relevan[] (sertifikasi yang relevan). PENTING: jangan otomatis memberi nada positif hanya karena istilah regeneratif terdengar baik — nilai secara kritis berdasarkan skor rendah.",
            default => '',
        };

        return <<<PROMPT
            Anda adalah konsultan senior GODEVI (PT Banua Wisata Lestari) — ahli pariwisata regeneratif, tata kelola destinasi, dan ekonomi desa. Tulis dalam Bahasa Indonesia formal, spesifik, tidak generik. Hindari kalimat template.

            INSTRUKSI KRITIS:
            - Skor di bawah dihitung sistem dan bersifat final. Jangan menghitung ulang skor, jangan mengubah kategori.
            - Jawab HANYA dengan JSON valid tanpa markdown, tanpa teks di luar JSON.
            - Kunci JSON wajib: ringkasan (string), kekuatan (tepat 3 string), tantangan (tepat 3 string), langkah_prioritas (tepat 5 string berurutan dari paling mendesak), layanan_godevi_disarankan[] (layanan GODEVI yang relevan).{$extra}

            PROFIL:
            {$profile}

            SKOR DIMENSI:
            {$dims}

            SKOR TOTAL: {$computed['total']}/100 — KATEGORI: {$computed['band']}
            {$sub}
            PROMPT;
    }

    /**
     * @return array{ok: bool, report?: array, error?: string, usage?: array}
     */
    public static function generate(AssessmentResult $result, array $computed): array
    {
        $driver = config('ai.driver', 'deepseek');
        $prompt = self::buildPrompt($result, $computed);

        for ($attempt = 1; $attempt <= 2; $attempt++) {
            $res = $driver === 'claude'
                ? self::callClaude($prompt)
                : self::callDeepSeek($prompt);

            if (! $res['ok']) {
                Log::warning("AI report attempt $attempt gagal: ".$res['error']);

                continue;
            }

            $report = self::extractJson($res['text']);
            $err = self::validate($result->track->slug, $report);
            if ($err === null) {
                Log::info('AI report OK', [
                    'result' => $result->uuid,
                    'driver' => $driver,
                    'model' => config('ai.model'),
                    'usage' => $res['usage'] ?? null,
                ]);

                return ['ok' => true, 'report' => $report, 'usage' => $res['usage'] ?? null];
            }

            Log::warning("AI report attempt $attempt JSON tidak valid: $err");
        }

        return ['ok' => false, 'error' => $err ?? ($res['error'] ?? 'unknown')];
    }

    protected static function callDeepSeek(string $prompt): array
    {
        try {
            $resp = Http::timeout(config('ai.timeout', 120))
                ->withToken(config('ai.deepseek_key'))
                ->post(rtrim(config('ai.deepseek_base'), '/').'/chat/completions', [
                    'model' => config('ai.model', 'deepseek-flash'),
                    'messages' => [
                        ['role' => 'system', 'content' => 'Anda konsultan senior GODEVI. Jawab hanya JSON valid tanpa markdown.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.7,
                ]);

            if (! $resp->successful()) {
                return ['ok' => false, 'error' => 'deepseek_http_'.$resp->status()];
            }

            $json = $resp->json();

            return [
                'ok' => true,
                'text' => $json['choices'][0]['message']['content'] ?? '',
                'usage' => $json['usage'] ?? null,
            ];
        } catch (\Throwable $th) {
            return ['ok' => false, 'error' => 'deepseek_exc: '.$th->getMessage()];
        }
    }

    protected static function callClaude(string $prompt): array
    {
        try {
            $resp = Http::timeout(config('ai.timeout', 120))
                ->withHeaders([
                    'x-api-key' => config('ai.claude_key'),
                    'anthropic-version' => config('ai.claude_version', '2023-06-01'),
                ])
                ->post(rtrim(config('ai.claude_base'), '/').'/v1/messages', [
                    'model' => config('ai.model'),
                    'max_tokens' => 3000,
                    'system' => 'Anda konsultan senior GODEVI. Jawab hanya JSON valid tanpa markdown.',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                ]);

            if (! $resp->successful()) {
                return ['ok' => false, 'error' => 'claude_http_'.$resp->status()];
            }

            $json = $resp->json();
            $text = '';
            foreach ($json['content'] ?? [] as $block) {
                if (($block['type'] ?? '') === 'text') {
                    $text .= $block['text'];
                }
            }

            return ['ok' => true, 'text' => $text, 'usage' => $json['usage'] ?? null];
        } catch (\Throwable $th) {
            return ['ok' => false, 'error' => 'claude_exc: '.$th->getMessage()];
        }
    }

    protected static function extractJson(string $text): mixed
    {
        $text = trim($text);
        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        // Fallback: ambil objek JSON pertama yang ditemukan.
        if (preg_match('/\{.*\}/s', $text, $m)) {
            $decoded = json_decode($m[0], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    protected static function validate(string $slug, mixed $report): ?string
    {
        if (! is_array($report)) {
            return 'bukan_json';
        }
        foreach (self::requiredKeys($slug) as $key) {
            if (! array_key_exists($key, $report)) {
                return 'kunci_hilang:'.$key;
            }
        }
        foreach (['kekuatan' => 3, 'tantangan' => 3, 'langkah_prioritas' => 5] as $key => $n) {
            if (! is_array($report[$key] ?? null) || count($report[$key]) !== $n) {
                return 'jumlah_salah:'.$key;
            }
        }
        if (empty($report['ringkasan']) || ! is_string($report['ringkasan'])) {
            return 'ringkasan_kosong';
        }

        return null;
    }
}
