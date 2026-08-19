@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Village Events & Festivals"
    subtitle="Join authentic village ceremonies, workshops and community events across Bali."
    image="assets/customer/img/page-title-area/header-event.png"
    :crumbs="['Home' => '/', 'Events' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($packages as $pack)
                <a href="{{ url('events/' . $pack->slug) }}" data-vue="Reveal"
                    class="group card card-hover flex flex-col overflow-hidden">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ $pack->default_img ? asset('storage/events/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                            alt="{{ $pack->name }} — acara desa di Bali" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        @if ($pack->date_event)
                            <div class="absolute left-4 top-4 flex flex-col items-center rounded-2xl bg-white/95 px-3.5 py-2 text-center shadow-lg backdrop-blur">
                                <span class="text-lg font-extrabold leading-none text-brand-600">{{ \Carbon\Carbon::parse($pack->date_event)->format('d') }}</span>
                                <span class="text-[10px] font-bold uppercase leading-tight text-ink-500">{{ \Carbon\Carbon::parse($pack->date_event)->format('M') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-ink-400">
                            @if ($pack->location ?? null)
                                <span class="flex items-center gap-1"><svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>{{ $pack->location }}</span>
                            @endif
                        </div>
                        <h2 class="mt-2 font-display text-xl font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $pack->name }}</h2>
                        <p class="mt-2 line-clamp-2 flex-1 text-sm text-ink-500">{{ strip_tags($pack->description ?? '') }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-ink-50 pt-4">
                            <span class="text-lg font-bold text-brand-600">
                                @if ($pack->is_free)
                                    Gratis
                                @elseif ($pack->price > 0)
                                    Rp {{ number_format($pack->price, 0, ',', '.') }}
                                @else
                                    Free
                                @endif
                            </span>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-cream-50 px-4 py-2 text-sm font-bold text-ink-700 transition group-hover:bg-brand-600 group-hover:text-white">
                                View Event
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-ink-400">No events available yet.</p>
                </div>
            @endforelse
        </div>

        @if ($packages->hasPages())
            <div class="mt-14 flex justify-center">
                <div class="flex items-center gap-2">
                    @if ($packages->onFirstPage())
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-300">‹</span>
                    @else
                        <a href="{{ $packages->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:text-brand-600">‹</a>
                    @endif
                    @for ($i = 1; $i <= $packages->lastPage(); $i++)
                        <a href="{{ $packages->url($i) }}"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition {{ $packages->currentPage() == $i ? 'bg-brand-600 text-white' : 'border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600' }}">
                            {{ $i }}
                        </a>
                    @endfor
                    @if ($packages->hasMorePages())
                        <a href="{{ $packages->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-600 transition hover:border-brand-600 hover:text-brand-600">›</a>
                    @else
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 text-ink-300">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection