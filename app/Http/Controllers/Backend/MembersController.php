<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;
use App\Services\OrderService;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\VillageService;
use Yajra\DataTables\Html\Builder;


use Session;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class MembersController extends Controller
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
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            return DataTables::of(UserService::get_by_role(3))
            ->addColumn('action', function($member){
                return view('datatable._action_dinamyc', [
                    'model'           => $member,
                    'delete'          => route('user_member.destroy', $member->id),
                    'url'             => [
                        'Edit'            => route('user_member.edit', $member->id),
                        'Show'            => route('user_member.show', $member->id)
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $member->name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($member){
                if($member->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })
            ->editColumn('created_at', function($member){
                return date('Y-m-d', strtotime($member->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama' ])
              ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email' ])
              ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => 'No. Telp' ])
              ->addColumn(['data' => 'country', 'name' => 'country', 'title' => 'Negara' ])
              ->addColumn(['data' => 'village_name', 'name' => 'village_name', 'title' => 'Village' ])

              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [5, 'desc']
              ]);

        return view('backend.member.index')->with(compact('html'));
    }

    public function create()
    {
        $villages = VillageService::pluck()->prepend('Pilih Village', '');
        return view('backend.member.create')->with(compact(
           'villages'
        ));
    }

    public function store(UserCreateRequest $request)
    {
        $result = UserService::create($request->except('_token'));

        if ($result) 
            return redirect(route('user_member.index'))->with('status', 'Successfully created');
        else
            return redirect(route('user_member.create'))->with('error', 'Failed to create');
    }

    public function show($id, Request $request, Builder $htmlBuilder)
    {
        $member = UserService::find($id);

        if (request()->ajax()) {
            return DataTables::of(OrderService::get_order_by_user($id))
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
            })->rawColumns(['payment_status', 'action'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'code', 'name' => 'code', 'title' => 'No. Order' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Pemesanan' ])
              ->addColumn(['data' => 'package_name', 'name' => 'package_name', 'title' => 'Nama Paket' ])
              // ->addColumn(['data' => 'package_price', 'name' => 'package_price', 'title' => 'Harga Paket' ])
              // ->addColumn(['data' => 'package_discount', 'name' => 'package_discount', 'title' => 'Diskon' ])
              ->addColumn(['data' => 'total_payment', 'name' => 'total_payment', 'title' => 'Total Pembayaran' ])
              ->addColumn(['data' => 'payment_type', 'name' => 'payment_type', 'title' => 'Metode Pembayaran' ])
              ->addColumn(['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Status Pembayaran' ])
              ->parameters([
                'scrollX' => true,
              ]);

        return view('backend.member.show')->with(compact(
            'member',
            'html'
        ));
    }

    public function edit($id)
    {
        $villages = VillageService::pluck();
        $member = UserService::find($id);

        return view('backend.member.edit')->with(compact(
            'member',
            'villages'
        ));
    }

    public function update($id, UserUpdateRequest $request)
    {
        if (empty($request->password)) {
            $result = UserService::update($id, $request->except('_token', 'password'));
        } else {
            $result = UserService::update($id, $request->except('_token'));
        }
        
        if ($result) 
            return redirect(route('user_member.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = UserService::destroy($id);

        if ($result)
            return redirect(route('user_member.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('user_member.index'))->with('error','Failed to delete');
    }

}