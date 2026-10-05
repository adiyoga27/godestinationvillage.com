@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="terms"
    :title="$page?->localTitle() ?? __('Terms & Conditions')"
    subtitle="{{ __('Please read these terms of use carefully before using GODEVI services.') }}"
    image="assets/customer/img/page-title-area/terms.jpg"
    :crumbs="[__('Home') => '/', __('Terms & Conditions') => '']"
/>

<section class="section-pad">
    <div class="container-gd max-w-4xl">
        <div class="card p-8 sm:p-12">
            <div class="prose-gd text-sm leading-relaxed">
                {!! $page?->localContent() !!}
                @if ($page?->last_updated)
                    <p class="!mt-8 text-xs font-semibold uppercase tracking-wider text-ink-400">{{ __('Last updated') }}: {{ $page->last_updated->translatedFormat('j F Y') }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
