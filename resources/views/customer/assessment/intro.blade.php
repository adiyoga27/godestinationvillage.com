@extends('customer/layout')

@section('content')

{{-- Hero ringkas bernomor jalur --}}
<section class="relative overflow-hidden bg-ink-950">
    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-brand-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 26px 26px;"></div>
    <div class="container-gd relative z-10 py-14 sm:py-16">
        <nav aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-white/50">
                <li><a href="{{ url('/') }}" class="transition hover:text-white">Beranda</a></li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <a href="{{ route('assessment.index') }}" class="transition hover:text-white">Asesmen</a>
                </li>
                <li class="flex items-center gap-1.5">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    <span class="text-brand-400" aria-current="page">{{ $track->name }}</span>
                </li>
            </ol>
        </nav>
        <div class="mt-6 max-w-3xl" data-vue="Reveal">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-400">{{ $track->tagline }}</p>
            <h1 class="mt-3 font-display text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">Asesmen: {{ $track->name }}</h1>
            <p class="mt-4 max-w-2xl leading-relaxed text-white/70">{{ $track->description }}</p>
            <div class="mt-6 flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-white/80">{{ $track->target_audience }}</span>
                <span class="rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-white/80">±{{ $track->estimated_minutes }} menit · {{ $track->questions_count }} pernyataan</span>
                <span class="rounded-full bg-white px-3.5 py-1.5 text-ink-950">Skor gratis · Laporan Rp {{ number_format($track->price ?? 199000, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</section>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-5xl">
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
            {{-- Cara mengisi --}}
            <div data-vue="Reveal" class="h-fit rounded-3xl bg-ink-950 p-7 text-white sm:p-8 lg:sticky lg:top-28">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-brand-400">Sebelum mulai</p>
                <h2 class="mt-2 font-display text-2xl font-bold">Cara mengisi yang jujur</h2>
                <ol class="mt-6 space-y-5">
                    @foreach ([
                        ['Nilai apa adanya', '1 = Belum ada, sampai 5 = Sangat matang. Jangan menilai harapan, nilai kenyataan hari ini.'],
                        ['Lengkapi semua soal', 'Semua pernyataan wajib dijawab agar skor dan strategi akurat.'],
                        ['Terima skor gratis', 'Skor, kekuatan, dan tantangan langsung tampil setelah kirim.'],
                        ['Buka laporan bila perlu', 'Strategi lengkap per jalur dibuka dengan satu pembayaran.'],
                    ] as $i => $s)
                        <li class="flex gap-4">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/10 font-display text-sm font-bold text-brand-400">{{ $i + 1 }}</span>
                            <span>
                                <span class="block text-sm font-bold">{{ $s[0] }}</span>
                                <span class="mt-0.5 block text-sm leading-relaxed text-white/60">{{ $s[1] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
                @if ($track->slug === 'daya-saing-destinasi')
                    <div class="mt-6 rounded-2xl border border-amber-400/30 bg-amber-400/10 p-4 text-xs leading-relaxed text-amber-200">
                        <p class="font-bold text-amber-300">Catatan metode</p>
                        <p class="mt-1">{{ \App\Services\AssessmentService::TTDI_DISCLAIMER }}</p>
                    </div>
                @endif
            </div>

            {{-- Form identitas --}}
            <form action="{{ route('assessment.start', $track->slug) }}" method="post" data-vue="Reveal" data-props='{"delay":120}' class="rounded-3xl border border-ink-100 bg-white p-7 shadow-soft sm:p-9">
                @csrf
                <h2 class="font-display text-2xl font-bold text-ink-950">Identitas pengisi</h2>
                <p class="mt-1 text-sm text-ink-500">Hanya untuk mencantumkan nama pada hasil dan keperluan tindak lanjut.</p>
                <div class="mt-6 space-y-5">
                    <label class="block">
                        <span class="label-gd">Nama lengkap *</span>
                        <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name" class="input-gd" placeholder="Nama Anda">
                    </label>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="label-gd">Email *</span>
                            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="input-gd" placeholder="nama@email.com">
                        </label>
                        <label class="block">
                            <span class="label-gd">No. telepon / WA</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" class="input-gd" placeholder="08xx…">
                        </label>
                    </div>
                    <label class="block">
                        <span class="label-gd">Organisasi / Desa / Usaha</span>
                        <input type="text" name="organization" value="{{ old('organization') }}" class="input-gd" placeholder="cth: Desa Penglipuran / Kopdes Merah Putih">
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl bg-cream-50 p-4 text-xs leading-relaxed text-ink-600">
                        <input type="checkbox" required class="mt-0.5 h-4 w-4 shrink-0 accent-brand-600">
                        <span>Saya setuju data profil ini digunakan untuk keperluan asesmen dan tindak lanjut GODEVI sesuai kebijakan privasi. *</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary mt-7 w-full !py-4">
                    Lanjut ke {{ $track->questions_count }} pernyataan
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
