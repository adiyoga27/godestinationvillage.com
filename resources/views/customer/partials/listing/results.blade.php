{{--
  Bar hasil daftar: jumlah + chip filter aktif (klik = hapus filter itu) + reset.
  Param: total (int), noun (teks setelah angka), chips ([query key => label]), base (path halaman, mis. 'homestay').
--}}
@php
    $chips = array_filter($chips ?? []);
    // URL halaman ini tanpa filter tertentu (page ikut di-reset).
    $without = function (string $key) use ($base) {
        $query = array_filter(
            \Illuminate\Support\Arr::except(request()->query(), [$key, 'page']),
            fn ($v) => $v !== null && $v !== ''
        );

        return url($base).($query ? '?'.http_build_query($query) : '');
    };
@endphp
<div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-ink-500">
        <strong class="text-base text-ink-950">{{ $total }}</strong> {{ $noun }}
    </p>
    @if ($chips)
        <div class="flex flex-wrap items-center gap-2">
            @foreach ($chips as $key => $label)
                <a href="{{ $without($key) }}" class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-700 transition hover:bg-brand-100" aria-label="{{ __('Remove filter') }}: {{ $label }}">
                    {{ $label }}
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endforeach
            <a href="{{ url($base) }}" class="text-xs font-bold text-ink-500 underline-offset-2 hover:text-brand-600 hover:underline">{{ __('Reset all') }}</a>
        </div>
    @endif
</div>
