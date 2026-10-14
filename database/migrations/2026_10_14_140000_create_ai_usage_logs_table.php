<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Catatan setiap panggilan API AI (DeepSeek/Claude) untuk halaman Saldo & Pemakaian AI.
 * DeepSeek tidak menyediakan API riwayat pemakaian, jadi token dicatat dari respons sendiri.
 * Riwayat lama diisi dari arsip ai_meta di assessment_results.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('driver', 20);
            $table->string('model', 80)->nullable();
            // report = laporan asesmen, translate_report = terjemahan laporan, translate_content = terjemahan soal/jalur.
            $table->string('purpose', 30)->default('report');
            $table->unsignedBigInteger('assessment_result_id')->nullable()->index();
            $table->boolean('ok')->default(true);
            $table->string('error', 300)->nullable();
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('cache_hit_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->unsignedInteger('reasoning_tokens')->default(0);
            $table->unsignedInteger('total_tokens')->default(0);
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        $this->backfill();
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_usage_logs');
    }

    private function backfill(): void
    {
        DB::table('assessment_results')->whereNotNull('ai_meta')->orderBy('id')
            ->select(['id', 'ai_meta'])
            ->chunk(200, function ($results) {
                $rows = [];
                foreach ($results as $result) {
                    $meta = json_decode($result->ai_meta, true) ?: [];
                    foreach (['report' => $meta, 'translate_report' => $meta['translation_en'] ?? []] as $purpose => $block) {
                        foreach ($block['attempts'] ?? [] as $attempt) {
                            $usage = $attempt['usage'] ?? [];
                            $rows[] = [
                                'driver' => $block['driver'] ?? $meta['driver'] ?? 'deepseek',
                                'model' => $block['model'] ?? $meta['model'] ?? null,
                                'purpose' => $purpose,
                                'assessment_result_id' => $result->id,
                                'ok' => (bool) ($attempt['ok'] ?? false),
                                'error' => isset($attempt['error']) ? mb_substr((string) $attempt['error'], 0, 300) : null,
                                'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? $usage['input_tokens'] ?? 0),
                                'cache_hit_tokens' => (int) ($usage['prompt_cache_hit_tokens'] ?? $usage['cache_read_input_tokens'] ?? 0),
                                'completion_tokens' => (int) ($usage['completion_tokens'] ?? $usage['output_tokens'] ?? 0),
                                'reasoning_tokens' => (int) ($usage['completion_tokens_details']['reasoning_tokens'] ?? 0),
                                'total_tokens' => (int) ($usage['total_tokens'] ?? (($usage['input_tokens'] ?? 0) + ($usage['output_tokens'] ?? 0))),
                                'duration_ms' => null,
                                'created_at' => isset($attempt['at']) ? Carbon::parse($attempt['at'])->setTimezone(config('app.timezone')) : now(),
                            ];
                        }
                    }
                }
                if ($rows) {
                    DB::table('ai_usage_logs')->insert($rows);
                }
            });
    }
};
