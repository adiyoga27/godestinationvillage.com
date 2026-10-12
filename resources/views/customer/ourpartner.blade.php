@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="ourpartner"
    title="{{ __('Our Partners') }}"
    subtitle="{{ __('The partners and collaborators supporting GODEVI in building sustainable village tourism communities across Indonesia.') }}"
    image="assets/customer/img/page-title-area/partner.jpg"
    :crumbs="[__('Home') => '/', __('Our Partners') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($partners as $p)
                @php $tag = $p->url ? 'a' : 'div'; @endphp
                <{{ $tag }} @if ($p->url) href="{{ $p->url }}" target="_blank" rel="noopener" @endif data-vue="Reveal"
                    class="{{ $p->url ? 'group card card-hover' : 'card' }} flex flex-col items-center justify-center p-10 text-center">
                    <div class="flex h-32 items-center justify-center">
                        @if ($p->logo_url)
                            <img src="{{ $p->logo_url }}" alt="{{ $p->localizedName() }}"
                                class="max-h-28 w-auto object-contain transition duration-500 group-hover:scale-105" loading="lazy">
                        @endif
                    </div>
                    <h3 class="mt-6 font-display text-lg font-bold text-ink-950 transition {{ $p->url ? 'group-hover:text-brand-600' : '' }}">{{ $p->localizedName() }}</h3>
                    @if ($p->localizedDesc())
                        <p class="mt-2 text-sm text-ink-500">{{ $p->localizedDesc() }}</p>
                    @endif
                </{{ $tag }}>
            @endforeach
        </div>
    </div>
</section>
@endsection