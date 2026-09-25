<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\OurTeam;
use Illuminate\Http\Request;

class AssessmentResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AssessmentResult::with(['track', 'pic'])->latest('id');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->get('track_id'));
        }
        if ($request->filled('status') && in_array($request->get('status'), ['baru', 'dihubungi', 'selesai'], true)) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('organization', 'like', "%{$q}%");
            });
        }

        $data['results'] = $query->paginate(20)->withQueryString();
        $data['tracks'] = AssessmentTrack::orderBy('sort_order')->get();
        $data['filters'] = $request->only(['track_id', 'status', 'q']);

        return view('backend.assessments.results.index', $data);
    }

    public function show($id)
    {
        $data['result'] = AssessmentResult::with(['track', 'track.activeQuestions', 'pic'])->findOrFail($id);
        $data['teams'] = OurTeam::orderBy('name')->get();

        return view('backend.assessments.results.show', $data);
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
