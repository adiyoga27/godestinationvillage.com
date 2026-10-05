@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="events"
    :title="__('Village Events & Festivals')"
    subtitle="{{ __('Join authentic village ceremonies, workshops and community events across Indonesia.') }}"
    image="assets/customer/img/page-title-area/header-event.png"
    :crumbs="[__('Home') => '/', __('Events') => '']"
/>

@php
    $whenLabels = ['upcoming' => __('Upcoming'), 'past' => __('Past events'), 'all' => __('All dates')];
    $priceLabels = ['' => __('Any price'), 'free' => __('Free'), 'paid' => __('Paid events')];
    $sortLabels = ['date' => __('Event date'), 'newest' => __('Recently added')];
    $chips = [
        'q' => $filters['q'] ? '“'.$filters['q'].'”' : null,
        'category' => $filters['category'] ? __($filterOptions['categories'][$filters['category']] ?? '') : null,
        'when' => $filters['when'] !== 'upcoming' ? $whenLabels[$filters['when']] : null,
        'price' => $filters['price'] ? $priceLabels[$filters['price']] : null,
    ];
    $activeFilterCount = count(array_filter($chips));
    $today = now()->startOfDay();
@endphp

<section class="pb-16 sm:pb-20 lg:pb-24">
    <div class="container-gd">
        {{-- ============ FILTER ============ --}}
        <form method="GET" action="{{ url('events') }}" data-listing-filter
            class="relative z-10 -mt-10 rounded-3xl border border-ink-100 bg-white p-4 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)] sm:-mt-14 sm:p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <label class="flex-1">
                    <span class="label-gd">{{ __('Search') }}</span>
                    <span class="relative block">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="search" name="q" value="{{ $filters['q'] }}" maxlength="100" class="input-gd !pl-11" placeholder="{{ __('Event name or location...') }}">
                    </span>
                </label>
                <label class="lg:w-60">
                    <span class="label-gd">{{ __('Category') }}</span>
                    <select name="category" class="input-gd" data-autosubmit>
                        <option value="">{{ __('All categories') }}</option>
                        @foreach ($filterOptions['categories'] as $id => $categoryName)
                            <option value="{{ $id }}" @selected($filters['category'] == $id)>{{ __($categoryName) }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="btn btn-primary !py-3 lg:!px-7">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    {{ __('Search') }}
                </button>
            </div>
            <div class="mt-4 flex flex-col gap-3 border-t border-ink-100 pt-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    {{-- "Akan datang" = default → tidak ikut ke URL. --}}
                    @foreach ($whenLabels as $key => $label)
                        @include('customer.partials.listing.pill', ['name' => 'when', 'value' => $key === 'upcoming' ? '' : $key, 'label' => $label, 'checked' => $filters['when'] === $key])
                    @endforeach
                    <span class="mx-1 hidden h-6 w-px bg-ink-200 sm:block"></span>
                    @foreach ($priceLabels as $key => $label)
                        @include('customer.partials.listing.pill', ['name' => 'price', 'value' => $key, 'label' => $label, 'checked' => ($filters['price'] ?? '') === $key, 'tone' => 'forest'])
                    @endforeach
                </div>
                <label class="flex shrink-0 items-center gap-2 text-sm font-semibold text-ink-600">
                    <span class="whitespace-nowrap">{{ __('Sort by') }}</span>
                    <select name="sort" class="input-gd !w-auto !py-2" data-autosubmit data-default="date">
                        @foreach ($sortLabels as $key => $label)
                            <option value="{{ $key }}" @selected($filters['sort'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </form>

        @include('customer.partials.listing.results', ['total' => $packages->total(), 'noun' => __('events found'), 'chips' => $chips, 'base' => 'events'])

        <div class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($packages as $pack)
                @php
                    $tr = $pack->translate?->firstWhere('lang', App::getLocale());
                    $name = $tr?->name ?: $pack->name;
                    $desc = $tr?->description ?: $pack->description;
                    $location = $tr?->location ?: $pack->location;
                    $date = $pack->date_event ? \Carbon\Carbon::parse($pack->date_event) : null;
                    $isPast = $date && $date->lt($today);
                    $isFree = $pack->is_free || $pack->price <= 0;
                    $hasDiscount = ! $isFree && ! $pack->is_paywish && $pack->disc > 0 && $pack->disc < $pack->price;
                @endphp
                <a href="{{ url('events/' . $pack->slug) }}" data-vue="Reveal"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-ink-100">
                        <img src="{{ $pack->default_img ? asset('storage/events/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $name }} — {{ __('village event in Indonesia') }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105 {{ $isPast ? 'grayscale-[40%]' : '' }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/60 via-transparent to-transparent"></div>
                        <div class="absolute inset-x-4 top-4 flex items-start justify-between gap-2">
                            @if ($date)
                                <span class="flex w-16 flex-col items-center rounded-2xl bg-white/95 py-2 text-center shadow-lg backdrop-blur">
                                    <span class="text-2xl font-extrabold leading-none text-brand-600">{{ $date->format('d') }}</span>
                                    <span class="mt-0.5 text-[11px] font-bold uppercase leading-tight text-ink-500">{{ $date->translatedFormat('M Y') }}</span>
                                </span>
                            @else
                                <span></span>
                            @endif
                            <span class="flex flex-col items-end gap-1.5">
                                @if ($pack->category)
                                    <span class="badge bg-white/95 text-ink-800 shadow-sm backdrop-blur">{{ __($pack->category->name) }}</span>
                                @endif
                                @if ($isPast)
                                    <span class="badge bg-ink-950/75 text-white">{{ __('Past event') }}</span>
                                @elseif ($hasDiscount)
                                    <span class="badge bg-brand-600 text-white shadow-sm">-{{ (int) floor((1 - $pack->disc / $pack->price) * 100) }}%</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex-1">
                            <h2 class="font-display text-xl font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $name }}</h2>
                            <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-ink-500">
                                @if ($date)
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                        {{ $date->translatedFormat('l, j F Y') }}
                                    </span>
                                @endif
                                @if ($location)
                                    <span class="inline-flex min-w-0 items-center gap-1.5">
                                        <svg class="h-4 w-4 shrink-0 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                        <span class="line-clamp-1">{{ $location }}</span>
                                    </span>
                                @endif
                            </div>
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-ink-500">{{ \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags($desc ?? ''))), 160) }}</p>
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-3 border-t border-ink-100 pt-4">
                            <div>
                                @if ($isFree)
                                    <span class="text-xl font-bold text-forest-700">{{ __('Free') }}</span>
                                @elseif ($pack->is_paywish)
                                    <span class="text-base font-bold text-ink-950">{{ __('Pay as you wish') }}</span>
                                @else
                                    @if ($hasDiscount)
                                        <span class="block text-xs text-ink-400 line-through">Rp {{ number_format($pack->price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="text-xl font-bold text-ink-950">Rp {{ number_format($hasDiscount ? $pack->disc : $pack->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                @php $onlyDefault = $activeFilterCount === 0; @endphp
                @include('customer.partials.listing.empty', [
                    'filtered' => ! $onlyDefault, 'base' => 'events',
                    'title' => $onlyDefault ? __('No upcoming events right now') : __('No events match your filters'),
                    'hint' => $onlyDefault ? __('New village events are added regularly — check back soon or see past events.') : __('Try another category or date.'),
                ])
                @if ($onlyDefault)
                    <div class="col-span-full -mt-4 text-center">
                        <a href="{{ url('events').'?when=past' }}" class="text-sm font-bold text-brand-600 hover:underline">{{ __('See past events') }} →</a>
                    </div>
                @endif
            @endforelse
        </div>

        @include('customer.partials.listing.pagination', ['paginator' => $packages])
    </div>
</section>

@include('customer.partials.listing.script')
@endsection
