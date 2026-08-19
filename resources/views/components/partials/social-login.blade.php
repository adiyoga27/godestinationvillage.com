@php
    $socialProviders = [];
    if (config('services.facebook.client_id') && config('services.facebook.client_secret')) {
        $socialProviders[] = ['name' => 'facebook', 'url' => url('auth/facebook')];
    }
    if (config('services.google.client_id') && config('services.google.client_secret')) {
        $socialProviders[] = ['name' => 'google', 'url' => url('auth/google')];
    }
    if (config('services.twitter.client_id') && config('services.twitter.client_secret')) {
        $socialProviders[] = ['name' => 'twitter', 'url' => url('auth/twitter')];
    }
@endphp

@if (count($socialProviders))
    <div class="relative my-7">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-ink-100"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-white px-3 text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Or') }}</span>
        </div>
    </div>

    <div class="grid gap-3">
        @foreach ($socialProviders as $provider)
            <a href="{{ $provider['url'] }}"
                class="flex w-full items-center justify-center gap-3 rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm font-semibold text-ink-800 transition hover:bg-cream-50">
                @if ($provider['name'] === 'facebook')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="#1877F2" aria-hidden="true"><path d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3v-4h-3a5 5 0 0 0-5 5v2.01h-2l-.396 3.98h2.396v8.01Z"/></svg>
                @elseif ($provider['name'] === 'google')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M21.6 12.227c0-.709-.064-1.39-.182-2.045H12v3.868h5.382a4.6 4.6 0 0 1-1.996 3.018v2.51h3.232c1.891-1.742 2.982-4.305 2.982-7.351Z"/><path fill="#34A853" d="M12 22c2.7 0 4.964-.895 6.618-2.423l-3.232-2.509c-.895.6-2.04.954-3.386.954-2.605 0-4.81-1.76-5.596-4.125H3.064v2.591A9.996 9.996 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.404 13.897A6.014 6.014 0 0 1 6.132 12c0-.66.109-1.3.272-1.897V7.512H3.064A9.998 9.998 0 0 0 2 12c0 1.614.386 3.14 1.064 4.488l3.34-2.591Z"/><path fill="#EA4335" d="M12 5.977c1.468 0 2.786.504 3.823 1.494l2.868-2.868C16.955 2.79 14.69 2 12 2a9.996 9.996 0 0 0-8.936 5.512l3.34 2.591C7.19 7.737 9.395 5.977 12 5.977Z"/></svg>
                @elseif ($provider['name'] === 'twitter')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="#000000" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H9.92l4.714 6.231 5.61-6.231Zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77Z"/></svg>
                @endif
                {{ __('Continue with :provider', ['provider' => ucfirst($provider['name'])]) }}
            </a>
        @endforeach
    </div>
@endif