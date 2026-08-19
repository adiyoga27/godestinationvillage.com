@extends('customer/layout')

@section('content')
<section class="relative min-h-screen bg-ink-950">
    <div class="absolute inset-0">
        <img src="{{ asset('assets/customer/frontdata/images/bg_3.jpg') }}" alt="" aria-hidden="true" class="h-full w-full object-cover opacity-30" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950/80 to-ink-950/95"></div>
    </div>

    <div class="container-gd relative z-10 flex items-center justify-center py-20">
        <div class="w-full max-w-md rounded-[2rem] bg-white p-8 text-center shadow-2xl sm:p-10">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-50">
                <svg class="h-8 w-8 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="mt-6 font-display text-2xl font-bold text-ink-950">Verify Your Email</h1>

            @if (session('resent'))
                <div class="mt-5 rounded-2xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-700">
                    A fresh verification link has been sent to your email address.
                </div>
            @endif

            <p class="mt-4 text-sm leading-relaxed text-ink-600">
                Before proceeding, please check your email for a verification link.
                If you did not receive the email,
                <a href="{{ route('verification.resend') }}" class="font-bold text-brand-600 hover:underline">click here to request another</a>.
            </p>
        </div>
    </div>
</section>
@endsection