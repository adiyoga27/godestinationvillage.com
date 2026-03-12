<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use App\Mail\OrderEventEmail;
use App\Models\OrderEvent;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\OrderEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class OrderEventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            $query = OrderEventService::all();
            if(Auth::user()->role_id == 2){
                $query->where('events.village_id', Auth::user()->village_id)->where('payment_status' ,'success');
            }
            return DataTables::of($query)
            ->addColumn('action', function($order){
                return "<a href='". route('order-event.show', $order->uuid) ."' class='btn btn-sm btn-outline-primary'>Show</a>";
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
              ->addColumn(['data' => 'customer_name', 'name' => 'customer_name', 'title' => 'Nama Customer' ])
              ->addColumn(['data' => 'customer_phone', 'name' => 'customer_phone', 'title' => 'No. Telp' ])
              ->addColumn(['data' => 'customer_email', 'name' => 'customer_email', 'title' => 'Email' ])
              ->addColumn(['data' => 'event_name', 'name' => 'package_name', 'title' => 'Nama Paket' ])
              ->addColumn(['data' => 'pax', 'name' => 'package_price', 'title' => 'Pax' ])
              ->addColumn(['data' => 'event_price', 'name' => 'event_price', 'title' => 'Harga Paket' ])
              ->addColumn(['data' => 'event_discount', 'name' => 'pax', 'title' => 'disc' ])
              ->addColumn(['data' => 'total_payment', 'name' => 'total_payment', 'title' => 'Total' ])
              ->addColumn(['data' => 'payment_type', 'name' => 'payment_type', 'title' => 'Metode Pembayaran' ])
              ->addColumn(['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Status Pembayaran' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.events.order.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $order = OrderEventService::find($id)->first();

        return view('backend.events.order.show')->with(compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function change_status($id, $status)
    {
        $result = OrderEventService::change_status($id, $status);
        $order =  OrderEventService::find($id); 

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
            BotHelper::sendTelegram("Godevi - Approve Manual Events Success, \n\nDate: $date \nInvoice : $order->code  \nPayment Type : $order->payment_type.\n");

            $email = new OrderEventEmail($subject, $order, $message);
            Mail::to([$order->customer_email])->send($email);
            return redirect(route('order-event.show', $id))->with('status', 'Successfully updated');
        }
        else
        {
            return redirect(route('order-event.show', $id))->with('error','Failed to updated');
        }
    }
    
    public function sendEvent(Request $request)
    {
        
        $data = OrderEventService::sendEvent($request->except('_token'));
        return redirect('payment/event/' . $data->uuid);
    }

    public function sendEventFree(Request $request)
    {
        $data = OrderEventService::sendEventFree($request->except('_token'));
        return redirect('pay/finish');
    }
    public function showMidtrans($inv)
    {
        $order = OrderEvent::where('code',$inv)->first()->toArray();
        $request = [
            'transaction_details' => [
                'order_id' => $order['code'],
                'gross_amount' => $order['total_payment'],
            ],
            'item_details' => [
                [
                    'id' => $order['event_id'],
                    'price' => $order['event_price'],
                    'quantity' => $order['pax'],
                    'name' => $order['event_name'],
                ],
               
            ],
            'customer_details' => [
                'first_name' => $order['customer_name'],
                'email' => $order['customer_email'],
                'phone' => $order['customer_phone'],
            ]
        ];

            // Jika snap token masih NULL, buat token snap dan simpan ke database
            $midtrans = new CreateSnapTokenService($order);

            $snapToken = $midtrans->getSnapToken($request);

        return view('customer.midtrans', compact('order', 'snapToken'));
    }
}
