@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()->title('Login — GODEVI')->noindex()->toArray();
    $benefits = [
        ['M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z', __('Book village tours & homestays')],
        ['M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z', __('Take the village readiness assessment')],
        ['M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605', __('Track your orders and reports')],
    ];
@endphp

<section class="relative overflow-hidden bg-cream-50">
    <div aria-hidden="true" class="pointer-events-none absolute -left-40 -top-40 h-[28rem] w-[28rem] rounded-full bg-brand-500/10 blur-3xl"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -bottom-48 -right-32 h-[30rem] w-[30rem] rounded-full bg-forest-500/10 blur-3xl"></div>

    <div class="container-gd relative z-10 flex min-h-[calc(100vh-8rem)] items-center py-10 sm:py-16">
        <div class="mx-auto grid w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-[0_40px_80px_-30px_rgb(26_26_38/0.35)] ring-1 ring-ink-100 lg:grid-cols-[1.05fr_1fr]">

            {{-- ============ Panel brand ============ --}}
            <div class="relative isolate min-h-[15rem] overflow-hidden bg-ink-950 lg:min-h-[38rem]">
                <img src="{{ asset('assets/customer/img/page-title-area/explorer.jpg') }}" alt="" aria-hidden="true" class="absolute inset-0 -z-10 h-full w-full object-cover object-[18%_30%]" loading="eager">
                <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/45 to-ink-950/10"></div>

                <div class="flex h-full flex-col justify-between p-7 sm:p-10">
                    <a href="{{ url('/') }}" class="inline-flex w-fit"><img src="{{ asset('assets/godevi-white.png') }}" alt="GODEVI" class="h-9 w-auto sm:h-10"></a>

                    <div class="mt-10 lg:mt-0">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-400">Go Destination Village</p>
                        <p class="mt-3 font-display text-2xl font-bold leading-tight text-white sm:text-[2rem]">{{ __('Authentic Indonesia, one village at a time.') }}</p>
                        <ul class="mt-6 hidden space-y-3 sm:block">
                            @foreach ($benefits as [$icon, $text])
                                <li class="flex items-center gap-3 text-sm font-medium text-white/85">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/15 backdrop-blur">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                                    </span>
                                    {{ $text }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ============ Form ============ --}}
            <div class="flex flex-col justify-center p-7 sm:p-10 lg:p-12">
                <h1 class="font-display text-3xl font-bold text-ink-950">{{ __('Welcome back') }}</h1>
                <p class="mt-1.5 text-sm text-ink-500">{{ __('Login to manage your reservations and bookings.') }}</p>

                @if ($errors->any())
                    <div role="alert" class="mt-6 flex gap-3 rounded-2xl border border-brand-100 bg-brand-50 px-4 py-3 text-sm text-brand-800">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <div class="font-semibold">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
                    </div>
                @endif
                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-forest-100 bg-forest-50 px-4 py-3 text-sm font-semibold text-forest-800">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5" id="login-form">
                    @csrf
                    <label class="block">
                        <span class="label-gd">{{ __('Email address') }}</span>
                        <span class="relative mt-1 block">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                placeholder="you@example.com" class="input-gd !py-3.5 !pl-12 @error('email') !border-brand-400 @enderror">
                        </span>
                    </label>

                    <label class="block">
                        <span class="flex items-center justify-between">
                            <span class="label-gd">{{ __('Password') }}</span>
                            <a href="{{ url('password/reset') }}" class="text-xs font-semibold text-brand-600 hover:underline">{{ __('Forgot password?') }}</a>
                        </span>
                        <span class="relative mt-1 block">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            <input type="password" name="password" id="login-password" required autocomplete="current-password"
                                placeholder="••••••••" class="input-gd !py-3.5 !pl-12 !pr-12">
                            <button type="button" id="toggle-password" aria-label="{{ __('Show password') }}" data-show="{{ __('Show password') }}" data-hide="{{ __('Hide password') }}"
                                class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-xl text-ink-400 transition hover:bg-cream-100 hover:text-ink-700">
                                <svg data-eye class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <svg data-eye-off class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            </button>
                        </span>
                    </label>

                    <label class="flex w-fit cursor-pointer items-center gap-2.5 text-sm font-medium text-ink-600">
                        <input type="checkbox" name="remember" id="remember" @checked(old('remember')) class="h-4 w-4 rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                        {{ __('Remember me') }}
                    </label>

                    <button type="submit" id="login-submit" class="btn btn-primary group w-full !py-3.5 text-base">
                        <span data-label class="inline-flex items-center gap-2">
                            {{ __('Login') }}
                            <svg class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </span>
                        <span data-loading class="hidden items-center gap-2">
                            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                            {{ __('Signing in...') }}
                        </span>
                    </button>
                </form>

                <x-partials.social-login />

                <p class="mt-8 text-center text-sm text-ink-500">
                    {{ __('Don\'t have an account?') }}
                    <a href="{{ url('user/register') }}" class="font-bold text-brand-600 hover:underline">{{ __('Create one') }}</a>
                </p>
                <p class="mt-3 text-center text-xs text-ink-400">{{ __('Village managers and GODEVI staff also sign in here.') }}</p>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var input = document.getElementById('login-password');
    var toggle = document.getElementById('toggle-password');
    toggle.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        toggle.querySelector('[data-eye]').classList.toggle('hidden', show);
        toggle.querySelector('[data-eye-off]').classList.toggle('hidden', !show);
        toggle.setAttribute('aria-label', show ? toggle.dataset.hide : toggle.dataset.show);
        input.focus();
    });

    // Cegah submit ganda & tampilkan status memproses.
    document.getElementById('login-form').addEventListener('submit', function () {
        var btn = document.getElementById('login-submit');
        btn.disabled = true;
        btn.querySelector('[data-label]').classList.add('hidden');
        var loading = btn.querySelector('[data-loading]');
        loading.classList.remove('hidden');
        loading.classList.add('inline-flex');
    });
})();
</script>
@endsection
