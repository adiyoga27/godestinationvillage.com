@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()
        ->title('Payment Pending — GODEVI')
        ->noindex()
        ->toArray();
@endphp

<section class="section-pad bg-ink-50/60">
    <div class="container-gd flex min-h-[60vh] items-center justify-center">
        <div class="w-full max-w-xl rounded-3xl border border-ink-100 bg-white p-10 text-center shadow-xl shadow-ink-950/5 sm:p-14">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-amber-100">
                <svg class="h-10 w-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h1 class="mt-7 font-display text-3xl font-semibold text-ink-950 sm:text-4xl">Payment Pending</h1>
            <p class="mt-4 text-ink-500">
                Your purchase request is being verified. We'll be in touch shortly — no further action is needed right now.
            </p>
            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ url('/') }}" class="btn btn-primary w-full sm:w-auto">Back to Home</a>
                <a href="{{ url('reservation/' . (request('email') ?? '')) }}" class="btn btn-secondary w-full sm:w-auto">My Reservations</a>
            </div>
        </div>
    </div>
</section>

@endsection