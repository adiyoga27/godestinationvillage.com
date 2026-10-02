@extends('customer/layout')

@section('content')

@php
    $heroImage = $formConfig['hero_image'] ?? 'assets/customer/img/page-title-area/services.jpg';
    $priceLabel = $track->price > 0 ? 'Rp '.number_format($track->price, 0, ',', '.') : 'Gratis';
    $unitLabel = $dimensions->count() === $track->questions_count ? 'dimensi' : 'pernyataan';
    $icons = \App\Services\AssessmentService::DIMENSION_ICONS;
@endphp

{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden bg-ink-950">
    <img src="{{ url($heroImage) }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-55" loading="eager">
    <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/80 to-ink-950/40"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-brand-600/25 blur-3xl"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -bottom-40 left-1/4 h-96 w-96 rounded-full bg-forest-500/20 blur-3xl"></div>

    <div class="container-gd relative z-10 grid items-center gap-12 pb-28 pt-14 sm:pt-20 lg:grid-cols-12 lg:pb-36">
        <div class="animate-fade-up lg:col-span-7">
            <nav aria-label="Breadcrumb" class="text-xs font-semibold uppercase tracking-wider text-white/50">
                <a href="{{ url('/') }}" class="transition hover:text-white">Home</a>
                <span class="mx-1.5">/</span>
                <a href="{{ route('assessment.index') }}" class="transition hover:text-white">Asesmen</a>
                <span class="mx-1.5">/</span>
                <span class="text-brand-400">{{ $track->name }}</span>
            </nav>
            <p class="mt-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white/80 backdrop-blur">
                <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-400 opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-brand-500"></span></span>
                {{ $track->tagline }}
            </p>
            <h1 class="mt-5 font-display text-4xl font-bold leading-tight text-white text-balance sm:text-5xl lg:text-6xl">
                Asesmen {{ $track->name }}
            </h1>
            <p class="mt-5 max-w-xl text-lg leading-relaxed text-white/70">{{ $track->description }}</p>

            <div class="mt-7 flex flex-wrap gap-2.5 text-sm font-semibold text-white/85">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 backdrop-blur">
                    <svg class="h-4 w-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    ±{{ $track->estimated_minutes }} menit
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 backdrop-blur">
                    <svg class="h-4 w-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/></svg>
                    {{ $track->questions_count }} {{ $unitLabel }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 backdrop-blur">
                    <svg class="h-4 w-4 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Tanpa login
                </span>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-4">
                <a href="#form-asesmen" class="btn-primary !px-8 !py-4 text-base">
                    Mulai Asesmen
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                </a>
                <a href="{{ route('assessment.status') }}" class="text-sm font-semibold text-white/70 underline-offset-4 transition hover:text-white hover:underline">Sudah pernah mengisi? Cek status →</a>
            </div>
        </div>

        {{-- Pratinjau laporan --}}
        <div class="relative hidden lg:col-span-5 lg:block">
            <div class="animate-float rounded-[1.75rem] border border-white/15 bg-white/95 p-7 shadow-[0_40px_80px_-20px_rgb(0_0_0/0.6)] backdrop-blur">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ink-400">Contoh Laporan</p>
                    <span class="rounded-full bg-forest-50 px-3 py-1 text-xs font-bold text-forest-700">Siap</span>
                </div>
                <div class="mt-5 flex items-center gap-5">
                    <div class="relative h-28 w-28 shrink-0">
                        <svg viewBox="0 0 36 36" class="h-28 w-28 -rotate-90">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#fde3e4" stroke-width="3.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#d81c25" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="72 100" class="intro-ring"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="font-display text-3xl font-bold text-ink-950">72</span>
                            <span class="text-[10px] font-semibold text-ink-400">dari 100</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-display text-lg font-bold text-ink-950">Skor Kesiapan</p>
                        <p class="mt-1 text-sm leading-snug text-ink-500">Lengkap dengan analisis kekuatan, tantangan & draf strategi.</p>
                    </div>
                </div>
                <div class="mt-6 space-y-3.5">
                    @foreach ($dimensions->take(4) as $i => $dim)
                        @php $demo = [86, 64, 48, 71][$i] ?? 60; @endphp
                        <div>
                            <div class="flex justify-between text-xs font-semibold"><span class="text-ink-700">{{ $dim['name'] }}</span><span class="text-ink-400">{{ $demo }}</span></div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-cream-100">
                                <div class="intro-bar h-full rounded-full {{ $demo >= 60 ? 'bg-forest-500' : 'bg-amber-500' }}" style="--w: {{ $demo }}%; animation-duration: {{ 1000 + $i * 250 }}ms"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="relative mt-6 overflow-hidden rounded-2xl bg-cream-50 p-4">
                    <div class="space-y-2 blur-[3px]" aria-hidden="true">
                        <div class="h-2.5 w-11/12 rounded-full bg-ink-200"></div>
                        <div class="h-2.5 w-4/5 rounded-full bg-ink-200"></div>
                        <div class="h-2.5 w-2/3 rounded-full bg-ink-200"></div>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center gap-2 text-xs font-bold text-ink-700">
                        <svg class="h-4 w-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Draf strategi prioritas
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-6 -left-8 flex items-center gap-3 rounded-2xl bg-ink-950 px-5 py-4 text-white shadow-2xl ring-1 ring-white/10">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                </span>
                <div>
                    <p class="text-xs text-white/60">Biaya analisa</p>
                    <p class="font-display text-lg font-bold">{{ $priceLabel }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ LANGKAH ============ --}}
<section class="relative z-20 -mt-16 lg:-mt-20">
    <div class="container-gd">
        <div class="grid gap-px overflow-hidden rounded-3xl bg-ink-100 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.22)] sm:grid-cols-3">
            @foreach ([
                $profile['business_fields']
                    ? ['Isi profil usaha', 'Nama usaha, jenis & sektor, lokasi, dan kontak pengurus.']
                    : ['Isi profil desa', 'Nama desa/DTW, lokasi, dan kontak pengelola.'],
                ['Nilai '.$track->questions_count.' '.$unitLabel, 'Skala 1–5 sesuai kondisi nyata saat ini.'],
                ['Terima analisa', $track->price > 0 ? 'Bayar '.$priceLabel.', hasil langsung terbuka.' : 'Hasil langsung terbuka.'],
            ] as $i => [$title, $desc])
                <div data-vue="Reveal" style="--reveal-delay: {{ $i * 120 }}ms" class="flex items-start gap-4 bg-white p-6 sm:p-7">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-brand-50 font-display text-lg font-bold text-brand-600">{{ $i + 1 }}</span>
                    <div>
                        <p class="font-bold text-ink-950">{{ $title }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-ink-500">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ DIMENSI ============ --}}
<section class="bg-white pb-8 pt-20 sm:pt-24">
    <div class="container-gd">
        <div class="mx-auto max-w-2xl text-center">
            <p class="eyebrow justify-center">{{ $formConfig['title'] ?? 'Yang Dinilai' }}</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">{{ $dimensions->count() }} dimensi kesiapan destinasi</h2>
            <p class="mt-4 text-ink-600">{{ $formConfig['intro'] ?? 'Setiap dimensi dinilai dengan skala 1–5 dan menjadi dasar rekomendasi.' }}</p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($dimensions as $i => $dim)
                <article data-vue="Reveal" style="--reveal-delay: {{ ($i % 3) * 100 }}ms" class="group relative overflow-hidden rounded-3xl border border-ink-100 bg-cream-50/60 p-7 transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:bg-white hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.18)]">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-600 shadow-sm ring-1 ring-ink-100 transition group-hover:bg-brand-600 group-hover:text-white group-hover:ring-brand-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$i % count($icons)] }}"/></svg>
                        </span>
                        @if ($showWeight)
                            <span class="font-display text-3xl font-bold text-ink-200 transition group-hover:text-brand-200">{{ $dim['weight'] }}<span class="text-lg">%</span></span>
                        @endif
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-ink-950">{{ $dim['name'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink-500">{{ $dim['description'] }}</p>
                    @if ($showWeight)
                        <div class="mt-5">
                            <div class="flex justify-between text-[11px] font-semibold uppercase tracking-wider text-ink-400"><span>Bobot</span><span>{{ $dim['weight'] }}%</span></div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-ink-100">
                                <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-brand-700" style="width: {{ min(100, $dim['weight'] * 4) }}%"></div>
                            </div>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ FORM ============ --}}
<section id="form-asesmen" class="scroll-mt-24 bg-white pb-20 pt-12 sm:pb-28">
    <div class="container-gd">
        <div class="grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-8">
                @if (session('error'))
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form action="{{ route('assessment.start', $track->slug) }}" method="post" class="card p-6 sm:p-10">
                    @csrf
                    <div class="flex items-center gap-4 border-b border-ink-100 pb-6">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-[0_10px_25px_-8px_rgb(216_28_37/0.5)]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                        </span>
                        <div>
                            <h2 class="font-display text-2xl font-bold text-ink-950">{{ $profile['title'] }}</h2>
                            <p class="text-sm text-ink-500">{{ $profile['subtitle'] ?? 'Langkah 1 dari 2 — setelah ini Anda menilai '.$track->questions_count.' '.$unitLabel.'.' }}</p>
                        </div>
                    </div>

                    {{-- Profil utama --}}
                    <fieldset class="mt-8">
                        <legend class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-ink-400"><span class="h-px w-6 bg-ink-200"></span>{{ $profile['main_group'] }}</legend>
                        <div class="mt-4 grid gap-5">
                            <label class="block"><span class="label-gd">{{ $profile['organization_label'] }} <span class="text-brand-600">*</span></span><input type="text" name="organization" value="{{ old('organization') }}" required class="input-gd !py-3.5" placeholder="{{ $profile['organization_placeholder'] }}"></label>

                            <div class="relative" id="location-picker">
                                <label class="block">
                                    <span class="label-gd">Provinsi / Kabupaten / Kota <span class="text-brand-600">*</span></span>
                                    <span class="relative block">
                                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                        <input type="text" id="location-search" autocomplete="off" class="input-gd !py-3.5 !pl-12 !pr-11" placeholder="Ketik min. 3 huruf nama desa, kecamatan, atau kab/kota…">
                                        <span id="location-spinner" class="loc-spinner" hidden aria-hidden="true"></span>
                                        <svg id="location-check" class="loc-check" hidden viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-8 8a1 1 0 01-1.4 0l-4-4a1 1 0 111.4-1.4L8 12.6l7.3-7.3a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                </label>
                                <ul id="location-results" class="loc-results absolute left-0 right-0 z-20 mt-1 hidden max-h-72 overflow-y-auto rounded-2xl border border-cream-200 bg-white text-sm shadow-lg"></ul>
                                <input type="hidden" name="province" id="location-province" value="{{ old('province') }}">
                                <input type="hidden" name="regency" id="location-city" value="{{ old('regency') }}">
                                <input type="hidden" name="district" id="location-district" value="{{ old('district') }}">
                                <input type="hidden" name="subdistrict" id="location-subdistrict" value="{{ old('subdistrict') }}">
                            </div>

                            @if ($profile['business_fields'])
                                <div class="grid gap-5 sm:grid-cols-2">
                                    <label class="block">
                                        <span class="label-gd">Jenis Badan Usaha <span class="text-brand-600">*</span></span>
                                        <select name="business_type" required class="input-gd !py-3.5">
                                            <option value="" disabled {{ old('business_type') ? '' : 'selected' }}>Pilih...</option>
                                            @foreach (\App\Services\AssessmentService::BUSINESS_TYPES as $opt)
                                                <option value="{{ $opt }}" {{ old('business_type') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="block">
                                        <span class="label-gd">Sektor Usaha <span class="text-brand-600">*</span></span>
                                        <select name="business_sector" required class="input-gd !py-3.5">
                                            <option value="" disabled {{ old('business_sector') ? '' : 'selected' }}>Pilih...</option>
                                            @foreach (\App\Services\AssessmentService::BUSINESS_SECTORS as $opt)
                                                <option value="{{ $opt }}" {{ old('business_sector') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <label class="block sm:max-w-xs">
                                    <span class="label-gd">Jumlah Anggota/Pelaku Usaha <span class="font-normal text-ink-400">(opsional)</span></span>
                                    <input type="number" name="member_count" value="{{ old('member_count') }}" min="1" inputmode="numeric" class="input-gd !py-3.5" placeholder="mis. 25">
                                </label>
                            @endif
                        </div>
                    </fieldset>

                    {{-- Kontak --}}
                    <fieldset class="mt-9">
                        <legend class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-ink-400"><span class="h-px w-6 bg-ink-200"></span>{{ $profile['contact_group'] }}</legend>
                        <div class="mt-4 grid gap-5 sm:grid-cols-2">
                            <label class="block"><span class="label-gd">{{ $profile['contact_label'] }} <span class="text-brand-600">*</span></span><input type="text" name="name" value="{{ old('name') }}" required class="input-gd !py-3.5" placeholder="{{ $profile['contact_placeholder'] }}"></label>
                            <label class="block">
                                <span class="label-gd">No. WhatsApp / Telepon <span class="text-brand-600">*</span></span>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required class="input-gd !py-3.5" placeholder="08xx-xxxx-xxxx">
                                <span class="mt-1.5 block text-xs text-ink-400">Dipakai untuk cek status & membuka hasil kembali.</span>
                            </label>
                        </div>
                    </fieldset>

                    {{-- Deskripsi --}}
                    <fieldset class="mt-9">
                        <legend class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-ink-400"><span class="h-px w-6 bg-ink-200"></span>{{ $profile['description_group'] }}</legend>
                        <label class="mt-4 block">
                            <span class="label-gd">{{ $profile['description_label'] }} <span class="font-normal text-ink-400">(opsional)</span></span>
                            <textarea name="profile_description" id="profile-description" rows="4" maxlength="3000" class="input-gd" placeholder="{{ $profile['description_placeholder'] }}">{{ old('profile_description') }}</textarea>
                            <span class="mt-1.5 flex justify-between gap-4 text-xs text-ink-400">
                                <span class="inline-flex items-center gap-1.5"><svg class="h-3.5 w-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1l2.6 5.6 6.1.7-4.5 4.2 1.2 6L10 14.6 4.6 17.5l1.2-6L1.3 7.3l6.1-.7L10 1z"/></svg>{{ $profile['description_hint'] }}</span>
                                <span><span id="desc-count">0</span>/3000</span>
                            </span>
                        </label>
                    </fieldset>

                    <div class="mt-10 flex flex-col-reverse gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('assessment.index') }}" class="btn-secondary !py-4 sm:!px-7">← Batal</a>
                        <button type="submit" class="btn-primary group flex-1 !py-4 text-base">
                            {{ $profile['submit_label'] ?? 'Lanjut Menilai '.ucfirst($unitLabel) }}
                            <svg class="h-5 w-5 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </div>
                    <p class="mt-4 text-center text-xs text-ink-400">Data hanya dipakai untuk analisa & tindak lanjut tim GODEVI.</p>
                </form>
            </div>

            {{-- Ringkasan biaya --}}
            <aside class="lg:col-span-4">
                <div class="space-y-5 lg:sticky lg:top-28">
                    <div class="overflow-hidden rounded-3xl bg-ink-950 text-white shadow-[0_25px_50px_-12px_rgb(26_26_38/0.35)]">
                        <div class="relative p-7">
                            <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full bg-brand-600/30 blur-2xl"></div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/50">Biaya Analisa</p>
                            <p class="mt-2 font-display text-4xl font-bold">{{ $priceLabel }}</p>
                            <p class="mt-1 text-sm text-white/60">Sekali bayar · per asesmen</p>
                        </div>
                        <ul class="space-y-3.5 border-t border-white/10 p-7 text-sm">
                            @foreach (['Skor kesiapan total & kategori', 'Skor per dimensi '.($showWeight ? 'berbobot' : ''), 'Analisis kekuatan & tantangan', 'Draf strategi prioritas', 'Bisa dibuka ulang kapan saja'] as $benefit)
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-forest-500/20 text-forest-300">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span class="text-white/85">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-ink-100 bg-cream-50 p-6 text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 shrink-0 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            <p class="font-semibold text-ink-900">Pembayaran aman via Midtrans</p>
                        </div>
                        <p class="mt-2 leading-relaxed text-ink-500">Transfer bank, e-wallet, QRIS & kartu. Jawaban tersimpan dulu, bayar setelah selesai menilai.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@section('js')
<style>
    .loc-spinner { position: absolute; right: 1rem; top: 50%; width: 1.15rem; height: 1.15rem; margin-top: -.575rem; border: 2px solid rgb(0 0 0 / .12); border-top-color: currentColor; border-radius: 9999px; color: #15803d; animation: loc-spin .7s linear infinite; }
    .loc-check { position: absolute; right: 1rem; top: 50%; width: 1.25rem; height: 1.25rem; margin-top: -.625rem; color: #15803d; animation: loc-pop .25s ease-out; }
    .loc-results:not(.hidden) { animation: loc-drop .18s ease-out; transform-origin: top; }
    .loc-skeleton { height: .7rem; border-radius: 9999px; background: linear-gradient(90deg, #f1ede4 25%, #faf8f3 50%, #f1ede4 75%); background-size: 200% 100%; animation: loc-shimmer 1.1s linear infinite; }
    .intro-ring { animation: intro-ring 1.6s cubic-bezier(.22,1,.36,1) both .2s; }
    .intro-bar { width: var(--w); animation: intro-bar 1.2s cubic-bezier(.22,1,.36,1) both; }
    @keyframes intro-ring { from { stroke-dasharray: 0 100; } }
    @keyframes intro-bar { from { width: 0; } }
    @keyframes loc-spin { to { transform: rotate(360deg); } }
    @keyframes loc-pop { from { opacity: 0; transform: scale(.5); } to { opacity: 1; transform: scale(1); } }
    @keyframes loc-drop { from { opacity: 0; transform: translateY(-4px) scaleY(.97); } to { opacity: 1; transform: none; } }
    @keyframes loc-shimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }
</style>
<script>
(function () {
    const input = document.getElementById('location-search');
    const list = document.getElementById('location-results');
    const fields = {
        province: document.getElementById('location-province'),
        city: document.getElementById('location-city'),
        district: document.getElementById('location-district'),
        subdistrict: document.getElementById('location-subdistrict'),
    };
    const spinner = document.getElementById('location-spinner');
    const check = document.getElementById('location-check');
    const endpoint = @json(route('location.search'));
    let timer = null;
    let controller = null;

    const titleCase = (str) => (str || '').toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
    const hide = () => list.classList.add('hidden');
    const message = (text) => {
        list.innerHTML = '';
        const li = document.createElement('li');
        li.className = 'px-4 py-3 text-ink-500';
        li.textContent = text;
        list.appendChild(li);
        list.classList.remove('hidden');
    };

    const loading = (on) => {
        spinner.hidden = !on;
        if (on) check.hidden = true;
    };
    const skeleton = () => {
        list.innerHTML = '';
        for (let i = 0; i < 3; i++) {
            const li = document.createElement('li');
            li.className = 'space-y-2 px-4 py-3';
            li.innerHTML = '<div class="loc-skeleton" style="width:' + (55 + i * 12) + '%"></div><div class="loc-skeleton" style="width:' + (35 + i * 8) + '%;height:.55rem"></div>';
            list.appendChild(li);
        }
        list.classList.remove('hidden');
    };
    const clearSelection = () => {
        Object.values(fields).forEach((f) => { f.value = ''; });
        check.hidden = true;
    };

    const render = (items) => {
        if (!items.length) return message('Lokasi tidak ditemukan. Coba kata kunci lain.');
        list.innerHTML = '';
        items.forEach((item) => {
            const li = document.createElement('li');
            li.className = 'cursor-pointer px-4 py-3 hover:bg-cream-50';
            const main = document.createElement('div');
            main.className = 'font-semibold text-ink-900';
            main.textContent = titleCase(item.city) + ', ' + titleCase(item.province);
            const sub = document.createElement('div');
            sub.className = 'text-xs text-ink-500';
            sub.textContent = [item.subdistrict, item.district].filter(Boolean).map(titleCase).join(', ');
            li.append(main, sub);
            li.addEventListener('mousedown', (e) => {
                e.preventDefault();
                fields.province.value = titleCase(item.province);
                fields.city.value = titleCase(item.city);
                fields.district.value = titleCase(item.district);
                fields.subdistrict.value = titleCase(item.subdistrict);
                input.value = [item.subdistrict, item.district, item.city, item.province].filter(Boolean).map(titleCase).join(', ');
                check.hidden = false;
                hide();
            });
            list.appendChild(li);
        });
        list.classList.remove('hidden');
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        clearSelection();
        if (controller) controller.abort();
        const q = input.value.trim();
        if (q.length < 3) {
            loading(false);
            return hide();
        }
        loading(true);
        skeleton();
        timer = setTimeout(() => {
            controller = new AbortController();
            fetch(endpoint + '?q=' + encodeURIComponent(q), { signal: controller.signal, headers: { Accept: 'application/json' } })
                .then((r) => r.json())
                .then((res) => { loading(false); render(res.data || []); })
                .catch((err) => {
                    if (err.name === 'AbortError') return;
                    loading(false);
                    message('Gagal memuat lokasi. Coba lagi.');
                });
        }, 350);
    });
    input.addEventListener('blur', hide);
    input.addEventListener('keydown', (e) => { if (e.key === 'Enter') e.preventDefault(); });

    // Provinsi & kab/kota disimpan di input hidden, jadi pastikan lokasi sudah dipilih sebelum submit.
    input.form.addEventListener('submit', (e) => {
        if (!fields.province.value || !fields.city.value) {
            e.preventDefault();
            input.focus();
            message('Pilih lokasi dari hasil pencarian terlebih dahulu.');
        }
    });

    const desc = document.getElementById('profile-description');
    const descCount = document.getElementById('desc-count');
    const countDesc = () => { descCount.textContent = desc.value.length; };
    desc.addEventListener('input', countDesc);
    countDesc();

    if (fields.city.value) {
        input.value = [fields.subdistrict.value, fields.district.value, fields.city.value, fields.province.value].filter(Boolean).join(', ');
        check.hidden = false;
    }
})();
</script>
@endsection
