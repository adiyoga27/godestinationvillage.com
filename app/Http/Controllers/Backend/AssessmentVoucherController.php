<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentTrack;
use App\Models\AssessmentVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Asesmen > Voucher Diskon: kode yang dimasukkan guest di halaman pembayaran
 * untuk asesmen gratis atau potongan harga.
 */
class AssessmentVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $batch = $request->query('batch');
        $search = AssessmentVoucher::normalizeCode($request->query('q'));

        $data['vouchers'] = AssessmentVoucher::withCount('paidOrders')
            ->when($batch, fn ($q) => $q->where('batch', $batch))
            ->when($search !== '', fn ($q) => $q->where('code', 'like', '%'.$search.'%'))
            ->latest('id')->paginate(50)->withQueryString();
        $data['tracks'] = AssessmentTrack::orderBy('sort_order')->pluck('name', 'id');
        $data['batches'] = AssessmentVoucher::whereNotNull('batch')
            ->selectRaw('batch, count(*) as total, sum(is_active) as active, max(created_at) as created_at')
            ->groupBy('batch')->orderByDesc('created_at')->get();
        $data['batch'] = $batch;
        $data['search'] = $search;

        return view('backend.assessments.vouchers.index', $data);
    }

    public function create()
    {
        return view('backend.assessments.vouchers.form', [
            'voucher' => new AssessmentVoucher(['type' => 'percent', 'is_active' => true, 'code' => AssessmentVoucher::generateCode(null, 8)]),
            'tracks' => AssessmentTrack::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->input('mode') !== 'bulk') {
            AssessmentVoucher::create($this->validated($request) + ['created_by' => $request->user()->id]);

            return redirect()->route('assessment-vouchers.index')->with('status', 'Voucher dibuat.');
        }

        // Bulk: banyak kode acak dengan pengaturan sama, dikelompokkan dalam satu batch.
        $bulk = $request->validate([
            'quantity' => 'required|integer|min:2|max:1000',
            'prefix' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_-]+$/'],
        ], ['prefix.regex' => 'Awalan hanya boleh huruf, angka, - dan _.'], ['quantity' => 'jumlah kode', 'prefix' => 'awalan']);
        $settings = $this->validated($request, null, false);
        $prefix = AssessmentVoucher::normalizeCode($bulk['prefix'] ?? '');
        $batch = ($prefix !== '' ? $prefix : 'BATCH').'-'.now()->format('ymd-His');

        DB::transaction(function () use ($bulk, $settings, $prefix, $batch, $request) {
            for ($i = 0; $i < $bulk['quantity']; $i++) {
                AssessmentVoucher::create($settings + [
                    'code' => AssessmentVoucher::generateCode($prefix),
                    'batch' => $batch,
                    'created_by' => $request->user()->id,
                ]);
            }
        });

        return redirect()->route('assessment-vouchers.index', ['batch' => $batch])
            ->with('status', $bulk['quantity'].' kode voucher dibuat (batch '.$batch.'). Unduh daftarnya lewat tombol "Unduh CSV".');
    }

    /** Unduh daftar kode (semua, atau satu batch) sebagai CSV untuk dibagikan. */
    public function export(Request $request): StreamedResponse
    {
        $batch = $request->query('batch');
        $tracks = AssessmentTrack::pluck('name', 'id');
        $vouchers = AssessmentVoucher::withCount('paidOrders')->when($batch, fn ($q) => $q->where('batch', $batch))->orderBy('id')->get();

        return response()->streamDownload(function () use ($vouchers, $tracks) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Kode', 'Diskon', 'Jalur', 'Terpakai', 'Kuota', 'Mulai', 'Berakhir', 'Status', 'Batch', 'Keterangan']);
            foreach ($vouchers as $v) {
                fputcsv($out, [
                    $v->code,
                    $v->label(),
                    empty($v->track_ids) ? 'Semua jalur' : collect($v->track_ids)->map(fn ($id) => $tracks[$id] ?? '#'.$id)->implode(', '),
                    $v->usedCount(),
                    $v->max_uses ?? 'Tanpa batas',
                    $v->starts_at?->format('Y-m-d H:i'),
                    $v->expires_at?->format('Y-m-d H:i'),
                    $v->statusLabel(),
                    $v->batch,
                    $v->description,
                ]);
            }
            fclose($out);
        }, 'voucher-asesmen'.($batch ? '-'.$batch : '').'.csv', ['Content-Type' => 'text/csv']);
    }

    /** Aktifkan / nonaktifkan semua kode dalam satu batch. */
    public function toggleBatch(Request $request)
    {
        $data = $request->validate(['batch' => 'required|string|max:60', 'active' => 'required|boolean']);
        $count = AssessmentVoucher::where('batch', $data['batch'])->update(['is_active' => (bool) $data['active']]);

        return back()->with('status', $count.' voucher di batch '.$data['batch'].($data['active'] ? ' diaktifkan.' : ' dinonaktifkan.'));
    }

    public function edit($id)
    {
        return view('backend.assessments.vouchers.form', [
            'voucher' => AssessmentVoucher::withCount('paidOrders')->findOrFail($id),
            'tracks' => AssessmentTrack::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $voucher = AssessmentVoucher::findOrFail($id);
        $voucher->update($this->validated($request, $voucher));

        return redirect()->route('assessment-vouchers.index')->with('status', 'Voucher '.$voucher->code.' diperbarui.');
    }

    public function toggle($id)
    {
        $voucher = AssessmentVoucher::findOrFail($id);
        $voucher->update(['is_active' => ! $voucher->is_active]);

        return back()->with('status', 'Voucher '.$voucher->code.($voucher->is_active ? ' diaktifkan.' : ' dinonaktifkan.'));
    }

    public function destroy($id)
    {
        $voucher = AssessmentVoucher::withCount('orders')->findOrFail($id);

        // Voucher yang sudah tercatat di invoice cukup dinonaktifkan agar riwayat tetap utuh.
        if ($voucher->orders_count > 0) {
            $voucher->update(['is_active' => false]);

            return back()->with('status', 'Voucher '.$voucher->code.' sudah pernah dipakai, jadi dinonaktifkan (tidak dihapus).');
        }

        $voucher->delete();

        return back()->with('status', 'Voucher '.$voucher->code.' dihapus.');
    }

    /**
     * @param  bool  $withCode  false untuk bulk (kode dibuat otomatis)
     */
    protected function validated(Request $request, ?AssessmentVoucher $voucher = null, bool $withCode = true): array
    {
        $request->merge(['code' => AssessmentVoucher::normalizeCode($request->input('code'))]);

        $data = $request->validate([
            'code' => $withCode
                ? ['required', 'string', 'max:40', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('assessment_vouchers', 'code')->ignore($voucher?->id)]
                : ['nullable'],
            'description' => 'nullable|string|max:191',
            'type' => ['required', Rule::in(array_keys(AssessmentVoucher::TYPES))],
            'value' => 'nullable|required_unless:type,free|integer|min:1|max:100000000',
            'track_ids' => 'nullable|array',
            'track_ids.*' => 'integer|exists:assessment_tracks,id',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ], [
            'code.regex' => 'Kode hanya boleh huruf, angka, - dan _ (tanpa spasi).',
            'value.required_unless' => 'Isi besar diskon.',
        ], [
            'code' => 'kode voucher', 'value' => 'besar diskon', 'max_uses' => 'kuota', 'starts_at' => 'mulai berlaku', 'expires_at' => 'berakhir',
        ]);

        if ($data['type'] === 'percent' && $data['value'] > 100) {
            throw ValidationException::withMessages(['value' => 'Diskon persen maksimal 100.']);
        }

        $data['value'] = $data['type'] === 'free' ? 100 : (int) $data['value'];
        $data['track_ids'] = ! empty($data['track_ids']) ? array_map('intval', $data['track_ids']) : null;
        $data['is_active'] = $request->boolean('is_active');
        if (! $withCode) {
            unset($data['code']);
        }

        return $data;
    }
}
