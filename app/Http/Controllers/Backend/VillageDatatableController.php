<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\PackageService;
use App\Services\OrderService;

use Yajra\DataTables\Html\Builder;
use DataTables;

use Session;
use Auth;

class VillageDatatableController extends Controller
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

    public function get_packages($id)
    {
        return Datatables::of(PackageService::find_by_user($id))
        ->editColumn('is_active', function($package){
            if($package->is_active == 0)
                return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
            else
                return "<label class='badge badge-gradient-success'>Aktif</label>";
        })
        ->editColumn('price', function($package){
            return number_format($package->price);
        })->rawColumns(['is_active'])->make(true);
    }

    public function get_orders($id)
    {
    	return Datatables::of(OrderService::find_by_user($id))
        ->addColumn('action', function($order){
            return "<a href='". route('orders.show', $order->id) ."' class='btn btn-sm btn-outline-primary'>Show</a>";
        })
        ->editColumn('package_price', function($order){
            return number_format($order->package_price);
        })
        ->editColumn('package_discount', function($order){
            return number_format($order->package_discount);
        })
        ->editColumn('total_payment', function($order){
            return number_format($order->total_payment);
        })
        ->editColumn('payment_type', function($order){
            return str_replace('_', '', strtoupper($order->payment_type));
        })
        ->editColumn('payment_status', function($order){
            if($order->payment_status == 'pending')
                return "<label class='badge badge-gradient-warning'>Pending</label>";
            elseif($order->payment_status == 'success')
                return "<label class='badge badge-gradient-success'>Sukses</label>";
            else
                return "<label class='badge badge-gradient-danger'>Cancel</label>";
        })
        ->editColumn('created_at', function($order){
            return date('Y-m-d', strtotime($order->created_at));
        })->rawColumns(['payment_status', 'action'])->make(true);
    }

}