<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;
use Illuminate\Http\Request;

class AssessmentQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($trackId)
    {
        $track = AssessmentTrack::findOrFail($trackId);
        $data['track'] = $track;
        $data['questions'] = $track->questions()->orderBy('sort_order')->paginate(50);

        return view('backend.assessments.questions.index', $data);
    }

    public function create($trackId)
    {
        $data['track'] = AssessmentTrack::findOrFail($trackId);

        return view('backend.assessments.questions.create', $data);
    }

    public function store(Request $request, $trackId)
    {
        $track = AssessmentTrack::findOrFail($trackId);

        $validated = $request->validate([
            'dimension' => 'required|string|max:191',
            'question' => 'required|string|max:2000',
            'help_text' => 'nullable|string|max:2000',
            'weight' => 'nullable|integer|min:1|max:10',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $track->questions()->create($validated);

        return redirect()->route('assessments.questions.index', $track->id)
            ->with('status', 'Pertanyaan ditambahkan.');
    }

    public function edit($trackId, $id)
    {
        $data['track'] = AssessmentTrack::findOrFail($trackId);
        $data['question'] = AssessmentQuestion::where('track_id', $trackId)->findOrFail($id);

        return view('backend.assessments.questions.edit', $data);
    }

    public function update(Request $request, $trackId, $id)
    {
        $question = AssessmentQuestion::where('track_id', $trackId)->findOrFail($id);

        $validated = $request->validate([
            'dimension' => 'required|string|max:191',
            'question' => 'required|string|max:2000',
            'help_text' => 'nullable|string|max:2000',
            'weight' => 'nullable|integer|min:1|max:10',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $question->update($validated);

        return redirect()->route('assessments.questions.index', $trackId)
            ->with('status', 'Pertanyaan diperbarui.');
    }

    public function destroy($trackId, $id)
    {
        AssessmentQuestion::where('track_id', $trackId)->findOrFail($id)->delete();

        return redirect()->route('assessments.questions.index', $trackId)
            ->with('status', 'Pertanyaan dihapus.');
    }
}
