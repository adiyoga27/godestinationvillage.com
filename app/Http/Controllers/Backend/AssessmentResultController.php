<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\OurTeam;
use App\Jobs\GenerateAssessmentReport;
use App\Services\AssessmentMailer;
use App\Services\AssessmentPaymentService;
use Illuminate\Http\Request;

class AssessmentResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Tab daftar hasil (filter di index()). */
    protected const TABS = ['all', 'followup', 'unpaid', 'paid', 'failed'];

    public function index(Request $request)
    {
        // Filter umum (pencarian, jalur, sumber, status tindak lanjut) berlaku untuk daftar & hitungan tab.
        $base = function () use ($request) {
            $query = AssessmentResult::query();
            if ($request->filled('track_id')) {
                $query->where('track_id', $request->get('track_id'));
            }
            if (in_array($request->get('status'), ['baru', 'dihubungi', 'selesai'], true)) {
                $query->where('status', $request->get('status'));
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

            return $query;
        };
        $applyTab = function ($query, string $tab) {
            return match ($tab) {
                // Sudah lunas/terbuka tetapi belum dihubungi tim.
                'followup' => $query->where('is_unlocked', true)->where('status', 'baru'),
                'unpaid' => $query->where('is_unlocked', false),
                'paid' => $query->where('is_unlocked', true),
                'failed' => $query->where('is_unlocked', true)->whereNull('ai_report')->where('report_status', 'failed'),
                default => $query,
            };
        };

        // Kompatibel dengan link lama ?payment=belum|lunas.
        $tab = $request->get('tab', ['belum' => 'unpaid', 'lunas' => 'paid'][$request->get('payment')] ?? 'all');
        $tab = in_array($tab, self::TABS, true) ? $tab : 'all';

        $data['results'] = $applyTab($base(), $tab)
            ->with(['track', 'pic', 'latestOrder', 'orders:id,assessment_result_id,code'])
            ->latest('id')
            ->paginate(20)
            ->withQueryString();
        $data['tabCounts'] = collect(self::TABS)->mapWithKeys(fn ($t) => [$t => $applyTab($base(), $t)->count()]);
        $data['tab'] = $tab;
        $data['tracks'] = AssessmentTrack::orderBy('sort_order')->get();
        $data['filters'] = $request->only(['track_id', 'status', 'q', 'source']);
        $data['stats'] = [
            'total' => AssessmentResult::count(),
            'followup' => AssessmentResult::where('is_unlocked', true)->where('status', 'baru')->count(),
            'unpaid' => AssessmentResult::where('is_unlocked', false)->count(),
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
     * Input Manual: pilih jalur, lalu admin mengisi form guest yang sama persis (Mode Input Admin).
     */
    public function create()
    {
        $data['tracks'] = AssessmentTrack::where('is_active', true)->withCount('activeQuestions')->orderBy('sort_order')->get();

        return view('backend.assessments.results.create', $data);
    }

    /**
     * Aktifkan Mode Input Admin untuk jalur ini lalu buka alur guest (profil → soal → hasil).
     */
    public function input(Request $request, string $slug)
    {
        $track = AssessmentTrack::where('is_active', true)->where('slug', $slug)->firstOrFail();

        $request->session()->put('assessment_admin_mode', ['track_id' => $track->id, 'user_id' => $request->user()->id]);
        $request->session()->forget('assessment_identity_'.$track->id);

        return redirect()->route('assessment.intro', $track->slug);
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

    public function destroy(Request $request, $id)
    {
        $result = AssessmentResult::findOrFail($id);
        $label = $result->organization ?: $result->name;
        // Invoice ikut terhapus (FK cascade).
        $result->delete();

        // Kembali ke daftar dengan filter/halaman yang sama bila dihapus dari daftar.
        $back = (string) $request->input('redirect');
        $index = route('assessment-results.index');
        $target = str_starts_with($back, $index) ? $back : $index;

        return redirect()->to($target)->with('status', 'Hasil asesmen "'.$label.'" dihapus.');
    }
}
