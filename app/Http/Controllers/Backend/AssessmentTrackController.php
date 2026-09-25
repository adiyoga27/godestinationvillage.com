<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssessmentTrackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['tracks'] = AssessmentTrack::withCount(['questions', 'results'])->orderBy('sort_order')->get();

        return view('backend.assessments.tracks.index', $data);
    }

    public function create()
    {
        return view('backend.assessments.tracks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'tagline' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:5000',
            'target_audience' => 'nullable|string|max:191',
            'estimated_minutes' => 'nullable|integer|min:1|max:120',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.time();
        $validated['is_active'] = $request->boolean('is_active', true);

        $track = AssessmentTrack::create($validated);

        return redirect()->route('assessments.questions.index', $track->id)
            ->with('status', 'Jalur asesmen dibuat. Tambahkan pertanyaan.');
    }

    public function edit($id)
    {
        $data['track'] = AssessmentTrack::findOrFail($id);

        return view('backend.assessments.tracks.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $track = AssessmentTrack::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'tagline' => 'nullable|string|max:191',
            'description' => 'nullable|string|max:5000',
            'target_audience' => 'nullable|string|max:191',
            'estimated_minutes' => 'nullable|integer|min:1|max:120',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $track->update($validated);

        return redirect()->route('assessments.index')->with('status', 'Jalur asesmen diperbarui.');
    }

    public function destroy($id)
    {
        AssessmentTrack::findOrFail($id)->delete();

        return redirect()->route('assessments.index')->with('status', 'Jalur asesmen dihapus.');
    }
}
