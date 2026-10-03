@extends('customer/layout')

@section('content')

@php
    $icons = \App\Services\AssessmentService::DIMENSION_ICONS;
    $totalWeight = max(1, $weights->sum());
    $showWeight = ($formConfig['show_weight'] ?? false) || $weights->unique()->count() > 1;
    // Judul kelompok (mis. subindeks TTDI) ditampilkan sebelum dimensi pertama tiap kelompok.
    $sectionStarts = collect($formConfig['sections'] ?? [])->mapWithKeys(fn ($dims, $title) => [$dims[0] => $title]);
    $totalQuestions = $track->activeQuestions->count();
    $unitLabel = $grouped->count() === $totalQuestions ? 'dimensi' : 'pernyataan';
@endphp

@include('customer.assessment._hero', [
    'title' => $formConfig['title'] ?? 'Isi Asesmen '.$track->name,
    'subtitle' => $formConfig['intro'] ?? 'Beri nilai 1–5 yang paling jujur menggambarkan kondisi saat ini.',
    'eyebrow' => 'Langkah 2 dari 2 · '.$track->name,
    'crumbs' => ['Asesmen' => route('assessment.index'), $track->name => route('assessment.intro', $track->slug), 'Penilaian' => null],
    'image' => $formConfig['hero_image'] ?? null,
    'overlap' => true,
])

<section class="relative flow-root bg-cream-50 pb-20 sm:pb-28">
    <div class="container-gd max-w-4xl">
        {{-- Progress melayang --}}
        <div class="sticky top-20 z-30 -mt-20 mb-8 lg:top-24">
            <div class="rounded-3xl border border-ink-100 bg-white/95 p-5 backdrop-blur-md shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)] sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="relative h-12 w-12 shrink-0">
                            <svg viewBox="0 0 36 36" class="h-12 w-12 -rotate-90">
                                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#ececf2" stroke-width="3.5"/>
                                <circle data-progress-ring cx="18" cy="18" r="15.9" fill="none" stroke="#d81c25" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="0 100" style="transition: stroke-dasharray .5s cubic-bezier(.22,1,.36,1)"/>
                            </svg>
                            <span data-progress-label class="absolute inset-0 flex items-center justify-center text-[11px] font-bold text-ink-900">0%</span>
                        </div>
                        <div>
                            <p class="font-bold text-ink-950"><span data-answered>0</span> dari {{ $totalQuestions }} {{ $unitLabel }} dinilai</p>
                            <p class="text-xs text-ink-500">Jawaban tersimpan saat Anda menekan kirim.</p>
                        </div>
                    </div>
                    <div class="hidden items-center gap-1.5 sm:flex">
                        @foreach ($grouped as $dimension => $questions)
                            <a href="#dim-{{ $loop->iteration }}" data-dot="{{ $loop->iteration }}" title="{{ $dimension }}" class="h-2.5 w-7 rounded-full bg-ink-100 transition-all duration-300 hover:bg-ink-200"></a>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 hidden grid-cols-5 gap-1.5 text-center text-[11px] font-semibold uppercase tracking-wide text-ink-400 sm:grid">
                    @foreach ($labels as $val => $label)
                        <span class="flex items-center justify-center gap-1.5"><span class="scale-dot" data-val="{{ $val }}"></span>{{ $val }} · {{ $label }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @include('customer.assessment._admin_banner')
        <form action="{{ route('assessment.submit', $track->slug) }}" method="post" id="assessment-form" novalidate class="space-y-6">
            @csrf
            @foreach ($grouped as $dimension => $questions)
                @php
                    // Dimensi berisi satu pernyataan bernama sama → tampilkan sebagai satu kartu penilaian.
                    $single = $questions->count() === 1 && $questions->first()->question === $dimension;
                    $noteKey = $questions->first()->id;
                    $hasNote = filled(old('notes.'.$noteKey));
                @endphp
                @if ($sectionStarts->has($dimension))
                    <h3 class="flex items-center gap-3 pt-4 font-display text-lg font-bold text-ink-950 sm:text-xl"><span class="h-px w-8 bg-brand-500"></span>{{ $sectionStarts[$dimension] }}</h3>
                @endif
                <article id="dim-{{ $loop->iteration }}" data-dimension="{{ $loop->iteration }}" class="dim-card scroll-mt-56 rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-12px_rgb(26_26_38/0.12)] transition-all duration-300 sm:p-9">
                    <div class="flex items-start gap-4">
                        <span class="dim-badge relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 transition-all duration-300">
                            <svg class="dim-icon h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[($loop->iteration - 1) % count($icons)] }}"/></svg>
                            <svg class="dim-done absolute h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-ink-400">Dimensi {{ $loop->iteration }}/{{ $grouped->count() }}</p>
                                @if ($showWeight)
                                    <span class="rounded-full bg-cream-100 px-2.5 py-0.5 text-[11px] font-bold text-ink-700">Bobot {{ round($weights[$dimension] / $totalWeight * 100) }}%</span>
                                @endif
                            </div>
                            <h2 class="mt-1 font-display text-xl font-bold text-ink-950 sm:text-2xl">{{ $dimension }}</h2>
                            @if ($single && $questions->first()->help_text)
                                <p class="mt-2 leading-relaxed text-ink-600">{{ $questions->first()->help_text }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 space-y-6">
                        @foreach ($questions as $q)
                            <fieldset data-question class="{{ $single ? '' : 'rounded-2xl bg-cream-50 p-4 sm:p-5' }}">
                                <legend class="sr-only">{{ $q->question }}</legend>
                                @unless ($single)
                                    <p class="font-semibold leading-relaxed text-ink-900">{{ $loop->iteration }}. {{ $q->question }}</p>
                                    @if ($q->help_text)
                                        <p class="mt-1 text-sm text-ink-500">{{ $q->help_text }}</p>
                                    @endif
                                @endunless
                                <div class="{{ $single ? '' : 'mt-4' }} grid grid-cols-5 gap-2 sm:gap-3">
                                    @foreach ($labels as $val => $label)
                                        <label class="scale-opt block cursor-pointer" data-val="{{ $val }}">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $val }}" class="sr-only" {{ (string) old('answers.'.$q->id) === (string) $val ? 'checked' : '' }}>
                                            <span class="scale-tile flex h-full flex-col items-center justify-center gap-1 rounded-2xl border-2 border-ink-100 bg-white px-1 py-3.5 text-center transition-all duration-200 sm:py-4">
                                                <span class="font-display text-2xl font-bold leading-none sm:text-3xl">{{ $val }}</span>
                                                <span class="text-[10px] font-semibold leading-tight sm:text-xs">{{ $label }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        <button type="button" data-note-toggle class="{{ $hasNote ? 'hidden' : '' }} inline-flex items-center gap-1.5 text-sm font-semibold text-ink-500 transition hover:text-brand-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah catatan singkat <span class="font-normal text-ink-400">(opsional)</span>
                        </button>
                        <label data-note class="{{ $hasNote ? '' : 'hidden' }} block">
                            <span class="label-gd">Catatan singkat <span class="font-normal text-ink-400">(opsional)</span></span>
                            <textarea name="notes[{{ $noteKey }}]" rows="2" maxlength="1000" class="input-gd" placeholder="Kondisi nyata, contoh, atau kendala di dimensi ini…">{{ old('notes.'.$noteKey) }}</textarea>
                        </label>
                    </div>
                </article>
            @endforeach

            <div class="relative overflow-hidden rounded-3xl bg-ink-950 p-7 text-white shadow-[0_25px_50px_-12px_rgb(26_26_38/0.35)] sm:p-10">
                <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-brand-600/30 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p data-submit-title class="font-display text-2xl font-bold">Hampir selesai!</p>
                        <p data-submit-hint class="mt-1 text-sm text-white/60">Nilai semua {{ $unitLabel }} untuk melihat hasil.</p>
                    </div>
                    <div class="flex shrink-0 flex-col-reverse gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('assessment.intro', $track->slug) }}" class="btn border border-white/25 !py-4 text-white hover:bg-white/10">← Kembali</a>
                        <button type="submit" class="btn-primary group !px-9 !py-4 text-base">
                            {{ $formConfig['submit_label'] ?? ($track->price > 0 ? 'Kirim & Lanjut Pembayaran' : 'Kirim & Lihat Hasil') }}
                            <svg class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<style>
    /* Warna skala 1 (merah) → 5 (hijau) */
    [data-val="1"] { --c: #dc2626; --bg: #fef2f2; }
    [data-val="2"] { --c: #ea580c; --bg: #fff7ed; }
    [data-val="3"] { --c: #d97706; --bg: #fffbeb; }
    [data-val="4"] { --c: #379868; --bg: #f0faf4; }
    [data-val="5"] { --c: #206144; --bg: #dbf3e4; }
    .scale-dot { display: inline-block; width: .5rem; height: .5rem; border-radius: 9999px; background: var(--c); }
    .scale-tile { color: #535577; }
    .scale-opt:hover .scale-tile { border-color: var(--c); color: var(--c); transform: translateY(-2px); }
    .scale-opt input:focus-visible + .scale-tile { outline: 2px solid var(--c); outline-offset: 2px; }
    .scale-opt input:checked + .scale-tile {
        border-color: var(--c); background: var(--bg); color: var(--c);
        box-shadow: 0 12px 24px -12px var(--c); transform: translateY(-3px) scale(1.03);
    }
    .dim-done { opacity: 0; transform: scale(.4); transition: all .3s cubic-bezier(.22,1,.36,1); }
    .dim-card.is-done { border-color: #b9e5cd; }
    .dim-card.is-done .dim-badge { background: #379868; color: #fff; }
    .dim-card.is-done .dim-icon { opacity: 0; }
    .dim-card.is-done .dim-done { opacity: 1; transform: scale(1); }
    .dim-card.is-missing { border-color: #f7a4a8; animation: dim-shake .45s; }
    [data-dot].is-done { background: #379868; }
    @keyframes dim-shake { 20%, 60% { transform: translateX(-6px); } 40%, 80% { transform: translateX(6px); } }
</style>
<script>
    (function () {
        var form = document.getElementById('assessment-form');
        var cards = Array.prototype.slice.call(document.querySelectorAll('.dim-card'));
        var fieldsets = document.querySelectorAll('[data-question]');
        var total = fieldsets.length;
        var ring = document.querySelector('[data-progress-ring]');
        var label = document.querySelector('[data-progress-label]');
        var answeredEl = document.querySelector('[data-answered]');
        var title = document.querySelector('[data-submit-title]');
        var hint = document.querySelector('[data-submit-hint]');

        function cardDone(card) {
            return Array.prototype.every.call(card.querySelectorAll('[data-question]'), function (fs) {
                return fs.querySelector('input:checked');
            });
        }

        function update() {
            var answered = 0;
            fieldsets.forEach(function (fs) { if (fs.querySelector('input:checked')) answered++; });
            var pct = total ? Math.round(answered / total * 100) : 0;
            ring.setAttribute('stroke-dasharray', pct + ' 100');
            label.textContent = pct + '%';
            answeredEl.textContent = answered;
            cards.forEach(function (card) {
                var done = cardDone(card);
                card.classList.toggle('is-done', done);
                if (done) card.classList.remove('is-missing');
                var dot = document.querySelector('[data-dot="' + card.dataset.dimension + '"]');
                if (dot) dot.classList.toggle('is-done', done);
            });
            var left = total - answered;
            title.textContent = left === 0 ? 'Semua sudah dinilai' : 'Hampir selesai!';
            hint.textContent = left === 0 ? 'Periksa kembali bila perlu, lalu kirim jawaban Anda.' : 'Masih ' + left + ' penilaian lagi sebelum bisa dikirim.';
        }

        document.querySelectorAll('[data-question] input').forEach(function (input) {
            input.addEventListener('change', function () {
                var card = input.closest('.dim-card');
                update();
                // Otomatis gulir ke dimensi berikutnya yang belum selesai.
                if (cardDone(card)) {
                    var next = cards.slice(cards.indexOf(card) + 1).find(function (c) { return !cardDone(c); });
                    if (next) setTimeout(function () { next.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 350);
                }
            });
        });

        document.querySelectorAll('[data-note-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var note = btn.parentElement.querySelector('[data-note]');
                btn.classList.add('hidden');
                note.classList.remove('hidden');
                note.querySelector('textarea').focus();
            });
        });

        form.addEventListener('submit', function (e) {
            var missing = cards.filter(function (c) { return !cardDone(c); });
            if (!missing.length) return;
            e.preventDefault();
            missing.forEach(function (c) {
                c.classList.remove('is-missing');
                void c.offsetWidth;
                c.classList.add('is-missing');
            });
            missing[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        update();
    })();
</script>
@endsection
