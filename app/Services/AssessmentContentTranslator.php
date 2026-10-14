<?php

namespace App\Services;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;

/**
 * Isi versi English (*_en) jalur & soal asesmen dengan AI, dari teks Bahasa Indonesia.
 * Default hanya kolom yang masih kosong — terjemahan yang sudah dirapikan admin tidak ditimpa.
 */
class AssessmentContentTranslator
{
    public const TRACK_FIELDS = ['name', 'tagline', 'description', 'target_audience'];

    /**
     * @return array{ok: bool, filled: int, error?: string}
     */
    public static function translateTrack(AssessmentTrack $track, bool $overwrite = false): array
    {
        $needs = fn ($model, string $field) => filled($model->{$field}) && ($overwrite || blank($model->{$field.'_en'}));

        $payload = ['track' => [], 'dimensions' => [], 'questions' => []];
        foreach (self::TRACK_FIELDS as $field) {
            if ($needs($track, $field)) {
                $payload['track'][$field] = $track->{$field};
            }
        }

        $questions = $track->questions()->get();
        foreach ($questions->groupBy('dimension') as $dimension => $items) {
            if ($overwrite || $items->every(fn ($q) => blank($q->dimension_en))) {
                $payload['dimensions'][$dimension] = $dimension;
            }
        }
        foreach ($questions as $q) {
            $item = [];
            foreach (['question', 'help_text'] as $field) {
                if ($needs($q, $field)) {
                    $item[$field] = $q->{$field};
                }
            }
            if ($item) {
                $payload['questions'][(string) $q->id] = $item;
            }
        }

        $payload = array_filter($payload);
        if (! $payload) {
            return ['ok' => true, 'filled' => 0];
        }

        // Soal dikirim per 20 agar jawaban AI tidak terpotong batas token.
        $chunks = array_filter([array_filter(['track' => $payload['track'] ?? [], 'dimensions' => $payload['dimensions'] ?? []])]);
        foreach (array_chunk($payload['questions'] ?? [], 20, true) as $batch) {
            $chunks[] = ['questions' => $batch];
        }

        $translated = ['track' => [], 'dimensions' => [], 'questions' => []];
        $error = null;
        foreach ($chunks as $chunk) {
            $out = self::ask($chunk);
            if (! is_array($out)) {
                $error = $out;

                continue;
            }
            foreach (array_keys($translated) as $key) {
                $translated[$key] += is_array($out[$key] ?? null) ? $out[$key] : [];
            }
        }

        $filled = 0;
        $trackUpdate = [];
        foreach ($payload['track'] ?? [] as $field => $_) {
            if (is_string($value = $translated['track'][$field] ?? null) && $value !== '') {
                $trackUpdate[$field.'_en'] = $value;
            }
        }
        if ($trackUpdate) {
            $track->update($trackUpdate);
            $filled += count($trackUpdate);
        }

        foreach ($payload['dimensions'] ?? [] as $dimension => $_) {
            if (is_string($value = $translated['dimensions'][$dimension] ?? null) && $value !== '') {
                $filled += AssessmentQuestion::where('track_id', $track->id)->where('dimension', $dimension)->update(['dimension_en' => $value]) > 0 ? 1 : 0;
            }
        }

        foreach ($payload['questions'] ?? [] as $id => $fields) {
            $update = [];
            foreach ($fields as $field => $_) {
                if (is_string($value = $translated['questions'][$id][$field] ?? null) && $value !== '') {
                    $update[$field.'_en'] = $value;
                }
            }
            if ($update) {
                AssessmentQuestion::whereKey($id)->update($update);
                $filled += count($update);
            }
        }

        return $error === null ? ['ok' => true, 'filled' => $filled] : ['ok' => false, 'filled' => $filled, 'error' => $error];
    }

    /** @return array|string hasil terjemahan, atau pesan error */
    protected static function ask(array $payload): array|string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $prompt = <<<PROMPT
            Translate every string VALUE in the JSON below from Indonesian into clear, natural English.
            Context: a self-assessment questionnaire for tourism villages, village enterprises/cooperatives, destinations (WEF TTDI pillars), and regenerative businesses in Indonesia. Respondents rate each statement 1–5.

            RULES:
            - Return ONLY valid JSON with exactly the same keys and structure. Never translate keys.
            - "dimensions" are short section headings: keep them concise (title case). If a value already contains an English term in parentheses, e.g. "Kesiapan ICT (ICT Readiness)", return just the English name ("ICT Readiness").
            - Keep Indonesian institution terms that have no exact equivalent (BUMDes, Pokdarwis, Pemda, Koperasi Merah Putih, DTW) and add a short English gloss in parentheses where helpful.
            - Do not add or drop information.

            JSON:
            {$json}
            PROMPT;

        $err = 'unknown';
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            $res = AiReportService::call($prompt, 'You are a professional Indonesian–English translator. Reply with valid JSON only, no markdown.');
            if (! $res['ok']) {
                $err = $res['error'];

                continue;
            }
            $out = AiReportService::extractJson($res['text']);
            if (is_array($out)) {
                return $out;
            }
            $err = 'bukan_json';
        }

        return $err;
    }
}
