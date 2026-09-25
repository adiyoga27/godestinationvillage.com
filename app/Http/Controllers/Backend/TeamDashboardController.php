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
        $status = $request->get('status');

        $submissions = VillageSubmission::with('pic')->latest('id')->limit(10)->get();
        $packageOrders = Order::with('pic')->latest('id')->limit(10)->get();
        $eventOrders = OrderEvent::latest('id')->limit(10)->get();
        $homestayOrders = OrderHomestay::latest('id')->limit(10)->get();
        $assessmentResults = AssessmentResult::with(['track', 'pic'])->latest('id')->limit(10)->get();

        $data = [
            'teams' => OurTeam::orderBy('name')->get(),
            'stats' => [
                'submission_pending' => VillageSubmission::where('status', 'pending')->count(),
                'submission_total' => VillageSubmission::count(),
                'package_pending' => Order::where('payment_status', 'pending')->count(),
                'package_success' => Order::where('payment_status', 'success')->count(),
                'event_pending' => OrderEvent::where('payment_status', 'pending')->count(),
                'event_success' => OrderEvent::where('payment_status', 'success')->count(),
                'homestay_pending' => OrderHomestay::where('payment_status', 'pending')->count(),
                'homestay_success' => OrderHomestay::where('payment_status', 'success')->count(),
                'unassigned' => Order::whereNull('pic_team_id')->count()
                    + OrderEvent::whereNull('pic_team_id')->count()
                    + OrderHomestay::whereNull('pic_team_id')->count()
                    + VillageSubmission::whereNull('pic_team_id')->count()
                    + AssessmentResult::whereNull('pic_team_id')->count(),
                'assessment_new' => AssessmentResult::where('status', 'baru')->count(),
                'assessment_total' => AssessmentResult::count(),
            ],
            'submissions' => $submissions,
            'packageOrders' => $packageOrders,
            'eventOrders' => $eventOrders,
            'homestayOrders' => $homestayOrders,
            'assessmentResults' => $assessmentResults,
            'tracks' => AssessmentTrack::orderBy('sort_order')->get(),
            'filter_status' => $status,
        ];

        return view('backend.team_dashboard.index', $data);
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
