@extends('customer/layout')

@section('content')

{{-- Hero khas asesmen: gelap, editorial, bukan template generik --}}
<section class="relative overflow-hidden bg-ink-950">
    <div class="pointer-events-none absolute -left-32 top-0 h-[28rem] w-[28rem] rounded-full bg-brand-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 h-[22rem] w-[22rem] rounded-full bg-forest-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.15]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 26px 26px;"></div>

    <div class="container-gd relative z-10 py-16 sm:py-20 lg:py-24">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-white/50">
                <li><a href="{{ url('/') }}" class="transition hover:text-white">Beranda</a></li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-brand-400" aria-current="page">Asesmen</span>
                </li>
            </ol>
        </nav>

        <div class="mt-8 grid items-end gap-10 lg:grid-cols-[1.4fr_1fr]">
            <div data-vue="Reveal">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-brand-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand-400"></span>
                    Asesmen Kesiapan GODEVI
                </p>
                <h1 class="mt-5 font-display text-4xl font-bold leading-[1.08] text-white sm:text-5xl lg:text-6xl">
                    Ukur kesiapanmu,<br>
                    <span class="italic text-brand-400">rancang langkah</span> berikutnya.
                </h1>
                <p class="mt-5 max-w-xl leading-relaxed text-white/70">
                    Empat jalur penilaian mandiri untuk desa wisata, usaha desa, kawasan, dan produk regeneratif.
                    Skor berskala 1–5, hasil & draf strategi terbuka setelah pembayaran.
                </p>
                <div class="mt-7 flex flex-wrap items-center gap-x-7 gap-y-3 text-sm text-white/70">
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-forest-300" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tanpa login
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-forest-300" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        ±10–15 menit
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-forest-300" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" /></svg>
                        Laporan strategi AI
                    </span>
                </div>
            </div>
            <div data-vue="Reveal" data-props='{"delay":140}' class="hidden lg:block">
                <ol class="space-y-0 rounded-3xl border border-white/10 bg-white/[0.04] p-2 backdrop-blur">
                    @foreach ([['Pilih jalur', 'Sesuai kebutuhanmu'], ['Isi profil & nilai', 'Skala 1–5 yang jujur'], ['Bayar sekali', 'QRIS / VA / e-wallet'], ['Buka hasil', 'Skor + laporan strategi']] as $i => $s)
                        <li class="flex items-center gap-4 rounded-2xl px-5 py-4 {{ $i === 0 ? 'bg-white/[0.07]' : '' }}">
                            <span class="font-display text-2xl font-bold {{ $i === 0 ? 'text-brand-400' : 'text-white/25' }}">0{{ $i + 1 }}</span>
                            <span>
                                <span class="block text-sm font-bold text-white">{{ $s[0] }}</span>
                                <span class="block text-xs text-white/50">{{ $s[1] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</section>

{{-- Daftar jalur --}}
<section class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="mb-12 flex flex-wrap items-end justify-between gap-6" data-vue="Reveal">
            <div class="max-w-xl">
                <p class="eyebrow">Empat Jalur</p>
                <h2 class="mt-2 font-display text-3xl font-bold text-ink-950 sm:text-4xl">Mulai dari yang paling sesuai</h2>
            </div>
            <p class="max-w-sm text-sm leading-relaxed text-ink-500">Skor kesiapan dan laporan strategi lengkap terbuka dengan satu pembayaran per asesmen.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($tracks as $i => $track)
                @php
                    $accents = [
                        'pariwisata' => ['bg' => 'bg-brand-600', 'soft' => 'bg-brand-50', 'text' => 'text-brand-700', 'ring' => 'group-hover:border-brand-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />'],
                        'ekonomi-desa' => ['bg' => 'bg-forest-600', 'soft' => 'bg-forest-50', 'text' => 'text-forest-700', 'ring' => 'group-hover:border-forest-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" />'],
                        'daya-saing-destinasi' => ['bg' => 'bg-ink-950', 'soft' => 'bg-ink-50', 'text' => 'text-ink-900', 'ring' => 'group-hover:border-ink-950', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />'],
                        'regeneratif' => ['bg' => 'bg-amber-600', 'soft' => 'bg-amber-50', 'text' => 'text-amber-700', 'ring' => 'group-hover:border-amber-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.61a.75.75 0 00-1.152-.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248zM15.75 14.25a3.75 3.75 0 11-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 011.925-3.545 3.75 3.75 0 013.255 3.717z" />'],
                    ];
                    $a = $accents[$track->slug] ?? $accents['pariwisata'];
                @endphp
                <article data-vue="Reveal" data-props='{"delay":{{ $i * 90 }}}'
                    class="group card flex flex-col border-t-4 p-0 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lift {{ $a['ring'] }} {{ $track->slug === 'pariwisata' ? 'border-t-brand-600' : ($track->slug === 'ekonomi-desa' ? 'border-t-forest-600' : ($track->slug === 'daya-saing-destinasi' ? 'border-t-ink-950' : 'border-t-amber-600')) }}">
                    <div class="flex flex-1 flex-col p-7 sm:p-9">
                        <div class="flex items-start justify-between gap-4">
                            <span class="flex h-13 w-13 items-center justify-center rounded-2xl p-3 text-white {{ $a['bg'] }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">{!! $a['icon'] !!}</svg>
                            </span>
                            <span class="font-display text-5xl font-bold leading-none text-ink-100 transition group-hover:text-ink-200">0{{ $i + 1 }}</span>
                        </div>
                        <h3 class="mt-6 font-display text-2xl font-bold leading-tight text-ink-950">{{ $track->name }}</h3>
                        <p class="mt-2 text-xs font-bold uppercase tracking-[0.18em] {{ $a['text'] }}">{{ $track->tagline }}</p>
                        <p class="mt-3 flex-1 text-[15px] leading-relaxed text-ink-600">{{ $track->description }}</p>
                        <dl class="mt-6 grid grid-cols-3 divide-x divide-ink-100 rounded-2xl bg-cream-50 py-4 text-center">
                            <div class="px-2">
                                <dt class="sr-only">Untuk</dt>
                                <dd class="truncate text-xs font-semibold text-ink-700" title="{{ $track->target_audience }}">{{ $track->target_audience }}</dd>
                            </div>
                            <div class="px-2">
                                <dt class="sr-only">Durasi</dt>
                                <dd class="text-xs font-semibold text-ink-700">±{{ $track->estimated_minutes }} mnt</dd>
                            </div>
                            <div class="px-2">
                                <dt class="sr-only">Pernyataan</dt>
                                <dd class="text-xs font-semibold text-ink-700">{{ $track->questions_count }} soal</dd>
                            </div>
                        </dl>
                        <div class="mt-6 flex items-center justify-between gap-4 border-t border-dashed border-ink-100 pt-5">
                            <p class="text-sm text-ink-500">Skor + laporan <strong class="text-ink-900">Rp {{ number_format($track->price ?? 199000, 0, ',', '.') }}</strong></p>
                            <a href="{{ route('assessment.intro', $track->slug) }}"
                                class="inline-flex shrink-0 items-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold text-white transition group-hover:gap-3 {{ $a['bg'] }}">
                                Mulai
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <p class="mx-auto mt-10 max-w-2xl text-center text-sm leading-relaxed text-ink-500" data-vue="Reveal">
            Data profil Anda dilindungi UU Pelindungan Data Pribadi. Hasil asesmen bersifat penilaian mandiri —
            tim GODEVI siap mendampingi tindak lanjutnya.
        </p>
        <p class="mt-4 text-center text-sm text-ink-500">Sudah pernah mengisi? <a href="{{ route('assessment.status') }}" class="font-semibold text-brand-600 underline underline-offset-2">Cek status & hasil asesmen</a></p>
    </div>
</section>
@endsection
