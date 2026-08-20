@extends('customer/layout')

@section('content')
<style>
    .hero-fade {
        transition: opacity 1.2s ease-in-out;
    }
</style>

{{-- ============ HERO ============ --}}
<section class="relative flex min-h-[88vh] items-center overflow-hidden bg-ink-950" aria-label="GODEVI intro">
    <div id="heroSlides" class="absolute inset-0">
        @foreach (['slider1.png', 'slider2.png', 'slider3.png'] as $i => $slide)
            <div data-hero-slide data-index="{{ $i }}" class="hero-fade absolute inset-0 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                <img src="{{ asset('assets/customer/img/etc/slider/' . $slide) }}"
                    alt="GODEVI authentic Balinese village {{ $i + 1 }}" class="h-full w-full object-cover" fetchpriority="{{ $i === 0 ? 'high' : 'auto' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
            </div>
        @endforeach
        <div class="absolute inset-0 bg-gradient-to-r from-ink-950/90 via-ink-950/60 to-ink-950/20"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 via-transparent to-transparent"></div>
    </div>

    <div class="container-gd relative z-10 py-24">
        <div class="max-w-3xl">
            <p data-hero-reveal class="eyebrow !text-white animate-fade-up" style="animation-delay: 0.1s">{{ __('Go Destination Village · Bali') }}</p>
            <h1 class="font-display text-4xl font-bold leading-[1.15] text-white sm:text-5xl lg:text-6xl animate-fade-up" style="animation-delay: 0.25s">
                {{ __('Authentic Village') }} <span class="italic text-brand-400">{{ __('Experiences') }}</span> {{ __('in the Heart of Bali') }}
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/80 animate-fade-up" style="animation-delay: 0.4s">
                {{ __('Discover socially responsible tourism that empowers local communities. Immerse yourself in genuine Balinese culture, homestays and unforgettable experiences.') }}
            </p>
            <div class="mt-9 flex flex-wrap items-center gap-4 animate-fade-up" style="animation-delay: 0.55s">
                <a href="{{ url('village') }}" class="btn btn-primary !px-8 !py-4 text-base">
                    {{ __('Explore Villages') }}
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </a>
                <a href="{{ url('tour-packages') }}" class="btn btn-white !px-8 !py-4 text-base">{{ __('View Tour Packages') }}</a>
            </div>
        </div>

        </div>

    {{-- Scroll cue --}}
    <a href="#explore" class="absolute bottom-6 left-1/2 z-10 hidden -translate-x-1/2 flex-col items-center gap-2 text-white/60 transition hover:text-white lg:flex" aria-label="Scroll down">
        <span class="text-[10px] font-bold uppercase tracking-[0.25em]">Scroll</span>
        <span class="flex h-9 w-5 items-start justify-center rounded-full border border-white/40 p-1">
            <span class="h-2 w-1 animate-bounce rounded-full bg-white"></span>
        </span>
    </a>
</section>

{{-- ============ STATS ============ --}}
<section class="relative z-20 -mt-14">
    <div class="container-gd">
        <div class="grid grid-cols-2 gap-6 rounded-3xl border border-ink-100 bg-white p-8 shadow-[0_25px_60px_-20px_rgb(26_26_38/0.25)] md:grid-cols-4">
            @php
                $stats = [
                    ['value' => count($village), 'suffix' => '+', 'label' => 'Village Destinations'],
                    ['value' => count($packages), 'suffix' => '+', 'label' => 'Curated Experiences'],
                    ['value' => 12, 'suffix' => '', 'label' => 'Villages Partnership'],
                    ['value' => 100, 'suffix' => '%', 'label' => 'Social Responsibility'],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="text-center">
                    <div class="font-display text-4xl font-bold text-brand-600">
                        <span data-vue="CountUp" data-props="{{ json_encode(['value' => $s['value'], 'suffix' => $s['suffix'], 'duration' => 1600]) }}" class="inline-block">0{{ $s['suffix'] }}</span>
                    </div>
                    <p class="mt-2 text-sm font-semibold text-ink-500">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ EXPLORE VILLAGE ============ --}}
<section id="explore" class="section-pad">
    <div class="container-gd">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end">
            <div data-vue="Reveal" class="max-w-2xl">
                <p class="eyebrow">{{ __('Explore Village') }}</p>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Beautiful Balinese villages, authentic stories') }}</h2>
                <p class="mt-4 text-ink-500">{{ __('Every village has a story. Step into living traditions and meet the communities whose daily lives inspire our tourism experiences.') }}</p>
            </div>
            <a href="{{ url('village') }}" class="btn btn-secondary shrink-0">
                {{ __('View All Villages') }}
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
        </div>

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($tag->take(6) as $t)
                <a href="{{ url('village/' . $t->slug ?? '') }}" data-vue="Reveal"
                    class="group relative block h-80 overflow-hidden rounded-3xl" aria-label="{{ __('Explore') }} {{ $t->name }}">
                    <img src="{{ asset('storage/tag/' . $t->image) }}" alt="{{ $t->name }}, {{ __('village destination in Bali') }}"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-1.jpg') }}';">
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-ink-950/30 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-6">
                        <h3 class="font-display text-2xl font-semibold text-white">{{ $t->name }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-white/70">{{ $t->desc }}</p>
                        <span class="mt-3 inline-flex items-center gap-2 text-sm font-bold text-brand-400 transition-all group-hover:gap-3">
                            {{ __('Explore Now') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ ABOUT / WHY GODEVI ============ --}}
<section class="section-pad bg-cream-50">
    <div class="container-gd grid items-center gap-14 lg:grid-cols-2">
        <div class="relative" data-vue="Reveal">
            <div class="overflow-hidden rounded-3xl shadow-2xl">
                <img src="{{ asset('assets/customer/frontdata/images/about.jpg') }}" alt="GODEVI village tourism community in Bali"
                    class="h-[480px] w-full object-cover" loading="lazy">
            </div>
            <div class="absolute -bottom-8 -right-4 hidden w-52 rounded-3xl bg-brand-600 p-6 text-center text-white shadow-xl sm:block animate-float">
                <p class="font-display text-4xl font-bold">{{ __('SEE') }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wider">{{ __('Sustainability · Empowerment · Entrepreneurship') }}</p>
            </div>
        </div>

        <div data-vue="Reveal" data-props='{"delay":120}'>
            <p class="eyebrow">{{ __('Why GODEVI') }}</p>
            <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Tourism that gives back to Bali\'s villages') }}</h2>
            <p class="mt-5 leading-relaxed text-ink-500">
                {{ __('GODEVI (Go Destination Village) is a socially pro-active business dedicated to uplifting local communities in developing villages through tourism. We create a fair-trade marketplace by empowering village communities — ensuring travel benefits the people who call these places home.') }}
            </p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @php
                    $features = [
                        ['icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Socially Responsible', 'desc' => 'Every experience supports local livelihoods and community growth.'],
                        ['icon' => 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z', 'title' => 'Community Empowerment', 'desc' => 'A marketplace that champions fair trade and village entrepreneurs.'],
                        ['icon' => 'M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.909m1.15-4.455l-1.15 4.455m0 0a7.5 7.5 0 003.106 3.106m4.134-12.26a3 3 0 014.242 4.242L7.5 19.5H6l-.25-2.25L7.5 15.75l3.75-3.75', 'title' => 'Sustainable Tourism', 'desc' => 'Rooted in sustainability, balancing people, planet and prosperity.'],
                        ['icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'title' => 'Local Experiences', 'desc' => 'Genuine tours, homestays and events led by village communities.'],
                    ];
                @endphp
                @foreach ($features as $f)
                    <div class="rounded-2xl bg-white p-5 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)] transition hover:-translate-y-1">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}" /></svg>
                        </span>
                        <h3 class="mt-3 font-bold">{{ __($f['title']) }}</h3>
                        <p class="mt-1 text-sm text-ink-500">{{ __($f['desc']) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============ SERVICES ============ --}}
<section class="section-pad">
    <div class="container-gd">
        <div class="mx-auto mb-14 max-w-2xl text-center" data-vue="Reveal">
            <p class="eyebrow justify-center !gap-2">{{ __('Our Services') }}</p>
            <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Beyond travel — we build thriving villages') }}</h2>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $services = [
                    ['img' => 'internship.png', 'title' => 'Internship Program'],
                    ['img' => 'perencanaan.png', 'title' => 'Tourism Planning & Strategy'],
                    ['img' => 'portofolio.png', 'title' => 'Portfolio'],
                    ['img' => 'kajian.png', 'title' => 'Project Management'],
                    ['img' => 'sdm.png', 'title' => 'Human Resources Development'],
                    ['img' => 'branding.png', 'title' => 'Destination Branding & Digital Marketing'],
                    ['img' => 'tren.png', 'title' => 'Consumer Trend & Tourism Insight'],
                    ['img' => 'research.jpg', 'title' => 'Research Analytics & Scientific Consulting'],
                ];
            @endphp
            @foreach ($services as $i => $s)
                <a data-vue="Reveal" data-props="{{ json_encode(['delay' => ($i % 4) * 80]) }}"
                    href="{{ url('services') }}"
                    class="group card card-hover p-6 text-center">
                    <div class="mx-auto flex h-40 w-40 items-center justify-center">
                        <img src="{{ asset('assets/customer/img/etc/' . $s['img']) }}" alt="{{ $s['title'] }}" class="h-full w-full object-contain drop-shadow-lg transition-transform duration-300 group-hover:-translate-y-2 group-hover:scale-105" loading="lazy">
                    </div>
                    <h3 class="mt-5 font-bold leading-snug">{{ __($s['title']) }}</h3>
                    <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 opacity-0 transition-opacity group-hover:opacity-100">
                        {{ __('Learn more') }}
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ VIRTUAL REALITY ============ --}}
<section class="relative overflow-hidden bg-ink-950 section-pad">
    <div class="pointer-events-none absolute -left-24 top-1/2 h-96 w-96 -translate-y-1/2 rounded-full bg-forest-600/20 blur-3xl"></div>
    <div class="container-gd relative grid items-center gap-12 lg:grid-cols-2">
        <div data-vue="Reveal">
            <p class="eyebrow !text-brand-400">{{ __('Virtual Reality') }}</p>
            <h2 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ __('Witness the wonders of Balinese villages — before you arrive') }}</h2>
            <p class="mt-5 leading-relaxed text-white/70">{{ __('Step into an immersive virtual reality experience that transports you to the fascinating world of Bali\'s villages. Preview the culture, landscapes and activities that await you.') }}</p>
            <a href="https://www.vrfmipa.com/meler" target="_blank" rel="noopener" class="btn btn-primary mt-8 !px-8 !py-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                {{ __('Go Virtual') }}
            </a>
        </div>
        <div data-vue="Reveal" data-props='{"delay":150}' class="overflow-hidden rounded-3xl border border-white/10 shadow-2xl">
            <video controls preload="metadata" playsinline class="aspect-video w-full object-cover"
                poster="{{ asset('assets/customer/frontdata/images/bg_4.jpg') }}">
                <source src="{{ asset('storage/videos/vr-godevi.mp4') }}" type="video/mp4">
                {{ __('Your browser does not support the video tag.') }}
            </video>
        </div>
    </div>
</section>

{{-- ============ FEATURED TOUR PACKAGES ============ --}}
<section id="tours" class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end" data-vue="Reveal">
            <div class="max-w-2xl">
                <p class="eyebrow">{{ __('Tour Packages') }}</p>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Featured experiences you\'ll love') }}</h2>
                <p class="mt-4 text-ink-500">{{ __('Handpicked village tours and activities curated for authentic cultural immersion.') }}</p>
            </div>
            <a href="{{ url('tour-packages') }}" class="btn btn-secondary shrink-0">{{ __('See All Packages') }}</a>
        </div>

        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($packages->take(3) as $pkg)
                <a href="{{ url('tour-packages/' . $pkg->slug) }}" data-vue="Reveal"
                    class="group card card-hover flex flex-col overflow-hidden">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $pkg->default_img ? asset('storage/packages/' . $pkg->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $pkg->name }} — {{ __('Bali village tour package') }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        <span class="badge absolute left-4 top-4 bg-white/95 text-brand-600 shadow-sm">X</span>
                        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-ink-950/60 to-transparent"></div>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-ink-400">
                            <span class="rounded-full bg-cream-50 px-2.5 py-1">{{ $pkg->cat_name ?? __('Village Tour') }}</span>
                            @if ($pkg->vil_name ?? null)
                                <span class="flex items-center gap-1"><svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>{{ $pkg->vil_name }}</span>
                            @endif
                        </div>
                        <h3 class="mt-3 flex-1 font-display text-xl font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $pkg->name }}</h3>
                        <div class="mt-5 flex items-center justify-between border-t border-ink-50 pt-4">
                            <span class="text-lg font-bold text-brand-600">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink-500 transition group-hover:text-brand-600">
                                {{ __('Book Now') }}
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ LATEST NEWS ============ --}}
<section class="section-pad">
    <div class="container-gd">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 md:flex-row md:items-end" data-vue="Reveal">
            <div class="max-w-2xl">
                <p class="eyebrow">{{ __('News & Insights') }}</p>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Stories from the villages') }}</h2>
            </div>
            <a href="{{ url('news') }}" class="btn btn-secondary shrink-0">{{ __('All Articles') }}</a>
        </div>

        <div class="grid gap-7 md:grid-cols-3">
            @foreach ($recent_blog->take(3) as $rec)
                <article data-vue="Reveal" class="group card card-hover flex flex-col overflow-hidden">
                    <a href="{{ url('news/' . $rec->slug) }}" class="relative block h-52 overflow-hidden" aria-label="{{ $rec->post_title }}">
                        <img src="{{ asset('storage/blogs/' . $rec->post_thumbnail) }}"
                            alt="{{ $rec->post_title }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('assets/customer/img/etc/slider/blog%201%205x2.png') }}';">
                    </a>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-4 text-xs font-semibold text-ink-400">
                            <span class="flex items-center gap-1.5"><svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>{{ \Carbon\Carbon::parse($rec->created_at)->format('M d, Y') }}</span>
                            <span class="flex items-center gap-1.5"><svg class="h-4 w-4 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>{{ $rec->post_tags ?: __('News') }}</span>
                        </div>
                        <a href="{{ url('news/' . $rec->slug) }}" class="mt-3 block font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">
                            {{ $rec->post_title }}
                        </a>
                        <p class="mt-2 flex-1 text-sm text-ink-500">{!! \Illuminate\Support\Str::words(strip_tags($rec->post_content), 22, '...') !!}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-ink-50 pt-4">
                            <span class="flex items-center gap-2 text-sm font-semibold text-ink-500">
                                <span class="flex h-8 w-8 overflow-hidden rounded-full bg-cream-100">
                                    @if ($rec->user?->avatar)
                                        <img src="{{ asset('storage/users/' . $rec->user->avatar) }}" alt="{{ $rec->user->name }}" class="h-full w-full object-cover">
                                    @else
                                        <svg class="m-auto h-4 w-4 text-ink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2.5c-4.14 0-7.5 2.16-7.5 4.82V21h15v-1.68c0-2.66-3.36-4.82-7.5-4.82z"/></svg>
                                    @endif
                                </span>
                                {{ $rec->user?->name ?? __('GODEVI Team') }}
                            </span>
                            <a href="{{ url('news/' . $rec->slug) }}" class="text-sm font-bold text-brand-600">{{ __('Read More') }}</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ TESTIMONIALS ============ --}}
@if (count($reviews))
    <section class="section-pad bg-ink-950">
        <div class="container-gd">
            <div class="mx-auto mb-14 max-w-2xl text-center" data-vue="Reveal">
                <p class="eyebrow justify-center !gap-2 !text-brand-400">{{ __('Testimonials') }}</p>
                <h2 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ __('What our travelers say') }}</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($reviews->take(3) as $r)
                    <figure data-vue="Reveal" class="flex flex-col rounded-3xl border border-white/10 bg-white/5 p-7 backdrop-blur transition hover:-translate-y-1" itemscope itemtype="https://schema.org/Review">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= $r->rating ? '' : 'opacity-25' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.5a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                            @endfor
                        </div>
                        <blockquote class="mt-5 flex-1 text-white/80" itemprop="reviewBody" style="font-style: italic">"{{ $r->comment }}"</blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-white/10 pt-5">
                            <span class="flex h-12 w-12 overflow-hidden rounded-full bg-white/10 ring-2 ring-white/20">
                                <img src="{{ asset('storage/reviews/' . $r->avatar) }}" alt="{{ $r->name }}" class="h-full w-full object-cover" loading="lazy" itemprop="image">
                            </span>
                            <span>
                                <span class="block font-bold text-white" itemprop="author">{{ $r->name }}</span>
                                <span class="block text-sm text-white/50">{{ $r->job }}</span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ============ FINAL CTA ============ --}}
<section class="section-pad">
    <div class="container-gd">
        <div data-vue="Reveal" class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-16 text-center sm:px-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ __('Ready for an authentic village experience?') }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-white/80">{{ __('Book your tour, homestay or event today and support the communities that make Bali extraordinary.') }}</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('tour-packages') }}" class="btn btn-white !px-8 !py-4">{{ __('Browse Experiences') }}</a>
                <a href="{{ url('contact') }}" class="btn border border-white/40 text-white hover:bg-white/10 !px-8 !py-4">{{ __('Contact Us') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    // Hero crossfade
    (function () {
        const slides = Array.from(document.querySelectorAll('[data-hero-slide]'));
        if (slides.length < 2) return;
        let current = 0;
        setInterval(() => {
            slides[current].classList.add('opacity-0');
            current = (current + 1) % slides.length;
            slides[current].classList.remove('opacity-0');
        }, 6000);
    })();
</script>
@endsection