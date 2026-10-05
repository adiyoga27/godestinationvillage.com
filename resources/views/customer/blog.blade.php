@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="news"
    :title="__('News & Insights')"
    subtitle="{{ __('Stories, updates and insights about sustainable village tourism and community empowerment in Indonesia.') }}"
    image="assets/customer/img/page-title-area/blog-style3.jpg"
    :crumbs="[__('Home') => '/', __('News') => '']"
/>

@php
    $chips = [
        'q' => $filters['q'] ? '“'.$filters['q'].'”' : null,
        'year' => $filters['year'],
        'sort' => $filters['sort'] === 'oldest' ? __('Oldest first') : null,
    ];
    $activeFilterCount = count(array_filter($chips));
    // Artikel terbaru tampil besar hanya di halaman pertama tanpa filter.
    $featured = ($blog->onFirstPage() && ! $activeFilterCount) ? $blog->first() : null;
    $fallbackImg = asset('assets/customer/img/etc/slider/blog%201%205x2.png');
    $meta = function ($post) {
        $words = str_word_count(strip_tags($post->post_content ?? ''));

        return [
            'date' => $post->created_at ? \Carbon\Carbon::parse($post->created_at)->translatedFormat('j M Y') : null,
            'minutes' => max(1, (int) ceil($words / 200)),
            'excerpt' => \Illuminate\Support\Str::words(trim(html_entity_decode(strip_tags($post->post_content ?? ''))), 30, '…'),
            // Akun penulis berisi nama internal (Admin, intern) → tampil sebagai tim.
            'author' => __('GODEVI Team'),
        ];
    };
@endphp

<section class="pb-16 sm:pb-20 lg:pb-24">
    <div class="container-gd">
        {{-- ============ FILTER ============ --}}
        <form method="GET" action="{{ url('news') }}" data-listing-filter
            class="relative z-10 -mt-10 rounded-3xl border border-ink-100 bg-white p-4 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)] sm:-mt-14 sm:p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                <label class="flex-1">
                    <span class="label-gd">{{ __('Search') }}</span>
                    <span class="relative block">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="search" name="q" value="{{ $filters['q'] }}" maxlength="100" class="input-gd !pl-11" placeholder="{{ __('Search articles...') }}">
                    </span>
                </label>
                <div class="grid grid-cols-2 gap-3 lg:flex">
                    <label class="lg:w-40">
                        <span class="label-gd">{{ __('Year') }}</span>
                        <select name="year" class="input-gd" data-autosubmit>
                            <option value="">{{ __('All years') }}</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @selected($filters['year'] == $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="lg:w-44">
                        <span class="label-gd">{{ __('Sort by') }}</span>
                        <select name="sort" class="input-gd" data-autosubmit data-default="newest">
                            <option value="newest" @selected($filters['sort'] === 'newest')>{{ __('Newest first') }}</option>
                            <option value="oldest" @selected($filters['sort'] === 'oldest')>{{ __('Oldest first') }}</option>
                        </select>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary !py-3 lg:!px-7">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    {{ __('Search') }}
                </button>
            </div>
        </form>

        @include('customer.partials.listing.results', ['total' => $blog->total(), 'noun' => __('articles'), 'chips' => $chips, 'base' => 'news'])

        {{-- ============ ARTIKEL UTAMA ============ --}}
        @if ($featured)
            @php $m = $meta($featured); @endphp
            <a href="{{ url('news/' . $featured->slug) }}" data-vue="Reveal"
                class="group mt-6 grid overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)] lg:grid-cols-2">
                <div class="relative aspect-[16/10] overflow-hidden bg-ink-100 lg:aspect-auto lg:min-h-[22rem]">
                    <img src="{{ asset('storage/blogs/' . $featured->post_thumbnail) }}" alt="{{ $featured->post_title }}"
                        class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="eager"
                        onerror="this.onerror=null;this.src='{{ $fallbackImg }}';">
                    <span class="badge absolute left-5 top-5 bg-brand-600 text-white shadow-sm">{{ __('Latest') }}</span>
                </div>
                <div class="flex flex-col justify-center p-7 sm:p-10">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-semibold uppercase tracking-wide text-ink-400">
                        @if ($m['date'])<span>{{ $m['date'] }}</span><span aria-hidden="true">·</span>@endif
                        <span>{{ $m['minutes'] }} {{ __('min read') }}</span>
                    </div>
                    <h2 class="mt-3 font-display text-2xl font-bold leading-tight text-ink-950 transition group-hover:text-brand-600 sm:text-3xl">{{ $featured->post_title }}</h2>
                    <p class="mt-4 line-clamp-4 leading-relaxed text-ink-500">{{ $m['excerpt'] }}</p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-brand-600 transition-all group-hover:gap-3">
                        {{ __('Read article') }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </div>
            </a>
        @endif

        {{-- ============ DAFTAR ARTIKEL ============ --}}
        <div class="mt-7 grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($blog as $val)
                @continue($featured && $val->is($featured))
                @php $m = $meta($val); @endphp
                <a href="{{ url('news/' . $val->slug) }}" data-vue="Reveal"
                    class="group flex flex-col overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.15)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[16/10] overflow-hidden bg-ink-100">
                        <img src="{{ asset('storage/blogs/' . $val->post_thumbnail) }}" alt="{{ $val->post_title }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ $fallbackImg }}';">
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-semibold uppercase tracking-wide text-ink-400">
                            @if ($m['date'])<span>{{ $m['date'] }}</span><span aria-hidden="true">·</span>@endif
                            <span>{{ $m['minutes'] }} {{ __('min read') }}</span>
                        </div>
                        <h2 class="mt-2 font-display text-lg font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $val->post_title }}</h2>
                        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-ink-500">{{ $m['excerpt'] }}</p>
                        <div class="mt-5 flex items-center justify-between gap-3 border-t border-ink-100 pt-4">
                            <span class="flex min-w-0 items-center gap-2 text-sm font-semibold text-ink-600">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-xs font-bold text-brand-700">{{ mb_strtoupper(mb_substr($m['author'], 0, 1)) }}</span>
                                <span class="truncate">{{ $m['author'] }}</span>
                            </span>
                            <span class="shrink-0 text-sm font-bold text-brand-600">{{ __('Read More') }} →</span>
                        </div>
                    </div>
                </a>
            @empty
                @include('customer.partials.listing.empty', ['filtered' => $activeFilterCount > 0, 'base' => 'news',
                    'title' => $activeFilterCount ? __('No articles match your search') : __('No articles published yet.'),
                    'hint' => $activeFilterCount ? __('Try other keywords or another year.') : null])
            @endforelse
        </div>

        @include('customer.partials.listing.pagination', ['paginator' => $blog])
    </div>
</section>

@include('customer.partials.listing.script')
@endsection
