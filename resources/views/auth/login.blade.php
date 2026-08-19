@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()->title('Login — GODEVI')->noindex()->toArray();
@endphp

<section class="relative min-h-[80vh] overflow-hidden bg-ink-950">
    <div class="absolute inset-0">
        <img src="{{ asset('assets/customer/frontdata/images/bg_3.jpg') }}" alt="" aria-hidden="true" class="h-full w-full object-cover opacity-30" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950/80 to-ink-950/95"></div>
    </div>

    <div class="container-gd relative z-10 flex min-h-[80vh] items-center py-20">
        <div class="mx-auto grid w-full max-w-4xl overflow-hidden rounded-[2rem] bg-white shadow-2xl sm:grid-cols-2">
            {{-- Brand side --}}
            <div class="relative hidden sm:block">
                <img src="{{ asset('assets/customer/frontdata/images/about.jpg') }}" alt="GODEVI village tourism Bali" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-brand-900/80 to-brand-950/60"></div>
                <div class="absolute bottom-0 p-8">
                    <p class="font-display text-3xl font-bold text-white">{{ __('Authentic Bali, one village at a time.') }}</p>
                    <p class="mt-3 text-sm text-white/70">{{ __('Login to manage your reservations and bookings.') }}</p>
                </div>
            </div>

            {{-- Form side --}}
            <div class="p-8 sm:p-10">
                <div class="mb-8 flex items-center gap-3 sm:hidden">
                    <img src="{{ asset('assets/customer/img/logo.png') }}" alt="GODEVI" class="h-10 w-auto">
                </div>
                <h1 class="font-display text-2xl font-bold text-ink-950">{{ __('Welcome back') }}</h1>
                <p class="mt-1 text-sm text-ink-500">{{ __('Login to continue to your account.') }}</p>

                @if ($errors->any())
                    <div class="mt-5 rounded-2xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-700">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                    @csrf
                    <label class="block">
                        <span class="label-gd">{{ __('Email address') }}</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                            placeholder="you@example.com" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Password') }}</span>
                        <input type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••" class="input-gd">
                    </label>
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 font-medium text-ink-600">
                            <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                            {{ __('Remember me') }}
                        </label>
                        <a href="{{ url('password/reset') }}" class="font-semibold text-brand-600 hover:underline">{{ __('Forgot password?') }}</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-full !py-3.5">{{ __('Login') }}</button>
                </form>

                <x-partials.social-login />

                <p class="mt-7 text-center text-sm text-ink-500">
                    {{ __('Don\'t have an account?') }}
                    <a href="{{ url('user/register') }}" class="font-bold text-brand-600 hover:underline">{{ __('Create one') }}</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection