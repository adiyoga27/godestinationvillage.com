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

        $email = 'asesmen'.time().'@example.com';
        $this->post(route('assessment.start', $track->slug), [
            'name' => 'Pengisi Test',
            'email' => $email,
            'phone' => '08123456789',
            'organization' => 'Desa Test',
        ])->assertRedirect(route('assessment.form', $track->slug));

        $this->get(route('assessment.form', $track->slug))->assertStatus(200);

        $answers = [];
        foreach ($questions as $i => $q) {
            $answers[$q->id] = ($i % 5) + 1;
        }

        $submit = $this->post(route('assessment.submit', $track->slug), ['answers' => $answers]);

        try {
            $result = AssessmentResult::where('email', $email)->first();
            $this->assertNotNull($result, 'hasil asesmen tidak tersimpan');
            $submit->assertRedirect(route('assessment.result', $result->uuid));

            $this->assertTrue($result->total_score >= 0 && $result->total_score <= 100);
            $this->assertNotEmpty($result->band);
            $this->assertNotEmpty($result->dimension_scores);

            $page = $this->get(route('assessment.result', $result->uuid));
            $page->assertStatus(200);
            $page->assertSee((string) $result->band, false);
            $page->assertSee('Kekuatan', false);
            $page->assertSee('Tantangan', false);
            $page->assertSee('Draf Strategi', false);
        } finally {
            AssessmentResult::where('email', $email)->delete();
        }
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
        $this->assertSame('Transisi', AssessmentService::bandFor($track, 30)['label']);
        $this->assertSame('Berkembang ke Regeneratif', AssessmentService::bandFor($track, 60)['label']);
        $this->assertSame('Regeneratif Matang', AssessmentService::bandFor($track, 90)['label']);
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
            $this->actingAs($admin)->get(route('team-dashboard.index'))->assertStatus(200);

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
