{{-- State kosong daftar. Param: title, hint (opsional), filtered (bool: tampilkan tombol reset), base (path halaman). --}}
<div class="col-span-full rounded-3xl border border-dashed border-ink-200 bg-cream-50 px-6 py-16 text-center">
    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-brand-600 shadow-sm">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
    </span>
    <h2 class="mt-4 font-display text-xl font-semibold text-ink-950">{{ $title }}</h2>
    @if (! empty($hint))<p class="mx-auto mt-2 max-w-md text-sm text-ink-500">{{ $hint }}</p>@endif
    @if (! empty($filtered))
        <a href="{{ url($base) }}" class="btn btn-secondary mt-6">{{ __('Reset all') }}</a>
    @endif
</div>
