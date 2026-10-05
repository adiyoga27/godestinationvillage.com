@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="tour-packages"
    title="{{ __('Village Tour Packages') }}"
    subtitle="{{ __('Curated village experiences, cultural immersion and unforgettable adventures designed with local communities.') }}"
    image="assets/customer/img/page-title-area/bestoffer.jpg"
    :crumbs="[__('Home') => '/', __('Tour Packages') => '']"
/>

@php
    $priceLabels = [
        'free' => __('Free'),
        'under-300k' => __('Under Rp300k'),
        '300k-700k' => __('Rp300k – 700k'),
        '700k-1500k' => __('Rp700k – 1.5M'),
        'over-1500k' => __('Over Rp1.5M'),
    ];
    $sortLabels = [
        'newest' => __('Newest'),
        'price_asc' => __('Lowest price'),
        'price_desc' => __('Highest price'),
        'name' => __('Name (A–Z)'),
    ];
    $tagNames = $filterOptions['tags']->mapWithKeys(fn ($t) => [$t->id => $t->localName()]);
    $chips = [
        'q' => $filters['q'] ? '“'.$filters['q'].'”' : null,
        'tag' => $filters['tag'] ? ($tagNames[$filters['tag']] ?? null) : null,
        'village' => $filters['village'] ? ($filterOptions['villages'][$filters['village']] ?? null) : null,
        'category' => $filters['category'] ? __($filterOptions['categories'][$filters['category']] ?? '') : null,
        'price' => $filters['price'] ? $priceLabels[$filters['price']] : null,
        'promo' => $filters['promo'] ? __('On promo') : null,
    ];
    $activeFilterCount = count(array_filter($chips));
@endphp

<section class="pb-16 sm:pb-20 lg:pb-24">
    <div class="container-gd">
        {{-- ============ FILTER ============ --}}
        <form method="GET" action="{{ url('tour-packages') }}" data-listing-filter
            class="relative z-10 -mt-10 rounded-3xl border border-ink-100 bg-white p-4 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)] sm:-mt-14 sm:p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <label class="flex-1">
                    <span class="label-gd">{{ __('Search') }}</span>
                    <span class="relative block">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="search" name="q" value="{{ $filters['q'] }}" maxlength="100" class="input-gd !pl-11" placeholder="{{ __('Package name or village...') }}">
                    </span>
                </label>
                <div class="grid grid-cols-2 gap-3 lg:flex">
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
                        <span class="label-gd">{{ __('Duration / type') }}</span>
                        <select name="category" class="input-gd" data-autosubmit>
                            <option value="">{{ __('All types') }}</option>
                            @foreach ($filterOptions['categories'] as $id => $categoryName)
                                <option value="{{ $id }}" @selected($filters['category'] == $id)>{{ __($categoryName) }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary !py-3 lg:!px-7">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    {{ __('Search') }}
                </button>
            </div>

            @if ($filterOptions['tags']->isNotEmpty())
                <div class="mt-4 flex flex-wrap items-center gap-2" role="radiogroup" aria-label="{{ __('Theme') }}">
                    <span class="mr-1 text-xs font-bold uppercase tracking-[0.15em] text-ink-400">{{ __('Theme') }}</span>
                    <label class="cursor-pointer">
                        <input type="radio" name="tag" value="" class="peer sr-only" data-autosubmit @checked(! $filters['tag'])>
                        <span class="inline-flex items-center rounded-full border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-brand-300 peer-checked:border-ink-950 peer-checked:bg-ink-950 peer-checked:text-white">{{ __('All themes') }}</span>
                    </label>
                    @foreach ($filterOptions['tags'] as $tag)
                        <label class="cursor-pointer">
                            <input type="radio" name="tag" value="{{ $tag->id }}" class="peer sr-only" data-autosubmit @checked($filters['tag'] == $tag->id)>
                            <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 py-1 pl-1 pr-4 text-sm font-semibold text-ink-600 transition hover:border-brand-300 peer-checked:border-ink-950 peer-checked:bg-ink-950 peer-checked:text-white">
                                <img src="{{ asset('storage/tag/'.$tag->image) }}" alt="" class="h-8 w-8 rounded-full object-cover" loading="lazy" onerror="this.style.visibility='hidden'">
                                {{ $tag->localName() }}
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="mt-4 flex flex-col gap-3 border-t border-ink-100 pt-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="{{ __('Price per person') }}">
                    @foreach (['' => __('Any price')] + $priceLabels as $key => $label)
                        @include('customer.partials.listing.pill', ['name' => 'price', 'value' => $key, 'label' => $label, 'checked' => ($filters['price'] ?? '') === $key])
                    @endforeach
                    @include('customer.partials.listing.pill', ['type' => 'checkbox', 'name' => 'promo', 'value' => 1, 'label' => __('On promo'), 'checked' => $filters['promo'], 'tone' => 'forest',
                        'icon' => '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>'])
                </div>
                <label class="flex shrink-0 items-center gap-2 text-sm font-semibold text-ink-600">
                    <span class="whitespace-nowrap">{{ __('Sort by') }}</span>
                    <select name="sort" class="input-gd !w-auto !py-2" data-autosubmit data-default="newest">
                        @foreach ($sortLabels as $key => $label)
                            <option value="{{ $key }}" @selected($filters['sort'] === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </form>

        @include('customer.partials.listing.results', ['total' => $packages->total(), 'noun' => __('tour packages found'), 'chips' => $chips, 'base' => 'tour-packages'])

        <div class="mt-6 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($packages as $pack)
                @php
                    $tr = $pack->translate?->firstWhere('lang', App::getLocale());
                    $name = $tr?->name ?: $pack->name;
                    $desc = $tr?->desc ?: $pack->desc;
                    $duration = trim(str_replace("\u{00A0}", ' ', html_entity_decode(strip_tags((string) ($tr?->duration ?: $pack->duration)))));
                    $hasDiscount = $pack->disc > 0 && $pack->disc < $pack->price;
                    $finalPrice = $hasDiscount ? $pack->disc : $pack->price;
                    $tagName = $pack->tag_id ? ($tagNames[$pack->tag_id] ?? null) : null;
                @endphp
                <a href="{{ url('tour-packages/' . $pack->slug) }}" data-vue="Reveal"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-ink-100">
                        <img src="{{ $pack->default_img ? asset('storage/packages/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $name }} — {{ __('village tour package in Indonesia') }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}';">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-transparent"></div>
                        <div class="absolute inset-x-4 top-4 flex items-start justify-between gap-2">
                            <span class="flex flex-wrap gap-1.5">
                                @if ($pack->cat_name)
                                    <span class="badge bg-white/95 text-ink-800 shadow-sm backdrop-blur">{{ __($pack->cat_name) }}</span>
                                @endif
                                @if ($tagName)
                                    <span class="badge bg-ink-950/70 text-white backdrop-blur">{{ $tagName }}</span>
                                @endif
                            </span>
                            @if ($hasDiscount)
                                <span class="badge shrink-0 bg-brand-600 text-white shadow-sm">-{{ (int) floor((1 - $pack->disc / $pack->price) * 100) }}%</span>
                            @endif
                        </div>
                        @if ($pack->vil_name)
                            <span class="absolute bottom-4 left-4 inline-flex items-center gap-1.5 text-sm font-semibold text-white drop-shadow">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ \Illuminate\Support\Str::squish($pack->vil_name) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex-1">
                            <h2 class="font-display text-xl font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $name }}</h2>
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-ink-500">{{ \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags($desc ?? ''))), 160) }}</p>
                            @if ($duration !== '' && mb_strlen($duration) <= 40)
                                <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-ink-500">
                                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $duration }}
                                </span>
                            @endif
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-3 border-t border-ink-100 pt-4">
                            <div>
                                @if ($finalPrice <= 0)
                                    <span class="text-xl font-bold text-forest-700">{{ __('Free') }}</span>
                                @else
                                    @if ($hasDiscount)
                                        <span class="block text-xs text-ink-400 line-through">Rp {{ number_format($pack->price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="text-xs font-semibold text-ink-400">{{ __('From') }}</span>
                                    <span class="text-xl font-bold text-ink-950">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                    <span class="text-sm text-ink-400">/ {{ __('person') }}</span>
                                @endif
                            </div>
                            <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                @include('customer.partials.listing.empty', ['filtered' => $activeFilterCount > 0, 'base' => 'tour-packages',
                    'title' => $activeFilterCount ? __('No tour packages match your filters') : __('No tour packages available yet.'),
                    'hint' => $activeFilterCount ? __('Try another village, theme or price range.') : null])
            @endforelse
        </div>

        @include('customer.partials.listing.pagination', ['paginator' => $packages])
    </div>
</section>

@include('customer.partials.listing.script')
@endsection
