<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/** Satu panggilan API AI beserta token yang dipakai (lihat AiReportService::call). */
class AiUsageLog extends Model
{
    public const UPDATED_AT = null;

    public const PURPOSES = [
        'report' => 'Laporan asesmen',
        'translate_report' => 'Terjemahan laporan (EN)',
        'translate_content' => 'Terjemahan soal & jalur',
    ];

    protected $fillable = [
        'driver', 'model', 'purpose', 'assessment_result_id', 'ok', 'error',
        'prompt_tokens', 'cache_hit_tokens', 'completion_tokens', 'reasoning_tokens', 'total_tokens', 'duration_ms',
    ];

    protected $casts = ['ok' => 'boolean'];

    public function result()
    {
        return $this->belongsTo(AssessmentResult::class, 'assessment_result_id');
    }

    /**
     * Catat satu panggilan. Format usage DeepSeek (OpenAI) & Claude dinormalkan.
     * Gagal mencatat tidak boleh menggagalkan pembuatan laporan.
     */
    public static function record(array $response, string $purpose, ?int $resultId, int $durationMs): void
    {
        $usage = $response['usage'] ?? [];

        try {
            self::create([
                'driver' => config('ai.driver', 'deepseek'),
                'model' => \App\Services\AiReportService::model(),
                'purpose' => $purpose,
                'assessment_result_id' => $resultId,
                'ok' => (bool) ($response['ok'] ?? false),
                'error' => isset($response['error']) ? mb_substr((string) $response['error'], 0, 300) : null,
                'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? $usage['input_tokens'] ?? 0),
                'cache_hit_tokens' => (int) ($usage['prompt_cache_hit_tokens'] ?? $usage['cache_read_input_tokens'] ?? 0),
                'completion_tokens' => (int) ($usage['completion_tokens'] ?? $usage['output_tokens'] ?? 0),
                'reasoning_tokens' => (int) ($usage['completion_tokens_details']['reasoning_tokens'] ?? 0),
                'total_tokens' => (int) ($usage['total_tokens'] ?? (($usage['input_tokens'] ?? 0) + ($usage['output_tokens'] ?? 0))),
                'duration_ms' => $durationMs,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal mencatat pemakaian AI: '.$e->getMessage());
        }
    }
}
