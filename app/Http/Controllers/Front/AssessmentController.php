<?php

namespace App\Http\Controllers\Front;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\AssessmentSubmitRequest;
use App\Jobs\GenerateAssessmentReport;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Services\AssessmentPaymentService;
use App\Services\AssessmentMailer;
use App\Services\AssessmentService;
use App\Services\AssessmentSubmissionService;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $track->load('activeQuestions');
        $track->questions_count = $track->activeQuestions->count();

        // Ringkasan dimensi yang dinilai + bobotnya untuk halaman intro.
        $grouped = $track->activeQuestions->groupBy('dimension');
        $weights = AssessmentService::displayWeights($track, $grouped);
        $data['dimensions'] = $grouped->map(fn ($qs, $name) => [
            'name' => $name,
            'description' => $qs->count() === 1 ? $qs->first()->help_text : $qs->count().' pernyataan',
            'weight' => round($weights[$name]),
        ])->values();
        $data['formConfig'] = AssessmentService::formFor($track);
        $data['showWeight'] = ($data['formConfig']['show_weight'] ?? false)
            || $weights->map(fn ($w) => round($w, 1))->unique()->count() > 1;
        $data['profile'] = AssessmentService::profileFor($track);
        $data['adminMode'] = $this->adminMode(request(), $track);

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
        [$rules, $messages, $attributes] = AssessmentSubmissionService::profileRules($track, $this->adminMode($request, $track));
        $validated = $request->validate($rules, $messages, $attributes);
        $validated['phone'] = AssessmentPaymentService::normalizePhone($validated['phone']);

        // Bukti persetujuan: waktu & IP saat responden mencentang S&K.
        if ($request->boolean('agree_terms')) {
            $validated['consent_terms_at'] = now()->toDateTimeString();
            $validated['consent_ip'] = $request->ip();
        }
        $validated['consent_contact'] = $request->boolean('consent_contact');
        $validated['consent_research'] = $request->boolean('consent_research');
        unset($validated['agree_terms']);

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
        $data['formConfig'] = AssessmentService::formFor($track);
        $data['weights'] = AssessmentService::displayWeights($track, $grouped);
        $data['adminMode'] = $this->adminMode($request, $track);
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

        // Mode Input Admin: form & perhitungan sama persis dengan guest, tetapi hasil langsung terbuka tanpa bayar.
        $adminMode = $this->adminMode($request, $track);
        $extra = $adminMode
            ? ['source' => 'admin', 'created_by' => $request->user()->id, 'is_unlocked' => true]
            : ['source' => 'guest'];

        try {
            $validated = $request->validated();
            $result = AssessmentSubmissionService::store($track, $identity, $validated['answers'], $validated['notes'] ?? [], $extra);

            if (! $result) {
                return back()->withInput()->with('error', 'Masih ada pernyataan yang belum dijawab.');
            }

            $request->session()->forget('assessment_identity_'.$track->id);

            if ($adminMode) {
                $request->session()->forget('assessment_admin_mode');

                return redirect()->route('assessment.result', $result->uuid)
                    ->with('status', 'Input admin tersimpan — hasil langsung terbuka tanpa pembayaran. Laporan AI sedang dibuat.');
            }

            // Invoice langsung dibuat & dikirim ke email guest (setelah respons, agar halaman tidak lambat).
            if ($order = AssessmentPaymentService::ensureInvoice($result)) {
                dispatch(fn () => AssessmentMailer::send($result->fresh(), 'invoice', $order))->afterResponse();
            }

            return redirect()->route('assessment.result', $result->uuid);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Assessment Submit', $th);

            return back()->withInput()->with('error', 'Gagal menyimpan hasil asesmen. Silakan coba lagi.');
        }
    }

    public function result(string $uuid)
    {
        $result = AssessmentResult::with(['track', 'track.activeQuestions', 'latestOrder'])->where('uuid', $uuid)->firstOrFail();

        // Hasil hanya tampil setelah lunas. Webhook bisa terlambat → cek langsung ke Midtrans.
        if (! $result->is_unlocked) {
            if ($result->latestOrder) {
                AssessmentPaymentService::sync($result->latestOrder);
                $result->refresh()->load('latestOrder');
            }

            if (! $result->is_unlocked) {
                $data['result'] = $result;
                $data['pendingOrder'] = $result->latestOrder?->status === 'pending' && $result->latestOrder->gateway_ref ? $result->latestOrder : null;
                $data['seo'] = Seo::make()->title('Pembayaran Asesmen: '.$result->track->name)->description('Selesaikan pembayaran untuk membuka skor dan analisa lengkap asesmen '.$result->track->name.' dari GODEVI.')->image('assets/godevi-black.png')->noindex()->toArray();

                return view('customer.assessment.payment', $data);
            }
        }

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
        $data['bands'] = array_reverse(AssessmentService::bandsFor($result->track));
        $data['formConfig'] = AssessmentService::formFor($result->track);
        $data['pendingOrder'] = AssessmentOrder::where('assessment_result_id', $result->id)
            ->where('status', 'pending')->latest()->first();
        $data['seo'] = Seo::make()->title('Hasil Asesmen: '.$result->track->name)->description('Laporan hasil asesmen '.$result->track->name.' ('.($result->organization ?: $result->name).') — skor kesiapan, analisis kekuatan & tantangan, dan draf strategi dari GODEVI.')->image('assets/godevi-black.png')->noindex()->toArray();

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

        // Pakai invoice yang sudah dibuat saat submit (nomor tetap sama dengan di email).
        $order = AssessmentPaymentService::ensureInvoice($result);
        if (! $order) {
            return redirect()->route('assessment.result', $result->uuid);
        }

        try {
            AssessmentPaymentService::ensureSnapToken($order);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Assessment Checkout', $th);

            return back()->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
        }

        if (! AssessmentPaymentService::invoiceEmailed($result, $order)) {
            AssessmentMailer::send($result, 'invoice', $order);
        }

        return redirect()->route('assessment.payment', $order->code);
    }

    public function payment(string $code)
    {
        $order = AssessmentOrder::with('result.track')->where('code', $code)->firstOrFail();

        if ($order->status === 'paid' || $order->result->is_unlocked) {
            return redirect()->route('assessment.result', $order->result->uuid);
        }

        // Link "Bayar Sekarang" di email invoice: token Midtrans dibuat saat halaman bayar dibuka.
        if (! $order->gateway_ref && $order->status === 'pending') {
            try {
                AssessmentPaymentService::ensureSnapToken($order);
            } catch (\Throwable $th) {
                BotHelper::errorBot('Assessment Payment Token', $th);
            }
        }

        if (! $order->gateway_ref || $order->status !== 'pending') {
            return redirect()->route('assessment.result', $order->result->uuid)
                ->with('error', 'Pembayaran untuk invoice ini tidak tersedia. Silakan klik "Bayar Sekarang" di halaman hasil.');
        }

        $data['snapToken'] = $order->gateway_ref;
        $data['redirectURISuccess'] = route('assessment.result', $order->result->uuid);
        $data['redirectURIError'] = route('assessment.result', $order->result->uuid);
        $data['seo'] = Seo::make()->title('Pembayaran Laporan Asesmen')->noindex()->toArray();

        return view('customer.payment.midtrans', $data);
    }

    /**
     * Guest mencoba ulang pembuatan laporan AI yang gagal (atau macet saat diproses).
     * Hanya untuk hasil yang sudah lunas; laporan yang sudah jadi tidak bisa ditimpa dari sini.
     */
    public function retryReport(string $uuid)
    {
        $result = AssessmentResult::where('uuid', $uuid)->firstOrFail();

        if (! $result->is_unlocked || ! empty($result->ai_report) || ! $result->canRetryReport()) {
            return redirect()->route('assessment.result', $result->uuid);
        }

        $result->update(['report_status' => 'pending', 'report_error' => null]);
        GenerateAssessmentReport::dispatch($result->uuid);

        $result->refresh();

        return redirect()->route('assessment.result', $result->uuid)->with(
            $result->report_status === 'failed' ? 'error' : 'status',
            $result->report_status === 'failed'
                ? 'Laporan masih belum berhasil dibuat. Silakan coba lagi beberapa saat lagi atau hubungi tim kami.'
                : 'Laporan sedang / sudah disusun ulang.'
        );
    }

    /**
     * Admin/staf yang memulai "Input Manual" dari panel admin untuk jalur ini.
     */
    protected function adminMode(Request $request, AssessmentTrack $track): bool
    {
        $mode = $request->session()->get('assessment_admin_mode');
        $user = $request->user();

        return $user && (int) $user->role_id !== 3
            && is_array($mode)
            && (int) ($mode['track_id'] ?? 0) === (int) $track->id
            && (int) ($mode['user_id'] ?? 0) === (int) $user->id;
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
            GenerateAssessmentReport::dispatch($result->uuid)->afterCommit();
        }

        return redirect()->route('assessment.result', $result->uuid)
            ->with('status', 'Laporan dibuka via Mode Tim GODEVI.');
    }

    /**
     * Cek status & riwayat asesmen guest berdasarkan No. WhatsApp.
     */
    public function status(Request $request)
    {
        $phone = AssessmentPaymentService::normalizePhone($request->query('phone'));

        $data['phone'] = $request->query('phone');
        $data['results'] = strlen($phone) >= 8
            ? AssessmentResult::with(['track', 'latestOrder'])->where('phone', $phone)->latest()->get()
            : null;
        $data['seo'] = Seo::make()->title('Cek Status Asesmen')->noindex()->toArray();

        return view('customer.assessment.status', $data);
    }

    protected function findTrack(string $slug): AssessmentTrack
    {
        return AssessmentTrack::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }
}
