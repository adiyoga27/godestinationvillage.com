<?php

namespace App\Http\Controllers\Front;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\AssessmentSubmitRequest;
use App\Jobs\GenerateAssessmentReport;
use App\Jobs\TranslateAssessmentReport;
use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use App\Models\AssessmentVoucher;
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
            ->title(__('Asesmen Kesiapan'))
            ->description(__('Pilih jenis asesmen yang ingin dilakukan: Pariwisata, Ekonomi Desa & Koperasi, Daya Saing Destinasi (TTDI), atau Regeneratif. Setiap jalur menghasilkan skor kesiapan, analisis kekuatan/tantangan, dan draf strategi.'))
            ->canonical('/asesmen')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', __('Asesmen') => '/asesmen'])
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
            'name' => $qs->first()->local('dimension'),
            'description' => $qs->count() === 1 ? $qs->first()->local('help_text') : __(':count pernyataan', ['count' => $qs->count()]),
            'weight' => round($weights[$name]),
        ])->values();
        $data['formConfig'] = AssessmentService::formFor($track);
        $data['showWeight'] = ($data['formConfig']['show_weight'] ?? false)
            || $weights->map(fn ($w) => round($w, 1))->unique()->count() > 1;
        $data['profile'] = AssessmentService::profileFor($track);
        $data['adminMode'] = $this->adminMode(request(), $track);

        $data['track'] = $track;
        $data['seo'] = Seo::make()
            ->title(__('Asesmen').': '.$track->local('name'))
            ->description($track->local('tagline').' — '.$track->local('description'))
            ->canonical('/asesmen/'.$track->slug)
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', __('Asesmen') => '/asesmen', $track->local('name') => '/asesmen/'.$track->slug])
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
                ->with('error', __('Isi identitas terlebih dahulu sebelum mengerjakan asesmen.'));
        }

        $track->load(['activeQuestions']);
        $grouped = $track->activeQuestions->groupBy('dimension');

        $data['track'] = $track;
        $data['grouped'] = $grouped;
        $data['labels'] = AssessmentService::scaleLabels();
        $data['formConfig'] = AssessmentService::formFor($track);
        $data['weights'] = AssessmentService::displayWeights($track, $grouped);
        $data['adminMode'] = $this->adminMode($request, $track);
        $data['seo'] = Seo::make()->title(__('Isi Asesmen').': '.$track->local('name'))->noindex()->toArray();

        return view('customer.assessment.form', $data);
    }

    public function submit(AssessmentSubmitRequest $request, string $slug)
    {
        $track = $this->findTrack($slug);
        $identity = $request->session()->get('assessment_identity_'.$track->id);

        if (! $identity) {
            return redirect()->route('assessment.intro', $track->slug)
                ->with('error', __('Sesi identitas berakhir. Mohon isi ulang identitas.'));
        }

        // Mode Input Admin: form & perhitungan sama persis dengan guest, tetapi hasil langsung terbuka tanpa bayar.
        $adminMode = $this->adminMode($request, $track);
        $extra = $adminMode
            ? ['source' => 'admin', 'created_by' => $request->user()->id, 'is_unlocked' => true]
            : ['source' => 'guest'];
        // Bahasa saat mengirim jawaban → bahasa email & laporan responden.
        $extra['locale'] = app()->getLocale();

        try {
            $validated = $request->validated();
            $result = AssessmentSubmissionService::store($track, $identity, $validated['answers'], $validated['notes'] ?? [], $extra);

            if (! $result) {
                return back()->withInput()->with('error', __('Masih ada pernyataan yang belum dijawab.'));
            }

            $request->session()->forget('assessment_identity_'.$track->id);

            if ($adminMode) {
                $request->session()->forget('assessment_admin_mode');

                return redirect()->route('assessment.result', $result->uuid)
                    ->with('status', __('Input admin tersimpan — hasil langsung terbuka tanpa pembayaran. Laporan AI sedang dibuat.'));
            }

            // Invoice langsung dibuat & dikirim ke email guest (setelah respons, agar halaman tidak lambat).
            if ($order = AssessmentPaymentService::ensureInvoice($result)) {
                dispatch(fn () => AssessmentMailer::send($result->fresh(), 'invoice', $order))->afterResponse();
            }

            return redirect()->route('assessment.result', $result->uuid);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Assessment Submit', $th);

            return back()->withInput()->with('error', __('Gagal menyimpan hasil asesmen. Silakan coba lagi.'));
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
                $data['seo'] = Seo::make()->title(__('Pembayaran Asesmen').': '.$result->track->local('name'))->description(__('Selesaikan pembayaran untuk membuka skor dan analisa lengkap asesmen :track dari GODEVI.', ['track' => $result->track->local('name')]))->image('assets/godevi-black.png')->noindex()->toArray();

                return view('customer.assessment.payment', $data);
            }
        }

        $track = $result->track;
        $band = AssessmentService::bandFor($track, (float) $result->total_score);
        // Nama dimensi & teks soal tersimpan dalam Bahasa Indonesia → tampilkan sesuai bahasa aktif.
        $questionText = $track->questions()->get()->mapWithKeys(fn ($q) => [$q->id => $q->local('question')]);
        $dimensions = [];
        foreach ($result->dimension_scores ?? [] as $name => $dim) {
            $dim['label'] = $track->dimensionLabel($name);
            $dim['advice'] = AssessmentService::dimensionAdvice($dim['label'], (float) $dim['score']);
            $dimensions[$name] = $dim;
        }

        $computed = [
            'dimensions' => $dimensions,
            'total' => (float) $result->total_score,
            'band' => __($result->band),
            'band_desc' => __($band['desc']),
        ];

        if ($track->slug === 'daya-saing-destinasi') {
            $computed['subindexes'] = AssessmentService::subindexScores($computed['dimensions']);
        }

        $names = array_keys($computed['dimensions']);
        $computed['strengths'] = array_slice($names, 0, 2);
        $computed['challenges'] = array_slice(array_reverse($names), 0, 2);
        $computed['priority_actions'] = [];
        foreach ($computed['dimensions'] as $name => $dim) {
            foreach ($dim['questions'] ?? [] as $item) {
                if (($item['score'] ?? 5) <= 2) {
                    $computed['priority_actions'][] = [
                        'dimension' => $dim['label'],
                        'action' => __('Perkuat: :item', ['item' => $questionText[$item['id'] ?? 0] ?? $item['question']]),
                    ];
                }
            }
            if (count($computed['priority_actions']) >= 7) {
                break;
            }
        }

        // Pengunjung English: laporan AI diterjemahkan sekali di antrean, lalu disimpan.
        if ($result->needsEnglishReport()) {
            TranslateAssessmentReport::dispatchOnce($result);
        }

        $data['result'] = $result;
        $data['computed'] = $computed;
        $data['labels'] = AssessmentService::scaleLabels();
        $data['bands'] = array_map(fn ($b) => ['label' => __($b['label'])] + $b, array_reverse(AssessmentService::bandsFor($track)));
        $data['formConfig'] = AssessmentService::formFor($result->track);
        $data['pendingOrder'] = AssessmentOrder::where('assessment_result_id', $result->id)
            ->where('status', 'pending')->latest()->first();
        $data['seo'] = Seo::make()->title(__('Hasil Asesmen').': '.$track->local('name'))->description(__('Laporan hasil asesmen :track (:organization) — skor kesiapan, analisis kekuatan & tantangan, dan draf strategi dari GODEVI.', ['track' => $track->local('name'), 'organization' => $result->organization ?: $result->name]))->image('assets/godevi-black.png')->noindex()->toArray();

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

        // Kode voucher (opsional) ikut dikirim bersama tombol bayar — tanpa langkah "Pakai" terpisah.
        $voucherCode = AssessmentVoucher::normalizeCode($request->input('voucher_code'));
        if ($voucherCode !== '') {
            $current = $result->orders()->where('status', 'pending')->latest('id')->first();
            if (! $current || $current->voucher_code !== $voucherCode) {
                $out = AssessmentPaymentService::applyVoucher($result, $voucherCode);
                if (! $out['ok']) {
                    return redirect()->route('assessment.result', $result->uuid)
                        ->withInput($request->only('voucher_code'))
                        ->with('voucher_error', $out['message']);
                }
                if ($result->refresh()->is_unlocked) { // voucher gratis → langsung terbuka
                    return redirect()->route('assessment.result', $result->uuid)->with('status', $out['message']);
                }
            }
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

            return back()->with('error', __('Gagal membuat pembayaran. Silakan coba lagi.'));
        }

        if (! AssessmentPaymentService::invoiceEmailed($result, $order)) {
            AssessmentMailer::send($result, 'invoice', $order);
        }

        return redirect()->route('assessment.payment', $order->code);
    }

    public function payment(string $code)
    {
        $order = AssessmentOrder::with('result.track')->where('code', $code)->firstOrFail();

        // Invoice lama yang diganti (voucher dipakai/dilepas) → halaman hasil menampilkan invoice terbaru.
        if ($order->status === 'paid' || $order->status === 'cancelled' || $order->result->is_unlocked) {
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
                ->with('error', __('Pembayaran untuk invoice ini tidak tersedia. Silakan klik "Bayar Sekarang" di halaman hasil.'));
        }

        $data['snapToken'] = $order->gateway_ref;
        $data['redirectURISuccess'] = route('assessment.result', $order->result->uuid);
        $data['redirectURIError'] = route('assessment.result', $order->result->uuid);
        $data['seo'] = Seo::make()->title(__('Pembayaran Laporan Asesmen'))->noindex()->toArray();

        return view('customer.payment.midtrans', $data);
    }

    /** Guest memakai kode voucher di halaman pembayaran. */
    public function applyVoucher(Request $request, string $uuid)
    {
        $result = AssessmentResult::with('track')->where('uuid', $uuid)->firstOrFail();
        $request->validate(['voucher_code' => 'required|string|max:40'], [], ['voucher_code' => __('Kode voucher')]);

        $out = AssessmentPaymentService::applyVoucher($result, $request->input('voucher_code'));

        return redirect()->route('assessment.result', $result->uuid)
            ->withInput($out['ok'] ? [] : $request->only('voucher_code'))
            ->with($out['ok'] ? 'status' : 'voucher_error', $out['message']);
    }

    /** Cek kode voucher secara langsung (tanpa mengubah invoice). */
    public function checkVoucher(Request $request, string $uuid)
    {
        $result = AssessmentResult::with('track')->where('uuid', $uuid)->firstOrFail();

        return response()->json(AssessmentPaymentService::previewVoucher($result, $request->input('voucher_code')));
    }

    public function removeVoucher(string $uuid)
    {
        $result = AssessmentResult::with('track')->where('uuid', $uuid)->firstOrFail();
        AssessmentPaymentService::removeVoucher($result);

        return redirect()->route('assessment.result', $result->uuid)->with('status', __('Voucher dilepas.'));
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
                ? __('Laporan masih belum berhasil dibuat. Silakan coba lagi beberapa saat lagi atau hubungi tim kami.')
                : __('Laporan sedang / sudah disusun ulang.')
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
            ->with('status', __('Laporan dibuka via Mode Tim GODEVI.'));
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
        $data['seo'] = Seo::make()->title(__('Cek Status Asesmen'))->noindex()->toArray();

        return view('customer.assessment.status', $data);
    }

    protected function findTrack(string $slug): AssessmentTrack
    {
        return AssessmentTrack::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }
}
