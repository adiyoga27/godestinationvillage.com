{{-- Pagination ringkas (1 … 4 5 6 … 12), filter ikut terbawa. Param: paginator. --}}
@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $pages = collect([1, $current - 1, $current, $current + 1, $last])->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
        $pill = 'inline-flex h-11 min-w-11 items-center justify-center rounded-full px-3 text-sm font-bold transition';
    @endphp
    <nav class="mt-14 flex items-center justify-center gap-2" aria-label="{{ __('Pagination') }}">
        @if ($paginator->onFirstPage())
            <span class="{{ $pill }} border border-ink-100 text-ink-300" aria-hidden="true">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $pill }} border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600" aria-label="{{ __('Previous') }}">‹</a>
        @endif
        @foreach ($pages as $i => $page)
            @if ($i > 0 && $page - $pages[$i - 1] > 1)
                <span class="px-1 text-ink-300">…</span>
            @endif
            @if ($page == $current)
                <span class="{{ $pill }} bg-brand-600 text-white" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $paginator->url($page) }}" class="{{ $pill }} border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600">{{ $page }}</a>
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $pill }} border border-ink-200 text-ink-600 hover:border-brand-600 hover:text-brand-600" aria-label="{{ __('Next') }}">›</a>
        @else
            <span class="{{ $pill }} border border-ink-100 text-ink-300" aria-hidden="true">›</span>
        @endif
    </nav>
@endif
