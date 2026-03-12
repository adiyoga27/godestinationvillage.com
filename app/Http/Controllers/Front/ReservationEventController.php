<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\OrderEvent;
use Illuminate\Http\Request;

class ReservationEventController extends Controller
{
    public function reservation(Request $request)

    {

        $data['order'] = OrderEvent::where('payment_status', 'pending')
        ->with('package')

            ->where('customer_email', $request->email)

            ->orderBy('id', 'desc')

            ->paginate(10);

        $data['isiemail'] = $request->email;

        return view('customer/events/reservation/reservation', $data);
    }
    public function paid(Request $request)

    {

        $data['order'] = OrderEvent::where('payment_status', 'success')
        ->with('package')

            ->where('customer_email', $request->email)

            ->orderBy('id', 'desc')

            ->paginate(10);

        $data['isiemail'] = $request->email;

        // dd($data);
        return view('customer/events/reservation/paid', $data);
    }
    public function cancel(Request $request)

    {

        $data['order'] = OrderEvent::where('payment_status', 'cancel')
        ->with('package')

            ->where('customer_email', $request->email)

            ->orderBy('id', 'desc')

            ->paginate(10);

        $data['isiemail'] = $request->email;

        // dd($data);
        return view('customer/events/reservation/cancel', $data);
    }
}
