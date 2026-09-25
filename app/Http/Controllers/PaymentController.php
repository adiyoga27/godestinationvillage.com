<?php

namespace App\Http\Controllers;

use App\Mail\OrderEmail;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Throwable;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function token()
    {
        $transactionData = [
            'transaction_details' => [
                'order_id' => uniqid('demo-'),
                'gross_amount' => 200000,
            ],
            'item_details' => [
                ['id' => 'item1', 'price' => 100000, 'quantity' => 1, 'name' => 'Adidas f50'],
                ['id' => 'item2', 'price' => 50000, 'quantity' => 2, 'name' => 'Nike N90'],
            ],
            'customer_details' => [
                'first_name' => 'Andri',
                'last_name' => 'Setiawan',
                'email' => 'andrisetiawan@example.com',
                'phone' => '081322311801',
            ],
        ];

        try {
            echo Snap::getSnapToken($transactionData);
        } catch (Throwable $e) {
            Log::error('Midtrans token error: '.$e->getMessage());

            return response($e->getMessage(), 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notif = new Notification;
        } catch (Throwable $e) {
            Log::error('Midtrans notification error: '.$e->getMessage());

            return response('Invalid notification', 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status ?? null;

        if ($transaction === 'capture') {
            if ($type === 'credit_card' && $fraud === 'challenge') {
                $this->change_status($orderId, 'success');
            } else {
                $this->change_status($orderId, 'success');
            }
        } elseif ($transaction === 'settlement') {
            $this->change_status($orderId, 'success');
        } elseif ($transaction === 'pending') {
            $this->change_status($orderId, 'pending');
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'], true)) {
            $this->change_status($orderId, 'cancel');
        }

        return response('OK');
    }

    public function vtweb($id)
    {
        $data = Order::where('code', $id)->firstOrFail();

        $transactionData = [
            'transaction_details' => [
                'order_id' => $data->code,
                'gross_amount' => (int) $data->total_payment,
            ],
            'item_details' => [
                [
                    'id' => $data->package_id,
                    'price' => (int) $data->package_price,
                    'quantity' => (int) $data->pax,
                    'name' => substr($data->package_name, 0, 50),
                ],
            ],
            'customer_details' => [
                'first_name' => $data->customer_name,
                'email' => $data->customer_email,
                'phone' => $data->customer_phone,
            ],
        ];

        try {
            $transaction = Snap::createTransaction($transactionData);

            return redirect($transaction->redirect_url);
        } catch (Throwable $e) {
            Log::error('Midtrans vtweb error: '.$e->getMessage());

            return back()->with('error', 'Gagal membuat sesi pembayaran Midtrans.');
        }
    }

    public function change_status($id, $status)
    {
        $result = OrderService::change_status($id, $status);
        $order = OrderService::find($id)->first();

        if (! $order) {
            return false;
        }

        if ($status === 'success') {
            $subject = 'Godevi - Order '.$order->code.' - Success';
            $message = 'We have received your Order and Payment, here are your order details: <br> ';
        } else {
            $subject = 'Godevi - Order '.$order->code.' - Failed';
            $message = 'Sorry Your Order and Payment were declined, here are your order details:';
        }

        if ($result) {
            try {
                $email = new OrderEmail($subject, $order, $message);
                $recipients = array_filter([$order->customer_email, optional($order->village)->email]);
                if (! empty($recipients)) {
                    Mail::to($recipients)->send($email);
                }
            } catch (Throwable $e) {
                Log::error('Order status mail error: '.$e->getMessage());
            }
        }

        return $result;
    }
}
