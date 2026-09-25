<?php

namespace App\Http\Controllers\Front;

use App\Helpers\BotHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guest\GuestEventBookingRequest;
use App\Http\Requests\Guest\GuestHomestayBookingRequest;
use App\Http\Requests\Guest\GuestPackageBookingRequest;
use App\Mail\OrderEmail;
use App\Mail\OrderEventEmail;
use App\Mail\OrderHomestayEmail;
use App\Models\Event;
use App\Models\Homestay;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OrderHomestay;
use App\Models\Package;
use App\Support\Seo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuestBookingController extends Controller
{
    public function packageForm(string $slug)
    {
        $package = Package::with(['category', 'village'])->where('slug', $slug)->firstOrFail();

        $data['packages'] = $package;
        $data['seo'] = Seo::make()->title('Book: '.$package->name)->noindex()->toArray();

        return view('customer.guest-booking.package', $data);
    }

    public function packageStore(GuestPackageBookingRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $package = Package::where('id', $validated['idtour'])->firstOrFail();
            $price = (float) $package->price;
            $discount = ((float) $package->disc > 0) ? ((float) $package->price - (float) $package->disc) : 0;
            $total = ($price - $discount) * (int) $validated['pax'];

            $uuid = (string) Str::uuid();
            $code = 'INV-'.(((int) Order::max('id')) + 1);

            $order = Order::create([
                'package_id' => $package->id,
                'user_id' => Auth::id(),
                'village_id' => $package->village_id,
                'code' => $code,
                'package_name' => $package->name,
                'village_name' => optional($package->detailVillage)->village_name ?? optional($package->villageDetail)->village_name ?? '-',
                'customer_name' => $validated['customername'],
                'customer_address' => $validated['address'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'package_price' => $price,
                'package_discount' => $discount,
                'total_payment' => $total,
                'payment_status' => 'pending',
                'pax' => (int) $validated['pax'],
                'special_note' => 'Location - '.($validated['pickup'] ?? '-').' | Hotel Name - '.($validated['pickupname'] ?? '-').' | Special Note - '.($validated['special_note'] ?? '-'),
                'checkin_date' => $validated['checkin_date'] ?? null,
                'uuid' => $uuid,
            ]);

            DB::commit();

            $this->sendMailSafe(
                [$order->customer_email],
                new OrderEmail(
                    'Godevi - Order '.$order->code.' - Confirmation',
                    $order,
                    "This is your booking information, please make payment to confirm your reservation (<a href='".url('reservation/'.$order->customer_email)."'>Details Order</a>)."
                )
            );

            return redirect('payment/package/'.$order->uuid);
        } catch (\Throwable $th) {
            DB::rollBack();
            BotHelper::errorBot('Guest Checkout Package', $th);

            return back()->withInput()->with('error', 'Gagal membuat booking. Silakan coba lagi.');
        }
    }

    public function eventForm(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $data['packages'] = $event;
        $data['seo'] = Seo::make()->title('Book Event: '.$event->name)->noindex()->toArray();

        return view('customer.guest-booking.event', $data);
    }

    public function eventStore(GuestEventBookingRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $event = Event::where('id', $validated['idevent'])->firstOrFail();
            $price = (float) $event->price;
            $discount = ((float) $event->disc > 0) ? ((float) $event->price - (float) $event->disc) : 0;
            $total = ($price - $discount) * (int) $validated['pax'];
            $status = 'pending';
            $paymentType = 'bank_transfer';

            if ((int) $event->is_free === 1 || $total <= 0) {
                $status = 'success';
                $paymentType = 'Free';
                $total = 0;
            }

            if ((int) $event->is_paywish === 1 && isset($validated['price'])) {
                $price = (float) $validated['price'];
                $total = $price * (int) $validated['pax'];
                $discount = 0;
            }

            $uuid = (string) Str::uuid();
            $code = 'EVT-'.(((int) OrderEvent::max('id')) + 1);

            $order = OrderEvent::create([
                'event_id' => $event->id,
                'user_id' => Auth::id(),
                'code' => $code,
                'uuid' => $uuid,
                'event_name' => $event->name,
                'customer_name' => $validated['customername'],
                'customer_address' => $validated['address'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'event_price' => $price,
                'event_discount' => $discount,
                'total_payment' => $total,
                'payment_type' => $paymentType,
                'payment_status' => $status,
                'payment_date' => date('Y-m-d'),
                'pax' => (int) $validated['pax'],
                'special_note' => $validated['special_note'] ?? null,
            ]);

            DB::commit();

            if ($status === 'success') {
                $this->sendMailSafe(
                    [$order->customer_email],
                    new OrderEventEmail('Godevi - Order Event '.$order->code.' - Success', $order, 'Tiket gratis Anda berhasil dibuat. Terima kasih telah mendukung desa wisata!')
                );

                return redirect('reservation-events/paid/'.$order->customer_email)
                    ->with('status', 'Booking event gratis berhasil.');
            }

            $link = url('payment/event/'.$uuid);
            $this->sendMailSafe(
                [$order->customer_email],
                new OrderEventEmail(
                    'Godevi - Order Events '.$order->code.' - Confirmation',
                    $order,
                    "Hallo {$order->customer_name}.<br><br>Reservasi Anda diterima. Silakan klik <a href='{$link}'>di sini</a> untuk melanjutkan pembayaran Midtrans."
                )
            );

            return redirect('payment/event/'.$order->uuid);
        } catch (\Throwable $th) {
            DB::rollBack();
            BotHelper::errorBot('Guest Checkout Event', $th);

            return back()->withInput()->with('error', 'Gagal membuat booking event. Silakan coba lagi.');
        }
    }

    public function homestayForm(int $id)
    {
        $homestay = Homestay::where('id', $id)->firstOrFail();

        $data['packages'] = $homestay;
        $data['seo'] = Seo::make()->title('Book Homestay: '.$homestay->name)->noindex()->toArray();

        return view('customer.guest-booking.homestay', $data);
    }

    public function homestayStore(GuestHomestayBookingRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $homestay = Homestay::where('id', $validated['idhomestay'])->firstOrFail();
            $price = (float) $homestay->price;
            $discount = (float) $homestay->disc;
            $total = $discount > 0 ? $discount * (int) $validated['pax'] : $price * (int) $validated['pax'];
            $status = 'pending';

            if ($total <= 0) {
                $status = 'success';
                $total = 0;
            }

            $uuid = (string) Str::uuid();
            $code = 'HST-'.(((int) OrderHomestay::max('id')) + 1);

            $order = OrderHomestay::create([
                'homestay_id' => $homestay->id,
                'user_id' => Auth::id(),
                'code' => $code,
                'uuid' => $uuid,
                'homestay_name' => $homestay->name,
                'customer_name' => $validated['customername'],
                'customer_address' => $validated['address'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'homestay_price' => $price,
                'homestay_discount' => $discount,
                'total_payment' => $total,
                'payment_type' => 'bank_transfer',
                'payment_status' => $status,
                'payment_date' => date('Y-m-d'),
                'pax' => (int) $validated['pax'],
                'special_note' => $validated['special_note'] ?? null,
            ]);

            DB::commit();

            if ($status === 'success') {
                $this->sendMailSafe(
                    [$order->customer_email],
                    new OrderHomestayEmail('Godevi - Order Homestay '.$order->code.' - Success', $order, 'Booking homestay Anda berhasil dibuat.')
                );

                return redirect('reservation-homestay/paid/'.$order->customer_email)
                    ->with('status', 'Booking homestay berhasil.');
            }

            $link = url('payment/homestay/'.$uuid);
            $this->sendMailSafe(
                [$order->customer_email],
                new OrderHomestayEmail(
                    'Godevi - Order Homestay '.$order->code.' - Confirmation',
                    $order,
                    "Terima kasih atas reservasi Anda. Klik <a href='{$link}'>tautan ini</a> untuk pembayaran Midtrans."
                )
            );

            return redirect('payment/homestay/'.$order->uuid);
        } catch (\Throwable $th) {
            DB::rollBack();
            BotHelper::errorBot('Guest Checkout Homestay', $th);

            return back()->withInput()->with('error', 'Gagal membuat booking homestay. Silakan coba lagi.');
        }
    }

    protected function sendMailSafe(array $to, $mailable): void
    {
        try {
            Mail::to($to)->send($mailable);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Guest Booking Mail', $th);
        }
    }
}
