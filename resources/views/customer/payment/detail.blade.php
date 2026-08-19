@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()->title('Payment Details — GODEVI')->noindex()->toArray();
@endphp

<x-partials.page-hero
    title="{{ __('Payment Details') }}"
    subtitle="{{ __('Transfer your payment to the GODEVI bank account below, then confirm your transfer.') }}"
    image="assets/customer/img/page-title-area/privacy.jpg"
    :crumbs="[__('Home') => '/', __('Payment Details') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd mx-auto max-w-3xl">
        <div class="card overflow-hidden">
            <div class="border-b border-ink-100 bg-ink-950 px-8 py-6">
                <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Order Code') }}</p>
                <p class="mt-1 font-display text-xl font-bold text-white">{{ $order->code ?? '-' }}</p>
            </div>
            <div class="space-y-8 p-8 sm:p-10">
                <div class="text-center">
                    <p class="text-sm text-ink-500">{{ __('Please transfer') }}</p>
                    <p class="mt-2 font-display text-5xl font-bold tracking-tight text-brand-600">
                        <span class="align-top text-2xl">Rp</span>
                        {{ number_format($order->total_payment, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-ink-400">{{ __('for your purchase order') }}</p>
                </div>

                <div class="rounded-2xl bg-cream-100 p-6">
                    <p class="text-center text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Transfer to') }}</p>
                    @if ($order->bank_account)
                        <p class="mt-3 text-center font-bold text-ink-950">{{ $order->bank_account->bank_name }}</p>
                        <p class="mt-1 text-center font-display text-2xl font-bold tracking-wide text-ink-950">{{ $order->bank_account->bank_acc_no }}</p>
                        <p class="text-center text-sm text-ink-600">a.n. {{ $order->bank_account->bank_acc_name }}</p>
                    @else
                        <p class="mt-2 text-center text-sm text-ink-500">{{ __('Bank account not assigned yet.') }}</p>
                    @endif
                </div>

                <a href="{{ url('payment-confirm/' . $order->id) }}" class="btn btn-primary w-full !py-4">
                    {{ __('I\'ve Transferred — Confirm Now') }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection