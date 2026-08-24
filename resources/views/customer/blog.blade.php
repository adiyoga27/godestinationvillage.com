@extends('customer/layout')

@section('content')

<x-partials.page-hero
    :title="__('News & Insights')"
    subtitle="{{ __('Stories, updates and insights about sustainable village tourism and community empowerment in Bali.') }}"
    image="assets/customer/img/page-title-area/blog-style3.jpg"
    :crumbs="[__('Home') => '/', __('News') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_320px]">
            <div class="grid gap-7 sm:grid-cols-2">
                @forelse ($blog as $val)
                    <article data-vue="Reveal" class="group card card-hover flex flex-col overflow-hidden">
                        <a href="{{ url('news/' . $val->slug) }}" class="relative block h-52 overflow-hidden" aria-label="{{ $val->post_title }}">
                            <img src="{{ asset('storage/blogs/' . $val->post_thumbnail) }}" alt="{{ $val->post_title }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('assets/customer/img/etc/slider/blog%201%205x2.png') }}';">
                        </a>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-ink-400">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    {{ \Carbon\Carbon::parse($val->created_at)->format('M d, Y') }}
                                </span>
                                @if ($val->post_tags)
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                                        {{ $val->post_tags }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ url('news/' . $val->slug) }}" class="mt-3 block font-display text-lg font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">
                                {{ $val->post_title }}
                            </a>
                            <p class="mt-2 flex-1 text-sm text-ink-500">{!! \Illuminate\Support\Str::words(strip_tags($val->post_content), 24, '...') !!}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-ink-50 pt-4">
                                <span class="flex items-center gap-2 text-sm font-semibold text-ink-500">
                                    <span class="flex h-8 w-8 overflow-hidden rounded-full bg-cream-100">
                                        <svg class="m-auto h-4 w-4 text-ink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2.5c-4.14 0-7.5 2.16-7.5 4.82V21h15v-1.68c0-2.66-3.36-4.82-7.5-4.82z"/></svg>
                                    </span>
                                    {{ __('GODEVI Team') }}
                                </span>
                                <a href="{{ url('news/' . $val->slug) }}" class="text-sm font-bold text-brand-600">{{ __('Read More') }} →</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-ink-400">{{ __('No articles published yet.') }}</p>
                    </div>
                @endforelse

                @if ($blog->hasPages())
                    @php
                        $current = $blog->currentPage();
                        $last = $blog->lastPage();
                        $start = max(1, $current - 2);
                        $end = min($last, $current + 2);
                    @endphp
                    <div class="col-span-full mt-8">
                        <nav class="flex flex-wrap items-center justify-center gap-2">
                            @if ($blog->onFirstPage())
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-300">‹</span>
                            @else
                                <a href="{{ $blog->previousPageUrl() }}" rel="prev" aria-label="Previous"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:bg-brand-50 hover:text-brand-600">‹</a>
                            @endif

                            @if ($start > 1)
                                <a href="{{ $blog->url(1) }}"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-sm font-bold transition {{ $current == 1 ? 'bg-brand-600 text-white shadow-[0_8px_20px_-8px_rgb(216_28_37/0.5)]' : 'border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600' }}">1</a>
                                @if ($start > 2)
                                    <span class="inline-flex h-11 w-11 items-center justify-center text-sm font-bold text-ink-300">…</span>
                                @endif
                            @endif

                            @for ($i = $start; $i <= $end; $i++)
                                <a href="{{ $blog->url($i) }}"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-sm font-bold transition {{ $current == $i ? 'bg-brand-600 text-white shadow-[0_8px_20px_-8px_rgb(216_28_37/0.5)]' : 'border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600' }}">
                                    {{ $i }}
                                </a>
                            @endfor

                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="inline-flex h-11 w-11 items-center justify-center text-sm font-bold text-ink-300">…</span>
                                @endif
                                <a href="{{ $blog->url($last) }}"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full text-sm font-bold transition {{ $current == $last ? 'bg-brand-600 text-white shadow-[0_8px_20px_-8px_rgb(216_28_37/0.5)]' : 'border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600' }}">{{ $last }}</a>
                            @endif

                            @if ($blog->hasMorePages())
                                <a href="{{ $blog->nextPageUrl() }}" rel="next" aria-label="Next"
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:bg-brand-50 hover:text-brand-600">›</a>
                            @else
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-ink-200 text-ink-300">›</span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>

            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">{{ __('Recent Articles') }}</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($recent as $rec)
                            <li>
                                <a href="{{ url('news/' . $rec->slug) }}" class="group flex items-center gap-4">
                                    <img src="{{ asset('storage/blogs/' . $rec->post_thumbnail) }}" alt="{{ $rec->post_title }}"
                                        class="h-16 w-20 flex-shrink-0 rounded-xl object-cover" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/customer/img/etc/slider/blog%201%205x2.png') }}';">
                                    <span class="min-w-0">
                                        <span class="block line-clamp-2 text-sm font-semibold leading-snug text-ink-800 transition group-hover:text-brand-600">{{ $rec->post_title }}</span>
                                        <span class="mt-0.5 block text-xs font-semibold uppercase tracking-wide text-ink-400">{{ \Carbon\Carbon::parse($rec->created_at)->format('M d, Y') }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection