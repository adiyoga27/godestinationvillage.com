<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OrderHomestay;
use App\Models\OurTeam;
use App\Models\VillageSubmission;
use Illuminate\Http\Request;

class TeamDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Dashboard Tim sudah digabung ke Dashboard utama (/administrator/dashboard).
        // URL lama tetap diarahkan ke sana agar bookmark tidak rusak.
        // Form assign PIC (POST team-dashboard.assign) tetap aktif karena partial
        // _inbox yang dipakai dashboard utama mengarah ke route tersebut.
        return redirect()->route('home');
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:village_submission,package,event,homestay,assessment',
            'id' => 'required|integer',
            'pic_team_id' => 'nullable|exists:our_teams,id',
            'internal_note' => 'nullable|string|max:2000',
            'status' => 'nullable|string|max:50',
        ]);

        $model = match ($validated['type']) {
            'village_submission' => VillageSubmission::findOrFail($validated['id']),
            'package' => Order::findOrFail($validated['id']),
            'event' => OrderEvent::findOrFail($validated['id']),
            'homestay' => OrderHomestay::findOrFail($validated['id']),
            'assessment' => AssessmentResult::findOrFail($validated['id']),
        };

        if (array_key_exists('pic_team_id', $validated)) {
            $model->pic_team_id = $validated['pic_team_id'];
        }
        if (array_key_exists('internal_note', $validated)) {
            $model->internal_note = $validated['internal_note'];
        }
        if (! empty($validated['status'])) {
            if ($validated['type'] === 'village_submission' && in_array($validated['status'], ['pending', 'verified', 'rejected'], true)) {
                $model->status = $validated['status'];
            }
            if (in_array($validated['type'], ['package', 'event', 'homestay'], true) && in_array($validated['status'], ['pending', 'success', 'cancel'], true)) {
                $model->payment_status = $validated['status'];
            }
            if ($validated['type'] === 'assessment' && in_array($validated['status'], ['baru', 'dihubungi', 'selesai'], true)) {
                $model->status = $validated['status'];
            }
        }

        $model->save();

        return back()->with('status', 'Penugasan tim berhasil disimpan.');
    }
}
