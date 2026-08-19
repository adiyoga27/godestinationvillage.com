@if(!Auth::guest())
    @if(Auth::user()->role_id <> 3)
    <script type="text/javascript">
        window.location = "{{ url('/administrator/dashboard') }}";//here double curly bracket
    </script>
    @endif
@endif

<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    @include('partials.seo')

    <link rel="icon" href="{{ url('assets/customer/img/favicon.png') }}" type="image/png" />

    <!-- Preconnect + Fonts (Plus Jakarta Sans + Playfair Display) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        document.documentElement.classList.add('js');
    </script>

    <style>
        .header-scrolled .site-header {
            box-shadow: 0 1px 0 0 rgb(26 26 38 / 0.06), 0 12px 40px -20px rgb(26 26 38 / 0.25);
        }
    </style>
</head>

<body class="bg-white antialiased">
    <a href="#main"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-brand-600 focus:px-5 focus:py-2 focus:text-white">Skip to content</a>

    <!-- ============ TOP BAR ============ -->
    <div class="bg-ink-950 text-ink-200">
        <div class="container-gd flex items-center justify-between gap-4 py-2.5 text-[13px]">
            <div class="flex items-center gap-6">
                <a href="tel:+6281997674778" class="flex items-center gap-2 hover:text-white transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    +62 819-9767-4778
                </a>
                <a href="mailto:hello@godestinationvillage.com" class="hidden items-center gap-2 hover:text-white transition sm:flex">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    hello@godestinationvillage.com
                </a>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2">
                    @if (app()->getLocale() == 'en')
                        <a href="{{ url('locale/id') }}" class="flex items-center gap-1.5 rounded-full px-3 py-1 hover:bg-white/10 transition">
                            <img src="{{ url('assets/customer/img/flag-indonesia.png') }}" alt="Indonesian flag" class="h-3.5 w-5 rounded-sm object-cover">
                            Indonesia
                        </a>
                    @else
                        <a href="{{ url('locale/en') }}" class="flex items-center gap-1.5 rounded-full px-3 py-1 hover:bg-white/10 transition">
                            <img src="{{ url('assets/customer/img/flag-uk.png') }}" alt="English flag" class="h-3.5 w-5 rounded-sm object-cover">
                            English
                        </a>
                    @endif
                </div>
                <span class="hidden h-4 w-px bg-white/20 sm:block"></span>
                <div class="hidden items-center gap-3 sm:flex">
                    <a href="https://www.facebook.com/godestinationvillage/" target="_blank" rel="noopener" aria-label="Facebook"
                        class="opacity-80 transition hover:opacity-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.92 3.78-3.92 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.45 2.9h-2.33V22c4.78-.79 8.43-4.94 8.43-9.94z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/godestinationvillage/" target="_blank" rel="noopener" aria-label="Instagram"
                        class="opacity-80 transition hover:opacity-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zm0-2.16C8.74 0 8.33.01 7.05.07 2.7.27.27 2.69.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.2 4.36 2.62 6.78 6.98 6.98 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c4.35-.2 6.78-2.62 6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95C23.73 2.7 21.31.28 16.95.07 15.67.01 15.26 0 12 0zm0 5.84A6.16 6.16 0 105.84 12 6.16 6.16 0 0012 5.84zm0 10.16a4 4 0 114-4 4 4 0 01-4 4zm6.4-11.85a1.44 1.44 0 11-1.44 1.44 1.44 1.44 0 011.44-1.44z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/channel/UCule1cMKmK4RKh_n-Rrx81A" target="_blank" rel="noopener" aria-label="YouTube"
                        class="opacity-80 transition hover:opacity-100">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.19a3.02 3.02 0 00-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 00.5 6.19C0 8.07 0 12 0 12s0 3.93.5 5.81a3.02 3.02 0 002.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 002.12-2.14C24 15.93 24 12 24 12s0-3.93-.5-5.81zM9.55 15.57V8.43L15.82 12l-6.27 3.57z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN HEADER ============ -->
    <header class="site-header sticky top-0 z-50 bg-white/90 backdrop-blur-md transition-shadow duration-300">
        <div class="container-gd">
            <div class="flex h-20 items-center justify-between gap-6">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="shrink-0" aria-label="GODEVI Home">
                    <img src="{{ url('assets/customer/img/logo.png') }}" alt="GODEVI - Go Destination Village" class="h-12 w-auto" width="200" height="48">
                </a>

                <!-- Desktop Nav -->
                <nav class="hidden items-center gap-1 lg:flex" aria-label="Main navigation">
                    @php
                        $nav = [
                            ['url' => '/', 'label' => 'Home', 'key' => 'Home'],
                            ['url' => 'services', 'label' => 'Our Services', 'key' => 'Our Services'],
                            ['url' => 'village', 'label' => 'Explore Village', 'key' => 'Explore Village'],
                            ['url' => 'events', 'label' => 'Events', 'key' => 'Events'],
                            ['url' => 'homestay', 'label' => 'Home Stay', 'key' => 'Home Stay'],
                            ['url' => 'news', 'label' => 'News', 'key' => 'News'],
                            ['url' => 'contact', 'label' => 'Contact', 'key' => 'Contact Us'],
                        ];
                    @endphp
                    @foreach ($nav as $item)
                        <a href="{{ url($item['url']) }}"
                            class="group relative rounded-full px-4 py-2 text-sm font-semibold text-ink-700 transition hover:bg-ink-50 hover:text-brand-600 {{ request()->is(ltrim($item['url'], '/')) ? 'text-brand-600' : '' }}">
                            {{ __($item['key']) }}
                            <span class="absolute inset-x-4 -bottom-0.5 h-0.5 origin-left scale-x-0 rounded-full bg-brand-600 transition-transform duration-300 group-hover:scale-x-100"></span>
                        </a>
                    @endforeach
                </nav>

                <!-- Right actions -->
                <div class="flex items-center gap-2.5">
                    <button id="searchToggle" type="button" aria-label="Search"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:text-brand-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>

                    @auth
                        <div id="accountMenu" class="relative hidden sm:block">
                            <button type="button" data-account-toggle aria-label="Account menu"
                                class="inline-flex h-11 items-center gap-2 rounded-full border border-ink-200 pl-1.5 pr-4 font-semibold text-ink-700 transition hover:border-brand-600 hover:text-brand-600">
                                <span class="flex h-8 w-8 overflow-hidden rounded-full bg-cream-100">
                                    @if (Auth::user()->avatar)
                                        <img src="{{ url('storage/users/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                    @else
                                        <svg class="m-auto h-5 w-5 text-ink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2.5c-4.14 0-7.5 2.16-7.5 4.82V21h15v-1.68c0-2.66-3.36-4.82-7.5-4.82z"/></svg>
                                    @endif
                                </span>
                                <span class="hidden md:inline">{{ Str::limit(Auth::user()->name, 12) }}</span>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                            </button>

                            <div data-account-dropdown class="invisible absolute right-0 top-full z-50 mt-3 w-64 origin-top-right scale-95 rounded-2xl border border-ink-100 bg-white p-2 opacity-0 shadow-2xl transition-all duration-200">
                                <div class="border-b border-ink-50 px-3 pb-3 pt-2">
                                    <p class="truncate text-sm font-bold text-ink-900">{{ Auth::user()->name }}</p>
                                    <p class="truncate text-xs text-ink-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ url('account') }}" class="mt-1 block rounded-xl px-3 py-2.5 text-sm font-medium text-ink-700 transition hover:bg-cream-50 hover:text-brand-600">{{ __('My Account') }}</a>
                                <a href="{{ url('reservation/' . Auth::user()->email) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-ink-700 transition hover:bg-cream-50 hover:text-brand-600">{{ __('My Reservations') }}</a>
                                <a href="{{ url('reservation-events/' . Auth::user()->email) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-ink-700 transition hover:bg-cream-50 hover:text-brand-600">{{ __('My Events') }}</a>
                                <a href="{{ url('reservation-homestay/' . Auth::user()->email) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-ink-700 transition hover:bg-cream-50 hover:text-brand-600">{{ __('My Homestays') }}</a>
                                <div class="mt-1 border-t border-ink-50 pt-1">
                                    <form method="POST" action="{{ url('logout') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="block w-full rounded-xl px-3 py-2.5 text-left text-sm font-medium text-brand-600 transition hover:bg-brand-50">{{ __('Sign out') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ url('login') }}" class="hidden sm:inline-flex btn btn-secondary !px-5 !py-2.5 text-sm">{{ __('Login') }}</a>
                        <a href="{{ url('user/register') }}" class="hidden md:inline-flex btn btn-primary !px-5 !py-2.5 text-sm">{{ __('Register') }}</a>
                    @endauth

                    <a href="#searchBox" data-mfp-src="#searchBox" id="bookNowLink" class="hidden btn btn-primary !px-6 !py-2.5 text-sm lg:inline-flex">
                        {{ __('Book Now') }}
                    </a>

                    <button type="button" id="mobileMenuBtn" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-700 lg:hidden">
                        <svg id="iconOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                        <svg id="iconClose" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Search overlay -->
    <div id="searchOverlay" class="invisible fixed inset-0 z-[60] flex items-start justify-center bg-ink-950/60 opacity-0 backdrop-blur-sm transition-all duration-300">
        <form action="{{ url('search') }}" method="GET"
            class="container-gd mt-28 sm:mt-40">
            <div class="mx-auto max-w-2xl">
                <label for="searchInput" class="sr-only">{{ __('Search tours, villages, homestays and news') }}</label>
                <div class="flex items-center gap-3 rounded-full border border-white/20 bg-white p-2 pl-6 shadow-2xl">
                    <svg class="h-5 w-5 shrink-0 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <input id="searchInput" type="text" name="key" autocomplete="off" placeholder="{{ __('Search tours, villages, homestays, news...') }}"
                        class="w-full bg-transparent py-2 text-ink-900 placeholder-ink-400 focus:outline-none">
                    <button type="submit" class="btn btn-primary shrink-0 !py-2.5">{{ __('Search') }}</button>
                </div>
                <p class="mt-4 text-center text-sm text-white/70">{{ __('Try: village tour, homestay, Ubud, cultural experience') }}</p>
            </div>
        </form>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="fixed inset-0 z-[70] lg:hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-ink-950/50 opacity-0 transition-opacity duration-300" data-mobile-close></div>
        <div class="absolute right-0 top-0 flex h-full w-[85%] max-w-sm -translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300">
            <div class="flex items-center justify-between border-b border-ink-100 p-5">
                <img src="{{ url('assets/customer/img/logo.png') }}" alt="GODEVI" class="h-10 w-auto">
                <button type="button" data-mobile-close aria-label="Close menu" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto p-5" aria-label="Mobile navigation">
                <ul class="space-y-1">
                    @foreach ($nav as $item)
                        <li>
                            <a href="{{ url($item['url']) }}"
                                class="flex items-center justify-between rounded-xl px-4 py-3 font-semibold text-ink-800 transition hover:bg-cream-50 hover:text-brand-600">
                                {{ __($item['key']) }}
                                <svg class="h-4 w-4 text-ink-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ url('tour-packages') }}" class="flex items-center justify-between rounded-xl px-4 py-3 font-semibold text-ink-800 transition hover:bg-cream-50 hover:text-brand-600">
                            {{ __('Tour Packages') }}
                            <svg class="h-4 w-4 text-ink-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="space-y-3 border-t border-ink-100 p-5">
                @auth
                    <a href="{{ url('account') }}" class="flex items-center gap-3 rounded-2xl bg-cream-50 p-3">
                        <span class="flex h-10 w-10 overflow-hidden rounded-full bg-white">
                            @if (Auth::user()->avatar)
                                <img src="{{ url('storage/users/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                            @else
                                <svg class="m-auto h-5 w-5 text-ink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2.5c-4.14 0-7.5 2.16-7.5 4.82V21h15v-1.68c0-2.66-3.36-4.82-7.5-4.82z"/></svg>
                            @endif
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-bold text-ink-900">{{ Auth::user()->name }}</span>
                            <span class="block truncate text-xs text-ink-500">{{ Auth::user()->email }}</span>
                        </span>
                    </a>
                    <a href="{{ url('reservation/' . Auth::user()->email) }}" class="btn btn-secondary w-full">{{ __('My Reservations') }}</a>
                    <form method="POST" action="{{ url('logout') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-ghost w-full">{{ __('Sign Out') }}</button>
                    </form>
                @else
                    <a href="{{ url('login') }}" class="btn btn-primary w-full">{{ __('Login') }}</a>
                    <a href="{{ url('user/register') }}" class="btn btn-secondary w-full">{{ __('Create Account') }}</a>
                @endauth
            </div>
        </div>
    </div>

    <main id="main">
        @yield('content')
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="relative overflow-hidden bg-ink-950 text-ink-300">
        <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-brand-600/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 -left-20 h-96 w-96 rounded-full bg-forest-600/10 blur-3xl"></div>

        <div class="container-gd relative">
            <!-- Newsletter -->
            <div class="flex flex-col items-start justify-between gap-6 border-b border-white/10 py-12 lg:flex-row lg:items-center">
                <div class="max-w-xl">
                    <h2 class="font-display text-2xl font-semibold text-white sm:text-3xl">{{ __('Discover authentic village experiences in Bali') }}</h2>
                    <p class="mt-2 text-sm text-ink-400">{{ __('Subscribe for village stories, new experiences and travel inspiration.') }}</p>
                </div>
                <form class="flex w-full max-w-md items-center gap-2 rounded-full border border-white/15 bg-white/5 p-1.5 backdrop-blur" action="{{ url('news') }}" method="GET">
                    <input type="email" name="email" placeholder="{{ __('Your email address') }}" aria-label="{{ __('Email address') }}" disabled
                        class="w-full bg-transparent px-4 py-2.5 text-sm text-white placeholder-ink-400 focus:outline-none disabled:opacity-50">
                    <button type="submit" disabled class="btn btn-primary shrink-0 !px-5 !py-2.5 text-sm disabled:opacity-50">{{ __('Subscribe') }}</button>
                </form>
            </div>

            <!-- Columns -->
            <div class="grid grid-cols-2 gap-10 py-14 md:grid-cols-4 lg:grid-cols-5">
                <div class="col-span-2 lg:col-span-2">
                    <img src="{{ url('assets/customer/img/logo-white.png') }}" alt="GODEVI" class="h-12 w-auto">
                    <p class="mt-5 max-w-sm text-sm leading-relaxed text-ink-400">{{ __('GODEVI (Go Destination Village) is a socially pro-active tourism business under PT Banua Wisata Lestari — empowering village communities near coastal and developing areas of Bali through responsible tourism.') }}</p>
                    <div class="mt-6 flex items-center gap-3">
                        <a href="https://www.facebook.com/godestinationvillage/" target="_blank" rel="noopener" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-ink-200 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.92 3.78-3.92 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.45 2.9h-2.33V22c4.78-.79 8.43-4.94 8.43-9.94z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/godestinationvillage/" target="_blank" rel="noopener" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-ink-200 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.64.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85C2.38 3.92 3.9 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zm0-2.16C8.74 0 8.33.01 7.05.07 2.7.27.27 2.69.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.2 4.36 2.62 6.78 6.98 6.98 1.28.06 1.69.07 4.95.07s3.67-.01 4.95-.07c4.35-.2 6.78-2.62 6.98-6.98.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95C23.73 2.7 21.31.28 16.95.07 15.67.01 15.26 0 12 0zm0 5.84A6.16 6.16 0 105.84 12 6.16 6.16 0 0012 5.84zm0 10.16a4 4 0 114-4 4 4 0 01-4 4zm6.4-11.85a1.44 1.44 0 11-1.44 1.44 1.44 1.44 0 011.44-1.44z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/channel/UCule1cMKmK4RKh_n-Rrx81A" target="_blank" rel="noopener" aria-label="YouTube" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-ink-200 transition hover:border-brand-600 hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.19a3.02 3.02 0 00-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.5A3.02 3.02 0 00.5 6.19C0 8.07 0 12 0 12s0 3.93.5 5.81a3.02 3.02 0 002.12 2.14c1.88.5 9.38.5 9.38.5s7.5 0 9.38-.5a3.02 3.02 0 002.12-2.14C24 15.93 24 12 24 12s0-3.93-.5-5.81zM9.55 15.57V8.43L15.82 12l-6.27 3.57z"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">{{ __('Explore') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ url('village') }}" class="transition hover:text-white">{{ __('Villages') }}</a></li>
                        <li><a href="{{ url('tour-packages') }}" class="transition hover:text-white">{{ __('Tour Packages') }}</a></li>
                        <li><a href="{{ url('events') }}" class="transition hover:text-white">{{ __('Events') }}</a></li>
                        <li><a href="{{ url('homestay') }}" class="transition hover:text-white">{{ __('Homestays') }}</a></li>
                        <li><a href="{{ url('services') }}" class="transition hover:text-white">{{ __('Our Services') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">{{ __('Information') }}</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ url('faq') }}" class="transition hover:text-white">{{ __('FAQ') }}</a></li>
                        <li><a href="{{ url('term') }}" class="transition hover:text-white">{{ __('Terms & Conditions') }}</a></li>
                        <li><a href="{{ url('v-founding') }}" class="transition hover:text-white">{{ __('The Founding') }}</a></li>
                        <li><a href="{{ url('our-team') }}" class="transition hover:text-white">{{ __('Our Team') }}</a></li>
                        <li><a href="{{ url('v-board') }}" class="transition hover:text-white">{{ __('Board of Experts') }}</a></li>
                        <li><a href="{{ url('our-partner') }}" class="transition hover:text-white">{{ __('Our Partners') }}</a></li>
                        <li><a href="{{ url('news') }}" class="transition hover:text-white">{{ __('News & Insights') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">{{ __('Contact') }}</h3>
                    <ul class="mt-5 space-y-4 text-sm">
                        <li class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <span>Jl Kroya No 1, Denpasar, Bali</span>
                        </li>
                        <li><a href="tel:+6281997674778" class="flex items-center gap-2.5 transition hover:text-white">
                            <svg class="h-4 w-4 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                            +62 819-9767-4778</a></li>
                        <li><a href="mailto:hello@godestinationvillage.com" class="flex items-center gap-2.5 transition hover:text-white">
                            <svg class="h-4 w-4 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            hello@godestinationvillage.com</a></li>
                    </ul>
                    <img src="{{ asset('assets/customer/frontdata/images/payment.png') }}" alt="Accepted payment methods" class="mt-6 w-44" loading="lazy">
                </div>
            </div>

            <div class="flex flex-col items-center justify-between gap-3 border-t border-white/10 py-6 text-xs text-ink-500 sm:flex-row">
                <p>&copy; 2026 GODEVI — PT Banua Wisata Lestari. {{ __('All rights reserved.') }}</p>
                <p class="flex items-center gap-4">
                    <a href="{{ url('term') }}" class="transition hover:text-white">{{ __('Terms') }}</a>
                    <a href="{{ url('company-profile') }}" class="transition hover:text-white">{{ __('Company Profile') }}</a>
                    <a href="{{ url('sitemap.xml') }}" class="transition hover:text-white">{{ __('Sitemap') }}</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/6281997674778?text=Hi%20GODEVI,%20I%20want%20to%20book%20a%20village%20experience"
        target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
        class="fixed bottom-6 right-6 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg transition-transform hover:scale-110">
        <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.13.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.21-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35zm-5.42 7.4h-.004a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.9-9.88a9.83 9.83 0 019.88 9.89c0 5.45-4.43 9.88-9.88 9.88zM12.05 21.5c2.6 0 5.03-1.01 6.86-2.84a9.66 9.66 0 002.84-6.87 9.66 9.66 0 00-2.84-6.87c-1.83-1.82-4.26-2.83-6.86-2.83a9.68 9.68 0 00-9.69 9.69c0 1.7.45 3.37 1.3 4.83l-1.4 5.12 5.24-1.37a9.67 9.67 0 004.55 1.14z"/></svg>
    </a>

    @yield('js')

    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CD6TPM6T4N"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-CD6TPM6T4N', { 'anonymize_ip': true });
    </script>
</body>

</html>