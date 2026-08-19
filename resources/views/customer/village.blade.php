@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Explore Villages in Bali"
    subtitle="Discover authentic Balinese villages, culture and community-driven tourism experiences curated by GODEVI."
    image="assets/customer/img/page-title-area/explorer.jpg"
    :crumbs="['Home' => '/', 'Explore Village' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($village as $val)
                @php
                    $vd = $val->village_detail;
                    $slug = $vd->slug ?? '';
                    $name = $vd->village_name ?? $val->name;
                    $address = $vd->village_address ?? '';
                @endphp
                <a href="{{ url('village/' . $slug) }}" data-vue="Reveal"
                    class="group card card-hover flex flex-col overflow-hidden">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $val->avatar ? asset('storage/users/' . $val->avatar) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $name }} — desa wisata di Bali" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-ink-700 backdrop-blur">
                            <svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            Bali · Village
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="font-display text-xl font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $name }}</h2>
                        @if ($address)
                            <p class="mt-1 flex items-center gap-1.5 text-sm text-ink-500">
                                <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $address }}
                            </p>
                        @endif
                        @if ($vd->desc ?? null)
                            <p class="mt-3 flex-1 line-clamp-2 text-sm text-ink-500">{{ $vd->desc }}</p>
                        @endif
                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-bold text-brand-600 transition-all group-hover:gap-3">
                            Explore Village
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-ink-400">No villages published yet. Check back soon.</p>
                </div>
            @endforelse
        </div>

        @if ($village->hasPages())
            <div class="mt-14 flex justify-center">
                <div class="flex items-center gap-2">
                    @if ($village->onFirstPage())
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-300">‹</span>
                    @else
                        <a href="{{ $village->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:text-brand-600">‹</a>
                    @endif
                    @for ($i = 1; $i <= $village->lastPage(); $i++)
                        <a href="{{ $village->url($i) }}"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition {{ $village->currentPage() == $i ? 'bg-brand-600 text-white' : 'border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600' }}">
                            {{ $i }}
                        </a>
                    @endfor
                    @if ($village->hasMorePages())
                        <a href="{{ $village->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:text-brand-600">›</a>
                    @else
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-300">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection