<?php

namespace App\Http\Controllers;

use App\Mail\OrderEmail;
use App\Models\Order;
use App\Services\OrderService;
use App\Veritrans\Midtrans;
use App\Veritrans\Veritrans;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

class PaymentController extends Controller
{
    public function __construct()
    {
        Veritrans::$serverKey = 'SB-Mid-server-2ed2v2clYoRRg-laqkopu7m1';
        Veritrans::$isProduction = false;
    }

    public function token()
    {
        error_log('masuk ke snap token adri ajax');
        $midtrans = new Midtrans;
        $transaction_details = array(
            'order_id'          => uniqid(),
            'gross_amount'  => 200000
        );
        // Populate items
        $items = [
            array(
                'id'                => 'item1',
                'price'         => 100000,
                'quantity'  => 1,
                'name'          => 'Adidas f50'
            ),
            array(
                'id'                => 'item2',
                'price'         => 50000,
                'quantity'  => 2,
                'name'          => 'Nike N90'
            )
        ];
        // Populate customer's billing address
        $billing_address = array(
            'first_name'        => "Andri",
            'last_name'         => "Setiawan",
            'address'           => "Karet Belakang 15A, Setiabudi.",
            'city'                  => "Jakarta",
            'postal_code'   => "51161",
            'phone'                 => "081322311801",
            'country_code'  => 'IDN'
        );
        // Populate customer's shipping address
        $shipping_address = array(
            'first_name'    => "John",
            'last_name'     => "Watson",
            'address'       => "Bakerstreet 221B.",
            'city'              => "Jakarta",
            'postal_code' => "51162",
            'phone'             => "081322311801",
            'country_code' => 'IDN'
        );
        // Populate customer's Info
        $customer_details = array(
            'first_name'            => "Andri",
            'last_name'             => "Setiawan",
            'email'                     => "andrisetiawan@asdasd.com",
            'phone'                     => "081322311801",
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address
        );
        // Data yang akan dikirim untuk request redirect_url.
        $transaction_data = array(
            'transaction_details' => $transaction_details,
            'item_details'           => $items,
            'customer_details'   => $customer_details
        );

        try {
            $snap_token = $midtrans->getSnapToken($transaction_data);
            //return redirect($vtweb_url);
            echo $snap_token;
        } catch (Exception $e) {
            return $e->getMessage;
        }
    }
    public function notification()
    {
        $vt = new Veritrans;
        echo 'test notification handler';
        $json_result = file_get_contents('php://input');
        $result = json_decode($json_result);

        if ($result) {


            $notif = $vt->status($result->order_id);
            // echo $notif;
            echo json_encode($notif);

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            if ($transaction == 'capture') {
                // For credit card transaction, we need to check whether transaction is challenge by FDS or not
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        // TODO set payment status in merchant's database to 'Challenge by FDS'
                        // TODO merchant should decide whether this transaction is authorized or not in MAP
                        echo "Transaction order_id: " . $order_id . " is challenged by FDS";
                        $this->change_status($order_id, 'success');
                    } else {
                        // TODO set payment status in merchant's database to 'Success'
                        echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
                        $this->change_status($order_id, 'success');
                    }
                }
            } else if ($transaction == 'settlement') {
                // TODO set payment status in merchant's database to 'Settlement'
                echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
                $this->change_status($order_id, 'success');
            } else if ($transaction == 'pending') {
                // TODO set payment status in merchant's database to 'Pending'
                echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
                $this->change_status($order_id, 'pending');
            } else if ($transaction == 'deny') {
                // TODO set payment status in merchant's database to 'Denied'
                echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
                $this->change_status($order_id, 'cancel');
            }
        }
    }

    public function vtweb($id)
    {
        $data = Order::where('code', $id)->first();


        $vt = new Veritrans;
        $transaction_details = array(
            'order_id'          => $data->code,
            'gross_amount'  => $data->total_payment
        );
        // Populate items
        $items = [
            array(
                'id'                => $data->package_id,
                'price'         => $data->package_price,
                'quantity'  => $data->pax,
                'name'          => substr($data->package_name, 0, 10)
            ),

        ];
        // Populate customer's billing address
        $billing_address = array(
            'first_name'        => $data->customer_name,
            'address'           => $data->customer_address,
            'city'                  => "Jakarta",
            'postal_code'   => "51161",
            'phone'                 => $data->customer_phone,
            'country_code'  => 'IDN'
        );
        // Populate customer's shipping address
        // $shipping_address = array(
        //     'first_name'    => "John",
        //     'last_name'     => "Watson",
        //     'address'       => "Bakerstreet 221B.",
        //     'city'              => "Jakarta",
        //     'postal_code' => "51162",
        //     'phone'             => "081322311801",
        //     'country_code' => 'IDN'
        // );
        // Populate customer's Info
        $customer_details = array(
            'first_name'     => $data->customer_name,
            'email'          => $data->customer_email,
            'phone'          => $data->customer_phone,
            'billing_address' => $billing_address,
        );
        $transaction_data = array(
            'payment_type'          => 'vtweb',
            'vtweb'                         => array(
                //'enabled_payments'    => [],
                'credit_card_3d_secure' => true
            ),
            'transaction_details' => $transaction_details,
            'item_details'           => $items,
            'customer_details'   => $customer_details
        );

        try {
            $vtweb_url = $vt->vtweb_charge($transaction_data);
            return redirect($vtweb_url);
        } catch (Throwable $e) {
            return $e;
        }
    }


    public function change_status($id, $status)
    {
        $result = OrderService::change_status($id, $status);
        $order =  OrderService::find($id);

        if ($status == 'success') {
            $subject = 'Godevi - Order ' . $order->code . ' - Success';
            $message = "We have received your Order and Payment, here are your order details: <br> ";
        } else {
            $subject = 'Godevi - Order ' . $order->code . ' - Failed';
            $message = "Sorry Your Order and Payment were declined, here are your order details:";
        }

        if ($result) {
            $email = new OrderEmail($subject, $order, $message);
            Mail::to([$order->customer_email, $order->village->email])->send($email);
            // return redirect(route('orders.show', $id))->with('status', 'Successfully updated');
        } else {
            // return redirect(route('orders.show', $id))->with('error','Failed to updated');
        }
    }
}
