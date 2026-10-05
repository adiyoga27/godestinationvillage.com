@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="village"
    title="{{ __('Explore Villages in Indonesia') }}"
    subtitle="{{ __('Discover authentic Indonesian villages, culture and community-driven tourism experiences curated by GODEVI.') }}"
    image="assets/customer/img/page-title-area/explorer.jpg"
    :crumbs="[__('Home') => '/', __('Explore Village') => '']"
/>

@php
    $sortLabels = [
        'popular' => __('Most experiences'),
        'name' => __('Name (A–Z)'),
        'newest' => __('Newest'),
    ];
    $chips = [
        'q' => $filters['q'] ? '“'.$filters['q'].'”' : null,
        'has_packages' => $filters['has_packages'] ? __('Has tour packages') : null,
        'has_homestay' => $filters['has_homestay'] ? __('Has homestay') : null,
    ];
    $activeFilterCount = count(array_filter($chips));
@endphp

<section class="pb-16 sm:pb-20 lg:pb-24">
    <div class="container-gd">
        {{-- ============ FILTER ============ --}}
        <form method="GET" action="{{ url('village') }}" data-listing-filter
            class="relative z-10 -mt-10 rounded-3xl border border-ink-100 bg-white p-4 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)] sm:-mt-14 sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <label class="flex-1">
                    <span class="label-gd">{{ __('Search') }}</span>
                    <span class="relative block">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="search" name="q" value="{{ $filters['q'] }}" maxlength="100" class="input-gd !pl-11" placeholder="{{ __('Village name, regency or province...') }}">
                    </span>
                </label>
                <button type="submit" class="btn btn-primary !py-3 sm:!px-7">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    {{ __('Search') }}
                </button>
            </div>
            <div class="mt-4 flex flex-col gap-3 border-t border-ink-100 pt-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2">
                    @include('customer.partials.listing.pill', ['type' => 'checkbox', 'name' => 'has_packages', 'value' => 1, 'label' => __('Has tour packages'), 'checked' => $filters['has_packages'],
                        'icon' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" /></svg>'])
                    @include('customer.partials.listing.pill', ['type' => 'checkbox', 'name' => 'has_homestay', 'value' => 1, 'label' => __('Has homestay'), 'checked' => $filters['has_homestay'], 'tone' => 'forest',
                        'icon' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'])
                </div>
                <label class="flex shrink-0 items-center gap-2 text-sm font-semibold text-ink-600">
                    <span class="whitespace-nowrap">{{ __('Sort by') }}</span>
                    <select name="sort" class="input-gd !w-auto !py-2" data-autosubmit data-default="popular">
                        @foreach ($sortLabels as $key => $label)
                            <option value="{{ $key }}" @selected($filters['sort'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </form>

        @include('customer.partials.listing.results', ['total' => $village->total(), 'noun' => __('villages found'), 'chips' => $chips, 'base' => 'village'])

        <div class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($village as $val)
                @php
                    $vd = $val->village_detail;
                    $name = \Illuminate\Support\Str::squish($vd->village_name ?? $val->name);
                    $address = $vd->village_address ?? '';
                @endphp
                <a href="{{ url('village/' . ($vd->slug ?? '')) }}" data-vue="Reveal"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-ink-100">
                        <img src="{{ $val->avatar ? asset('storage/users/' . $val->avatar) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $name }} — {{ __('village destination in Indonesia') }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 via-ink-950/10 to-transparent"></div>
                        <div class="absolute inset-x-5 bottom-5">
                            <h2 class="font-display text-2xl font-semibold leading-tight text-white">{{ $name }}</h2>
                            @if ($address)
                                <p class="mt-1 flex items-center gap-1.5 text-sm text-white/80">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    <span class="line-clamp-1">{{ $address }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <p class="flex-1 line-clamp-3 text-sm leading-relaxed text-ink-500">{{ \Illuminate\Support\Str::words(trim(html_entity_decode(strip_tags($vd->desc ?? ''))), 26, '...') }}</p>
                        <div class="mt-5 flex items-center justify-between gap-3 border-t border-ink-100 pt-4">
                            <div class="flex flex-wrap gap-2">
                                @if ($val->packages_count)
                                    <span class="badge bg-brand-50 text-brand-700">{{ $val->packages_count }} {{ __('tour packages') }}</span>
                                @endif
                                @if ($val->homestays_count)
                                    <span class="badge bg-forest-50 text-forest-700">{{ $val->homestays_count }} {{ __('homestays') }}</span>
                                @endif
                                @if (! $val->packages_count && ! $val->homestays_count)
                                    <span class="text-xs font-semibold text-ink-400">{{ __('Village profile') }}</span>
                                @endif
                            </div>
                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                @include('customer.partials.listing.empty', ['filtered' => $activeFilterCount > 0, 'base' => 'village',
                    'title' => $activeFilterCount ? __('No villages match your search') : __('No villages published yet. Check back soon.'),
                    'hint' => $activeFilterCount ? __('Try another name, regency or province.') : null])
            @endforelse
        </div>

        @include('customer.partials.listing.pagination', ['paginator' => $village])
    </div>
</section>

@include('customer.partials.listing.script')
@endsection
