<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AiUsageLog;
use App\Services\AiAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Asesmen > Saldo & Pemakaian AI: saldo DeepSeek & biaya Claude (AiAccountService, cache 5 menit)
 * + pemakaian token dari ai_usage_logs (penyedia tidak menyediakan riwayat per panggilan).
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
        $fresh = $request->boolean('refresh');

        $sum = fn ($q) => $q->selectRaw('count(*) calls, sum(ok = 0) failed, sum(prompt_tokens) prompt, sum(cache_hit_tokens) cache_hit, sum(completion_tokens) completion, sum(reasoning_tokens) reasoning, sum(total_tokens) total')->first();

        return view('backend.ai_usage.index', [
            'deepseek' => AiAccountService::deepseek($fresh),
            'claude' => AiAccountService::claude($fresh),
            'byDriver' => AiUsageLog::where('created_at', '>=', now()->startOfMonth())
                ->selectRaw('driver, count(*) calls, sum(total_tokens) total')->groupBy('driver')->get()->keyBy('driver'),
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
}
