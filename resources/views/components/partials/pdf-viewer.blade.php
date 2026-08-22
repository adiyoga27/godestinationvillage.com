@props([
    'src' => '',
    'title' => 'GODEVI Booklet',
    'subtitle' => 'Company profile — 21 pages',
])

@php
    $pdfUrl = asset($src);
@endphp

<div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-[0_25px_60px_-20px_rgb(26_26_38/0.25)]">
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-ink-100 bg-ink-950 px-6 py-4">
        <div class="flex min-w-0 items-center gap-3">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            </span>
            <span class="min-w-0">
                <span class="block truncate font-display text-lg font-semibold text-white">{{ $title }}</span>
                <span class="block text-xs text-white/60">{{ $subtitle }}</span>
            </span>
        </div>
        <div class="flex shrink-0 items-center gap-2.5">
            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener"
                class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-sm font-semibold text-white transition hover:border-white/50 hover:bg-white/10">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                {{ __('Open Fullscreen') }}
            </a>
            <a href="{{ $pdfUrl }}" download
                class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-4 py-2 text-sm font-bold text-white transition hover:bg-brand-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                {{ __('Download PDF') }}
            </a>
        </div>
    </div>
    <div class="relative bg-ink-50">
        <div class="absolute inset-0 z-10 flex items-center justify-center gap-2 bg-ink-50 text-sm text-ink-400" data-pdf-loading>
            <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
            {{ __('Loading document…') }}
        </div>
        <iframe src="{{ $pdfUrl }}" title="{{ $title }}" loading="lazy"
            onload="this.parentElement.querySelector('[data-pdf-loading]')?.classList.add('hidden')"
            class="relative h-[70vh] w-full bg-white lg:h-[78vh]"></iframe>
    </div>
</div>