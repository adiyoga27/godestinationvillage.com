<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\OrderHomestay;
use Illuminate\Http\Request;
class ReservationHomeStayController extends Controller
{
    public function reservation(Request $request)
    {
        $data['order'] = OrderHomestay::with('package')->where('payment_status', 'pending')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['isiemail'] = $request->email;
        return view('customer/homestay/reservation/reservation', $data);
    }
    public function paid(Request $request)
    {
        $data['order'] = OrderHomestay::where('payment_status', 'success')
            ->with('package')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['isiemail'] = $request->email;
        // dd($data);
        return view('customer/homestay/reservation/paid', $data);
    }
    public function cancel(Request $request)
    {
        $data['order'] = OrderHomestay::where('payment_status', 'cancel')
            ->with('package')
            ->where('customer_email', $request->email)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['isiemail'] = $request->email;
        return view('customer/homestay/reservation/cancel', $data);
    }
}
