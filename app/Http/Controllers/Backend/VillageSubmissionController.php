<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OurTeam;
use App\Models\VillageSubmission;
use Illuminate\Http\Request;

class VillageSubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = VillageSubmission::with('pic')->latest('id');

        if ($request->filled('status') && in_array($request->get('status'), ['pending', 'verified', 'rejected'], true)) {
            $query->where('status', $request->get('status'));
        }
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($w) use ($q) {
                $w->where('village_name', 'like', "%{$q}%")
                    ->orWhere('contact_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $data['submissions'] = $query->paginate(20)->withQueryString();
        $data['teams'] = OurTeam::orderBy('name')->get();
        $data['filters'] = $request->only(['status', 'q']);

        return view('backend.village_submissions.index', $data);
    }

    public function show($id)
    {
        $data['submission'] = VillageSubmission::with('pic')->findOrFail($id);
        $data['teams'] = OurTeam::orderBy('name')->get();

        return view('backend.village_submissions.show', $data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'pic_team_id' => 'nullable|exists:our_teams,id',
            'internal_note' => 'nullable|string|max:5000',
        ]);

        $submission = VillageSubmission::findOrFail($id);
        $submission->update($validated);

        return redirect()->route('village-submissions.show', $submission->id)
            ->with('status', 'Status pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        VillageSubmission::findOrFail($id)->delete();

        return redirect()->route('village-submissions.index')
            ->with('status', 'Pengajuan berhasil dihapus.');
    }
}
