<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\OrderService;
use App\Services\VillageService;
use App\Services\PackageService;
use Excel;
use App\Exports\ReportOrderExport;

use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportVillageController extends Controller
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
    	$villages = VillageService::pluck()->prepend('Semua Desa', 'All');
        $packages = [];
        if(Auth::user()->role_id == 2)
        {
            $packages = PackageService::pluck(Auth::user()->id)->prepend('Semua Paket', 'All');
        }
    	return view('backend.village_report.index')->with(compact('villages', 'packages'));
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
        return DataTables::of(OrderService::search_order($request->village_id, $request->package_id, $request->start_date, $request->end_date))
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