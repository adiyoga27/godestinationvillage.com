<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AiUsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Asesmen > Saldo & Pemakaian AI: saldo langsung dari API DeepSeek (/user/balance, cache 5 menit)
 * + pemakaian token dari ai_usage_logs (DeepSeek tidak punya API riwayat pemakaian).
 */
class AiUsageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if ($request->boolean('refresh')) {
            Cache::forget('deepseek-balance');
        }

        $sum = fn ($q) => $q->selectRaw('count(*) calls, sum(ok = 0) failed, sum(prompt_tokens) prompt, sum(cache_hit_tokens) cache_hit, sum(completion_tokens) completion, sum(reasoning_tokens) reasoning, sum(total_tokens) total')->first();

        return view('backend.ai_usage.index', [
            'balance' => Cache::remember('deepseek-balance', now()->addMinutes(5), fn () => $this->balance()),
            'periods' => [
                'Hari ini' => $sum(AiUsageLog::where('created_at', '>=', today())),
                '7 hari' => $sum(AiUsageLog::where('created_at', '>=', today()->subDays(6))),
                '30 hari' => $sum(AiUsageLog::where('created_at', '>=', today()->subDays(29))),
                'Semua' => $sum(AiUsageLog::query()),
            ],
            'byPurpose' => AiUsageLog::selectRaw('purpose, count(*) calls, sum(ok = 0) failed, sum(total_tokens) total')->groupBy('purpose')->get(),
            'daily' => AiUsageLog::where('created_at', '>=', today()->subDays(29))
                ->selectRaw('date(created_at) day, count(*) calls, sum(ok = 0) failed, sum(prompt_tokens) prompt, sum(completion_tokens) completion, sum(total_tokens) total')
                ->groupBy(DB::raw('date(created_at)'))->orderByDesc('day')->get(),
            'recent' => AiUsageLog::with('result:id,uuid,organization,name')->latest('id')->limit(50)->get(),
        ]);
    }

    /** @return array{ok: bool, available?: bool, infos?: array, error?: string, checked_at: string} */
    private function balance(): array
    {
        $checked = now()->toDateTimeString();
        if (blank(config('ai.deepseek_key'))) {
            return ['ok' => false, 'error' => 'DEEPSEEK_API_KEY belum diisi di .env', 'checked_at' => $checked];
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
    }
}
