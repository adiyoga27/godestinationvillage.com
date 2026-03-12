<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Mail\OrderEmail;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
// use DB;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class OrderService
{

    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Order::query()
                ->leftJoin('bank_accounts', 'orders.bank_account_id', '=', 'bank_accounts.id')                            
                ->select([
                    DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                    DB::raw('orders.*'),
                ])->where('orders.deleted_at', NULL);
    }

    public static function count($village_id = NULL)
    {
        if($village_id == NULL)
            return Order::where('payment_status', 'success')->count();
        else
            return Order::where('payment_status', 'success')->where('village_id', $village_id)->count();
    }

    public static function income($village_id = NULL)
    {
        if($village_id == NULL)
            return Order::where('payment_status', 'success')->sum('total_payment');
        else
            return Order::where('payment_status', 'success')->where('village_id', $village_id)->sum('total_payment');
    }

    public static function find($id)
    {
        return Order::with(['bank_account', 'village'])->where('uuid',$id);
    }

    public static function get_order_by_user($user_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Order::query()
        ->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('orders.*')
        ])->where('orders.user_id', $user_id)->where('orders.deleted_at', NULL);
    }

    public static function find_by_user($user_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Order::query()
        ->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('orders.*')
        ])->where('orders.village_id', $user_id)->where('orders.deleted_at', NULL);
    }

    public static function find_by_package($package_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Order::query()
        ->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('orders.*')
        ])->where('orders.package_id', $package_id)->where('orders.deleted_at', NULL);
    }

    public static function destroy($id)
    {
        $model = Order::find($id);
        return $model->destroy($id);
    }

    public static function change_status($id, $status)
    {
        $order = Order::find($id);
        $order->payment_status = $status;
        return $order->save();
    }

    public static function search_order($village_id = 'All', $package_id = 'All', $start_date = 0, $end_date = 0)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $order = Order::query()
                ->leftJoin('bank_accounts', 'orders.bank_account_id', '=', 'bank_accounts.id')                            
                ->select([
                    DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                    DB::raw('orders.*'),
                ]);

        if($village_id != 'All')
            $order->where('orders.village_id', $village_id);

        if($package_id != 'All')
            $order->where('orders.package_id', $package_id);

        if($start_date != 0)
            $order->where(DB::raw('DATE(orders.created_at)'), '>=', date('Y-m-d', strtotime($start_date)));

        if($end_date != 0)
            $order->where(DB::raw('DATE(orders.created_at)'), '<=', date('Y-m-d', strtotime($end_date)));

        return $order->where('orders.deleted_at', NULL);
    }


    public static function sendEvent($payload)
    {
      

        
        try {
            DB::beginTransaction();
            $count =  Order::count();
            if ($count > 0) {
                $code = Order::latest()->first()->id + 1;
            } else {
                $code = 1;
            }

            $package = Package::where('id', $payload['idtour'])->first();
            $price_package = $package->price;
            $disc_package = $package->disc > 0 ? ($package->price - $package->disc) : 0;
            $total_package = ($price_package - $disc_package) * $payload['pax'];

            $encryptcode = (string) Str::uuid();

                $package_id = $payload['idtour'];
                $user_id = $payload['customerid'];
                $village_id = $payload['village_id'];
                $package_name = $payload['tourname'];
                $village_name = $payload['village'];
                $customer_name = $payload['customername'];
                $customer_address = $payload['address'];
                $customer_phone = $payload['phone'];
                $customer_email = $payload['email'];
                // $package_price = $payload['price'];
                // $package_discount = 0;
                // $total_payment = $payload['totalprice'];
                $pax = $payload['pax'];
                $pickup = $payload['pickup'];
                $pickupname = $payload['pickupname'];
                $special_note = "Location - " . $pickup . " | Hotel Name - " . $pickupname . " | Special Note - " . $payload['special_note'];
                $checkin_date = $payload['checkin_date'];
                $proses = Order::create(
                    [
                        'package_id' => $package_id,
                        'user_id' => $user_id,
                        'village_id' => $village_id,
                        'code' => 'INV-' . $code,
                        'package_name' => $package_name,
                        'village_name' => $village_name,
                        'customer_name' => $customer_name,
                        'customer_address' => $customer_address,
                        'customer_phone' => $customer_phone,
                        'customer_email' => $customer_email,
                        'package_price' => $price_package,
                        'package_discount' => $disc_package,
                        'total_payment' => $total_package,
                        'payment_status' => 'pending',
                        'pax' => $pax,
                        'special_note' => $special_note,
                        'checkin_date' => $checkin_date,
                        'uuid' => $encryptcode
                    ]
                );
                    $order =  Order::latest()->first();
                    $subject = 'Godevi - Order ' . $order->id . ' - Confirmation';
                    $message = "This is your booking information, please make payment to confirm your reservation as following details(<a href='https://godestinationvillage.com/reservation/" . $customer_email . "'>Details Order</a>) : <br> Note: We will proces your booking after we receive your payment. This can take up to 24 hours to verify your payment. After the verification you will get the e-tour voucher through email";


                    $email = new OrderEmail($subject, $order, $message);
                    Mail::to([$order->customer_email, 'transgodevi@gmail.com'])->send($email);
                    DB::commit();
                    return $proses;
            
            } catch (\Throwable $th) {
                DB::rollBack();
                BotHelper::errorBot('Checkout Booking Tour Package', $th);

                throw $th;
            }
    }

}