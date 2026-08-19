@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()
        ->title('Payment Failed — GODEVI')
        ->noindex()
        ->toArray();
@endphp

<section class="section-pad bg-ink-50/60">
    <div class="container-gd flex min-h-[60vh] items-center justify-center">
        <div class="w-full max-w-xl rounded-3xl border border-ink-100 bg-white p-10 text-center shadow-xl shadow-ink-950/5 sm:p-14">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rose-100">
                <svg class="h-10 w-10 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <h1 class="mt-7 font-display text-3xl font-semibold text-ink-950 sm:text-4xl">Payment Failed</h1>
            <p class="mt-4 text-ink-500">
                Sorry, your transaction failed. Please check your payment details and try again in a few moments.
            </p>
            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="{{ url('/') }}" class="btn btn-primary w-full sm:w-auto">Back to Home</a>
                <a href="{{ url('contact') }}" class="btn btn-secondary w-full sm:w-auto">Contact Us</a>
            </div>
        </div>
    </div>
</section>

@endsection