<?php

namespace Tests\Feature;

use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\OurTeam;
use App\Models\User;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\DB;
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

    public function test_start_requires_location_from_search(): void
    {
        $this->post(route('assessment.start', 'pariwisata'), [
            'organization' => 'Desa Test',
            'name' => 'Pengisi Test',
            'phone' => '08123456789',
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
