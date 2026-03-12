<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ReportOrderExport;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use App\Services\OrderEventService;
use App\Services\PackageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportEventController extends Controller
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
    	$event = EventService::pluck()->prepend('Semua Event', 'All');
        $packages = [];
      
    	return view('backend.events.report.index')->with(compact('event'));
    }

    public function get_package(Request $request)
    {
    	$data = PackageService::find_by_village($request->id);

    	return response()->json([
            'code' => 200,
            'status' => 'OK',
            'message' => 'Data have been gotten successfully',
            'data' => $data
        ]);
    }

    public function get_order(Request $request)
    {
        return DataTables::of(OrderEventService::search_order($request->village_id, $request->package_id, $request->start_date, $request->end_date))
        ->editColumn('package_price', function($order){
            return number_format($order->package_price);
        })->editColumn('total_payment', function($order){
            return number_format($order->total_payment);
        })->editColumn('payment_type', function($order){
            return str_replace('_', '', strtoupper($order->payment_type));
        })->editColumn('created_at', function($order){
            return date('Y-m-d', strtotime($order->created_at));
        })->editColumn('payment_status', function($order){
            if($order->payment_status == 'pending')
                return "<label class='badge badge-gradient-warning'>Pending</label>";
            elseif($order->payment_status == 'success')
                return "<label class='badge badge-gradient-success'>Sukses</label>";
            elseif($order->payment_status == 'cancel')
                return "<label class='badge badge-gradient-danger'>Cancel</label>";
        })->rawColumns(['action', 'payment_status'])->make(true);
    }

    public function export_xls(Request $request)
    {
    	return (new ReportOrderExport($request->village_id, $request->package_id, $request->start_date, $request->end_date))->download('report.xlsx');
    }
}
