@props([
    'pagesPath' => '',
    'pageCount' => 0,
    'pdfUrl' => '',
    'title' => 'GODEVI Booklet',
    'subtitle' => '',
])

@php $pdfUrl = asset($pdfUrl); @endphp

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
    <div class="space-y-4 bg-ink-950/5 p-4 sm:space-y-5 sm:p-6">
        @for ($i = 1; $i <= $pageCount; $i++)
            <img src="{{ asset(trim($pagesPath, '/') . '/p-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg') }}"
                alt="{{ $title }} — {{ __('page') }} {{ $i }}"
                loading="lazy"
                class="w-full rounded-xl bg-white shadow-[0_10px_30px_-12px_rgb(26_26_38/0.35)]">
        @endfor
    </div>
</div>