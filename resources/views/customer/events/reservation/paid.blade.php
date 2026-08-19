@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Paid Reservation"
    subtitle="Your confirmed event reservations."
    image="assets/customer/img/page-title-area/privacy.jpg"
    :crumbs="['Home' => '/', 'Reservation' => '', 'Paid' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    @forelse ($order as $orders)
                        <x-partials.order-card
                            :order="$orders"
                            folder="events"
                            label="event_name"
                            :actions="false"
                        />
                    @empty
                        <div class="card p-12 text-center">
                            <p class="font-display text-xl font-bold text-ink-950">No paid reservations</p>
                            <p class="mt-2 text-sm text-ink-500">You have no confirmed event bookings yet.</p>
                        </div>
                    @endforelse
                </div>
                @if (count($order) > 0)
                    <div class="mt-8 flex items-center justify-center gap-2">
                        @for ($i = 1; $i <= $order->lastPage(); $i++)
                            <a href="{{ $order->url($i) }}"
                                class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold transition {{ $order->currentPage() == $i ? 'bg-brand-600 text-white' : 'bg-white text-ink-600 hover:bg-cream-100' }}">{{ $i }}</a>
                        @endfor
                        @if ($order->lastPage() > 0 && $order->currentPage() < $order->lastPage())
                            <a href="{{ $order->nextPageUrl() }}" class="btn btn-secondary !px-5 !py-2.5">Next</a>
                        @endif
                    </div>
                @endif
            </div>
            <div>
                <x-partials.reservation-sidebar :email="$isiemail" base="reservation-events" active="paid" title="Status Booking Events" />
            </div>
        </div>
    </div>
</section>

@endsection