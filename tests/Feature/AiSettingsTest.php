<?php

namespace Tests\Feature;

use App\Jobs\GenerateAssessmentReport;
use App\Models\AiUsageLog;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\User;
use App\Services\AiReportService;
use App\Support\AiSettings;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Pengaturan AI dari admin (driver aktif, key terenkripsi, model) + saldo/biaya penyedia. */
class AiSettingsTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array']);
        DB::table('ai_settings')->delete();
        AiSettings::save([]);
        $this->admin = User::where('role_id', 1)->firstOrFail();
    }

    public function test_admin_sets_active_ai_and_keys_are_encrypted(): void
    {
        $this->actingAs($this->admin)->get(route('ai-settings.edit'))->assertOk()->assertSee('AI aktif untuk asesmen');

        // Claude tanpa key tidak boleh dijadikan aktif.
        config(['ai.claude_key' => null]);
        $this->actingAs($this->admin)->put(route('ai-settings.update'), ['driver' => 'claude'])
            ->assertSessionHasErrors('claude_key');

        $this->actingAs($this->admin)->put(route('ai-settings.update'), [
            'driver' => 'claude', 'claude_key' => 'sk-ant-api-rahasia-1234', 'claude_model' => 'claude-sonnet-5-5', 'timeout' => 200,
        ])->assertRedirect(route('ai-settings.edit'));

        $this->assertSame('claude', config('ai.driver'));
        $this->assertSame('sk-ant-api-rahasia-1234', config('ai.claude_key'));
        $this->assertSame('claude-sonnet-5-5', AiReportService::model());
        $this->assertSame(200, config('ai.timeout'));
        $stored = DB::table('ai_settings')->where('key', 'claude_key')->value('value');
        $this->assertStringNotContainsString('rahasia', $stored);

        // Key tidak pernah tampil utuh; kolom kosong = tidak diubah.
        $this->actingAs($this->admin)->get(route('ai-settings.edit'))->assertOk()
            ->assertDontSee('sk-ant-api-rahasia-1234')->assertSee('sk-ant…1234');
        $this->actingAs($this->admin)->put(route('ai-settings.update'), ['driver' => 'claude', 'claude_key' => ''])->assertRedirect();
        $this->assertSame('sk-ant-api-rahasia-1234', config('ai.claude_key'));

        // Hapus key → kembali ke nilai .env.
        $this->actingAs($this->admin)->put(route('ai-settings.update'), ['driver' => 'deepseek', 'deepseek_key' => 'sk-ds', 'clear' => ['claude_key']])->assertRedirect();
        $this->assertNull(DB::table('ai_settings')->where('key', 'claude_key')->value('value'));
        $this->assertSame('deepseek', config('ai.driver'));
    }

    public function test_non_super_admin_cannot_open_ai_pages(): void
    {
        $staff = User::where('role_id', '!=', 1)->where('role_id', '!=', 3)->first() ?? User::where('role_id', 2)->first();
        $this->assertNotNull($staff);
        $this->actingAs($staff)->get(route('ai-settings.edit'))->assertForbidden();
        $this->actingAs($staff)->get(route('ai-usage.index'))->assertForbidden();
    }

    public function test_report_is_generated_with_claude_when_active(): void
    {
        Mail::fake();
        AiSettings::save(['driver' => 'claude', 'claude_key' => 'sk-ant-api-x', 'claude_model' => 'claude-opus-5-5']);
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $report = array_fill_keys(AiReportService::requiredKeys('pariwisata'), ['x']);
        $report['ringkasan'] = 'Ringkasan Claude';
        $report['kekuatan'] = $report['tantangan'] = ['a', 'b', 'c'];
        $report['langkah_prioritas'] = ['1', '2', '3', '4', '5'];

        Http::fake(['api.anthropic.com/v1/messages' => Http::response([
            'content' => [['type' => 'thinking', 'thinking' => ''], ['type' => 'text', 'text' => json_encode($report)]],
            'stop_reason' => 'end_turn',
            'usage' => ['input_tokens' => 900, 'output_tokens' => 1200, 'cache_read_input_tokens' => 0],
        ])]);

        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Guest', 'organization' => 'Desa Claude',
            'answers' => [], 'dimension_scores' => [], 'total_score' => 70, 'band' => 'Siap', 'is_unlocked' => true,
        ]);
        (new GenerateAssessmentReport($result->uuid))->handle();

        $this->assertSame('Ringkasan Claude', $result->fresh()->ai_report['ringkasan']);
        Http::assertSent(fn (Request $r) => $r->url() === 'https://api.anthropic.com/v1/messages'
            && $r->hasHeader('x-api-key', 'sk-ant-api-x')
            && $r->hasHeader('anthropic-beta', 'server-side-fallback-2026-07-01')
            && $r['model'] === 'claude-opus-5-5'
            && $r['fallbacks'] === 'default'
            && $r['max_tokens'] === 16000);

        $log = AiUsageLog::where('assessment_result_id', $result->id)->firstOrFail();
        $this->assertSame('claude', $log->driver);
        $this->assertSame('claude-opus-5-5', $log->model);
        $this->assertSame(2100, $log->total_tokens);
    }

    public function test_claude_refusal_is_reported_as_failure(): void
    {
        AiSettings::save(['driver' => 'claude', 'claude_key' => 'sk-ant-api-x']);
        Http::fake(['api.anthropic.com/*' => Http::response(['content' => [], 'stop_reason' => 'refusal', 'stop_details' => ['category' => 'cyber']])]);

        $res = AiReportService::call('prompt');

        $this->assertFalse($res['ok']);
        $this->assertSame('claude_refusal: cyber', $res['error']);
    }

    public function test_usage_page_shows_deepseek_balance_and_claude_cost(): void
    {
        AiSettings::save(['driver' => 'deepseek', 'deepseek_key' => 'sk-ds', 'claude_key' => 'sk-ant-api-x', 'claude_admin_key' => 'sk-ant-admin-x']);
        Http::fake([
            'api.deepseek.com/user/balance' => Http::response(['is_available' => true, 'balance_infos' => [['currency' => 'USD', 'total_balance' => '12.34', 'granted_balance' => '0.00', 'topped_up_balance' => '12.34']]]),
            'api.anthropic.com/v1/models*' => Http::response(['data' => [['id' => 'claude-opus-5-5']]]),
            'api.anthropic.com/v1/organizations/cost_report*' => Http::response([
                'data' => [
                    ['starting_at' => now('UTC')->startOfMonth()->format('Y-m-d\T00:00:00\Z'), 'results' => [['currency' => 'USD', 'amount' => '250.5']]],
                    ['starting_at' => now('UTC')->format('Y-m-d\T00:00:00\Z'), 'results' => [['currency' => 'USD', 'amount' => '100']]],
                ],
                'has_more' => false,
            ]),
        ]);

        $page = $this->actingAs($this->admin)->get(route('ai-usage.index', ['refresh' => 1]))->assertOk()
            ->assertSee('USD 12.34')
            ->assertSee('API key valid');
        // 250.5 + 100 sen = US$ 3.51 (hari ini US$ 1.00; tanggal 1 → keduanya hari ini).
        now('UTC')->day === 1 ? $page->assertSee('US$ 3.51') : $page->assertSee('US$ 3.51')->assertSee('hari ini US$ 1.00');

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'cost_report') && $r->hasHeader('x-api-key', 'sk-ant-admin-x'));

        $this->actingAs($this->admin)->post(route('ai-settings.test', 'claude'))->assertSessionHas('status', fn ($m) => str_contains($m, 'API key valid'));
    }
}
