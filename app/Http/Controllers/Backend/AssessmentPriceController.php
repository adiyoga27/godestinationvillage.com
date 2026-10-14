<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentTrack;
use Illuminate\Http\Request;

/**
 * Asesmen > Harga: atur harga semua jalur dalam satu halaman.
 * Harga baru berlaku untuk invoice berikutnya; invoice yang sudah terbit tidak berubah.
 */
class AssessmentPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['tracks'] = AssessmentTrack::withCount(['results as pending_count' => fn ($q) => $q->where('is_unlocked', false)])
            ->orderBy('sort_order')->get();

        return view('backend.assessments.prices.index', $data);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'prices' => 'required|array',
            'prices.*' => 'required|integer|min:0|max:100000000',
        ], [], ['prices.*' => 'harga']);

        $changed = 0;
        foreach (AssessmentTrack::whereIn('id', array_keys($validated['prices']))->get() as $track) {
            $price = (int) $validated['prices'][$track->id];
            if ((int) $track->price !== $price) {
                $track->update(['price' => $price]);
                $changed++;
            }
        }

        return redirect()->route('assessment-prices.index')
            ->with('status', $changed ? $changed.' harga jalur diperbarui. Berlaku untuk invoice baru.' : 'Tidak ada harga yang berubah.');
    }
}
