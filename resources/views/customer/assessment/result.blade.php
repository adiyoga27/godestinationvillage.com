@extends('customer/layout')

@section('content')

{{-- Hero hasil: skor dalam cincin SVG --}}
<section class="relative overflow-hidden bg-ink-950">
    <div class="pointer-events-none absolute -left-24 top-1/3 h-80 w-80 rounded-full bg-brand-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 -top-16 h-72 w-72 rounded-full bg-forest-600/20 blur-3xl"></div>
    <div class="container-gd relative z-10 py-14 sm:py-18">
        <nav aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center justify-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-white/50">
                <li><a href="{{ url('/') }}" class="transition hover:text-white">Beranda</a></li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <a href="{{ route('assessment.index') }}" class="transition hover:text-white">Asesmen</a>
                </li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-brand-400" aria-current="page">Hasil</span>
                </li>
            </ol>
        </nav>

        <div class="mx-auto mt-8 max-w-3xl text-center" data-vue="Reveal">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-400">{{ $result->track->tagline }} · {{ $result->organization ?? $result->name }}</p>
            @php
                $pct = max(0, min(100, (float) $computed['total']));
                $circ = 2 * pi() * 80;
                $off = $circ * (1 - $pct / 100);
            @endphp
            <div class="relative mx-auto mt-8 h-52 w-52">
                <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
                    <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="14" />
                    <circle cx="100" cy="100" r="80" fill="none" stroke="url(#scoreGrad)" stroke-width="14" stroke-linecap="round"
                        stroke-dasharray="{{ $circ }}" stroke-dashoffset="{{ $off }}" />
                    <defs>
                        <linearGradient id="scoreGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#f16f76" />
                            <stop offset="100%" stop-color="#e8a33d" />
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-display text-6xl font-bold text-white">{{ number_format($computed['total'], 0) }}</span>
                    <span class="text-xs font-semibold uppercase tracking-widest text-white/50">dari 100</span>
                </div>
            </div>
            <h1 class="mt-6 font-display text-3xl font-bold text-white sm:text-4xl">{{ $computed['band'] }}</h1>
            <p class="mx-auto mt-3 max-w-xl leading-relaxed text-white/70">{{ $computed['band_desc'] }}</p>
            @if ($result->track->slug === 'daya-saing-destinasi')
                <p class="mx-auto mt-5 max-w-xl rounded-2xl border border-amber-400/30 bg-amber-400/10 p-4 text-left text-xs leading-relaxed text-amber-200">{{ \App\Services\AssessmentService::TTDI_DISCLAIMER }}</p>
            @endif
            <p class="mt-5 text-xs text-white/40">Kode hasil <code class="rounded bg-white/10 px-2 py-0.5 text-white/70">{{ $result->uuid }}</code> · {{ $result->created_at->format('d M Y') }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <button type="button" onclick="window.print()" class="btn border border-white/25 text-white hover:bg-white/10">Cetak / PDF</button>
                <a href="{{ route('assessment.index') }}" class="btn btn-white">Coba jalur lain</a>
            </div>
        </div>
    </div>
</section>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        {{-- Paywall --}}
        @if (! $result->is_unlocked)
            <div data-vue="Reveal" class="relative overflow-hidden rounded-3xl bg-ink-950 p-8 text-white sm:p-10">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-amber-500/20 blur-3xl"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-amber-500 text-ink-950">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    </span>
                    <div class="flex-1">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-400">Laporan strategi lengkap</p>
                        <h2 class="mt-1 font-display text-2xl font-bold">Masih terkunci — buka dengan sekali bayar</h2>
                        <p class="mt-2 max-w-xl text-sm leading-relaxed text-white/65">
                            Skor di atas gratis. Laporan lengkap — analisis kekuatan & tantangan, 5 langkah prioritas,
                            segmen pasar, dan strategi khusus untuk <strong class="text-white">{{ $result->organization ?? $result->name }}</strong> —
                            seharga <strong class="text-white">Rp {{ number_format($result->track->price ?? 199000, 0, ',', '.') }}</strong>
                            via QRIS / VA / e-wallet.
                        </p>
                        <div class="mt-5">
                            @if (! empty($pendingOrder))
                                <a href="{{ route('assessment.payment', $pendingOrder->code) }}" class="btn bg-amber-500 text-ink-950 hover:bg-amber-400">Lanjutkan pembayaran</a>
                            @else
                                <form action="{{ route('assessment.checkout', $result->uuid) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn bg-amber-500 text-ink-950 hover:bg-amber-400">Buka laporan — Rp {{ number_format($result->track->price ?? 199000, 0, ',', '.') }}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Laporan AI --}}
            <div id="laporan" data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-8 shadow-soft sm:p-10">
                <p class="eyebrow">Laporan strategi</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-ink-950 sm:text-3xl">Rekomendasi khusus untuk Anda</h2>
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
                @elseif (($result->report_status ?? '') === 'failed')
                    <p class="mt-4 rounded-2xl bg-red-50 p-4 text-sm text-red-700">Pembuatan laporan gagal ({{ $result->report_error }}). Tim kami akan menindaklanjuti — hubungi kami dengan menyebut kode hasil di atas.</p>
                @else
                    <p class="mt-4 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900">Laporan lengkap sedang disiapkan. Muat ulang halaman ini dalam beberapa saat.</p>
                @endif
            </div>
        @endif

        {{-- Skor per dimensi --}}
        <div data-vue="Reveal" class="mt-8 rounded-3xl border border-ink-100 bg-white p-8 shadow-soft sm:p-10">
            <h2 class="font-display text-2xl font-bold text-ink-950">Bedah skor per dimensi</h2>
            <div class="mt-7 space-y-6">
                @foreach ($computed['dimensions'] as $name => $dim)
                    <div>
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="text-[15px] font-bold text-ink-900">{{ $name }}</span>
                            <span class="shrink-0 font-display text-xl font-bold {{ $dim['score'] >= 60 ? 'text-forest-700' : ($dim['score'] >= 40 ? 'text-amber-600' : 'text-brand-600') }}">{{ number_format($dim['score'], 0) }}</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-cream-100">
                            <div class="h-full rounded-full {{ $dim['score'] >= 60 ? 'bg-forest-600' : ($dim['score'] >= 40 ? 'bg-amber-500' : 'bg-brand-600') }}" style="width: {{ $dim['score'] }}%"></div>
                        </div>
                        <p class="mt-1.5 text-[13px] leading-relaxed text-ink-500">{{ $dim['advice'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($result->track->slug === 'daya-saing-destinasi' && ! empty($computed['subindexes']))
            <div data-vue="Reveal" class="mt-8 rounded-3xl border border-ink-100 bg-white p-8 shadow-soft sm:p-10">
                <h2 class="font-display text-2xl font-bold text-ink-950">Subskor per subindeks</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($computed['subindexes'] as $sub => $score)
                        <div class="rounded-2xl bg-cream-50 p-5">
                            <p class="text-xs font-bold leading-snug text-ink-700">{{ $sub }}</p>
                            <p class="mt-2 font-display text-3xl font-bold text-ink-950">{{ number_format($score, 0) }}<span class="text-sm font-medium text-ink-400">/100</span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- CTA konsultasi --}}
        <div data-vue="Reveal" class="relative mt-8 overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-12 text-center sm:px-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Diskusikan hasil ini dengan tim GODEVI</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm text-white/80">Dari skor menjadi aksi: pendampingan desa, penguatan usaha, hingga tata kelola destinasi — dengan menyebut kode hasil Anda.</p>
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ url('contact') }}" class="btn btn-white !px-8 !py-3.5">Hubungi tim</a>
                <a href="{{ url('services') }}" class="btn border border-white/40 text-white hover:bg-white/10 !px-8 !py-3.5">Lihat layanan</a>
            </div>
        </div>
    </div>
</section>
@endsection
