<?php

namespace Tests\Feature;

use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\OurTeam;
use App\Models\User;
use App\Services\AssessmentService;
use App\Jobs\GenerateAssessmentReport;
use App\Services\AiReportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssessmentMail;
use Illuminate\Support\Str;
use Tests\TestCase;

class AssessmentFlowsTest extends TestCase
{
    public function test_index_lists_four_tracks(): void
    {
        $response = $this->get(route('assessment.index'));
        $response->assertStatus(200);

        foreach (['pariwisata', 'ekonomi-desa', 'daya-saing-destinasi', 'regeneratif'] as $slug) {
            $response->assertSee($slug, false);
        }
    }

    public function test_guest_can_complete_pariwisata_assessment(): void
    {
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $questions = $track->activeQuestions()->orderBy('sort_order')->get();
        $this->assertNotEmpty($questions);

        $this->get(route('assessment.intro', $track->slug))->assertStatus(200);

        $phone = '0899'.substr((string) time(), -7);
        $this->post(route('assessment.start', $track->slug), [
            'organization' => 'Desa Test',
            'province' => 'Bali',
            'regency' => 'Kab. Bangli',
            'district' => 'Bangli',
            'subdistrict' => 'Kubu',
            'name' => 'Pengisi Test',
            'phone' => '+62 '.substr($phone, 1),
            'email' => 'tes@example.com',
            'profile_description' => 'Sawah terasering dan tari tradisional.',
        ])->assertRedirect(route('assessment.form', $track->slug));

        $this->get(route('assessment.form', $track->slug))->assertStatus(200);

        $answers = [];
        foreach ($questions as $i => $q) {
            $answers[$q->id] = ($i % 5) + 1;
        }
        $notes = [$questions->first()->id => 'Ada air terjun.'];

        $submit = $this->post(route('assessment.submit', $track->slug), ['answers' => $answers, 'notes' => $notes]);

        try {
            // No. WA tersimpan dalam format 08… agar bisa dicari di Cek Status.
            $result = AssessmentResult::where('phone', $phone)->first();
            $this->assertNotNull($result, 'hasil asesmen tidak tersimpan');
            $submit->assertRedirect(route('assessment.result', $result->uuid));

            $this->assertSame('Kab. Bangli', $result->regency);
            $this->assertSame('Ada air terjun.', $result->dimension_notes[$questions->first()->dimension] ?? null);
            $this->assertTrue($result->total_score >= 0 && $result->total_score <= 100);
            $this->assertNotEmpty($result->band);
            $this->assertFalse($result->is_unlocked);

            // Sebelum bayar: hanya halaman pembayaran, tanpa skor.
            $locked = $this->get(route('assessment.result', $result->uuid));
            $locked->assertStatus(200);
            $locked->assertSee('Bayar Sekarang', false);
            $locked->assertDontSee('Peta kesiapan', false);

            $this->get(route('assessment.status', ['phone' => '62'.substr($phone, 1)]))
                ->assertStatus(200)
                ->assertSee('Desa Test', false)
                ->assertSee('Menunggu pembayaran', false);

            // Setelah lunas: hasil lengkap tampil.
            $result->update(['is_unlocked' => true, 'unlocked_at' => now(), 'report_status' => 'failed']);
            $page = $this->get(route('assessment.result', $result->uuid));
            $page->assertStatus(200);
            $page->assertSee((string) $result->band, false);
            $page->assertSee('Peta kesiapan', false);
            $page->assertSee('Kekuatan', false);
            $page->assertSee('Tantangan', false);
            $page->assertSee('Draf Strategi', false);
        } finally {
            AssessmentResult::where('phone', $phone)->delete();
        }
    }

    public function test_ekonomi_desa_requires_business_profile(): void
    {
        $this->get(route('assessment.intro', 'ekonomi-desa'))
            ->assertStatus(200)
            ->assertSee('Profil Usaha / Koperasi', false)
            ->assertSee('Jenis Badan Usaha', false)
            ->assertSee('Lanjut ke Identifikasi Potensi', false);

        $base = [
            'organization' => 'Koperasi Merah Putih Desa Catur',
            'province' => 'Bali',
            'regency' => 'Kab. Bangli',
            'name' => 'Pengurus Test',
            'phone' => '08123456789',
            'email' => 'tes@example.com',
        ];

        $this->post(route('assessment.start', 'ekonomi-desa'), $base)
            ->assertSessionHasErrors(['business_type', 'business_sector']);

        $this->post(route('assessment.start', 'ekonomi-desa'), $base + [
            'business_type' => 'Koperasi',
            'business_sector' => 'Pertanian & Perkebunan',
            'member_count' => 25,
        ])->assertRedirect(route('assessment.form', 'ekonomi-desa'));

        $this->assertSame('Koperasi', session('assessment_identity_'.AssessmentTrack::where('slug', 'ekonomi-desa')->value('id'))['business_type']);
    }

    public function test_start_requires_location_from_search(): void
    {
        $this->post(route('assessment.start', 'pariwisata'), [
            'organization' => 'Desa Test',
            'name' => 'Pengisi Test',
            'phone' => '08123456789',
            'email' => 'tes@example.com',
        ])->assertSessionHasErrors(['province', 'regency']);
    }

    public function test_form_requires_identity_first(): void
    {
        $this->get(route('assessment.form', 'pariwisata'))
            ->assertRedirect(route('assessment.intro', 'pariwisata'));
    }

    public function test_regeneratif_uses_spectrum_bands(): void
    {
        $track = AssessmentTrack::where('slug', 'regeneratif')->firstOrFail();

        $this->assertSame('Ekstraktif', AssessmentService::bandFor($track, 10)['label']);
        $this->assertSame('Ekstraktif', AssessmentService::bandFor($track, 40)['label']);
        $this->assertSame('Berkelanjutan', AssessmentService::bandFor($track, 50)['label']);
        $this->assertSame('Regeneratif Awal', AssessmentService::bandFor($track, 70)['label']);
        $this->assertSame('Regeneratif Matang', AssessmentService::bandFor($track, 90)['label']);
    }

    public function test_score_formula_matches_brief(): void
    {
        // Brief §3: overall = round((Σ skor_dimensi × bobot) / 5 × 100).
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $questions = $track->activeQuestions()->orderBy('sort_order')->get();
        $this->assertNotEmpty($questions);

        $allThree = [];
        foreach ($questions as $q) {
            $allThree[$q->id] = 3;
        }
        // Pariwisata: bobot 25/20/15/15/15/10 → semua 3 = 60.
        $this->assertSame(60, AssessmentService::compute($track, $questions, $allThree)['total']);

        $allFive = [];
        foreach ($questions as $q) {
            $allFive[$q->id] = 5;
        }
        $this->assertSame(100, AssessmentService::compute($track, $questions, $allFive)['total']);

        $allOne = [];
        foreach ($questions as $q) {
            $allOne[$q->id] = 1;
        }
        $this->assertSame(20, AssessmentService::compute($track, $questions, $allOne)['total']);
    }

    public function test_daya_saing_destinasi_profile_and_seventeen_pillars(): void
    {
        $this->get(route('assessment.intro', 'daya-saing-destinasi'))
            ->assertStatus(200)
            ->assertSee('Profil Kawasan Destinasi', false)
            ->assertSee('Instansi/OPD Pengusul', false)
            ->assertSee('location-detail', false);

        $base = [
            'organization' => 'Kawasan Wisata Kintamani',
            'regency' => 'Bangli',
            'province' => 'Bali',
            'name' => 'Kadis Test',
            'phone' => '08123456789',
            'email' => 'tes@example.com',
        ];

        $this->post(route('assessment.start', 'daya-saing-destinasi'), $base)
            ->assertSessionHasErrors(['institution']);

        $this->post(route('assessment.start', 'daya-saing-destinasi'), $base + [
            'institution' => 'Dinas Pariwisata Kabupaten Bangli',
        ])->assertRedirect(route('assessment.form', 'daya-saing-destinasi'));

        $this->get(route('assessment.form', 'daya-saing-destinasi'))
            ->assertStatus(200)
            ->assertSee('A. Enabling Environment', false)
            ->assertSee('E. T&amp;T Sustainability', false)
            ->assertSee('Bobot 6%', false);

        $track = AssessmentTrack::where('slug', 'daya-saing-destinasi')->firstOrFail();
        $dims = $track->activeQuestions()->orderBy('sort_order')->pluck('dimension')->all();
        $this->assertSame(array_keys(AssessmentService::weightsFor($track)), $dims);
        $this->assertSame($dims, array_merge(...array_values(AssessmentService::TTDI_SUBINDEXES)));
    }

    public function test_regeneratif_entity_profile_and_seven_weighted_dimensions(): void
    {
        $this->get(route('assessment.intro', 'regeneratif'))
            ->assertStatus(200)
            ->assertSee('Profil Usaha/Entitas', false)
            ->assertSee('Jenis Entitas', false)
            ->assertSee('location-search', false);

        $base = [
            'organization' => 'Cafe Kopi Catur',
            'province' => 'Bali',
            'regency' => 'Kab. Bangli',
            'name' => 'Pemilik Test',
            'phone' => '08123456789',
            'email' => 'tes@example.com',
        ];

        $this->post(route('assessment.start', 'regeneratif'), $base)
            ->assertSessionHasErrors(['business_type']);

        $this->post(route('assessment.start', 'regeneratif'), $base + ['business_type' => 'Restoran / Cafe'])
            ->assertRedirect(route('assessment.form', 'regeneratif'));

        $track = AssessmentTrack::where('slug', 'regeneratif')->firstOrFail();
        $questions = $track->activeQuestions()->orderBy('sort_order')->get();
        $weights = AssessmentService::weightsFor($track);
        $this->assertSame(array_keys($weights), $questions->pluck('dimension')->all());
        $this->assertSame(100, array_sum($weights));

        $this->get(route('assessment.form', 'regeneratif'))->assertSee('Bobot 18%', false);
    }

    public function test_every_track_sends_notes_to_ai_and_archives_report(): void
    {
        config(['ai.driver' => 'deepseek', 'ai.deepseek_key' => 'test-key']);
        $report = [];
        Http::fake(function () use (&$report) {
            return Http::response([
                'choices' => [['message' => ['content' => json_encode($report)]]],
                'usage' => ['total_tokens' => 1234],
            ]);
        });

        foreach (['pariwisata', 'ekonomi-desa', 'daya-saing-destinasi', 'regeneratif'] as $slug) {
            $track = AssessmentTrack::where('slug', $slug)->firstOrFail();
            $questions = $track->activeQuestions()->orderBy('sort_order')->get();
            $answers = $questions->mapWithKeys(fn ($q) => [$q->id => 3])->all();
            $computed = AssessmentService::compute($track, $questions, $answers);
            $dimension = $questions->first()->dimension;

            $report = array_fill_keys(AiReportService::requiredKeys($slug), ['x']);
            $report['ringkasan'] = 'Ringkasan '.$slug;
            $report['kekuatan'] = $report['tantangan'] = ['a', 'b', 'c'];
            $report['langkah_prioritas'] = ['1', '2', '3', '4', '5'];

            $result = AssessmentResult::create([
                'uuid' => (string) Str::uuid(),
                'track_id' => $track->id,
                'name' => 'AI Test',
                'organization' => 'Org '.$slug,
                'province' => 'Bali',
                'regency' => 'Kab. Bangli',
                'answers' => $answers,
                'dimension_scores' => $computed['dimensions'],
                'dimension_notes' => [$dimension => 'Catatan khusus '.$slug],
                'total_score' => $computed['total'],
                'band' => $computed['band'],
                'computed_result' => $computed,
                'status' => 'baru',
                'is_unlocked' => true,
                'unlocked_at' => now(),
            ]);

            try {
                (new GenerateAssessmentReport($result->uuid))->handle();
                $result->refresh();

                $this->assertSame('done', $result->report_status, $slug);
                $this->assertSame('Ringkasan '.$slug, $result->ai_report['ringkasan']);
                $this->assertStringContainsString('Catatan pengguna: Catatan khusus '.$slug, $result->ai_prompt);
                $this->assertSame('deepseek', $result->ai_meta['driver']);
                $this->assertSame(1234, $result->ai_meta['usage']['total_tokens']);
                $this->assertSame($computed['total'], $result->computed_result['total']);
                Http::assertSent(fn ($req) => str_contains($req['messages'][1]['content'], 'Catatan khusus '.$slug));
            } finally {
                $result->delete();
            }
        }
    }

    public function test_ekonomi_desa_uses_seven_weighted_dimensions(): void
    {
        $track = AssessmentTrack::where('slug', 'ekonomi-desa')->firstOrFail();
        $questions = $track->activeQuestions()->orderBy('sort_order')->get();

        $weights = AssessmentService::weightsFor($track);
        $this->assertCount(7, $weights);
        $this->assertSame(100, array_sum($weights));
        $this->assertEqualsCanonicalizing(array_keys($weights), $questions->pluck('dimension')->unique()->values()->all());

        // Hanya dimensi berbobot 20% bernilai 5, sisanya 1 → (5×20 + 1×80) / 5 = 36.
        $answers = $questions->mapWithKeys(fn ($q) => [$q->id => $q->dimension === 'Kejelasan Produk/Jasa Unggulan' ? 5 : 1])->all();
        $this->assertSame(36, AssessmentService::compute($track, $questions, $answers)['total']);
    }

    public function test_pariwisata_bands_match_brief(): void
    {
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();

        $this->assertSame('Rintisan', AssessmentService::bandFor($track, 40)['label']);
        $this->assertSame('Berkembang', AssessmentService::bandFor($track, 60)['label']);
        $this->assertSame('Siap', AssessmentService::bandFor($track, 80)['label']);
        $this->assertSame('Unggul', AssessmentService::bandFor($track, 81)['label']);
    }

    public function test_admin_pages_require_auth(): void
    {
        $this->get(route('assessments.index'))->assertRedirect('/login');
        $this->get(route('assessment-results.index'))->assertRedirect('/login');
        $this->get(route('team-dashboard.index'))->assertRedirect('/login');
    }

    /** Respons DeepSeek palsu yang valid untuk jalur apa pun. */
    private function fakeAi(string $slug): void
    {
        config(['ai.driver' => 'deepseek', 'ai.deepseek_key' => 'test-key']);
        $report = array_fill_keys(AiReportService::requiredKeys($slug), [['nama' => 'Opsi A', 'alasan' => 'Alasan A']]);
        $report['ringkasan'] = 'Ringkasan uji';
        $report['kekuatan'] = $report['tantangan'] = ['a', 'b', 'c'];
        $report['langkah_prioritas'] = ['1', '2', '3', '4', '5'];
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => json_encode($report)]]], 'usage' => ['total_tokens' => 10]])]);
    }

    public function test_intro_prefills_email_for_logged_in_user(): void
    {
        $user = User::whereNotNull('email')->first();
        $this->actingAs($user)->get(route('assessment.intro', 'pariwisata'))
            ->assertStatus(200)
            ->assertSee('name="email" value="'.e($user->email).'"', false);

        $this->post(route('assessment.start', 'pariwisata'), [
            'organization' => 'Desa', 'province' => 'Bali', 'regency' => 'Kab. Bangli', 'name' => 'X', 'phone' => '0812',
        ])->assertSessionHasErrors(['email']);
    }

    public function test_admin_manual_approve_sends_paid_and_report_emails(): void
    {
        Mail::fake();
        $this->fakeAi('ekonomi-desa');
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'ekonomi-desa')->firstOrFail();
        $questions = $track->activeQuestions()->get();
        $answers = $questions->mapWithKeys(fn ($q) => [$q->id => 4])->all();
        $computed = AssessmentService::compute($track, $questions, $answers);

        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Approve Test', 'email' => 'approve@example.com',
            'organization' => 'Koperasi Uji', 'answers' => $answers, 'dimension_scores' => $computed['dimensions'],
            'total_score' => $computed['total'], 'band' => $computed['band'], 'status' => 'baru',
        ]);

        try {
            $this->actingAs($admin)->get(route('assessment-results.index'))->assertSee('Approve', false);
            $this->actingAs($admin)->post(route('assessment-results.approve', $result->id), ['approval_note' => 'Transfer BCA'])->assertRedirect();

            $result->refresh();
            $this->assertTrue($result->is_unlocked);
            $this->assertSame($admin->id, $result->approved_by);
            $this->assertSame('paid', $result->latestOrder->status);
            $this->assertSame('manual', $result->latestOrder->payment_type);
            $this->assertSame('done', $result->report_status);
            Mail::assertSent(AssessmentMail::class, fn ($m) => $m->type === 'paid' && $m->hasTo('approve@example.com'));
            Mail::assertSent(AssessmentMail::class, fn ($m) => $m->type === 'report');
            $this->assertSame(['paid', 'report'], array_column($result->email_log, 'type'));

            $this->actingAs($admin)->get(route('assessment-results.show', $result->id))
                ->assertStatus(200)
                ->assertSee('Ringkasan uji', false)
                ->assertSee('Opsi A', false)
                ->assertSee('Di-approve manual', false);

            $this->actingAs($admin)->post(route('assessment-results.email', $result->id), ['type' => 'report'])->assertRedirect();
            Mail::assertSent(AssessmentMail::class, 3);
        } finally {
            $result->orders()->delete();
            $result->delete();
        }
    }

    public function test_admin_input_uses_same_guest_form_without_payment(): void
    {
        Mail::fake();
        $this->fakeAi('pariwisata');
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $questions = $track->activeQuestions()->get();

        $this->actingAs($admin)->get(route('assessment-results.create'))->assertStatus(200)->assertSee(route('assessment-results.input', 'pariwisata'), false);
        $this->actingAs($admin)->get(route('assessment-results.input', 'pariwisata'))->assertRedirect(route('assessment.intro', 'pariwisata'));

        // Form profil & soal yang sama dengan guest, hanya ada banner mode admin; email admin tidak diisikan.
        $this->actingAs($admin)->get(route('assessment.intro', 'pariwisata'))
            ->assertSee('Mode Input Admin', false)
            ->assertDontSee('value="'.e($admin->email).'"', false)
            // Layout publik biasanya mengalihkan staf ke dashboard; halaman asesmen dikecualikan.
            ->assertDontSee("window.location = \"".url('/administrator/dashboard'), false);

        $this->actingAs($admin)->post(route('assessment.start', 'pariwisata'), [
            'organization' => 'Desa Input Admin', 'province' => 'Bali', 'regency' => 'Kab. Gianyar',
            'name' => 'Kepala Desa', 'phone' => '081234', 'email' => 'desa@example.com',
        ])->assertRedirect(route('assessment.form', 'pariwisata'));
        $this->actingAs($admin)->get(route('assessment.form', 'pariwisata'))->assertSee('Mode Input Admin', false);

        $submit = $this->actingAs($admin)->post(route('assessment.submit', 'pariwisata'), [
            'answers' => $questions->mapWithKeys(fn ($q) => [$q->id => 3])->all(),
            'notes' => [$questions->first()->id => 'Catatan admin'],
        ]);

        $result = AssessmentResult::where('organization', 'Desa Input Admin')->latest('id')->first();
        try {
            $this->assertNotNull($result);
            $submit->assertRedirect(route('assessment.result', $result->uuid));
            $this->assertSame('admin', $result->source);
            $this->assertSame($admin->id, $result->created_by);
            $this->assertTrue($result->is_unlocked);
            $this->assertSame(0, $result->orders()->count());
            $this->assertSame('done', $result->report_status);
            $this->assertStringContainsString('Catatan admin', $result->ai_prompt);
            Mail::assertSent(AssessmentMail::class, fn ($m) => $m->type === 'report' && $m->hasTo('desa@example.com'));
            $this->assertNull(session('assessment_admin_mode'));

            // Skor sama dengan perhitungan guest untuk jawaban yang sama.
            $this->assertSame(AssessmentService::compute($track, $questions, $questions->mapWithKeys(fn ($q) => [$q->id => 3])->all())['total'], (int) $result->total_score);
        } finally {
            $result?->delete();
        }
    }

    public function test_guest_flow_unaffected_without_admin_mode(): void
    {
        $member = User::where('role_id', 3)->first();
        if (! $member) {
            $this->markTestSkipped('Butuh user member.');
        }
        // Member yang memalsukan session mode admin tetap diperlakukan sebagai guest.
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $this->actingAs($member)->withSession(['assessment_admin_mode' => ['track_id' => $track->id, 'user_id' => $member->id]])
            ->get(route('assessment.intro', 'pariwisata'))->assertDontSee('Mode Input Admin', false);
    }

    public function test_guest_can_retry_failed_report(): void
    {
        Mail::fake();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $questions = $track->activeQuestions()->get();
        $answers = $questions->mapWithKeys(fn ($q) => [$q->id => 3])->all();
        $computed = AssessmentService::compute($track, $questions, $answers);
        $make = fn (array $extra) => AssessmentResult::create(array_merge([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Retry Test', 'organization' => 'Desa Retry',
            'answers' => $answers, 'dimension_scores' => $computed['dimensions'], 'total_score' => $computed['total'],
            'band' => $computed['band'], 'status' => 'baru',
        ], $extra));

        $failed = $make(['is_unlocked' => true, 'unlocked_at' => now(), 'report_status' => 'failed', 'report_error' => 'deepseek_http_401']);
        $done = $make(['is_unlocked' => true, 'unlocked_at' => now(), 'report_status' => 'done', 'ai_report' => ['ringkasan' => 'Laporan lama']]);
        $locked = $make(['is_unlocked' => false]);

        try {
            // Guest melihat tombol coba lagi, bukan kode error teknis.
            $this->get(route('assessment.result', $failed->uuid))
                ->assertSee('Coba susun ulang laporan', false)
                ->assertDontSee('deepseek_http_401', false);

            $this->fakeAi('pariwisata');
            $this->post(route('assessment.retry_report', $failed->uuid))->assertRedirect(route('assessment.result', $failed->uuid));
            $failed->refresh();
            $this->assertSame('done', $failed->report_status);
            $this->assertSame('Ringkasan uji', $failed->ai_report['ringkasan']);

            // Laporan yang sudah jadi tidak ditimpa; hasil belum lunas tidak memicu AI.
            $this->post(route('assessment.retry_report', $done->uuid))->assertRedirect();
            $this->assertSame('Laporan lama', $done->fresh()->ai_report['ringkasan']);
            $this->post(route('assessment.retry_report', $locked->uuid))->assertRedirect();
            $this->assertNull($locked->fresh()->ai_report);
            $this->assertNotSame('done', $locked->fresh()->report_status);
        } finally {
            AssessmentResult::whereIn('id', [$failed->id, $done->id, $locked->id])->delete();
        }
    }

    public function test_admin_can_delete_result_from_list(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $make = fn () => AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Hapus Test', 'organization' => "Warung D'Uma",
            'answers' => [], 'dimension_scores' => [], 'total_score' => 50, 'band' => 'Berkembang', 'status' => 'baru',
        ]);
        $a = $make();
        $order = $a->orders()->create(['code' => 'ASM-TEST-'.strtoupper(Str::random(6)), 'amount' => 199000, 'status' => 'pending']);
        $b = $make();

        try {
            $list = route('assessment-results.index', ['payment' => 'belum']);
            $this->actingAs($admin)->get($list)
                ->assertSee(route('assessment-results.destroy', $a->id), false)
                ->assertSee('data-confirm="Hapus hasil asesmen Warung D&#039;Uma?', false);

            $this->actingAs($admin)->delete(route('assessment-results.destroy', $a->id), ['redirect' => $list])->assertRedirect($list);
            $this->assertNull(AssessmentResult::find($a->id));
            $this->assertDatabaseMissing('assessment_orders', ['id' => $order->id]);

            // Redirect ke luar aplikasi diabaikan.
            $this->actingAs($admin)->delete(route('assessment-results.destroy', $b->id), ['redirect' => 'https://evil.example.com'])
                ->assertRedirect(route('assessment-results.index'));
        } finally {
            AssessmentResult::whereIn('id', [$a->id, $b->id])->delete();
        }
    }

    public function test_results_list_tabs_filter_correctly(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $tag = 'TabTest'.Str::random(5);
        $make = fn (array $extra) => AssessmentResult::create(array_merge([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Tab', 'organization' => $tag,
            'answers' => [], 'dimension_scores' => [], 'total_score' => 50, 'band' => 'Berkembang', 'status' => 'baru',
        ], $extra));
        $unpaid = $make(['organization' => $tag.' Unpaid']);
        $followup = $make(['organization' => $tag.' Followup', 'is_unlocked' => true]);
        $failed = $make(['organization' => $tag.' Failed', 'is_unlocked' => true, 'status' => 'dihubungi', 'report_status' => 'failed']);

        try {
            $get = fn ($tab) => $this->actingAs($admin)->get(route('assessment-results.index', ['q' => $tag, 'tab' => $tab]))->assertStatus(200);
            $get('unpaid')->assertSee($tag.' Unpaid')->assertDontSee($tag.' Followup');
            $get('followup')->assertSee($tag.' Followup')->assertDontSee($tag.' Unpaid')->assertDontSee($tag.' Failed');
            $get('failed')->assertSee($tag.' Failed')->assertDontSee($tag.' Followup');
            $get('paid')->assertSee($tag.' Followup')->assertSee($tag.' Failed')->assertDontSee($tag.' Unpaid');
            // Link lama ?payment=belum tetap berfungsi.
            $this->actingAs($admin)->get(route('assessment-results.index', ['q' => $tag, 'payment' => 'belum']))->assertSee($tag.' Unpaid')->assertDontSee($tag.' Followup');
        } finally {
            AssessmentResult::whereIn('id', [$unpaid->id, $followup->id, $failed->id])->delete();
        }
    }

    public function test_admin_resend_email_respects_payment_status(): void
    {
        Mail::fake();
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $make = fn (array $extra) => AssessmentResult::create(array_merge([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Mail', 'organization' => 'Desa Mail', 'email' => 'mail@example.com',
            'answers' => [], 'dimension_scores' => [], 'total_score' => 50, 'band' => 'Berkembang', 'status' => 'baru',
        ], $extra));

        $unpaid = $make([]);
        $unpaid->orders()->create(['code' => 'ASM-T-'.strtoupper(Str::random(6)), 'amount' => 199000, 'status' => 'pending']);
        $paid = $make(['is_unlocked' => true, 'unlocked_at' => now(), 'ai_report' => ['ringkasan' => 'x']]);
        $paid->orders()->create(['code' => 'ASM-T-'.strtoupper(Str::random(6)), 'amount' => 199000, 'status' => 'paid', 'paid_at' => now()]);

        try {
            $this->assertSame(['invoice'], array_keys($unpaid->sendableEmails()));
            $this->assertSame(['paid', 'report'], array_keys($paid->sendableEmails()));

            // Menu daftar hanya menampilkan email yang relevan.
            $list = $this->actingAs($admin)->get(route('assessment-results.index', ['q' => 'Desa Mail']))->assertStatus(200);
            $list->assertSee('Kirim ulang email', false)->assertSee('Kirim email Invoice ke mail@example.com?', false)->assertSee('Kirim email Hasil &amp; strategi ke mail@example.com?', false);

            $this->actingAs($admin)->post(route('assessment-results.email', $unpaid->id), ['type' => 'invoice'])->assertSessionHas('status');
            Mail::assertSent(AssessmentMail::class, fn ($m) => $m->type === 'invoice' && $m->result->is($unpaid));

            // Tidak boleh: hasil belum lunas, atau invoice untuk yang sudah lunas.
            $this->actingAs($admin)->post(route('assessment-results.email', $unpaid->id), ['type' => 'report'])->assertSessionHas('error');
            $this->actingAs($admin)->post(route('assessment-results.email', $paid->id), ['type' => 'invoice'])->assertSessionHas('error');

            $this->actingAs($admin)->post(route('assessment-results.email', $paid->id), ['type' => 'paid'])->assertSessionHas('status');
            $this->actingAs($admin)->post(route('assessment-results.email', $paid->id), ['type' => 'report'])->assertSessionHas('status');
            Mail::assertSent(AssessmentMail::class, 3);
            $this->assertSame(['paid', 'report'], array_column($paid->fresh()->email_log, 'type'));
        } finally {
            foreach ([$unpaid, $paid] as $r) { $r->orders()->delete(); $r->delete(); }
        }
    }

    public function test_admin_can_follow_up_result(): void
    {
        $admin = User::where('role_id', 1)->first();
        $team = OurTeam::first();
        $track = AssessmentTrack::where('slug', 'ekonomi-desa')->firstOrFail();
        if (! $admin || ! $team) {
            $this->markTestSkipped('Butuh data admin & OurTeam.');
        }

        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(),
            'track_id' => $track->id,
            'name' => 'Follow Up Test',
            'email' => 'fu'.time().'@example.com',
            'answers' => [],
            'dimension_scores' => [],
            'total_score' => 55,
            'band' => 'Berkembang',
            'status' => 'baru',
        ]);

        try {
            $this->actingAs($admin)->get(route('assessment-results.index'))->assertStatus(200);
            $this->actingAs($admin)->get(route('assessment-results.show', $result->id))->assertStatus(200);
            $this->actingAs($admin)->get(route('assessments.index'))->assertStatus(200);
            // Dashboard Tim sudah digabung ke dashboard utama; URL lama diarahkan ke sana.
            $this->actingAs($admin)->get(route('team-dashboard.index'))->assertRedirect(route('home'));

            $this->actingAs($admin)->put(route('assessment-results.update', $result->id), [
                'status' => 'dihubungi',
                'pic_team_id' => $team->id,
                'internal_note' => 'Sudah dihubungi via WA.',
            ])->assertRedirect();

            $this->assertDatabaseHas('assessment_results', [
                'id' => $result->id,
                'status' => 'dihubungi',
                'pic_team_id' => $team->id,
            ]);
        } finally {
            AssessmentResult::where('id', $result->id)->delete();
            DB::table('activity_log')->where('log_name', 'default')->delete();
        }
    }
}
