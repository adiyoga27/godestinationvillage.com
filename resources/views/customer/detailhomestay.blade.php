@extends('customer/layout')

@section('content')
@php
    $locale = App::getLocale();
    $tr = ($locale === 'id' && isset($packages->translate[0])) ? $packages->translate[0] : null;
    $desc = $tr?->description ?: $packages->description;
    $facilities = $tr?->facilities ?: $packages->facilities;
    $additional = $tr?->additional_activities ?: $packages->additional_activities;
    $notes = $tr?->additional_notes ?: $packages->additional_notes;
    $hero = $packages->default_img ? asset('storage/homestay/' . $packages->default_img) : asset('assets/customer/frontdata/images/destination-1.jpg');
    $gallery = collect($images)->map(fn ($img) => asset('storage/' . $img))->push($hero)->unique()->values()->all();
    $price = $packages->disc > 0 ? $packages->disc : $packages->price;
@endphp

<x-partials.page-hero
    :title="$packages->name"
    :image="$hero"
    :crumbs="[__('Home') => '/', __('Homestay') => 'homestay', $packages->name => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_360px]">
            <div class="space-y-10">
                <div data-vue="Reveal">
                    <span data-vue="GallerySlider" data-props='{{ json_encode(["images" => $gallery, "alt" => $packages->name]) }}' class="block" style="display:block"></span>
                </div>

                <div data-vue="Reveal" data-props='{"delay":100}'>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge bg-forest-50 text-forest-700">{{ __('Homestay') }}</span>
                        @if ($packages->is_breakfast)
                            <span class="badge bg-cream-50 text-ink-700">{{ __('Breakfast included') }}</span>
                        @endif
                        @if ($packages->check_in_time)
                            <span class="badge bg-cream-50 text-ink-700">{{ __('Check-in') }} {{ $packages->check_in_time }}</span>
                        @endif
                        @if ($packages->check_out_time)
                            <span class="badge bg-cream-50 text-ink-700">{{ __('Check-out') }} {{ $packages->check_out_time }}</span>
                        @endif
                    </div>

                    <h1 class="mt-4 font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ $packages->name }}</h1>
                    @if ($packages->owner_name)
                        <p class="mt-2 text-sm font-semibold text-ink-500">{{ __('Hosted by') }} {{ $packages->owner_name }}</p>
                    @endif
                    <div class="prose-gd mt-6">{!! $desc !!}</div>

                    @if ($facilities)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Facilities') }}</h2>
                            <div class="prose-gd mt-3">{!! $facilities !!}</div>
                        </div>
                    @endif

                    @if ($additional)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Additional Activities') }}</h2>
                            <div class="prose-gd mt-3">{!! $additional !!}</div>
                        </div>
                    @endif

                    @if ($notes)
                        <div class="mt-10">
                            <h2 class="font-display text-2xl font-bold">{{ __('Note') }}</h2>
                            <div class="prose-gd mt-3">{!! $notes !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <div class="border-b border-ink-50 p-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Price per night') }}</p>
                        <p class="mt-1">
                            <span class="text-lg font-bold text-brand-600">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        </p>
                        @if ($packages->location)
                            <p class="mt-2 flex items-center gap-1.5 text-sm text-ink-500">
                                <svg class="h-4 w-4 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                {{ $packages->location }}
                            </p>
                        @endif
                    </div>
                    <div class="space-y-3 p-6">
                        <a href="{{ url('bookingHomeStay/' . $packages->id) }}" class="btn btn-primary w-full !py-4 !text-base">
                            {{ __('Book Stay') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                        </a>
                        <a href="{{ url('homestay') }}" class="btn btn-secondary w-full">{{ __('View All Homestays') }}</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection