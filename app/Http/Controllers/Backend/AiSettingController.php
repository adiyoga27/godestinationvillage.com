<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AiAccountService;
use App\Support\AiSettings;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Asesmen > Pengaturan AI: pilih AI aktif untuk laporan asesmen, API key & model
 * DeepSeek / Claude (menimpa .env; key terenkripsi). Khusus super admin.
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

    public function edit()
    {
        $sources = collect(array_keys(AiSettings::FIELDS))->mapWithKeys(fn ($k) => [$k => AiSettings::source($k)]);

        return view('backend.ai_settings.edit', [
            'sources' => $sources,
            'masked' => [
                'deepseek_key' => AiSettings::mask(config('ai.deepseek_key')),
                'claude_key' => AiSettings::mask(config('ai.claude_key')),
                'claude_admin_key' => AiSettings::mask(config('ai.claude_admin_key')),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'driver' => ['required', Rule::in(array_keys(AiSettings::DRIVERS))],
            'deepseek_model' => 'nullable|string|max:80',
            'claude_model' => 'nullable|string|max:80',
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
        unset($data['clear']);

        if ($data['driver'] === 'claude' && blank($data['claude_key']) && blank(config('ai.claude_key'))) {
            return back()->withInput()->withErrors(['claude_key' => 'Isi API key Claude sebelum menjadikannya AI aktif.']);
        }
        if ($data['driver'] === 'deepseek' && blank($data['deepseek_key']) && blank(config('ai.deepseek_key'))) {
            return back()->withInput()->withErrors(['deepseek_key' => 'Isi API key DeepSeek sebelum menjadikannya AI aktif.']);
        }

        AiSettings::save($data, $request->user()->id);
        AiAccountService::forget();

        return redirect()->route('ai-settings.edit')->with('status', 'Pengaturan AI disimpan. AI aktif untuk asesmen: '.AiSettings::DRIVERS[$data['driver']].'.');
    }

    /** Tes koneksi & key tanpa memakai token (saldo DeepSeek / daftar model Claude). */
    public function test(string $driver)
    {
        abort_unless(array_key_exists($driver, AiSettings::DRIVERS), 404);

        if ($driver === 'deepseek') {
            $res = AiAccountService::deepseek(true);
            $ok = $res['ok'];
            $msg = $ok ? 'DeepSeek terhubung. Saldo: '.collect($res['infos'])->map(fn ($i) => $i['currency'].' '.$i['total_balance'])->implode(', ') : 'DeepSeek gagal: '.$res['error'];
        } else {
            $res = AiAccountService::claude(true);
            $ok = $res['key_ok'];
            $msg = $ok ? 'Claude terhubung (API key valid).' : 'Claude gagal: '.($res['key_error'] ?? '-');
            if (isset($res['cost'])) {
                $msg .= $res['cost']['ok'] ? ' Biaya bulan ini: $'.number_format($res['cost']['month_usd'], 2).'.' : ' Admin key: '.$res['cost']['error'].'.';
            }
        }

        return back()->with($ok ? 'status' : 'error', $msg);
    }
}
