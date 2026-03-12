<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\OrderService;

use Yajra\DataTables\Html\Builder;


use App\Mail\OrderEmail as OrderEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
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
            $query = OrderService::all();
            if(Auth::user()->role_id == 2){
                $query->where('orders.village_id', Auth::user()->village_id)->where('payment_status' ,'success');
            }
            return DataTables::of($query)
            ->addColumn('action', function($order){
                return "<a href='". route('orders.show', $order->uuid) ."' class='btn btn-sm btn-outline-primary'>Show</a>";
            })->editColumn('package_price', function($order){
                return number_format($order->package_price);
            })->editColumn('total_payment', function($order){
                return 'Rp. '.number_format($order->total_payment, 2,'.',',');
            })->editColumn('payment_type', function($order){
                return str_replace('_', '', strtoupper($order->payment_type));
            })->editColumn('payment_status', function($order){
                if($order->payment_status == 'pending')
                    return "<label class='badge badge-gradient-warning'>Pending</label>";
                elseif($order->payment_status == 'success')
                    return "<label class='badge badge-gradient-success'>Sukses</label>";
                elseif($order->payment_status == 'cancel')
                    return "<label class='badge badge-gradient-danger'>Cancel</label>";
            })->editColumn('created_at', function($member){
                return date('Y-m-d', strtotime($member->created_at));
            })->rawColumns(['action', 'payment_status'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'code', 'name' => 'code', 'title' => 'No. Order' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Pemesanan' ])
              ->addColumn(['data' => 'village_name', 'name' => 'village_name', 'title' => 'Nama Desa' ])
              ->addColumn(['data' => 'customer_name', 'name' => 'customer_name', 'title' => 'Nama Customer' ])
              ->addColumn(['data' => 'customer_phone', 'name' => 'customer_phone', 'title' => 'No. Telp' ])
              ->addColumn(['data' => 'customer_email', 'name' => 'customer_email', 'title' => 'Email' ])
              ->addColumn(['data' => 'package_name', 'name' => 'package_name', 'title' => 'Nama Paket' ])
              ->addColumn(['data' => 'package_price', 'name' => 'package_price', 'title' => 'Harga Paket' ])
              ->addColumn(['data' => 'pax', 'name' => 'pax', 'title' => 'Pax' ])
              // ->addColumn(['data' => 'package_discount', 'name' => 'package_discount', 'title' => 'Discount' ])
              ->addColumn(['data' => 'total_payment', 'name' => 'total_payment', 'title' => 'Total' ])
              ->addColumn(['data' => 'payment_type', 'name' => 'payment_type', 'title' => 'Metode Pembayaran' ])
              ->addColumn(['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Status Pembayaran' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.order.index')->with(compact('html'));
    }

    public function show($id)
    {   
        $order = OrderService::find($id)->first();

        return view('backend.order.show')->with(compact('order'));
    }

    public function change_status($id, $status)
    {
        $result = OrderService::change_status($id, $status);
        $order =  OrderService::find($id)->first(); 

        if($status == 'success'){
            $subject = 'Godevi - Order '. $order->code .' - Success';
            $message = "We have received your Order and Payment, here are your order details: <br> ";
        }else{
            $subject = 'Godevi - Order '. $order->code .' - Failed';
            $message = "Sorry Your Order and Payment were declined, here are your order details:";
        }

        if ($result)
        {
            $date = date('d M Y H:i')." wita";
            BotHelper::sendTelegram("Godevi - Approve Manual Tour Package Success, \n\nDate: $date \nInvoice : $order->code  \nPayment Type : $order->payment_type.\n");

            $email = new OrderEmail($subject, $order, $message);
            Mail::to([$order->customer_email])->send($email);
            return redirect(route('orders.show', $id))->with('status', 'Successfully updated');
        }
        else
        {
            return redirect(route('orders.show', $id))->with('error','Failed to updated');
        }
    }

    public function destroy($id)
    {  
        $result = OrderService::destroy($id);

        if ($result)
            return redirect(route('orders.show', $id))->with('status', 'Successfully deleted');
        else
            return redirect(route('orders.show', $id))->with('error','Failed to delete');
    }

}