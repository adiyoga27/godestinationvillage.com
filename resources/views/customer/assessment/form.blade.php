@extends('customer/layout')

@section('content')

<style>
    .opt input:checked + span {
        border-color: #d81c25 !important;
        background-color: #fef2f2 !important;
        box-shadow: 0 0 0 2px #d81c25 inset;
    }
    .opt input:checked + span .opt-num { color: #b3121a !important; }
    .opt input:focus-visible + span { outline: 2px solid #d81c25; outline-offset: 2px; }
</style>

{{-- Header ramping + progres lengket --}}
<section class="sticky top-0 z-30 border-b border-ink-100 bg-white/90 backdrop-blur">
    <div class="container-gd py-4">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="truncate text-xs font-bold uppercase tracking-[0.18em] text-brand-600">{{ $track->tagline }}</p>
                <h1 class="truncate font-display text-lg font-bold text-ink-950 sm:text-xl">{{ $track->name }}</h1>
            </div>
            <p class="shrink-0 text-sm font-bold text-ink-700"><span data-progress-label class="text-brand-600">0%</span> <span class="font-medium text-ink-400">terisi</span></p>
        </div>
        <div class="mt-3 h-2 overflow-hidden rounded-full bg-cream-100">
            <div data-progress-bar class="h-full w-0 rounded-full bg-gradient-to-r from-brand-600 to-amber-500 transition-all duration-300"></div>
        </div>
    </div>
</section>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Legenda skala --}}
        <div class="mb-8 rounded-3xl border border-ink-100 bg-white p-5 sm:p-6">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-ink-500">Skala penilaian</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($labels as $val => $label)
                    <span class="inline-flex items-center gap-2 rounded-full bg-cream-50 px-3.5 py-1.5 text-xs font-semibold text-ink-700">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-ink-950 text-[11px] font-bold text-white">{{ $val }}</span>
                        {{ $label }}
                    </span>
                @endforeach
            </div>
        </div>

        <form action="{{ route('assessment.submit', $track->slug) }}" method="post" class="space-y-6">
            @csrf
            @foreach ($grouped as $dimension => $questions)
                <section class="overflow-hidden rounded-3xl border border-ink-100 bg-white shadow-soft">
                    <header class="flex items-center gap-4 border-b border-ink-100 bg-cream-50/60 px-6 py-5 sm:px-8">
                        <span class="font-display text-3xl font-bold text-ink-200">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h2 class="font-display text-lg font-bold leading-snug text-ink-950 sm:text-xl">{{ $dimension }}</h2>
                            <p class="text-xs text-ink-500">{{ $questions->count() }} pernyataan</p>
                        </div>
                    </header>
                    <div class="space-y-2 p-4 sm:p-6">
                        @foreach ($questions as $q)
                            <fieldset data-question class="rounded-2xl p-4 transition hover:bg-cream-50 sm:p-5">
                                <p class="font-semibold leading-relaxed text-ink-900">{{ $q->question }}</p>
                                @if ($q->help_text)
                                    <p class="mt-1 text-sm text-ink-500">{{ $q->help_text }}</p>
                                @endif
                                <div class="opt-group mt-4 grid grid-cols-5 gap-2" role="radiogroup" aria-label="{{ $q->question }}">
                                    @foreach ($labels as $val => $label)
                                        <label class="opt cursor-pointer">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $val }}" class="sr-only" {{ (string) old('answers.'.$q->id) === (string) $val ? 'checked' : '' }} required>
                                            <span class="flex flex-col items-center gap-1 rounded-2xl border-2 border-ink-100 bg-white px-1 py-3 text-center transition hover:border-brand-300">
                                                <span class="opt-num font-display text-xl font-bold text-ink-900">{{ $val }}</span>
                                                <span class="hidden text-[11px] font-medium leading-tight text-ink-500 lg:block">{{ $label }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            @if (! $loop->last)<hr class="border-ink-50">@endif
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div class="flex flex-col items-center justify-between gap-4 rounded-3xl bg-ink-950 p-7 sm:flex-row sm:p-8">
                <div>
                    <p class="font-display text-lg font-bold text-white">Sudah yakin semua terisi?</p>
                    <p class="text-sm text-white/60">Skor dan draf strategi langsung tampil setelah dikirim.</p>
                </div>
                <button type="submit" class="btn btn-white w-full shrink-0 !px-10 !py-4 sm:w-auto">Lihat hasil saya</button>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<script>
    (function () {
        var total = document.querySelectorAll('[data-question]').length;
        var bar = document.querySelector('[data-progress-bar]');
        var label = document.querySelector('[data-progress-label]');
        function update() {
            var answered = 0;
            document.querySelectorAll('[data-question]').forEach(function (fs) {
                if (fs.querySelector('input:checked')) answered++;
            });
            var pct = total ? Math.round(answered / total * 100) : 0;
            if (bar) bar.style.width = pct + '%';
            if (label) label.textContent = pct + '%';
        }
        document.querySelectorAll('[data-question] input').forEach(function (el) {
            el.addEventListener('change', update);
        });
        update();
    })();
</script>
@endsection
