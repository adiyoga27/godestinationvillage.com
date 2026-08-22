@extends('customer/layout')

@section('content')
@php
    $vd = $village->village_detail;
    $hero = $village->avatar ? asset('storage/users/' . $village->avatar) : asset('assets/customer/frontdata/images/about.jpg');
@endphp

<x-partials.page-hero
    :title="$vd->village_name ?? $village->name"
    :subtitle="$vd->village_address"
    :image="$hero"
    :crumbs="[__('Home') => '/', __('Explore Village') => 'village', ($vd->village_name ?? __('Village')) => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_340px]">
            {{-- Main --}}
            <div class="space-y-10">
                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <div class="relative aspect-[16/9] w-full sm:aspect-[16/7]">
                        <img src="{{ $hero }}" alt="{{ $vd->village_name ?? '' }} — {{ __('village destination in Bali') }}" class="h-full w-full object-cover" loading="eager">
                        <span class="badge absolute left-5 top-5 bg-white/95 text-forest-700">{{ __('Authentic Village') }}</span>
                    </div>
                </div>

                <article data-vue="Reveal" data-props='{"delay":100}'>
                    <h1 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ $vd->village_name ?? $village->name }}</h1>
                    <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-ink-500">
                        @if ($vd->village_address ?? null)
                            <span class="flex items-center gap-2"><svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>{{ $vd->village_address }}</span>
                        @endif
                        @if ($vd->contact_person ?? null)
                            <span class="flex items-center gap-2"><svg class="h-4 w-4 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>{{ $vd->contact_person }}</span>
                        @endif
                    </div>
                    <div class="prose-gd mt-6 text-ink-700">{!! $vd->desc ?? '' !!}</div>
                </article>

                @if ($vd->lat && $vd->lng)
                    <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100">
                        <div class="flex items-center justify-between border-b border-ink-100 bg-white px-6 py-4">
                            <h2 class="font-display text-xl font-semibold">{{ __('Location') }}</h2>
                            <a href="https://www.google.com/maps?q={{ $vd->lat }},{{ $vd->lng }}" target="_blank" rel="noopener" class="text-sm font-bold text-brand-600 hover:underline">{{ __('Open in Google Maps') }}</a>
                        </div>
                        <iframe
                            title="Map of {{ $vd->village_name ?? __('the village') }}"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $vd->lng - 0.02 }}%2C{{ $vd->lat - 0.015 }}%2C{{ $vd->lng + 0.02 }}%2C{{ $vd->lat + 0.015 }}&amp;layer=mapnik&amp;marker={{ $vd->lat }}%2C{{ $vd->lng }}"
                            class="h-[380px] w-full border-0" loading="lazy"></iframe>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">{{ __('Tour Packages') }}</h2>
                    <ul class="mt-4 space-y-4">
                        @forelse ($recent as $rec)
                            @php $recTr = $rec->translate?->firstWhere('lang', App::getLocale()); @endphp
                            <li>
                                <a href="{{ url('tour-packages/' . $rec->slug) }}" class="group flex items-center gap-4">
                                    <img src="{{ asset('storage/packages/' . $rec->default_img) }}"
                                        alt="{{ $recTr?->name ?: $rec->name }}" class="h-16 w-20 flex-shrink-0 rounded-xl object-cover"
                                        loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-1.jpg') }}';">
                                    <span class="min-w-0">
                                        <span class="block line-clamp-2 text-sm font-semibold text-ink-800 transition group-hover:text-brand-600">{{ $recTr?->name ?: $rec->name }}</span>
                                        <span class="mt-0.5 block text-xs font-semibold uppercase tracking-wide text-ink-400">{{ $rec->cat_name }}</span>
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="text-sm text-ink-400">{{ __('Packages coming soon.') }}</li>
                        @endforelse
                    </ul>
                </div>

                <a href="https://wa.me/6281997674778?text=Hi%20GODEVI%2C%20I%20want%20to%20book%20a%20tour%20in%20{{ urlencode($vd->village_name ?? '') }}"
                    target="_blank" rel="noopener" data-vue="Reveal"
                    class="flex items-center justify-center gap-3 rounded-2xl bg-[#25D366] px-6 py-4 font-bold text-white shadow-lg transition hover:-translate-y-1">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.13.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.21-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35zm-5.42 7.4h-.004a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.9-9.88a9.83 9.83 0 019.88 9.89c0 5.45-4.43 9.88-9.88 9.88zM12.05 21.5c2.6 0 5.03-1.01 6.86-2.84a9.66 9.66 0 002.84-6.87 9.66 9.66 0 00-2.84-6.87c-1.83-1.82-4.26-2.83-6.86-2.83a9.68 9.68 0 00-9.69 9.69c0 1.7.45 3.37 1.3 4.83l-1.4 5.12 5.24-1.37a9.67 9.67 0 004.55 1.14z"/></svg>
                    {{ __('Chat with local host') }}
                </a>
            </aside>
        </div>
    </div>
</section>

{{-- Packages in this village --}}
@if (count($packages))
    <section class="section-pad bg-cream-50">
        <div class="container-gd">
            <div class="mb-10" data-vue="Reveal">
                <p class="eyebrow">{{ __('Experiences') }}</p>
                <h2 class="font-display text-3xl font-bold sm:text-4xl">{{ __('Tours & activities in') }} {{ $vd->village_name ?? __('this village') }}</h2>
            </div>
            <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($packages as $pack)
                    @php $trPack = $pack->translate?->firstWhere('lang', App::getLocale()); @endphp
                    <a href="{{ url('tour-packages/' . $pack->slug) }}" data-vue="Reveal" class="group card card-hover flex flex-col overflow-hidden">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $pack->default_img ? asset('storage/packages/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                                alt="{{ $trPack?->name ?: $pack->name }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $trPack?->name ?: $pack->name }}</h3>
                            <div class="mt-4 flex items-center justify-between border-t border-ink-50 pt-4">
                                <span class="text-lg font-bold text-brand-600">
                                    @if ($pack->disc > 0)
                                        Rp {{ number_format($pack->disc, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($pack->price, 0, ',', '.') }}
                                    @endif
                                </span>
                                <span class="text-sm font-bold text-ink-500 transition group-hover:text-brand-600">{{ __('Details') }} →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection