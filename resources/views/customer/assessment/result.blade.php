@extends('customer/layout')

@section('content')

@php
    $icons = \App\Services\AssessmentService::DIMENSION_ICONS;
    $total = (int) round($computed['total']);
    $dims = $computed['dimensions'];
    $dimOrder = array_keys($result->track->activeQuestions->groupBy('dimension')->all());
    $iconFor = fn ($name) => $icons[(($i = array_search($name, $dimOrder, true)) === false ? crc32($name) : $i) % count($icons)];
    $tone = fn ($score) => $score >= 60 ? ['#379868', 'bg-forest-500', 'text-forest-700', 'bg-forest-50'] : ($score >= 40 ? ['#d97706', 'bg-amber-500', 'text-amber-700', 'bg-amber-50'] : ['#dc2626', 'bg-red-500', 'text-red-700', 'bg-red-50']);
    $totalWeight = array_sum(array_map(fn ($d) => $d['weight'] ?? 0, $dims));
    $showWeight = $totalWeight > 0 && count(array_unique(array_map(fn ($d) => $d['weight'] ?? 0, $dims))) > 1;
    $location = collect([$result->regency, $result->province])->filter()->implode(', ');
    $hasAiReport = ! empty($result->ai_report);
    $canRetry = $result->is_unlocked && $result->canRetryReport();
    $reportPending = ! $hasAiReport && ! $canRetry;
@endphp

@include('customer.assessment._hero', [
    'title' => $result->organization ?: $result->name,
    'subtitle' => 'Hasil asesmen '.$result->track->name.($location ? ' · '.$location : '').' — skor kesiapan, analisis kekuatan & tantangan, dan draf strategi.',
    'eyebrow' => 'Hasil Asesmen '.$result->track->name,
    'crumbs' => ['Asesmen' => route('assessment.index'), 'Hasil' => null],
    'image' => $formConfig['hero_image'] ?? null,
    'overlap' => true,
])

<section class="relative flow-root bg-cream-50 pb-20 sm:pb-28">
    <div class="container-gd max-w-5xl">

        {{-- ============ SKOR TOTAL ============ --}}
        <div class="relative z-10 -mt-20 overflow-hidden rounded-[1.75rem] border border-ink-100 bg-white shadow-[0_25px_50px_-12px_rgb(26_26_38/0.22)]">
            <div class="grid gap-8 p-6 sm:p-10 md:grid-cols-[auto_1fr] md:items-center">
                <div class="relative mx-auto h-48 w-48 sm:h-52 sm:w-52">
                    <svg viewBox="0 0 36 36" class="h-full w-full -rotate-90">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#fde3e4" stroke-width="2.6"/>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="url(#score-grad)" stroke-width="2.6" stroke-linecap="round" stroke-dasharray="{{ $total }} 100" class="result-ring"/>
                        <defs><linearGradient id="score-grad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#e63f49"/><stop offset="100%" stop-color="#95151c"/></linearGradient></defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="font-display text-6xl font-bold text-ink-950" data-count="{{ $total }}">{{ $total }}</span>
                        <span class="text-xs font-semibold uppercase tracking-wider text-ink-400">dari 100</span>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ink-400">Kategori kesiapan</p>
                    <h2 class="mt-1 font-display text-4xl font-bold text-brand-700 sm:text-5xl">{{ $computed['band'] }}</h2>
                    <p class="mt-3 max-w-xl leading-relaxed text-ink-600 md:max-w-none">{{ $computed['band_desc'] }}</p>
                    @if ($result->track->slug === 'daya-saing-destinasi')
                        <p class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left text-xs leading-relaxed text-amber-900">{{ \App\Services\AssessmentService::TTDI_DISCLAIMER }}</p>
                    @endif

                    {{-- Tangga kategori --}}
                    <div class="mt-6 grid grid-cols-4 gap-1.5">
                        @foreach ($bands as $band)
                            @php $active = $band['label'] === $computed['band']; @endphp
                            <div class="text-center">
                                <div class="h-2 rounded-full {{ $active ? 'bg-brand-600' : 'bg-ink-100' }}"></div>
                                <p class="mt-2 text-[11px] font-bold leading-tight {{ $active ? 'text-brand-700' : 'text-ink-400' }}">{{ $band['label'] }}</p>
                                <p class="text-[10px] text-ink-400">{{ $band['min'] }}+</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 border-t border-ink-100 bg-cream-50/70 px-6 py-4 text-xs text-ink-500 sm:flex-row sm:items-center sm:justify-between sm:px-10">
                <p>Kode hasil <code class="font-mono text-ink-700">{{ $result->uuid }}</code> · {{ $result->created_at->format('d M Y, H:i') }}</p>
                <div class="no-print flex flex-wrap gap-2">
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 font-semibold text-ink-700 transition hover:border-brand-600 hover:text-brand-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                        Cetak / PDF
                    </button>
                    <a href="{{ route('assessment.status', ['phone' => $result->phone]) }}" class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 font-semibold text-ink-700 transition hover:border-brand-600 hover:text-brand-600">Riwayat asesmen</a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if (auth()->check() && (int) auth()->user()->role_id !== 3)
            <a href="{{ route('assessment-results.show', $result->id) }}" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-800 transition hover:border-brand-600 hover:text-brand-600">Buka di panel admin →</a>
        @endif

        {{-- ============ LAPORAN STRATEGI (AI) ============ --}}
        <div id="laporan" class="mt-16 rounded-[1.75rem] border border-ink-100 bg-white p-7 shadow-[0_10px_30px_-12px_rgb(26_26_38/0.12)] sm:p-10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="eyebrow">Laporan Strategi</p>
                    <h2 class="font-display text-3xl font-bold text-ink-950">Rekomendasi khusus untuk {{ $result->organization ?: 'Anda' }}</h2>
                </div>
                @if ($reportPending)
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700"><span class="loc-spinner-sm"></span>Sedang disusun</span>
                @endif
            </div>
                @if (! empty($result->ai_report))
                    @php $rep = $result->ai_report; @endphp
                    <p class="mt-4 border-l-4 border-brand-600 pl-5 font-display text-lg italic leading-relaxed text-ink-800">{{ $rep['ringkasan'] ?? '' }}</p>
                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div class="rounded-3xl bg-forest-50 p-6">
                            <h3 class="flex items-center gap-2 font-bold text-forest-800">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Kekuatan utama
                            </h3>
                            <ul class="mt-3 space-y-2.5 text-sm leading-relaxed text-ink-700">
                                @foreach ((array) ($rep['kekuatan'] ?? []) as $s)<li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-forest-600"></span>{{ $s }}</li>@endforeach
                            </ul>
                        </div>
                        <div class="rounded-3xl bg-brand-50 p-6">
                            <h3 class="flex items-center gap-2 font-bold text-brand-700">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                Tantangan utama
                            </h3>
                            <ul class="mt-3 space-y-2.5 text-sm leading-relaxed text-ink-700">
                                @foreach ((array) ($rep['tantangan'] ?? []) as $c)<li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-600"></span>{{ $c }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                    <h3 class="mt-8 font-display text-xl font-bold text-ink-950">5 langkah prioritas</h3>
                    <ol class="mt-4 space-y-4">
                        @foreach ((array) ($rep['langkah_prioritas'] ?? []) as $i => $s)
                            <li class="flex gap-4 rounded-2xl border border-ink-100 p-4">
                                <span class="font-display text-2xl font-bold text-brand-600">{{ $i + 1 }}</span>
                                <span class="text-sm leading-relaxed text-ink-700">{{ $s }}</span>
                            </li>
                        @endforeach
                    </ol>
                    @foreach (['segmen_pasar' => 'Segmen pasar', 'strategi_pemasaran' => 'Strategi pemasaran', 'produk_unggulan_potensial' => 'Produk unggulan potensial', 'model_bisnis_disarankan' => 'Model bisnis disarankan', 'prioritas_kebijakan' => 'Prioritas kebijakan', 'rekomendasi_regeneratif' => 'Rekomendasi regeneratif', 'sertifikasi_relevan' => 'Sertifikasi relevan', 'layanan_godevi_disarankan' => 'Layanan GODEVI yang disarankan', 'catatan_kategori' => 'Catatan per subindeks'] as $key => $title)
                        @if (! empty($rep[$key]))
                            <h3 class="mt-8 font-display text-xl font-bold text-ink-950">{{ $title }}</h3>
                            <ul class="mt-3 space-y-2.5 text-sm leading-relaxed text-ink-700">
                                @foreach ((array) $rep[$key] as $s)<li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-ink-400"></span>{{ $s }}</li>@endforeach
                            </ul>
                        @endif
                    @endforeach
                    @if (! empty($rep['opsi_branding']))
                        <h3 class="mt-8 font-display text-xl font-bold text-ink-950">Opsi branding</h3>
                        <div class="mt-3 grid gap-4 md:grid-cols-3">
                            @foreach ((array) $rep['opsi_branding'] as $b)
                                <div class="rounded-3xl bg-ink-950 p-5 text-sm text-white">
                                    <p class="font-display text-base font-bold">{{ is_array($b) ? ($b['nama'] ?? '') : $b }}</p>
                                    @if (is_array($b))
                                        <p class="mt-1 italic text-brand-400">"{{ $b['tagline'] ?? '' }}"</p>
                                        <p class="mt-2 text-white/65">{{ $b['alasan'] ?? '' }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if (! empty($rep['posisi_spektrum']))
                        <div class="mt-8 rounded-3xl bg-forest-600 p-6 text-sm leading-relaxed text-white"><span class="font-bold">Posisi spektrum: </span>{{ $rep['posisi_spektrum'] }}</div>
                    @endif
                    @if (! empty($rep['catatan_ttdi']))
                        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-relaxed text-amber-900">{{ $rep['catatan_ttdi'] }}</div>
                    @endif
                @elseif ($canRetry)
                    <div class="mt-5 rounded-2xl border border-red-100 bg-red-50 p-5">
                        <p class="text-sm font-bold text-red-800">Laporan analisa belum berhasil disusun.</p>
                        <p class="mt-1 text-sm leading-relaxed text-red-700">Skor Anda di bawah sudah tersimpan dan aman. Silakan coba susun ulang laporannya — bila masih gagal, tim kami akan menindaklanjuti (sebutkan kode hasil di atas).</p>
                        <form action="{{ route('assessment.retry_report', $result->uuid) }}" method="post" class="mt-4" data-retry-form>
                            @csrf
                            <button type="submit" class="btn btn-primary inline-flex items-center gap-2 !py-3">
                                <span data-label class="inline-flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    Coba susun ulang laporan
                                </span>
                                <span data-loading class="hidden items-center gap-2">
                                    <svg class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                    Menyusun laporan… (bisa sampai 1 menit)
                                </span>
                            </button>
                        </form>
                        @if (auth()->check() && (int) auth()->user()->role_id !== 3)
                            <p class="mt-3 text-xs text-red-600">Info staf: {{ $result->report_error ?: 'proses macet' }}</p>
                        @endif
                    </div>
                @else
                    <div class="mt-6 space-y-3" aria-hidden="true">
                        <div class="report-skeleton h-4 w-11/12"></div>
                        <div class="report-skeleton h-4 w-4/5"></div>
                        <div class="report-skeleton h-4 w-2/3"></div>
                    </div>
                    <p class="mt-5 text-sm text-ink-500">Analisa AI sedang disusun berdasarkan skor, profil, dan catatan Anda. Halaman ini akan memuat ulang otomatis.</p>
                @endif
        </div>

        {{-- ============ SKOR PER DIMENSI ============ --}}
        <div class="mt-16">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Skor per Dimensi</p>
                    <h2 class="font-display text-3xl font-bold text-ink-950">Peta kesiapan {{ $result->track->name }}</h2>
                </div>
                <div class="flex gap-4 text-xs font-semibold text-ink-500">
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-forest-500"></span>≥ 60</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>40–59</span>
                    <span class="inline-flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>&lt; 40</span>
                </div>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @foreach ($dims as $name => $dim)
                    @php [$hex, $bar, $text, $soft] = $tone($dim['score']); @endphp
                    <article data-vue="Reveal" style="--reveal-delay: {{ ($loop->index % 2) * 100 }}ms" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-12px_rgb(26_26_38/0.12)]">
                        <div class="flex items-start gap-4">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $soft }} {{ $text }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconFor($name) }}"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-bold leading-snug text-ink-950">{{ $name }}</h3>
                                    <span class="shrink-0 font-display text-2xl font-bold {{ $text }}">{{ number_format($dim['score'], 0) }}</span>
                                </div>
                                <p class="mt-0.5 text-xs text-ink-400">
                                    Nilai rata-rata {{ $dim['average'] ?? '-' }}/5{{ $showWeight ? ' · Bobot '.round(($dim['weight'] ?? 0) / $totalWeight * 100).'%' : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-cream-100">
                            <div class="result-bar h-full rounded-full {{ $bar }}" style="--w: {{ max(2, $dim['score']) }}%; animation-duration: {{ 900 + $loop->index * 120 }}ms"></div>
                        </div>
                        @if (! empty($dim['advice']))
                            <p class="mt-4 text-sm leading-relaxed text-ink-600">{{ $dim['advice'] }}</p>
                        @endif
                        @if (! empty($result->dimension_notes[$name]))
                            <p class="mt-3 rounded-2xl bg-cream-50 px-4 py-3 text-xs leading-relaxed text-ink-600"><span class="font-bold text-ink-800">Catatan Anda:</span> {{ $result->dimension_notes[$name] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>

        @if ($result->track->slug === 'daya-saing-destinasi' && ! empty($computed['subindexes']))
            <div class="mt-10 rounded-3xl border border-ink-100 bg-white p-7 sm:p-8">
                <h3 class="font-display text-xl font-bold text-ink-950">Subskor per subindeks</h3>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($computed['subindexes'] as $sub => $score)
                        <div class="rounded-2xl bg-cream-50 p-5">
                            <p class="text-xs font-bold leading-snug text-ink-700">{{ $sub }}</p>
                            <p class="mt-2 font-display text-3xl font-bold text-ink-950">{{ number_format($score, 0) }}<span class="text-sm font-medium text-ink-400">/100</span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @unless ($hasAiReport)
        {{-- ============ KEKUATAN & TANTANGAN (ringkasan otomatis, bila laporan AI belum ada) ============ --}}
        <div class="mt-16 grid gap-6 md:grid-cols-2">
            <div class="rounded-3xl border border-forest-200 bg-gradient-to-br from-forest-50 to-white p-7 sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-forest-600 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                    </span>
                    <div>
                        <h3 class="font-display text-xl font-bold text-forest-800">Kekuatan</h3>
                        <p class="text-xs text-ink-500">Modal promosi & kemitraan</p>
                    </div>
                </div>
                <ul class="mt-6 space-y-3">
                    @foreach ($computed['strengths'] as $name)
                        <li class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-forest-100">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-ink-900">{{ $name }}</p>
                                <span class="shrink-0 rounded-full bg-forest-50 px-2.5 py-0.5 text-xs font-bold text-forest-700">{{ number_format($dims[$name]['score'], 0) }}/100</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="rounded-3xl border border-brand-200 bg-gradient-to-br from-brand-50 to-white p-7 sm:p-8">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    </span>
                    <div>
                        <h3 class="font-display text-xl font-bold text-brand-800">Tantangan</h3>
                        <p class="text-xs text-ink-500">Fokus perbaikan 3 bulan ke depan</p>
                    </div>
                </div>
                <ul class="mt-6 space-y-3">
                    @foreach ($computed['challenges'] as $name)
                        <li class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-brand-100">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-ink-900">{{ $name }}</p>
                                <span class="shrink-0 rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-bold text-brand-700">{{ number_format($dims[$name]['score'], 0) }}/100</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- ============ STRATEGI ============ --}}
        <div class="mt-16 rounded-[1.75rem] border border-ink-100 bg-white p-7 shadow-[0_10px_30px_-12px_rgb(26_26_38/0.12)] sm:p-10">
            <p class="eyebrow">Draf Strategi Prioritas</p>
            <h2 class="font-display text-3xl font-bold text-ink-950">Langkah yang perlu didahulukan</h2>
            <p class="mt-2 text-sm text-ink-500">Disusun dari penilaian dengan skor terendah.</p>

            @if (count($computed['priority_actions']) > 0)
                <ol class="relative mt-8 space-y-6 border-l-2 border-dashed border-ink-100 pl-8">
                    @foreach ($computed['priority_actions'] as $item)
                        <li class="relative">
                            <span class="absolute -left-[2.85rem] top-0 flex h-9 w-9 items-center justify-center rounded-full bg-brand-600 font-display text-sm font-bold text-white ring-4 ring-white">{{ $loop->iteration }}</span>
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-brand-600">{{ $item['dimension'] }}</p>
                            <p class="mt-1 leading-relaxed text-ink-800">{{ $item['action'] }}</p>
                        </li>
                    @endforeach
                </ol>
            @else
                <div class="mt-6 flex items-start gap-3 rounded-2xl bg-forest-50 p-5 text-sm text-ink-700">
                    <svg class="h-6 w-6 shrink-0 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p>Tidak ada penilaian bernilai rendah. Pertahankan ritme dan naikkan target ke level berikutnya: sertifikasi, ekspansi pasar, dan kemitraan.</p>
                </div>
            @endif

            @if ($result->profile_description)
                <div class="mt-10 rounded-2xl bg-cream-50 p-5 text-sm">
                    <p class="font-bold text-ink-900">Profil aset & potensi yang Anda kirim</p>
                    <p class="mt-2 whitespace-pre-line leading-relaxed text-ink-600">{{ $result->profile_description }}</p>
                </div>
            @endif
        </div>

        @endunless

        {{-- ============ CTA ============ --}}
        <div class="no-print relative mt-16 overflow-hidden rounded-[1.75rem] bg-ink-950 p-8 text-white sm:p-12">
            <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-brand-600/35 blur-3xl"></div>
            <div aria-hidden="true" class="pointer-events-none absolute -bottom-24 left-10 h-60 w-60 rounded-full bg-forest-500/20 blur-3xl"></div>
            <div class="relative grid gap-8 md:grid-cols-[1fr_auto] md:items-center">
                <div>
                    <h3 class="font-display text-2xl font-bold text-white sm:text-3xl">Ingin didampingi menyusun strategi final?</h3>
                    <p class="mt-3 max-w-xl text-white/70">Tim GODEVI mendampingi desa, UMKM/koperasi, dan pemda dari hasil asesmen hingga aksi. Sebutkan kode hasil di atas saat menghubungi kami.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('contact') }}" class="btn-white !px-7 !py-3.5">Hubungi Tim GODEVI</a>
                    <a href="{{ route('assessment.index') }}" class="btn border border-white/30 !px-7 !py-3.5 text-white hover:bg-white/10">Coba Jalur Lain</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<style>
    .report-skeleton { border-radius: 9999px; background: linear-gradient(90deg, #f1ede4 25%, #faf8f3 50%, #f1ede4 75%); background-size: 200% 100%; animation: report-shimmer 1.2s linear infinite; }
    .loc-spinner-sm { width: .8rem; height: .8rem; border: 2px solid rgb(0 0 0 / .12); border-top-color: currentColor; border-radius: 9999px; animation: report-spin .7s linear infinite; }
    @keyframes report-shimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }
    @keyframes report-spin { to { transform: rotate(360deg); } }
    .result-ring { animation: result-ring 1.8s cubic-bezier(.22,1,.36,1) both; }
    .result-bar { width: var(--w); animation: result-bar 1s cubic-bezier(.22,1,.36,1) both; }
    @keyframes result-ring { from { stroke-dasharray: 0 100; } }
    @keyframes result-bar { from { width: 0; } }
    @media print {
        .site-header, footer, .no-print { display: none !important; }
        html.js [data-vue="Reveal"] { opacity: 1 !important; transform: none !important; }
        body { background: #fff; }
    }
</style>
<script>
    @if ($reportPending)
        // Laporan AI masih disusun di antrean → muat ulang berkala sampai selesai.
        setTimeout(function () { window.location.reload(); }, 15000);
    @endif
    document.querySelectorAll('[data-retry-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button');
            btn.disabled = true;
            btn.querySelector('[data-label]').classList.add('hidden');
            var loading = btn.querySelector('[data-loading]');
            loading.classList.remove('hidden');
            loading.classList.add('inline-flex');
        });
    });
    (function () {
        // Angka skor menghitung naik dari 0.
        var el = document.querySelector('[data-count]');
        if (!el || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var target = parseInt(el.dataset.count, 10) || 0;
        var start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min(1, (ts - start) / 1600);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(step);
        }
        el.textContent = '0';
        requestAnimationFrame(step);
    })();
</script>
@endsection
