<?php

namespace App\Http\Controllers\Front;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\AssessmentSubmitRequest;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Services\AssessmentService;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    public function index()
    {
        $data['tracks'] = AssessmentTrack::withCount('activeQuestions as questions_count')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $data['seo'] = Seo::make()
            ->title('Asesmen Kesiapan')
            ->description('Pilih jenis asesmen yang ingin dilakukan: Pariwisata, Ekonomi Desa & Koperasi, Daya Saing Destinasi (TTDI), atau Regeneratif. Setiap jalur menghasilkan skor kesiapan, analisis kekuatan/tantangan, dan draf strategi.')
            ->canonical('/asesmen')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Asesmen' => '/asesmen'])
            ->toArray();

        return view('customer.assessment.index', $data);
    }

    public function intro(string $slug)
    {
        $track = $this->findTrack($slug);
        $track->loadCount('activeQuestions as questions_count');

        $data['track'] = $track;
        $data['seo'] = Seo::make()
            ->title('Asesmen: '.$track->name)
            ->description($track->tagline.' — '.$track->description)
            ->canonical('/asesmen/'.$track->slug)
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Asesmen' => '/asesmen', $track->name => '/asesmen/'.$track->slug])
            ->toArray();

        return view('customer.assessment.intro', $data);
    }

    public function start(Request $request, string $slug)
    {
        $track = $this->findTrack($slug);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:191',
        ]);

        $request->session()->put('assessment_identity_'.$track->id, $validated);

        return redirect()->route('assessment.form', $track->slug);
    }

    public function form(Request $request, string $slug)
    {
        $track = $this->findTrack($slug);

        if (! $request->session()->has('assessment_identity_'.$track->id)) {
            return redirect()->route('assessment.intro', $track->slug)
                ->with('error', 'Isi identitas terlebih dahulu sebelum mengerjakan asesmen.');
        }

        $track->load(['activeQuestions']);
        $grouped = $track->activeQuestions->groupBy('dimension');

        $data['track'] = $track;
        $data['grouped'] = $grouped;
        $data['labels'] = AssessmentService::SCALE_LABELS;
        $data['seo'] = Seo::make()->title('Isi Asesmen: '.$track->name)->noindex()->toArray();

        return view('customer.assessment.form', $data);
    }

    public function submit(AssessmentSubmitRequest $request, string $slug)
    {
        $track = $this->findTrack($slug);
        $identity = $request->session()->get('assessment_identity_'.$track->id);

        if (! $identity) {
            return redirect()->route('assessment.intro', $track->slug)
                ->with('error', 'Sesi identitas berakhir. Mohon isi ulang identitas.');
        }

        $questions = $track->activeQuestions()->get();
        $validIds = $questions->pluck('id')->all();
        $answers = collect($request->validated()['answers'])
            ->only($validIds)
            ->map(fn ($v) => (int) $v)
            ->all();

        if (count($answers) !== count($validIds)) {
            return back()->withInput()->with('error', 'Masih ada pernyataan yang belum dijawab.');
        }

        try {
            $computed = AssessmentService::compute($track, $questions, $answers);

            $result = AssessmentResult::create([
                'uuid' => (string) Str::uuid(),
                'track_id' => $track->id,
                'name' => $identity['name'],
                'email' => $identity['email'],
                'phone' => $identity['phone'] ?? null,
                'organization' => $identity['organization'] ?? null,
                'answers' => $answers,
                'dimension_scores' => $computed['dimensions'],
                'total_score' => $computed['total'],
                'band' => $computed['band'],
                'status' => 'baru',
            ]);

            $request->session()->forget('assessment_identity_'.$track->id);

            return redirect()->route('assessment.result', $result->uuid);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Assessment Submit', $th);

            return back()->withInput()->with('error', 'Gagal menyimpan hasil asesmen. Silakan coba lagi.');
        }
    }

    public function result(string $uuid)
    {
        $result = AssessmentResult::with(['track', 'track.activeQuestions'])->where('uuid', $uuid)->firstOrFail();
        $computed = [
            'dimensions' => $result->dimension_scores ?? [],
            'total' => (float) $result->total_score,
            'band' => $result->band,
            'band_desc' => AssessmentService::bandFor($result->track, (float) $result->total_score)['desc'],
        ];

        if ($result->track->slug === 'daya-saing-destinasi') {
            $computed['subindexes'] = AssessmentService::subindexScores($computed['dimensions']);
        }

        $names = array_keys($computed['dimensions']);
        $computed['strengths'] = array_slice($names, 0, 2);
        $computed['challenges'] = array_slice(array_reverse($names), 0, 2);
        $computed['priority_actions'] = [];
        foreach ($computed['dimensions'] as $name => $dim) {
            foreach ($dim['questions'] ?? [] as $item) {
                if (($item['score'] ?? 5) <= 2) {
                    $computed['priority_actions'][] = ['dimension' => $name, 'action' => 'Perkuat: '.$item['question']];
                }
            }
            if (count($computed['priority_actions']) >= 7) {
                break;
            }
        }

        $data['result'] = $result;
        $data['computed'] = $computed;
        $data['labels'] = AssessmentService::SCALE_LABELS;
        $data['pendingOrder'] = AssessmentOrder::where('assessment_result_id', $result->id)
            ->where('status', 'pending')->latest()->first();
        $data['seo'] = Seo::make()->title('Hasil Asesmen: '.$result->track->name)->noindex()->toArray();

        return view('customer.assessment.result', $data);
    }

    /**
     * Buat order Midtrans untuk membuka laporan lengkap (Brief §5).
     * Satu order = satu laporan. Harga diambil dari track (admin-editable).
     */
    public function checkout(Request $request, string $uuid)
    {
        $result = AssessmentResult::with('track')->where('uuid', $uuid)->firstOrFail();

        if ($result->is_unlocked) {
            return redirect()->route('assessment.result', $result->uuid);
        }

        $existing = AssessmentOrder::where('assessment_result_id', $result->id)
            ->where('status', 'pending')->latest()->first();

        if ($existing && $existing->gateway_ref) {
            return redirect()->route('assessment.payment', $existing->code);
        }

        $amount = (int) ($result->track->price ?? 199000);

        $order = AssessmentOrder::create([
            'assessment_result_id' => $result->id,
            'code' => AssessmentOrder::generateCode(),
            'amount' => $amount,
            'gateway' => 'midtrans',
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $order->code,
                'gross_amount' => $amount,
            ],
            'item_details' => [[
                'id' => 'asesmen-'.$result->track->slug,
                'price' => $amount,
                'quantity' => 1,
                'name' => 'Laporan Asesmen: '.$result->track->name,
            ]],
            'customer_details' => [
                'first_name' => $result->name,
                'email' => $result->email,
                'phone' => $result->phone,
            ],
            'credit_card' => ['secure' => true],
            'expiry' => ['unit' => 'hour', 'duration' => 24],
        ];

        try {
            $snap = new CreateSnapTokenService($order);
            $order->gateway_ref = $snap->getSnapToken($params);
            $order->save();
        } catch (\Throwable $th) {
            BotHelper::errorBot('Assessment Checkout', $th);
            $order->update(['status' => 'failed']);

            return back()->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('assessment.payment', $order->code);
    }

    public function payment(string $code)
    {
        $order = AssessmentOrder::with('result.track')->where('code', $code)->firstOrFail();

        if ($order->status === 'paid' || $order->result->is_unlocked) {
            return redirect()->route('assessment.result', $order->result->uuid);
        }

        if (! $order->gateway_ref) {
            return redirect()->route('assessment.result', $order->result->uuid)
                ->with('error', 'Token pembayaran tidak tersedia. Silakan buat ulang pembayaran.');
        }

        $data['snapToken'] = $order->gateway_ref;
        $data['redirectURISuccess'] = route('assessment.result', $order->result->uuid);
        $data['redirectURIError'] = route('assessment.result', $order->result->uuid);
        $data['seo'] = Seo::make()->title('Pembayaran Laporan Asesmen')->noindex()->toArray();

        return view('customer.payment.midtrans', $data);
    }

    /**
     * Mode Tim GODEVI: buka laporan tanpa bayar, wajib login staf (Brief §4).
     */
    public function unlockStaff(Request $request, string $uuid)
    {
        $result = AssessmentResult::where('uuid', $uuid)->firstOrFail();

        if (! $result->is_unlocked) {
            $result->update([
                'is_unlocked' => true,
                'unlocked_at' => now(),
            ]);
            \App\Jobs\GenerateAssessmentReport::dispatch($result->uuid)->afterCommit();
        }

        return redirect()->route('assessment.result', $result->uuid)
            ->with('status', 'Laporan dibuka via Mode Tim GODEVI.');
    }

    protected function findTrack(string $slug): AssessmentTrack
    {
        return AssessmentTrack::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }
}
