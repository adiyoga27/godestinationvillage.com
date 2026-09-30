@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="services"
    title="{{ __('Our Services') }}"
    subtitle="{{ __('From tourism planning to destination branding — we help villages thrive through responsible tourism.') }}"
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="[__('Home') => '/', __('Our Services') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <p class="eyebrow justify-center">{{ __('What We Do') }}</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ __('Services that empower villages') }}</h2>
            <p class="mt-4 text-ink-600">{{ __('GODEVI supports tourism villages with end-to-end solutions — from planning and development to branding and research.') }}</p>
        </div>

        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-4">
            @php $isId = app()->getLocale() === 'id'; @endphp
            @forelse (\App\Helpers\Homepage::services() as $service)
                @php
                    $sTitle = $isId ? ($service->title_id ?: $service->title) : $service->title;
                    $sDesc = $isId ? ($service->desc_id ?: $service->desc) : $service->desc;
                    $sUrl = \App\Helpers\Homepage::url($service->url);
                    $sBtns = collect($service->buttons ?? [])->map(fn ($b) => [
                        'label' => $isId ? (($b['label_id'] ?? '') ?: ($b['label'] ?? '')) : ($b['label'] ?? ''),
                        'url' => \App\Helpers\Homepage::url($b['url'] ?? null),
                    ])->filter(fn ($b) => $b['label'] !== '' && $b['url'] !== null)->values()->all();
                    $sImg = \App\Helpers\Homepage::serviceImage($service->image);
                @endphp
                @if ($sDesc)
                    <button type="button"
                        data-service-modal
                        data-title="{{ $sTitle }}"
                        data-desc="{{ $sDesc }}"
                        data-icon="{{ $sImg }}"
                        data-phone="{{ $service->phone ?: \App\Helpers\Homepage::SERVICE_DEFAULT_PHONE }}"
                        data-whatsapp="{{ $service->whatsapp ?: \App\Helpers\Homepage::SERVICE_DEFAULT_WHATSAPP }}"
                        data-file="{{ \App\Helpers\Homepage::serviceFile($service->file) }}"
                        data-file-label="{{ __('Download') }}"
                        data-buttons='@json($sBtns)'
                        @if ($sUrl) data-page="{{ $sUrl }}" data-page-label="{{ __('Learn more') }}" @endif
                        data-vue="Reveal"
                        class="group card card-hover flex flex-col items-center p-8 text-center">
                        <div class="flex h-40 w-40 items-center justify-center">
                            <img src="{{ $sImg }}" alt="{{ $sTitle }}" class="h-full w-full object-contain drop-shadow-lg transition duration-300 group-hover:scale-105" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $sTitle }}</h3>
                    </button>
                @elseif ($sUrl)
                    <a href="{{ $sUrl }}" data-vue="Reveal" class="group card card-hover flex flex-col items-center p-8 text-center">
                        <div class="flex h-40 w-40 items-center justify-center">
                            <img src="{{ $sImg }}" alt="{{ $sTitle }}" class="h-full w-full object-contain drop-shadow-lg transition duration-300 group-hover:scale-105" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $sTitle }}</h3>
                    </a>
                @else
                    <div data-vue="Reveal" class="group card flex flex-col items-center p-8 text-center">
                        <div class="flex h-40 w-40 items-center justify-center">
                            <img src="{{ $sImg }}" alt="{{ $sTitle }}" class="h-full w-full object-contain drop-shadow-lg transition duration-300 group-hover:scale-105" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $sTitle }}</h3>
                    </div>
                @endif
            @empty
                <p class="col-span-full text-center text-ink-400">{{ __('No services available yet.') }}</p>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-ink-950 py-16">
    <div class="container-gd flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
        <div>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">{{ __('Ready to build something together?') }}</h2>
            <p class="mt-2 text-white/60">{{ __('Partner with GODEVI to grow your tourism village sustainably.') }}</p>
        </div>
        <a href="{{ url('contact') }}" class="btn btn-white shrink-0">{{ __('Get in Touch') }}</a>
    </div>
</section>

@include('customer.partials.service-modal')
@endsection