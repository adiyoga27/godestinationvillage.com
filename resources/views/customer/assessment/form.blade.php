@extends('customer/layout')

@section('content')

{{-- Fallback indikator pilihan tanpa tergantung hasil build Tailwind --}}
<style>
    [data-question] input:checked + span {
        border-color: #d81c25 !important;
        background-color: #fef2f2 !important;
        color: #b3121a !important;
        box-shadow: 0 0 0 2px #d81c25 inset;
    }
</style>

<x-partials.page-hero
    title="Isi Asesmen: {{ $track->name }}"
    subtitle="Beri nilai 1–5 yang paling jujur menggambarkan kondisimu saat ini."
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="['Home' => '/', 'Asesmen' => '/asesmen', $track->name => '/asesmen/'.$track->slug, 'Soal' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @if (session('error'))
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card sticky top-24 z-20 mb-6 p-4">
            <div class="flex items-center justify-between gap-4 text-sm font-semibold">
                <span class="text-ink-700">Kemajuan: <span data-progress-label class="text-brand-600">0%</span></span>
                <span class="text-ink-500">{{ $track->activeQuestions->count() }} pernyataan</span>
            </div>
            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-cream-100">
                <div data-progress-bar class="h-full w-0 rounded-full bg-brand-600 transition-all duration-300"></div>
            </div>
            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-ink-500">
                @foreach ($labels as $val => $label)
                    <span><strong>{{ $val }}</strong> = {{ $label }}</span>
                @endforeach
            </div>
        </div>

        <form action="{{ route('assessment.submit', $track->slug) }}" method="post" class="space-y-8">
            @csrf
            @foreach ($grouped as $dimension => $questions)
                <div class="card p-8 sm:p-10">
                    <h2 class="font-display text-xl font-bold text-ink-950">{{ $loop->iteration }}. {{ $dimension }}</h2>
                    <div class="mt-6 space-y-6">
                        @foreach ($questions as $q)
                            <fieldset data-question class="rounded-2xl border border-ink-100 p-5">
                                <legend class="sr-only">{{ $q->question }}</legend>
                                <p class="font-semibold leading-relaxed text-ink-900">{{ $loop->iteration }}. {{ $q->question }}</p>
                                @if ($q->help_text)
                                    <p class="mt-1 text-sm text-ink-500">{{ $q->help_text }}</p>
                                @endif
                                <div class="mt-4 grid grid-cols-5 gap-2">
                                    @foreach ($labels as $val => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $val }}" class="peer sr-only" {{ (string) old('answers.'.$q->id) === (string) $val ? 'checked' : '' }} required>
                                            <span class="flex flex-col items-center gap-1 rounded-xl border border-ink-100 bg-white px-1 py-3 text-center transition peer-checked:border-brand-600 peer-checked:bg-brand-50 peer-checked:text-brand-700 hover:border-brand-400">
                                                <span class="font-display text-xl font-bold">{{ $val }}</span>
                                                <span class="hidden text-[11px] leading-tight sm:block">{{ $label }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="card flex flex-col gap-4 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <p class="text-sm text-ink-500">Pastikan semua pernyataan terjawab sebelum mengirim.</p>
                <button type="submit" class="btn btn-primary shrink-0 !px-10 !py-4">Lihat Hasil Asesmen</button>
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
