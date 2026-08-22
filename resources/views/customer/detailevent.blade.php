@extends('customer/layout')

@section('content')
@php
    $locale = App::getLocale();
    $tr = $packages->translate?->firstWhere('lang', $locale);
    $name = $tr?->name ?: $packages->name;
    $desc = $tr?->description ?: $packages->description;
    $interary = $tr?->interary ?: $packages->interary;
    $inclusion = $tr?->inclusion ?: $packages->inclusion;
    $additional = $tr?->additional ?: $packages->additional;
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    $default = $packages->default_img;
    $candidates = collect($disk->files('events/' . $packages->id))
        ->filter(fn ($f) => !$default || basename($f) !== $default)
        ->map(fn ($f) => ['url' => asset('storage/' . $f), 'hash' => md5($disk->get($f))])
        ->unique('hash')
        ->values();
    $heroExists = $default && $disk->exists('events/' . $default);
    if ($heroExists) {
        $hero = asset('storage/events/' . $default);
        $heroHash = md5($disk->get('events/' . $default));
        $gallery = collect([$hero])->merge($candidates->reject(fn ($c) => $c['hash'] === $heroHash)->pluck('url'))->unique()->values()->all();
    } else {
        $hero = $candidates->first()['url'] ?? asset('assets/customer/frontdata/images/destination-1.jpg');
        $gallery = $candidates->pluck('url')->values()->all();
    }
    $price = $packages->price;
    if ($packages->disc > 0 && !$packages->is_paywish && !$packages->is_free) {
        $price = $packages->disc;
    }
@endphp

<x-partials.page-hero
    :title="$name"
    :image="$hero"
    :crumbs="[__('Home') => '/', __('Events') => 'events', $name => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_360px]">
            <div class="space-y-10">
                <div data-vue="Reveal">
                    <span data-vue="GallerySlider" data-props='{{ json_encode(["images" => $gallery, "alt" => $name]) }}' class="block" style="display:block"></span>
                </div>

                <div data-vue="Reveal" data-props='{"delay":100}'>
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($packages->date_event)
                            <span class="badge bg-brand-50 text-brand-700">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                {{ \Carbon\Carbon::parse($packages->date_event)->format('d M Y H:i') }} WITA
                            </span>
                        @endif
                        @if ($packages->location)
                            <span class="badge bg-cream-50 text-ink-700">
                                <svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $packages->location }}
                            </span>
                        @endif
                    </div>

                    <h1 class="mt-4 font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ $name }}</h1>
                    <div class="prose-gd mt-6">{!! $desc !!}</div>

                    @if ($interary)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Program') }}</h2>
                            <div class="prose-gd mt-3">{!! $interary !!}</div>
                        </div>
                    @endif

                    @if ($inclusion)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Included') }}</h2>
                            <div class="prose-gd mt-3">{!! $inclusion !!}</div>
                        </div>
                    @endif

                    @if ($additional)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Additional Information') }}</h2>
                            <div class="prose-gd mt-3">{!! $additional !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <div class="border-b border-ink-50 p-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Price per person') }}</p>
                        <p class="mt-1 text-lg font-bold text-brand-600">
                            @if ($packages->is_free)
                                {{ __('Free Event') }}
                            @else
                                Rp {{ number_format($price, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                    <div class="space-y-3 p-6">
                        <a href="{{ url('bookingEvents/' . $packages->id) }}" class="btn btn-primary w-full !py-4 !text-base">
                            {{ __('Reserve Now') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ url('events') }}" class="btn btn-secondary w-full">{{ __('View All Events') }}</a>
                    </div>
                </div>

                <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">{{ __('Other Events') }}</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($recent->take(4) as $rec)
                            @php $recTr = $rec->translate?->firstWhere('lang', $locale); @endphp
                            <li>
                                <a href="{{ url('events/' . $rec->slug) }}" class="group flex items-center gap-4">
                                    <img src="{{ asset('storage/events/' . $rec->default_img) }}"
                                        alt="{{ $recTr?->name ?: $rec->name }}" class="h-16 w-20 flex-shrink-0 rounded-xl object-cover" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-1.jpg') }}';">
                                    <span class="min-w-0">
                                        <span class="block line-clamp-2 text-sm font-semibold text-ink-800 transition group-hover:text-brand-600">{{ $recTr?->name ?: $rec->name }}</span>
                                        @if ($rec->date_event)
                                            <span class="mt-0.5 block text-xs font-semibold uppercase tracking-wide text-ink-400">{{ \Carbon\Carbon::parse($rec->date_event)->format('d M Y') }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">{{ __('Instagram Post') }}</h2>
                    <div class="mt-4">
                        <x-partials.instagram-post :url="$instagram" />
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection