<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\OurTeam;
use App\Jobs\GenerateAssessmentReport;
use App\Services\AssessmentMailer;
use App\Services\AssessmentPaymentService;
use App\Services\AssessmentService;
use App\Services\AssessmentSubmissionService;
use Illuminate\Http\Request;

class AssessmentResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AssessmentResult::with(['track', 'pic', 'latestOrder'])->latest('id');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->get('track_id'));
        }
        if ($request->filled('status') && in_array($request->get('status'), ['baru', 'dihubungi', 'selesai'], true)) {
            $query->where('status', $request->get('status'));
        }
        if ($request->get('payment') === 'lunas') {
            $query->where('is_unlocked', true);
        } elseif ($request->get('payment') === 'belum') {
            $query->where('is_unlocked', false);
        }
        if (in_array($request->get('source'), ['guest', 'admin'], true)) {
            $query->where('source', $request->get('source'));
        }
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('organization', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('regency', 'like', "%{$q}%")
                    ->orWhere('province', 'like', "%{$q}%")
                    ->orWhereHas('orders', fn ($o) => $o->where('code', 'like', "%{$q}%"));
            });
        }

        $data['results'] = $query->paginate(20)->withQueryString();
        $data['tracks'] = AssessmentTrack::orderBy('sort_order')->get();
        $data['filters'] = $request->only(['track_id', 'status', 'q', 'payment', 'source']);
        $data['stats'] = [
            'total' => AssessmentResult::count(),
            'unpaid' => AssessmentResult::where('is_unlocked', false)->count(),
            'paid' => AssessmentResult::where('is_unlocked', true)->count(),
            'revenue' => (int) \App\Models\AssessmentOrder::where('status', 'paid')->where('gateway', '!=', 'manual')->sum('amount'),
        ];

        return view('backend.assessments.results.index', $data);
    }

    public function show($id)
    {
        $data['result'] = AssessmentResult::with(['track', 'track.activeQuestions', 'pic', 'latestOrder', 'orders', 'creator', 'approver'])->findOrFail($id);
        $data['teams'] = OurTeam::orderBy('name')->get();

        return view('backend.assessments.results.show', $data);
    }

    /**
     * Input asesmen manual oleh admin atas nama guest — tanpa pembayaran, hasil langsung terbuka.
     */
    public function create(Request $request)
    {
        $tracks = AssessmentTrack::where('is_active', true)->orderBy('sort_order')->get();
        $track = $tracks->firstWhere('slug', $request->get('track')) ?? null;

        if ($track) {
            $track->load('activeQuestions');
            $grouped = $track->activeQuestions->groupBy('dimension');
            $data['grouped'] = $grouped;
            $data['weights'] = AssessmentService::displayWeights($track, $grouped);
            $data['profile'] = AssessmentService::profileFor($track);
            $data['formConfig'] = AssessmentService::formFor($track);
        }

        $data['tracks'] = $tracks;
        $data['track'] = $track;
        $data['labels'] = AssessmentService::SCALE_LABELS;

        return view('backend.assessments.results.create', $data);
    }

    public function store(Request $request)
    {
        $track = AssessmentTrack::where('is_active', true)->findOrFail($request->input('track_id'));
        [$rules, $messages, $attributes] = AssessmentSubmissionService::profileRules($track);

        $validated = $request->validate(array_merge($rules, [
            // Admin boleh tanpa email (mis. data dari kunjungan lapangan).
            'email' => 'nullable|email:rfc|max:191',
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:1000',
            'internal_note' => 'nullable|string|max:5000',
        ]), $messages + ['answers.required' => 'Isi seluruh penilaian dimensi.'], $attributes);

        $result = AssessmentSubmissionService::store($track, $validated, $validated['answers'], $validated['notes'] ?? [], [
            'source' => 'admin',
            'created_by' => $request->user()->id,
            'is_unlocked' => true,
            'internal_note' => $validated['internal_note'] ?? null,
        ]);

        if (! $result) {
            return back()->withInput()->with('error', 'Masih ada dimensi yang belum dinilai.');
        }

        return redirect()->route('assessment-results.show', $result->id)
            ->with('status', 'Asesmen manual disimpan. Laporan AI sedang dibuat.');
    }

    /**
     * Approve pembayaran manual (transfer langsung, dll.) → hasil terbuka & laporan AI dibuat.
     */
    public function approve(Request $request, $id)
    {
        $result = AssessmentResult::with('track')->findOrFail($id);

        if ($result->is_unlocked) {
            return back()->with('error', 'Hasil ini sudah lunas / terbuka.');
        }

        $validated = $request->validate(['approval_note' => 'nullable|string|max:500']);
        $order = AssessmentPaymentService::approveManually($result, $request->user()->id, $validated['approval_note'] ?? null);

        return back()->with('status', 'Pembayaran di-approve manual ('.$order->code.'). Laporan AI sedang dibuat.');
    }

    /**
     * Buat ulang laporan AI (mis. setelah gagal atau API key baru dipasang).
     */
    public function regenerate($id)
    {
        $result = AssessmentResult::findOrFail($id);

        if (! $result->is_unlocked) {
            return back()->with('error', 'Laporan hanya dibuat untuk hasil yang sudah lunas / di-approve.');
        }

        $result->update(['ai_report' => null, 'report_status' => 'pending', 'report_error' => null]);
        GenerateAssessmentReport::dispatch($result->uuid);

        return back()->with('status', 'Laporan AI dijadwalkan ulang.');
    }

    /**
     * Kirim ulang email ke guest: invoice, bukti lunas, atau hasil & strategi.
     */
    public function resendEmail(Request $request, $id)
    {
        $result = AssessmentResult::with(['track', 'latestOrder', 'latestPaidOrder'])->findOrFail($id);
        $type = $request->validate(['type' => 'required|in:invoice,paid,report'])['type'];

        if (! $result->email) {
            return back()->with('error', 'Hasil ini belum punya email.');
        }
        if ($type === 'report' && empty($result->ai_report)) {
            return back()->with('error', 'Laporan AI belum tersedia.');
        }

        $order = $type === 'invoice' ? $result->latestOrder : $result->latestPaidOrder;
        if ($type !== 'report' && ! $order) {
            return back()->with('error', 'Belum ada invoice untuk dikirim.');
        }

        $ok = AssessmentMailer::send($result, $type, $order);

        return back()->with($ok ? 'status' : 'error', $ok ? 'Email terkirim ke '.$result->email.'.' : 'Email gagal dikirim — lihat riwayat email.');
    }

    public function update(Request $request, $id)
    {
        $result = AssessmentResult::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:baru,dihubungi,selesai',
            'pic_team_id' => 'nullable|exists:our_teams,id',
            'internal_note' => 'nullable|string|max:5000',
        ]);

        $result->update($validated);

        return redirect()->route('assessment-results.show', $result->id)
            ->with('status', 'Tindak lanjut hasil asesmen disimpan.');
    }

    public function destroy($id)
    {
        AssessmentResult::findOrFail($id)->delete();

        return redirect()->route('assessment-results.index')
            ->with('status', 'Hasil asesmen dihapus.');
    }
}
