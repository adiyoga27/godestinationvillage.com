<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;
use App\Services\AssessmentContentTranslator;
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
        $data['missingEn'] = $track->questions()->where(fn ($q) => $q->whereNull('question_en')->orWhere('question_en', ''))->count();

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
            'dimension_en' => 'nullable|string|max:191',
            'question' => 'required|string|max:2000',
            'question_en' => 'nullable|string|max:2000',
            'help_text' => 'nullable|string|max:2000',
            'help_text_en' => 'nullable|string|max:2000',
            'weight' => 'nullable|integer|min:1|max:10',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $track->questions()->create($validated);
        $this->syncDimensionEn($track->id, $validated);

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
            'dimension_en' => 'nullable|string|max:191',
            'question' => 'required|string|max:2000',
            'question_en' => 'nullable|string|max:2000',
            'help_text' => 'nullable|string|max:2000',
            'help_text_en' => 'nullable|string|max:2000',
            'weight' => 'nullable|integer|min:1|max:10',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $question->update($validated);
        $this->syncDimensionEn((int) $trackId, $validated);

        return redirect()->route('assessments.questions.index', $trackId)
            ->with('status', 'Pertanyaan diperbarui.');
    }

    public function destroy($trackId, $id)
    {
        AssessmentQuestion::where('track_id', $trackId)->findOrFail($id)->delete();

        return redirect()->route('assessments.questions.index', $trackId)
            ->with('status', 'Pertanyaan dihapus.');
    }

    /**
     * Isi otomatis versi English (AI) untuk jalur ini — hanya kolom English yang masih kosong.
     */
    public function translate($trackId)
    {
        $track = AssessmentTrack::findOrFail($trackId);
        set_time_limit(300);
        $out = AssessmentContentTranslator::translateTrack($track);

        return redirect()->route('assessments.questions.index', $track->id)->with(
            $out['ok'] ? 'status' : 'error',
            $out['ok']
                ? ($out['filled'] > 0 ? $out['filled'].' kolom English diisi otomatis. Periksa & rapikan bila perlu.' : 'Semua kolom English sudah terisi.')
                : 'Terjemahan otomatis gagal ('.$out['error'].'). '.$out['filled'].' kolom sempat terisi — coba lagi.'
        );
    }

    /**
     * Nama dimensi English berlaku per dimensi: samakan ke semua soal berdimensi sama
     * agar label di halaman hasil konsisten.
     */
    protected function syncDimensionEn(int $trackId, array $validated): void
    {
        if (filled($validated['dimension_en'] ?? null)) {
            AssessmentQuestion::where('track_id', $trackId)
                ->where('dimension', $validated['dimension'])
                ->update(['dimension_en' => $validated['dimension_en']]);
        }
    }
}
