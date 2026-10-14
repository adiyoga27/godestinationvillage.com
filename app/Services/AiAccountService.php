<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Status akun penyedia AI untuk admin: saldo DeepSeek, validitas key & biaya Claude.
 * Anthropic tidak punya API sisa saldo; yang tersedia hanya Cost Report (biaya terpakai,
 * USD) lewat Admin API key. Hasil disimpan 5 menit.
 */
class AiAccountService
{
    public static function forget(): void
    {
        Cache::forget('ai-account.deepseek');
        Cache::forget('ai-account.claude');
    }

    /** @return array{ok: bool, available?: bool, infos?: array, error?: string, checked_at: string} */
    public static function deepseek(bool $fresh = false): array
    {
        $fresh && Cache::forget('ai-account.deepseek');

        return Cache::remember('ai-account.deepseek', now()->addMinutes(5), function () {
            $checked = now()->toDateTimeString();
            if (blank(config('ai.deepseek_key'))) {
                return ['ok' => false, 'error' => 'API key DeepSeek belum diisi.', 'checked_at' => $checked];
            }

            try {
                $resp = Http::timeout(15)->withToken(config('ai.deepseek_key'))->acceptJson()
                    ->get(rtrim(config('ai.deepseek_base'), '/').'/user/balance');
                if (! $resp->successful()) {
                    return ['ok' => false, 'error' => 'HTTP '.$resp->status().($resp->status() === 401 ? ' (API key ditolak)' : ''), 'checked_at' => $checked];
                }

                return ['ok' => true, 'available' => (bool) $resp->json('is_available'), 'infos' => $resp->json('balance_infos') ?? [], 'checked_at' => $checked];
            } catch (\Throwable $e) {
                return ['ok' => false, 'error' => mb_substr($e->getMessage(), 0, 200), 'checked_at' => $checked];
            }
        });
    }

    /**
     * @return array{key_ok: bool, key_error?: string, cost?: array{ok: bool, month_usd?: float, today_usd?: float, error?: string}, checked_at: string}
     */
    public static function claude(bool $fresh = false): array
    {
        $fresh && Cache::forget('ai-account.claude');

        return Cache::remember('ai-account.claude', now()->addMinutes(5), function () {
            $out = ['key_ok' => false, 'checked_at' => now()->toDateTimeString()];
            $base = rtrim(config('ai.claude_base'), '/');
            $version = config('ai.claude_version', '2023-06-01');

            // Validitas API key: daftar model (gratis, tidak memakai token).
            if (blank(config('ai.claude_key'))) {
                $out['key_error'] = 'API key Claude belum diisi.';
            } else {
                try {
                    $resp = Http::timeout(15)->withHeaders(['x-api-key' => config('ai.claude_key'), 'anthropic-version' => $version])
                        ->get($base.'/v1/models', ['limit' => 1]);
                    $out['key_ok'] = $resp->successful();
                    if (! $resp->successful()) {
                        $out['key_error'] = 'HTTP '.$resp->status().($resp->status() === 401 ? ' (API key ditolak)' : '');
                    }
                } catch (\Throwable $e) {
                    $out['key_error'] = mb_substr($e->getMessage(), 0, 200);
                }
            }

            if (filled(config('ai.claude_admin_key'))) {
                $out['cost'] = self::claudeCost($base, $version);
            }

            return $out;
        });
    }

    /** Biaya bulan ini & hari ini (USD) dari Cost Report Admin API. Nilai "amount" dalam sen. */
    private static function claudeCost(string $base, string $version): array
    {
        $start = now('UTC')->startOfMonth();
        $today = now('UTC')->toDateString();
        $month = 0.0;
        $day = 0.0;
        $page = null;

        try {
            for ($i = 0; $i < 5; $i++) {
                $resp = Http::timeout(20)->withHeaders(['x-api-key' => config('ai.claude_admin_key'), 'anthropic-version' => $version])
                    ->get($base.'/v1/organizations/cost_report', array_filter([
                        'starting_at' => $start->format('Y-m-d\TH:i:s\Z'),
                        'bucket_width' => '1d',
                        'limit' => 31,
                        'page' => $page,
                    ]));
                if (! $resp->successful()) {
                    return ['ok' => false, 'error' => 'HTTP '.$resp->status().($resp->status() === 401 ? ' (Admin API key ditolak — harus sk-ant-admin…)' : '')];
                }

                foreach ($resp->json('data') ?? [] as $bucket) {
                    $sum = collect($bucket['results'] ?? [])->sum(fn ($r) => (float) ($r['amount'] ?? 0)) / 100;
                    $month += $sum;
                    if (str_starts_with((string) ($bucket['starting_at'] ?? ''), $today)) {
                        $day += $sum;
                    }
                }

                if (! $resp->json('has_more')) {
                    break;
                }
                $page = $resp->json('next_page');
            }
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => mb_substr($e->getMessage(), 0, 200)];
        }

        return ['ok' => true, 'month_usd' => round($month, 2), 'today_usd' => round($day, 2)];
    }
}
