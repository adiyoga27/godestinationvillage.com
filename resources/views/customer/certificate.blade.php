@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()->title('Certificate — GODEVI')->noindex()->toArray();
@endphp

<x-partials.page-hero
    title="{{ __('Certificate Details') }}"
    subtitle="{{ __('View and download your official GODEVI certificate.') }}"
    image="assets/customer/img/page-title-area/surat-sertif-header.png"
    :crumbs="[__('Home') => '/', __('Certificate') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd mx-auto max-w-2xl">
        <div class="card overflow-hidden">
            <div class="border-b border-ink-100 bg-ink-950 px-8 py-6">
                <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ __('Certificate') }}</p>
                <p class="mt-1 font-display text-xl font-bold text-white">{{ $certificate->category }}</p>
            </div>
            <dl class="divide-y divide-ink-100">
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('No Surat') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ $certificate->reference_number }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('Tanggal') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ date('d M Y', strtotime($certificate->date_at)) }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('Perihal') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ $certificate->regarding }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('Addressed To') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ $certificate->addressed_to }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('Signer') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ $certificate->signer }}</dd>
                </div>
                <div class="grid grid-cols-3 gap-4 px-8 py-5 text-sm">
                    <dt class="font-bold text-ink-800">{{ __('Position') }}</dt>
                    <dd class="col-span-2 text-ink-600">{{ $certificate->departemen }}</dd>
                </div>
            </dl>
            <div class="p-8">
                <a href="{{ url('storage/certification/' . $certificate->file) }}" class="btn btn-primary w-full !py-4">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    {{ __('Download') }}
                </a>
            </div>
        </div>
    </div>
</section>

@endsection