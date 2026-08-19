@extends('customer/layout')

@section('content')

<section class="relative min-h-screen bg-ink-950">
    <div class="absolute inset-0">
        <img src="{{ asset('assets/customer/frontdata/images/bg_3.jpg') }}" alt="" aria-hidden="true" class="h-full w-full object-cover opacity-30" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950/80 to-ink-950/95"></div>
    </div>

    <div class="container-gd relative z-10 flex items-center justify-center py-20">
        <div class="w-full max-w-lg rounded-[2rem] bg-white p-8 shadow-2xl sm:p-10">
            <img src="{{ asset('assets/customer/img/logo.png') }}" alt="GODEVI" class="mx-auto h-12 w-auto">
            <h1 class="mt-6 text-center font-display text-2xl font-bold text-ink-950">{{ __('Create your account') }}</h1>
            <p class="mt-1 text-center text-sm text-ink-500">{{ __('Join GODEVI to book village experiences and manage your reservations.') }}</p>

            @if ($errors->any())
                <div class="mt-5 rounded-2xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-700">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
                @csrf
                <input type="hidden" name="role_id" value="3">
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">{{ __('Full name') }}</span>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="{{ __('Your name') }}" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Country') }}</span>
                        <select name="country" class="input-gd">
                            <option value="Indonesia" selected>Indonesia</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Australia">Australia</option>
                            <option value="Canada">Canada</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Japan">Japan</option>
                            <option value="South Korea">South Korea</option>
                            <option value="China">China</option>
                            <option value="India">India</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                            <option value="Italy">Italy</option>
                            <option value="Spain">Spain</option>
                            <option value="Switzerland">Switzerland</option>
                        </select>
                    </label>
                </div>
                <label class="block">
                    <span class="label-gd">{{ __('Email address') }}</span>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="you@example.com" class="input-gd">
                </label>
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block">
                        <span class="label-gd">{{ __('Password') }}</span>
                        <input type="password" name="password" required autocomplete="new-password" placeholder="{{ __('Min. 8 characters') }}" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Confirm password') }}</span>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Repeat password') }}" class="input-gd">
                    </label>
                </div>
                <label class="flex items-start gap-2 text-sm text-ink-600">
                    <input type="checkbox" name="agree-term" id="agree-term" class="mt-0.5 h-4 w-4 rounded border-ink-300 text-brand-600 focus:ring-brand-500" required>
                    <span>{{ __('I agree to the') }} <a href="{{ url('term') }}" class="font-bold text-brand-600 hover:underline">{{ __('Terms of Service') }}</a>.</span>
                </label>
                <button type="submit" class="btn btn-primary w-full !py-3.5">{{ __('Create Account') }}</button>
            </form>

            <x-partials.social-login />

            <p class="mt-7 text-center text-sm text-ink-500">
                {{ __('Already have an account?') }}
                <a href="{{ url('login') }}" class="font-bold text-brand-600 hover:underline">{{ __('Login') }}</a>
            </p>
        </div>
    </div>
</section>
@endsection