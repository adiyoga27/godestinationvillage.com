<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AiCredit;
use App\Models\AiUsageLog;
use App\Services\AiAccountService;
use App\Support\AiSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Asesmen > Pengaturan AI: pilih AI aktif untuk laporan asesmen, API key & model
 * DeepSeek / Claude (menimpa .env; key terenkripsi), saldo/biaya, dan pemakaian token.
 * Khusus super admin.
 */
class AiSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless($request->user()->role_id == 1, 403);

            return $next($request);
        });
    }

    public function edit(Request $request)
    {
        $fresh = $request->boolean('refresh');
        $sum = fn ($q) => $q->selectRaw('count(*) calls, sum(ok = 0) failed, sum(prompt_tokens) prompt, sum(cache_hit_tokens) cache_hit, sum(completion_tokens) completion, sum(reasoning_tokens) reasoning, sum(total_tokens) total, sum(charged) charged')->first();

        return view('backend.ai_settings.edit', [
            'sources' => collect(array_keys(AiSettings::FIELDS))->mapWithKeys(fn ($k) => [$k => AiSettings::source($k)]),
            'savedAt' => DB::table('ai_settings')->whereNotNull('value')->pluck('updated_at', 'key'),
            'masked' => [
                'deepseek_key' => AiSettings::mask(config('ai.deepseek_key')),
                'claude_key' => AiSettings::mask(config('ai.claude_key')),
                'claude_admin_key' => AiSettings::mask(config('ai.claude_admin_key')),
            ],
            'accounts' => ['deepseek' => AiAccountService::deepseek($fresh), 'claude' => AiAccountService::claude($fresh)],
            'models' => ['deepseek' => AiAccountService::models('deepseek', $fresh), 'claude' => AiAccountService::models('claude', $fresh)],
            'credits' => AiCredit::all()->keyBy('driver'),
            'monthByDriver' => AiUsageLog::where('created_at', '>=', now()->startOfMonth())
                ->selectRaw('driver, count(*) calls, sum(total_tokens) total, sum(charged) charged')->groupBy('driver')->get()->keyBy('driver'),
            'periods' => [
                'Hari ini' => $sum(AiUsageLog::where('created_at', '>=', today())),
                '7 hari' => $sum(AiUsageLog::where('created_at', '>=', today()->subDays(6))),
                '30 hari' => $sum(AiUsageLog::where('created_at', '>=', today()->subDays(29))),
                'Total' => $sum(AiUsageLog::query()),
            ],
            'byPurpose' => AiUsageLog::selectRaw('purpose, count(*) calls, sum(ok = 0) failed, sum(total_tokens) total')->groupBy('purpose')->orderByDesc('total')->get(),
            'daily' => AiUsageLog::where('created_at', '>=', today()->subDays(29))
                ->selectRaw('date(created_at) day, count(*) calls, sum(ok = 0) failed, sum(total_tokens) total')
                ->groupBy(DB::raw('date(created_at)'))->get()->keyBy(fn ($r) => (string) $r->day),
            'recent' => AiUsageLog::with('result:id,uuid,organization,name')->latest('created_at')->latest('id')->limit(30)->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'driver' => ['required', Rule::in(array_keys(AiSettings::DRIVERS))],
            'deepseek_model' => 'nullable|string|max:80',
            'claude_model' => 'nullable|string|max:80',
            'deepseek_model_custom' => 'nullable|required_if:deepseek_model,__custom|string|max:80',
            'claude_model_custom' => 'nullable|required_if:claude_model,__custom|string|max:80',
            'tab' => 'nullable|in:deepseek,claude',
            'timeout' => 'nullable|integer|min:30|max:600',
            'deepseek_key' => 'nullable|string|max:300',
            'claude_key' => 'nullable|string|max:300',
            'claude_admin_key' => 'nullable|string|max:300',
            'clear' => 'nullable|array',
        ]);

        // Kolom key dibiarkan kosong = tidak diubah; centang "hapus" = kembali ke .env.
        foreach (['deepseek_key', 'claude_key', 'claude_admin_key'] as $key) {
            $data[$key] = in_array($key, $data['clear'] ?? [], true) ? '' : ($data[$key] ?? null);
        }
        // Model "ketik manual" (tidak ada di daftar API).
        foreach (['deepseek', 'claude'] as $provider) {
            if (($data[$provider.'_model'] ?? null) === '__custom') {
                $data[$provider.'_model'] = $data[$provider.'_model_custom'];
            }
            unset($data[$provider.'_model_custom']);
        }
        $tab = $data['tab'] ?? null;
        unset($data['clear'], $data['tab']);

        // Simpan dulu apa pun yang diisi (key tidak boleh hilang hanya karena AI aktif belum lengkap).
        AiSettings::save($data, $request->user()->id);
        AiAccountService::forget();

        // Tombol "Simpan & tes koneksi": tes langsung memakai key yang baru disimpan.
        if (in_array($testDriver = $request->input('test'), array_keys(AiSettings::DRIVERS), true)) {
            [$ok, $msg] = $this->testConnection($testDriver);

            return redirect()->route('ai-settings.edit')->with('ai_tab', $testDriver)
                ->with($ok ? 'status' : 'error', 'Pengaturan disimpan. '.$msg);
        }

        $redirect = redirect()->route('ai-settings.edit')->with('ai_tab', $tab)
            ->with('status', 'Pengaturan AI disimpan. AI aktif untuk asesmen: '.AiSettings::DRIVERS[$data['driver']].'.');

        // AI aktif tanpa API key → laporan akan gagal; beri peringatan (bukan menolak simpan).
        if (blank(config('ai.'.$data['driver'].'_key'))) {
            $redirect->with('error', 'Perhatian: '.AiSettings::DRIVERS[$data['driver']].' dipilih sebagai AI aktif tetapi belum punya API key — laporan asesmen akan gagal sampai key diisi.');
        }

        return $redirect;
    }

    /** Tes koneksi & key tersimpan tanpa memakai token (saldo DeepSeek / daftar model Claude). */
    public function test(string $driver)
    {
        abort_unless(array_key_exists($driver, AiSettings::DRIVERS), 404);
        [$ok, $msg] = $this->testConnection($driver);

        return back()->with('ai_tab', $driver)->with($ok ? 'status' : 'error', $msg);
    }

    /** @return array{0: bool, 1: string} */
    private function testConnection(string $driver): array
    {
        if ($driver === 'deepseek') {
            $res = AiAccountService::deepseek(true);

            return [$res['ok'], $res['ok']
                ? 'DeepSeek terhubung. Saldo: '.collect($res['infos'])->map(fn ($i) => $i['currency'].' '.$i['total_balance'])->implode(', ').'.'
                : 'DeepSeek gagal: '.$res['error']];
        }

        $res = AiAccountService::claude(true);
        $msg = $res['key_ok'] ? 'Claude terhubung (API key valid).' : 'Claude gagal: '.($res['key_error'] ?? '-');
        if (isset($res['cost'])) {
            $msg .= $res['cost']['ok'] ? ' Biaya bulan ini: US$ '.number_format($res['cost']['month_usd'], 2).'.' : ' Admin key: '.$res['cost']['error'].'.';
        }

        return [$res['key_ok'], $msg];
    }
}
