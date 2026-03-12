<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Input;
use function GuzzleHttp\json_encode;
use App\Helpers\CustomImage;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Mail\OrderEmail as OrderEmail;
use App\Mail\SendEmail;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    //
    public function send(Request $request)
    {
        $data = OrderService::sendEvent($request->except('_token'));
        return redirect('payment/package/' . $data->uuid);
    }
    
    

    public function paypalPayment(Request $request)
    {
        $id = $request->input('editid');
        $proses = Order::find($id);
        $proses->payment_type = 'paypal';
        $proses->payment_date = date("Y-m-d H:i:s");
        $proses->payment_status = 'success';
        $proses->save();
        $return = $proses->customer_email;
        if ($proses) {
            $order =  Order::find($id);
            $subject = 'Godevi - Order ' . $order->id . ' - Sukses';
            $message = "We have received your Order(<a href='https://godevi.id/reservation/" . $proses->customer_email . "'>Details Order</a>) and Payment through paypal, here are your order details:";
            $email = new OrderEmail($subject, $order, $message);
            Mail::to($order->customer_email)->send($email);
            Mail::to('hello@godevi.id')->send($email);
            return $return;
        }
    }

    public function bankPayment(Request $request)
    {
        $id = $request->idtrx;
        $proses = Order::find($id);

        // var_dump($request->bank);
        $proses->bank_account_id = $request->bank_godevi;
        $proses->bank_name = $request->bank;
        $proses->bank_account_id = $request->bank_godevi;
        $proses->bank_acc_name = $request->name;
        $proses->payment_type = 'bank_transfer';
        $proses->payment_date = $request->date;
        $proses->payment_status = 'pending';
        if (!empty($request->bukti)) {
            $upload = CustomImage::storeImage($request->bukti, 'orders');
            $proses->payment_img = $upload['name'];
        }



        $proses->save();
        if ($proses) {
            // return redirect('reservation/paid/' . $proses->customer_email);
            return redirect('payment-detail/' . $id);

        }
    }

    public function confirmPayment(Request $request)
    {
        $id = $request->idtrx;
        $proses = Order::find($id);
        $proses->bank_name = $request->bank;
        $proses->bank_acc_name = $request->name;
        $proses->payment_date = $request->date;
        $proses->payment_status = 'pending';
        if (!empty($request->bukti)) {
            $upload = CustomImage::storeImage($request->bukti, 'orders');
            $proses->payment_img = $upload['name'];
        }



        $proses->save();
        if ($proses) {
            return redirect('reservation/paid/' . $proses->customer_email);
           

        }
    }

    // public function reservationPaid()
    // {
    //     $data['order'] = Order::whereNotNull('payment_type')
    //         ->where('user_id', Auth::id())
    //         ->get();
    //     return view('frontend/reservation/paid', $data);
    // }

    // public function reservationPaypal()
    // {
    //     $data['order'] = Order::where('payment_type', 'paypal')
    //         ->where('user_id', Auth::id())
    //         ->get();
    //     return view('frontend/reservation/paypal', $data);
    // }

    // public function reservationBank()
    // {
    //     $data['order'] = Order::where('payment_type', 'bank_transfer')
    //         ->where('user_id', Auth::id())
    //         ->get();
    //     return view('frontend/reservation/bank', $data);
    // }

    // public function reservationCancel()
    // {
    //     $data['order'] = Order::where('payment_status','cancel')
    //         ->where('user_id',Auth::id())
    //         ->get();
    //     return view('frontend/reservation/cancel', $data);
    // }


    //non registred
    public function reservationPaid(Request $request)
    {
        $data['order'] = Order::with('package')->whereNotNull('payment_type')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(5);
        $data['isiemail'] = $request->email;
        return view('customer/reservation/paid', $data);
    }

    public function reservationPaypal(Request $request)
    {
        $data['order'] = Order::with('package')->where('payment_type', 'paypal')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(5);
        $data['isiemail'] = $request->email;
        return view('customer/reservation/paypal', $data);
    }

    public function reservationBank(Request $request)
    {
        $data['order'] = Order::where('payment_type', 'bank_transfer')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(5);
        $data['isiemail'] = $request->email;
        return view('customer/reservation/bank', $data);
    }

    public function reservationCancel(Request $request)
    {
        $data['order'] = Order::where('payment_status', 'cancel')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(5);
        $data['isiemail'] = $request->email;
        return view('customer/reservation/cancel', $data);
    }
}
