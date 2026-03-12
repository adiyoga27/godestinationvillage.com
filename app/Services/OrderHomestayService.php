<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Mail\OrderHomestayEmail;
use App\Mail\SendEmail;
use App\Models\Homestay;
use App\Models\OrderHomestay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrderHomestayService
{

    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return OrderHomestay::query()
            ->leftJoin('homestay', 'order_homestay.homestay_id', '=', 'homestay.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('order_homestay.*'),
            ])->where('order_homestay.deleted_at', NULL);
    }

    public static function count($village_id = NULL)
    {
        if ($village_id == NULL)
            return OrderHomestay::where('payment_status', 'success')->count();
        else
            return OrderHomestay::where('payment_status', 'success')->where('village_id', $village_id)->count();
    }

    public static function income($village_id = NULL)
    {
        if ($village_id == NULL)
            return OrderHomestay::where('payment_status', 'success')->sum('total_payment');
        else
            return OrderHomestay::where('payment_status', 'success')->where('village_id', $village_id)->sum('total_payment');
    }

    public static function find($id)
    {
        return OrderHomestay::with(['bank_account'])->where('uuid', $id);
    }

    public static function get_order_by_user($user_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return OrderHomestay::query()
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('order_homestay.*')
            ])->where('order_homestay.user_id', $user_id)->where('order_homestay.deleted_at', NULL);
    }

    public static function find_by_user($user_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return OrderHomestay::query()
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('order_homestay.*')
            ])->where('order_homestay.village_id', $user_id)->where('order_homestay.deleted_at', NULL);
    }

    public static function find_by_package($package_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return OrderHomestay::query()
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('order_homestay.*')
            ])->where('order_homestay.package_id', $package_id)->where('order_homestay.deleted_at', NULL);
    }

    public static function destroy($id)
    {
        $model = OrderHomestay::find($id);
        return $model->destroy($id);
    }

    public static function change_status($id, $status)
    {
        $order = OrderHomestay::find($id);
        $order->payment_status = $status;
        return $order->save();
    }

    public static function search_order($village_id = 'All', $package_id = 'All', $start_date = 0, $end_date = 0)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $order = OrderHomestay::query()
            ->leftJoin('bank_accounts', 'order_homestay.bank_account_id', '=', 'bank_accounts.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('order_homestay.*'),
            ]);

        if ($village_id != 'All')
            $order->where('order_homestay.village_id', $village_id);

        if ($package_id != 'All')
            $order->where('order_homestay.package_id', $package_id);

        if ($start_date != 0)
            $order->where(DB::raw('DATE(order_homestay.created_at)'), '>=', date('Y-m-d', strtotime($start_date)));

        if ($end_date != 0)
            $order->where(DB::raw('DATE(order_homestay.created_at)'), '<=', date('Y-m-d', strtotime($end_date)));

        return $order->where('order_homestay.deleted_at', NULL);
    }

    public static function sendHomeStay($payload)
    {
        DB::beginTransaction();
        try {
            $homestay = Homestay::where('id', $payload['idhomestay'])->first();
            $status = 'pending';
            $price = $payload['price'];
            $disc = $homestay->disc;
            $total_payment = $price * $payload['pax'];
            if ($disc > 0) {
                $total_payment = $disc * $payload['pax'];
            }

            if ($homestay->is_free) {
                $status = 'success';
                $price = '0';
                $total_payment = '0';
                $disc = '0';
            }
            if ($homestay->is_paywish) {
                $price = '0';
                $total_payment = '0';
                $disc = '0';
            }

            $datenow = date('Y-m-d');

            $count =  OrderHomestay::count();
            if ($count > 0) {
                $code = OrderHomestay::latest()->first()->id + 1;
            } else {
                $code = 1;
            }

            $encryptcode = (string) Str::uuid();
            $data = array(
                'homestay_id' => $payload['idhomestay'],
                'user_id' => Auth::user()->id ?? null,
                'code' => 'HST-' . $code,
                'uuid' => $encryptcode,
                'homestay_name' => $payload['homestayname'],
                'customer_name' => $payload['customername'],
                'customer_address' => $payload['address'],
                'customer_phone' => $payload['phone'],
                'customer_email' => $payload['email'],
                'homestay_price' => $price,
                'homestay_discount' => $disc,
                'total_payment' => $total_payment,
                'payment_type' => 'bank_transfer',
                'payment_status' => $status,
                'bank_account_id' => 7,
                'payment_date' => $datenow,
                'pax' => $payload['pax'],
                'special_note' => $payload['special_note'],
            );

            $link = url('payment/homestay/' . $encryptcode);
            $proses = OrderHomestay::create($data);
            if ($proses) {
                $order =  OrderHomestay::latest()->first();
                $subject = 'Godevi - Order Homestay ' . $order->code . ' - Confirmation';
                $message = "This is your booking confirmation. Thank you for joining our homestay. <br> Klik this <a href='$link'>link</a> for payment<br>";
                $email = new OrderHomestayEmail($subject, $order, $message);
                Mail::to([$order->customer_email, 'hello@godestinationvillage.com'])->send($email);
                DB::commit();

                return $proses;
            }
        } catch (\Throwable $th) {
            BotHelper::errorBot('Checkout Booking Homestay', $th);

            DB::rollBack();
            return $th;
        }
    }
}
