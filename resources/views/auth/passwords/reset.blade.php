@extends('customer/layout')

@section('content')
<section class="relative min-h-screen bg-ink-950">
    <div class="absolute inset-0">
        <img src="{{ asset('assets/customer/frontdata/images/bg_3.jpg') }}" alt="" aria-hidden="true" class="h-full w-full object-cover opacity-30" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950/80 to-ink-950/95"></div>
    </div>

    <div class="container-gd relative z-10 flex items-center justify-center py-20">
        <div class="w-full max-w-md rounded-[2rem] bg-white p-8 shadow-2xl sm:p-10">
            <img src="{{ asset('assets/customer/img/logo.png') }}" alt="GODEVI" class="mx-auto h-12 w-auto">
            <h1 class="mt-6 text-center font-display text-2xl font-bold text-ink-950">Reset Password</h1>
            <p class="mt-1 text-center text-sm text-ink-500">Choose a new password for your account.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-2xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-700">
                    <ul class="list-disc space-y-1 pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="mt-7 space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label class="block">
                    <span class="label-gd">Email address</span>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus autocomplete="email" class="input-gd">
                </label>
                <label class="block">
                    <span class="label-gd">New password</span>
                    <input type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" class="input-gd">
                </label>
                <label class="block">
                    <span class="label-gd">Confirm password</span>
                    <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat password" class="input-gd">
                </label>
                <button type="submit" class="btn btn-primary w-full !py-3.5">Reset Password</button>
            </form>
        </div>
    </div>
</section>
@endsection