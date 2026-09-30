<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;

use App\Services\UserService;
use App\Services\PackageService;
use App\Services\OrderService;
use App\Services\InstagramServices;
use App\Models\AssessmentResult;
use App\Models\AssessmentTrack;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OrderHomestay;
use App\Models\OurTeam;
use App\Models\VillageSubmission;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
      
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      
        $count_admin = UserService::count_by_role(1);
        $count_village = UserService::count_by_role(2);
        $count_member = UserService::count_by_role(3);

        if(Auth::user()->role_id == 1)
        {
            $count_package = PackageService::count();
            $count_order = OrderService::count();
            $sum_order = OrderService::income();
        }
        else
        {
            $count_package = PackageService::count(Auth::user()->id);
            $count_order = OrderService::count(Auth::user()->id);
            $sum_order = OrderService::income(Auth::user()->id);
        }

        $instagram = InstagramServices::randomPost();

        // Inbox tim digabung ke dashboard utama (khusus admin).
        // Nama variabel disamakan dengan TeamDashboardController agar partial bisa dipakai ulang.
        $teams = collect();
        $stats = [];
        $submissions = collect();
        $packageOrders = collect();
        $eventOrders = collect();
        $homestayOrders = collect();
        $assessmentResults = collect();
        $tracks = collect();

        if (Auth::user()->role_id == 1) {
            $teams = OurTeam::orderBy('name')->get();
            $stats = [
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
            ];
            $submissions = VillageSubmission::with('pic')->latest('id')->limit(10)->get();
            $packageOrders = Order::with('pic')->latest('id')->limit(10)->get();
            $eventOrders = OrderEvent::latest('id')->limit(10)->get();
            $homestayOrders = OrderHomestay::latest('id')->limit(10)->get();
            $assessmentResults = AssessmentResult::with(['track', 'pic'])->latest('id')->limit(10)->get();
            $tracks = AssessmentTrack::orderBy('sort_order')->get();
        }

        return view('backend.dashboard')->with(compact(
            'count_admin',
            'count_village',
            'count_member',
            'count_package',
            'count_order',
            'sum_order',
            'instagram',
            'teams',
            'stats',
            'submissions',
            'packageOrders',
            'eventOrders',
            'homestayOrders',
            'assessmentResults',
            'tracks'
        ));
    }
}
