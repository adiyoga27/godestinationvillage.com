@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="homestay"
    :title="__('Village Homestay & Stay')"
    subtitle="{{ __('Wake up to village life — stay with local families and experience genuine Indonesian hospitality.') }}"
    image="assets/customer/frontdata/images/bg_1.jpg"
    :crumbs="[__('Home') => '/', __('Homestay') => '']"
/>

@php
    $priceLabels = [
        'under-300k' => __('Under Rp300k'),
        '300k-700k' => __('Rp300k – 700k'),
        '700k-1500k' => __('Rp700k – 1.5M'),
        'over-1500k' => __('Over Rp1.5M'),
    ];
    $sortLabels = [
        'recommended' => __('Recommended'),
        'price_asc' => __('Lowest price'),
        'price_desc' => __('Highest price'),
        'newest' => __('Newest'),
    ];
    $activeChips = array_filter([
        'q' => $filters['q'] ? '“'.$filters['q'].'”' : null,
        'village' => $filters['village'] ? ($filterOptions['villages'][$filters['village']] ?? null) : null,
        'type' => $filters['type'] ? __($filterOptions['types'][$filters['type']] ?? '') : null,
        'price' => $filters['price'] ? $priceLabels[$filters['price']] : null,
        'breakfast' => $filters['breakfast'] ? __('Breakfast included') : null,
    ]);
@endphp

<section class="pb-16 sm:pb-20 lg:pb-24">
    <div class="container-gd">
        {{-- ============ FILTER ============ --}}
        <form method="GET" action="{{ url('homestay') }}" data-listing-filter
            class="relative z-10 -mt-10 rounded-3xl border border-ink-100 bg-white p-4 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)] sm:-mt-14 sm:p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <label class="flex-1">
                    <span class="label-gd">{{ __('Search') }}</span>
                    <span class="relative block">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="search" name="q" value="{{ $filters['q'] }}" maxlength="100" class="input-gd !pl-11"
                            placeholder="{{ __('Homestay name, village or location...') }}">
                    </span>
                </label>

                <div class="grid grid-cols-2 gap-3 lg:flex lg:w-auto">
                    <label class="lg:w-52">
                        <span class="label-gd">{{ __('Village') }}</span>
                        <select name="village" class="input-gd" data-autosubmit>
                            <option value="">{{ __('All villages') }}</option>
                            @foreach ($filterOptions['villages'] as $id => $villageName)
                                <option value="{{ $id }}" @selected($filters['village'] == $id)>{{ $villageName }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="lg:w-48">
                        <span class="label-gd">{{ __('Room type') }}</span>
                        <select name="type" class="input-gd" data-autosubmit>
                            <option value="">{{ __('All room types') }}</option>
                            @foreach ($filterOptions['types'] as $id => $typeName)
                                <option value="{{ $id }}" @selected($filters['type'] == $id)>{{ __($typeName) }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary !py-3 lg:!px-7">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    {{ __('Search') }}
                </button>
            </div>

            <div class="mt-4 flex flex-col gap-3 border-t border-ink-100 pt-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="{{ __('Price per night') }}">
                    @foreach (['' => __('Any price')] + $priceLabels as $key => $label)
                        <label class="shrink-0 cursor-pointer">
                            <input type="radio" name="price" value="{{ $key }}" class="peer sr-only" data-autosubmit @checked(($filters['price'] ?? '') === $key)>
                            <span class="inline-flex items-center rounded-full border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-brand-300 peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500/30">{{ $label }}</span>
                        </label>
                    @endforeach
                    <label class="shrink-0 cursor-pointer">
                        <input type="checkbox" name="breakfast" value="1" class="peer sr-only" data-autosubmit @checked($filters['breakfast'])>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-forest-300 peer-checked:border-forest-600 peer-checked:bg-forest-600 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-forest-500/30">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.37c-2.032 0-4.034.125-6 .37m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12M12.265 3.11a.375.375 0 11-.53 0L12 2.845l.265.265zm-3 0a.375.375 0 11-.53 0L9 2.845l.265.265zm6 0a.375.375 0 11-.53 0L15 2.845l.265.265z" /></svg>
                            {{ __('Breakfast included') }}
                        </span>
                    </label>
                </div>

                <label class="flex shrink-0 items-center gap-2 text-sm font-semibold text-ink-600">
                    <span class="whitespace-nowrap">{{ __('Sort by') }}</span>
                    <select name="sort" class="input-gd !w-auto !py-2" data-autosubmit data-default="recommended">
                        @foreach ($sortLabels as $key => $label)
                            <option value="{{ $key }}" @selected($filters['sort'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </form>

        {{-- ============ HASIL ============ --}}
        @include('customer.partials.listing.results', ['total' => $packages->total(), 'noun' => __('homestays found'), 'chips' => $activeChips, 'base' => 'homestay'])

        <div class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($packages as $pack)
                @php
                    $tr = $pack->translate?->firstWhere('lang', App::getLocale());
                    $name = $tr?->name ?: $pack->name;
                    $desc = $tr?->description ?: $pack->description;
                    $location = $tr?->location ?: $pack->location;
                    $finalPrice = $pack->finalPrice();
                    $hasDiscount = $pack->disc > 0 && $pack->disc < $pack->price;
                @endphp
                <a href="{{ url('homestay/' . $pack->id) }}" data-vue="Reveal"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-ink-100">
                        <img src="{{ $pack->default_img ? asset('storage/homestay/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $name }} — {{ __('village homestay in Indonesia') }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-transparent"></div>

                        <div class="absolute inset-x-4 top-4 flex items-start justify-between gap-2">
                            @if ($pack->category)
                                <span class="badge bg-white/95 text-ink-800 shadow-sm backdrop-blur">
                                    <svg class="h-3.5 w-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                                    {{ __($pack->category->name) }}
                                </span>
                            @else
                                <span></span>
                            @endif
                            @if ($hasDiscount)
                                <span class="badge bg-brand-600 text-white shadow-sm">-{{ (int) floor((1 - $pack->disc / $pack->price) * 100) }}%</span>
                            @endif
                        </div>

                        @if ($pack->village)
                            <span class="absolute bottom-4 left-4 inline-flex items-center gap-1.5 text-sm font-semibold text-white drop-shadow">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $pack->village->village_name }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex-1">
                        <h2 class="font-display text-xl font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $name }}</h2>
                        @if ($location)
                            <p class="mt-1.5 line-clamp-1 text-sm text-ink-500" title="{{ $location }}">{{ $location }}</p>
                        @endif
                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-ink-500">{{ \Illuminate\Support\Str::limit(trim(strip_tags($desc ?? '')), 160) }}</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if ($pack->is_breakfast)
                                <span class="badge bg-forest-50 text-forest-700">{{ __('Breakfast included') }}</span>
                            @endif
                            @if ($pack->check_in_time)
                                <span class="badge bg-ink-50 text-ink-600">{{ __('Check-in') }} {{ \Illuminate\Support\Str::limit($pack->check_in_time, 12, '') }}</span>
                            @endif
                            @if ($pack->owner_name)
                                <span class="badge bg-ink-50 text-ink-600">{{ __('Hosted by') }} {{ \Illuminate\Support\Str::limit($pack->owner_name, 18) }}</span>
                            @endif
                        </div>
                        </div>

                        <div class="mt-5 flex items-end justify-between gap-3 border-t border-ink-100 pt-4">
                            <div>
                                @if ($hasDiscount)
                                    <span class="block text-xs text-ink-400 line-through">Rp {{ number_format($pack->price, 0, ',', '.') }}</span>
                                @endif
                                <span class="text-xl font-bold text-ink-950">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                <span class="text-sm text-ink-400">/ {{ __('night') }}</span>
                            </div>
                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-ink-200 bg-cream-50 px-6 py-16 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-brand-600 shadow-sm">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </span>
                    <h2 class="mt-4 font-display text-xl font-semibold text-ink-950">{{ $activeFilterCount ? __('No homestays match your filters') : __('No homestays available yet.') }}</h2>
                    @if ($activeFilterCount)
                        <p class="mt-2 text-sm text-ink-500">{{ __('Try another village, room type or price range.') }}</p>
                        <a href="{{ url('homestay') }}" class="btn btn-secondary mt-6">{{ __('Reset all') }}</a>
                    @endif
                </div>
            @endforelse
        </div>

        @include('customer.partials.listing.pagination', ['paginator' => $packages])
    </div>
</section>

@include('customer.partials.listing.script')
@endsection
