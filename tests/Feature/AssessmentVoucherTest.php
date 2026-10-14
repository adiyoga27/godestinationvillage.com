<?php

namespace Tests\Feature;

use App\Jobs\GenerateAssessmentReport;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\AssessmentVoucher;
use App\Models\User;
use App\Services\AssessmentPaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Harga asesmen diatur admin + kode voucher (gratis / persen / nominal). */
class AssessmentVoucherTest extends TestCase
{
    use DatabaseTransactions;

    private AssessmentTrack $track;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Bus::fake([GenerateAssessmentReport::class]);
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);
        $this->track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $this->track->update(['price' => 200000]);
    }

    private function lockedResult(): AssessmentResult
    {
        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $this->track->id, 'name' => 'Guest', 'organization' => 'Desa Voucher',
            'phone' => '081200000777', 'email' => 'guest@example.com',
            'answers' => [], 'dimension_scores' => [], 'total_score' => 50, 'band' => 'Berkembang', 'is_unlocked' => false,
        ]);
        AssessmentPaymentService::ensureInvoice($result);

        return $result->fresh();
    }

    private function voucher(array $attrs = []): AssessmentVoucher
    {
        return AssessmentVoucher::create($attrs + ['code' => 'TES'.strtoupper(Str::random(6)), 'type' => 'percent', 'value' => 25, 'is_active' => true]);
    }

    public function test_percent_voucher_discounts_the_pending_invoice(): void
    {
        $result = $this->lockedResult();
        $invoice = $result->orders()->first();
        $voucher = $this->voucher();

        $this->post(route('assessment.voucher', $result->uuid), ['voucher_code' => strtolower($voucher->code)])
            ->assertRedirect(route('assessment.result', $result->uuid))
            ->assertSessionHas('status');

        $order = $invoice->fresh();
        $this->assertSame(150000, $order->amount);
        $this->assertSame(200000, $order->original_amount);
        $this->assertSame(50000, $order->discount_amount);
        $this->assertSame($voucher->code, $order->voucher_code);
        $this->assertFalse($result->fresh()->is_unlocked);

        $this->get(route('assessment.result', $result->uuid))->assertOk()
            ->assertSee('Rp 150.000')
            ->assertSee($voucher->code)
            ->assertSee('line-through', false);

        // Lepas voucher → harga normal lagi.
        $this->post(route('assessment.voucher.remove', $result->uuid))->assertRedirect();
        $this->assertSame(200000, $invoice->fresh()->amount);
        $this->assertNull($invoice->fresh()->voucher_id);
    }

    public function test_free_voucher_unlocks_results_without_payment(): void
    {
        $result = $this->lockedResult();
        $voucher = $this->voucher(['type' => 'free', 'value' => 100]);

        $this->post(route('assessment.voucher', $result->uuid), ['voucher_code' => $voucher->code])
            ->assertRedirect(route('assessment.result', $result->uuid));

        $result->refresh();
        $this->assertTrue($result->is_unlocked);
        $order = $result->latestOrder;
        $this->assertSame('paid', $order->status);
        $this->assertSame(0, $order->amount);
        $this->assertSame('voucher', $order->gateway);
        $this->assertSame('Gratis (voucher)', $result->paymentLabel());
        $this->assertSame(1, $voucher->usedCount());
        Bus::assertDispatched(GenerateAssessmentReport::class);

        $this->get(route('assessment.result', $result->uuid))->assertOk()->assertSee('Peta kesiapan');
    }

    public function test_fixed_voucher_larger_than_price_makes_it_free(): void
    {
        $result = $this->lockedResult();
        $voucher = $this->voucher(['type' => 'fixed', 'value' => 500000]);

        $out = AssessmentPaymentService::applyVoucher($result, $voucher->code);

        $this->assertTrue($out['ok']);
        $this->assertTrue($result->fresh()->is_unlocked);
        $this->assertSame(200000, $result->fresh()->latestOrder->discount_amount);
    }

    public function test_invoice_with_midtrans_token_is_replaced_by_new_invoice(): void
    {
        $result = $this->lockedResult();
        $old = $result->orders()->first();
        $old->update(['gateway_ref' => 'snap-token-lama']);

        $out = AssessmentPaymentService::applyVoucher($result, $this->voucher(['value' => 10])->code);

        $this->assertTrue($out['ok']);
        $this->assertSame('cancelled', $old->fresh()->status);
        $new = $result->orders()->where('status', 'pending')->latest('id')->first();
        $this->assertNotSame($old->code, $new->code);
        $this->assertSame(180000, $new->amount);
        $this->assertNull($new->gateway_ref);

        // Link "Bayar Sekarang" di email invoice lama → halaman hasil (invoice baru), bukan error.
        $this->get(route('assessment.payment', $old->code))
            ->assertRedirect(route('assessment.result', $result->uuid))
            ->assertSessionMissing('error');
    }

    public function test_invalid_vouchers_are_rejected(): void
    {
        $result = $this->lockedResult();
        $other = AssessmentTrack::where('slug', 'regeneratif')->firstOrFail();

        $cases = [
            'TIDAKADA-XYZ' => 'tidak ditemukan',
            $this->voucher(['is_active' => false])->code => 'tidak aktif',
            $this->voucher(['expires_at' => now()->subDay()])->code => 'kedaluwarsa',
            $this->voucher(['starts_at' => now()->addDay()])->code => 'belum berlaku',
            $this->voucher(['track_ids' => [$other->id]])->code => 'tidak berlaku untuk jalur',
        ];

        $full = $this->voucher(['max_uses' => 1]);
        $paid = $this->lockedResult();
        $paid->orders()->first()->update(['status' => 'paid', 'voucher_id' => $full->id]);
        $cases[$full->code] = 'Kuota';

        foreach ($cases as $code => $message) {
            $this->post(route('assessment.voucher', $result->uuid), ['voucher_code' => $code])
                ->assertSessionHas('voucher_error', fn ($m) => str_contains($m, $message));
        }

        $this->assertSame(200000, $result->orders()->first()->amount);
        $this->assertFalse($result->fresh()->is_unlocked);
    }

    public function test_admin_sets_prices_and_manages_vouchers(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();

        $this->actingAs($admin)->get(route('assessment-prices.index'))->assertOk()->assertSee('name="prices['.$this->track->id.']"', false);
        $this->actingAs($admin)->put(route('assessment-prices.update'), ['prices' => [$this->track->id => 99000]])
            ->assertRedirect(route('assessment-prices.index'));
        $this->assertSame(99000, (int) $this->track->fresh()->price);

        $this->actingAs($admin)->get(route('assessment-vouchers.create'))->assertOk();
        $this->actingAs($admin)->post(route('assessment-vouchers.store'), ['code' => 'diskon/50%', 'type' => 'percent', 'value' => 10])
            ->assertSessionHasErrors('code');
        $this->actingAs($admin)->post(route('assessment-vouchers.store'), ['code' => 'PERSEN', 'type' => 'percent', 'value' => 150])
            ->assertSessionHasErrors('value');

        $this->actingAs($admin)->post(route('assessment-vouchers.store'), [
            'code' => 'desa-2026', 'type' => 'free', 'track_ids' => [$this->track->id], 'max_uses' => 5, 'is_active' => '1',
        ])->assertRedirect(route('assessment-vouchers.index'));

        $voucher = AssessmentVoucher::where('code', 'DESA-2026')->firstOrFail();
        $this->assertSame('free', $voucher->type);
        $this->assertSame([$this->track->id], $voucher->track_ids);
        $this->actingAs($admin)->get(route('assessment-vouchers.index'))->assertOk()->assertSee('DESA-2026')->assertSee('0 / 5');

        // Voucher yang sudah tercatat di invoice hanya dinonaktifkan, tidak dihapus.
        $result = $this->lockedResult();
        AssessmentPaymentService::applyVoucher($result, 'DESA-2026');
        $this->actingAs($admin)->delete(route('assessment-vouchers.destroy', $voucher->id))->assertRedirect();
        $this->assertFalse($voucher->fresh()->is_active);

        $this->actingAs($admin)->get(route('assessment-results.show', $result->id))->assertOk()->assertSee('Voucher <strong>DESA-2026</strong>', false);
    }

    public function test_admin_bulk_generates_single_use_codes_and_exports_csv(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();

        $this->actingAs($admin)->get(route('assessment-vouchers.create'))->assertOk()
            ->assertSee('name="quantity"', false)
            ->assertSee('class="voucher-check"', false)
            ->assertDontSee('input-helper', false);

        $this->actingAs($admin)->post(route('assessment-vouchers.store'), [
            'mode' => 'bulk', 'quantity' => 2000, 'type' => 'free',
        ])->assertSessionHasErrors('quantity');

        $response = $this->actingAs($admin)->post(route('assessment-vouchers.store'), [
            'mode' => 'bulk', 'quantity' => 25, 'prefix' => 'bangli', 'description' => 'Pelatihan Bangli',
            'type' => 'percent', 'value' => 50, 'track_ids' => [$this->track->id], 'max_uses' => 1, 'is_active' => '1',
        ]);

        $vouchers = AssessmentVoucher::where('description', 'Pelatihan Bangli')->get();
        $this->assertCount(25, $vouchers);
        $this->assertCount(25, $vouchers->pluck('code')->unique());
        $batch = $vouchers->first()->batch;
        $response->assertRedirect(route('assessment-vouchers.index', ['batch' => $batch]));
        foreach ($vouchers as $v) {
            $this->assertMatchesRegularExpression('/^BANGLI-[A-HJ-NP-Z2-9]{6}$/', $v->code);
            $this->assertSame($batch, $v->batch);
            $this->assertSame(50, $v->value);
            $this->assertSame(1, $v->max_uses);
            $this->assertSame([$this->track->id], $v->track_ids);
        }

        // Kode sekali pakai: dipakai satu guest → guest lain ditolak.
        $code = $vouchers->first()->code;
        $first = $this->lockedResult();
        AssessmentPaymentService::applyVoucher($first, $code);
        $first->latestOrder->update(['status' => 'paid']);
        $this->assertFalse(AssessmentPaymentService::applyVoucher($this->lockedResult(), $code)['ok']);

        $this->actingAs($admin)->get(route('assessment-vouchers.index', ['batch' => $batch]))->assertOk()
            ->assertSee($code)->assertSee('25 kode');

        $csv = $this->actingAs($admin)->get(route('assessment-vouchers.export', ['batch' => $batch]))->assertOk()->streamedContent();
        $this->assertSame(26, count(array_filter(explode("\n", $csv))));
        $this->assertStringContainsString($code, $csv);

        $this->actingAs($admin)->post(route('assessment-vouchers.batch-toggle'), ['batch' => $batch, 'active' => 0])->assertRedirect();
        $this->assertSame(0, AssessmentVoucher::where('batch', $batch)->where('is_active', true)->count());
    }
}
