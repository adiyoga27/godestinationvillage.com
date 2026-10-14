<?php

namespace Tests\Feature;

use App\Jobs\GenerateAssessmentReport;
use App\Jobs\TranslateAssessmentReport;
use App\Mail\AssessmentMail;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\User;
use App\Services\AiReportService;
use App\Services\AssessmentContentTranslator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Asesmen dua bahasa: /en/asesmen, konten *_en dari admin, laporan AI English. */
class AssessmentEnglishTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cache.default' => 'array']);
    }

    private function abs(string $path): string
    {
        return 'http://localhost'.$path;
    }

    /** Laporan AI valid untuk jalur; $lang membedakan versi asli & terjemahan. */
    private function report(string $slug, string $lang): array
    {
        $report = array_fill_keys(AiReportService::requiredKeys($slug), ["$lang item"]);
        $report['ringkasan'] = "$lang summary";
        $report['kekuatan'] = $report['tantangan'] = ["$lang a", "$lang b", "$lang c"];
        $report['langkah_prioritas'] = ["$lang 1", "$lang 2", "$lang 3", "$lang 4", "$lang 5"];

        return $report;
    }

    /** DeepSeek palsu: permintaan terjemahan → versi EN, selain itu → versi ID. */
    private function fakeAi(string $slug): void
    {
        config(['ai.driver' => 'deepseek', 'ai.deepseek_key' => 'test-key']);
        Http::fake(fn (Request $request) => Http::response([
            'choices' => [['message' => ['content' => json_encode($this->report(
                $slug,
                str_contains($request['messages'][1]['content'], 'Translate every string') ? 'EN' : 'ID'
            ))]]],
            'usage' => ['total_tokens' => 10],
        ]));
    }

    public function test_english_pages_use_english_content_with_indonesian_fallback(): void
    {
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $track->update(['name_en' => 'Tourism Track EN', 'tagline_en' => 'Tagline EN', 'description_en' => 'Description EN']);
        [$first, $second] = $track->activeQuestions()->get()->take(2)->all();
        $first->update(['question_en' => 'First statement EN', 'dimension_en' => 'First Dimension EN']);
        $second->update(['question_en' => null]);

        $this->get($this->abs('/en/asesmen'))->assertOk()
            ->assertSee('Tourism Track EN')
            ->assertSee('Start with the one that fits best');

        $this->get($this->abs('/en/asesmen/pariwisata'))->assertOk()
            ->assertSee('Tourism Track EN Assessment')
            ->assertSee('First Dimension EN')
            ->assertSee('Start Assessment');

        // Bahasa Indonesia tidak berubah.
        $this->get($this->abs('/id/asesmen/pariwisata'))->assertOk()
            ->assertSee('Asesmen '.$track->name)
            ->assertDontSee('Tourism Track EN');

        $this->withSession(['assessment_identity_'.$track->id => ['name' => 'X']])
            ->get($this->abs('/en/asesmen/pariwisata/soal'))->assertOk()
            ->assertSee('First statement EN')
            ->assertSee($second->question) // English kosong → versi Indonesia
            ->assertSee('None yet'); // label skala 1
    }

    public function test_english_respondent_gets_english_report_and_email(): void
    {
        Mail::fake();
        $this->fakeAi('pariwisata');
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $questions = $track->activeQuestions()->get();
        $questions->first()->update(['dimension_en' => 'Attractions EN']);
        AssessmentQuestion::where('track_id', $track->id)->where('dimension', $questions->first()->dimension)->update(['dimension_en' => 'Attractions EN']);

        $this->post($this->abs('/en/asesmen/pariwisata/mulai'), [
            'organization' => 'English Village', 'province' => 'Bali', 'regency' => 'Kab. Bangli',
            'name' => 'Tester', 'phone' => '081234500099', 'email' => 'en@example.com', 'agree_terms' => '1',
        ])->assertRedirect('http://localhost/en/asesmen/pariwisata/soal');

        $this->post($this->abs('/en/asesmen/pariwisata/kirim'), [
            'answers' => $questions->mapWithKeys(fn ($q) => [$q->id => 2])->all(),
        ]);

        $result = AssessmentResult::where('organization', 'English Village')->latest('id')->firstOrFail();
        $this->assertSame('en', $result->locale);
        // Yang disimpan tetap Bahasa Indonesia (kunci dimensi, kategori, saran).
        $this->assertSame('Rintisan', $result->band);
        $this->assertArrayHasKey($questions->first()->dimension, $result->dimension_scores);

        $result->update(['is_unlocked' => true, 'unlocked_at' => now()]);
        (new GenerateAssessmentReport($result->uuid))->handle();
        $result->refresh();

        $this->assertSame('ID summary', $result->ai_report['ringkasan']);
        $this->assertSame('EN summary', $result->ai_report_en['ringkasan']);
        Mail::assertSent(AssessmentMail::class, fn ($mail) => $mail->type === 'report' && $mail->locale === 'en');

        $this->get($this->abs('/en/asesmen/hasil/'.$result->uuid))->assertOk()
            ->assertSee('EN summary')
            ->assertSee('Emerging') // kategori Rintisan
            ->assertSee('Attractions EN')
            ->assertSee('Attractions EN needs gradual strengthening')
            ->assertDontSee('Translating to English');

        $this->get($this->abs('/id/asesmen/hasil/'.$result->uuid))->assertOk()
            ->assertSee('ID summary')
            ->assertSee('Rintisan')
            ->assertDontSee('Attractions EN');

        $html = (new AssessmentMail('report', $result->fresh('track')))->locale('en')->render();
        $this->assertStringContainsString('EN summary', $html);
        $this->assertStringContainsString('Open Full Report', $html);
        $this->assertStringContainsString('/en/asesmen/hasil/'.$result->uuid, $html);
    }

    public function test_english_view_of_indonesian_report_queues_translation_once(): void
    {
        Bus::fake([TranslateAssessmentReport::class]);
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Guest', 'organization' => 'Desa ID',
            'answers' => [], 'dimension_scores' => [], 'total_score' => 70, 'band' => 'Siap',
            'is_unlocked' => true, 'ai_report' => $this->report('pariwisata', 'ID'), 'report_status' => 'done',
        ]);

        $this->get($this->abs('/en/asesmen/hasil/'.$result->uuid))->assertOk()
            ->assertSee('ID summary')
            ->assertSee('Translating to English')
            ->assertSee('Ready');
        $this->get($this->abs('/en/asesmen/hasil/'.$result->uuid))->assertOk();

        Bus::assertDispatchedTimes(TranslateAssessmentReport::class, 1);

        // Bahasa Indonesia tidak memicu terjemahan.
        $this->get($this->abs('/id/asesmen/hasil/'.$result->uuid))->assertOk()->assertDontSee('Menerjemahkan ke English');
    }

    public function test_failed_translation_stops_auto_reload(): void
    {
        config(['ai.driver' => 'deepseek', 'ai.deepseek_key' => 'test-key']);
        Http::fake(['*' => Http::response(['choices' => [['message' => ['content' => '{"ringkasan":"x"}']]]])]);
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $result = AssessmentResult::create([
            'uuid' => (string) Str::uuid(), 'track_id' => $track->id, 'name' => 'Guest',
            'answers' => [], 'dimension_scores' => [], 'total_score' => 70, 'band' => 'Siap',
            'is_unlocked' => true, 'ai_report' => $this->report('pariwisata', 'ID'), 'report_status' => 'done',
        ]);

        (new TranslateAssessmentReport($result->uuid))->handle();
        $result->refresh();

        $this->assertNull($result->ai_report_en);
        $this->assertTrue($result->englishTranslationFailed());
        $this->get($this->abs('/en/asesmen/hasil/'.$result->uuid))->assertOk()
            ->assertSee('not available yet')
            ->assertDontSee('window.location.reload(); }, 15000', false);
    }

    public function test_admin_saves_english_question_and_dimension_for_whole_dimension(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $track = AssessmentTrack::where('slug', 'pariwisata')->firstOrFail();
        $q = $track->activeQuestions()->firstOrFail();

        $this->actingAs($admin)->put(route('assessments.questions.update', [$track->id, $q->id]), [
            'dimension' => $q->dimension, 'dimension_en' => 'Dimension EN',
            'question' => $q->question, 'question_en' => 'Question EN',
            'help_text' => $q->help_text, 'help_text_en' => 'Help EN',
            'weight' => 1, 'sort_order' => $q->sort_order, 'is_active' => '1',
        ])->assertRedirect(route('assessments.questions.index', $track->id));

        $q->refresh();
        $this->assertSame('Question EN', $q->question_en);
        $this->assertSame('Help EN', $q->help_text_en);
        $this->assertSame(0, AssessmentQuestion::where('track_id', $track->id)->where('dimension', $q->dimension)
            ->where(fn ($w) => $w->whereNull('dimension_en')->orWhere('dimension_en', '!=', 'Dimension EN'))->count());

        $this->actingAs($admin)->get(route('assessments.questions.edit', [$track->id, $q->id]))->assertOk()
            ->assertSee('name="question_en"', false)->assertSee('Question EN');

        $this->actingAs($admin)->put(route('assessments.update', $track->id), [
            'name' => $track->name, 'name_en' => 'Track EN', 'price' => $track->price, 'is_active' => '1',
        ])->assertRedirect(route('assessments.index'));
        $this->assertSame('Track EN', $track->fresh()->name_en);
    }

    public function test_content_translator_fills_only_empty_english_fields(): void
    {
        config(['ai.driver' => 'deepseek', 'ai.deepseek_key' => 'test-key']);
        $track = AssessmentTrack::where('slug', 'regeneratif')->firstOrFail();
        $questions = $track->questions()->get();
        AssessmentQuestion::where('track_id', $track->id)->update(['question_en' => null, 'help_text_en' => null, 'dimension_en' => null]);
        $track->update(['name_en' => 'Kept by admin', 'tagline_en' => null, 'description_en' => null, 'target_audience_en' => null]);
        $kept = $questions->first();
        $kept->update(['question_en' => 'Kept question']);

        // AI palsu: kembalikan payload dengan awalan "EN " pada setiap nilai.
        Http::fake(function (Request $request) {
            preg_match('/JSON:\s*(\{.*\})\s*$/s', $request['messages'][1]['content'], $m);
            $payload = json_decode($m[1], true);
            array_walk_recursive($payload, function (&$v) {
                $v = 'EN '.$v;
            });

            return Http::response(['choices' => [['message' => ['content' => json_encode($payload)]]]]);
        });

        $out = AssessmentContentTranslator::translateTrack($track);
        $this->assertTrue($out['ok']);
        $this->assertGreaterThan(0, $out['filled']);

        $track->refresh();
        $this->assertSame('Kept by admin', $track->name_en);
        $this->assertSame('EN '.$track->tagline, $track->tagline_en);
        $this->assertSame('Kept question', $kept->fresh()->question_en);
        $other = $questions->last()->fresh();
        $this->assertSame('EN '.$other->question, $other->question_en);
        $this->assertSame('EN '.$other->dimension, $other->dimension_en);
        // Soal dikirim per 20 → beberapa panggilan AI.
        Http::assertSentCount((int) ceil(($questions->count() - 1) / 20) + 1);
    }
}
