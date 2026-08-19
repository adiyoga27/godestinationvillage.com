@extends('customer/layout')

@section('content')
@php
    $locale = App::getLocale();
    $tr = ($locale === 'id' && isset($packages->translate[0])) ? $packages->translate[0] : null;
    $desc = $tr?->desc ?: $packages->desc;
    $itenaries = $tr?->itenaries ?: $packages->itenaries;
    $inclusion = $tr?->inclusion ?: $packages->inclusion;
    $term = $tr?->term ?: $packages->term;
    $hero = $packages->default_img ? asset('storage/packages/' . $packages->default_img) : asset('assets/customer/frontdata/images/destination-1.jpg');
    $gallery = collect($images)->map(fn ($img) => asset('storage/' . $img))->push($hero)->unique()->values()->all();
    $isEvent = $packages->category_id == 5;
    $effectivePrice = $packages->disc > 0 ? $packages->disc : $packages->price;
@endphp

<x-partials.page-hero
    :title="$packages->name"
    :image="$hero"
    :crumbs="['Home' => '/', 'Tour Packages' => 'tour-packages', $packages->name => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_360px]">
            {{-- Main --}}
            <div class="space-y-10">
                <div data-vue="Reveal">
                    <span data-vue="GallerySlider" data-props='{{ json_encode(["images" => $gallery, "alt" => $packages->name]) }}' class="block" style="display:block"></span>
                    @if (count($gallery) < 2)
                        <div class="overflow-hidden rounded-3xl border border-ink-100 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                            <img src="{{ $hero }}" alt="{{ $packages->name }}" class="aspect-[16/9] w-full object-cover">
                        </div>
                    @endif
                </div>

                <div data-vue="Reveal" data-props='{"delay":100}'>
                    <div class="flex flex-wrap items-center gap-3">
                        @if (($packages->category->name ?? null))
                            <span class="badge bg-cream-50 text-ink-700">{{ $packages->category->name }}</span>
                        @endif
                        @if ($packages->duration)
                            <span class="badge bg-cream-50 text-ink-700">
                                <svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ strip_tags($packages->duration) }}
                            </span>
                        @endif
                        @if ($packages->village?->village_address ?? null)
                            <span class="badge bg-cream-50 text-ink-700">
                                <svg class="h-3.5 w-3.5 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $packages->village->village_address }}
                            </span>
                        @endif
                    </div>

                    <h1 class="mt-4 font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ $packages->name }}</h1>

                    <div class="prose-gd mt-6">{!! $desc !!}</div>

                    @if ($packages->itenaries || $itenaries)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">Itinerary</h2>
                            <div class="prose-gd mt-3">{!! $itenaries !!}</div>
                        </div>
                    @endif

                    @if ($packages->inclusion || $inclusion)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">Included</h2>
                            <div class="prose-gd mt-3">{!! $inclusion !!}</div>
                        </div>
                    @endif

                    @if ($packages->term || $term)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">Terms & Conditions</h2>
                            <div class="prose-gd mt-3">{!! $term !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <div class="border-b border-ink-50 p-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-ink-400">Price per person</p>
                        <p class="mt-1 text-sm">
                            @if ($packages->disc > 0)
                                <span class="mr-2 text-lg font-bold text-brand-600">Rp {{ number_format($packages->disc, 0, ',', '.') }}</span>
                                <s class="text-ink-400">Rp {{ number_format($packages->price, 0, ',', '.') }}</s>
                            @else
                                <span class="text-lg font-bold text-brand-600">Rp {{ number_format($packages->price, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="space-y-3 p-6">
                        <a href="{{ $isEvent ? url('bookingEvents/' . $packages->id) : url('booking/' . $packages->id) }}" class="btn btn-primary w-full !py-4 !text-base">
                            Book Now
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ url('tour-packages') }}" class="btn btn-secondary w-full">View All Packages</a>
                        <p class="text-center text-xs text-ink-400">Secure checkout via Midtrans · Instant confirmation</p>
                    </div>
                </div>

                <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">Recent Tour Packages</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($recent->take(4) as $rec)
                            <li>
                                <a href="{{ url('tour-packages/' . $rec->slug) }}" class="group flex items-center gap-4">
                                    <img src="{{ asset('storage/packages/' . $rec->default_img) }}"
                                        alt="{{ $rec->name }}" class="h-16 w-20 flex-shrink-0 rounded-xl object-cover" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/customer/frontdata/images/destination-1.jpg') }}';">
                                    <span class="min-w-0">
                                        <span class="block line-clamp-2 text-sm font-semibold text-ink-800 transition group-hover:text-brand-600">{{ $rec->name }}</span>
                                        <span class="mt-0.5 block text-xs font-semibold uppercase tracking-wide text-ink-400">{{ $rec->cat_name }}</span>
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