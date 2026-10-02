@extends('customer/layout')

@section('content')

@include('customer.assessment._hero', [
    'title' => 'Cek Status Asesmen',
    'subtitle' => 'Lihat status pembayaran dan buka kembali hasil asesmen sebelumnya dengan No. WhatsApp yang didaftarkan.',
    'eyebrow' => 'Riwayat Asesmen',
    'crumbs' => ['Asesmen' => route('assessment.index'), 'Cek Status' => null],
    'overlap' => true,
])

<section class="relative flow-root bg-cream-50 pb-20 sm:pb-28">
    <div class="container-gd max-w-4xl">
        <form action="{{ route('assessment.status') }}" method="get" class="relative z-10 -mt-20 rounded-3xl border border-ink-100 bg-white p-5 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.22)] sm:p-7">
            <label for="phone" class="label-gd">No. WhatsApp / Telepon</label>
            <div class="flex flex-col gap-3 sm:flex-row">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                    <input id="phone" type="tel" name="phone" value="{{ $phone }}" required minlength="8" class="input-gd !py-4 !pl-12 text-base" placeholder="08xxxxxxxxxx">
                </div>
                <button type="submit" class="btn-primary shrink-0 !px-8 !py-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    Cek Status
                </button>
            </div>
        </form>

        @if (! is_null($results))
            <div class="mt-10">
                @if ($results->isNotEmpty())
                    <p class="mb-4 text-sm font-semibold text-ink-500">{{ $results->count() }} asesmen ditemukan</p>
                @endif
                <div class="space-y-4">
                    @forelse ($results as $r)
                        <a href="{{ route('assessment.result', $r->uuid) }}" data-vue="Reveal" style="--reveal-delay: {{ min($loop->index, 5) * 80 }}ms" class="group flex flex-col gap-5 rounded-3xl border border-ink-100 bg-white p-5 shadow-[0_10px_30px_-12px_rgb(26_26_38/0.12)] transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-[0_25px_50px_-12px_rgb(26_26_38/0.2)] sm:flex-row sm:items-center sm:p-6">
                            @if ($r->is_unlocked)
                                @php $score = (int) round($r->total_score); @endphp
                                <div class="relative h-20 w-20 shrink-0">
                                    <svg viewBox="0 0 36 36" class="h-20 w-20 -rotate-90">
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f0faf4" stroke-width="3.5"/>
                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="{{ $score >= 60 ? '#379868' : ($score >= 40 ? '#d97706' : '#dc2626') }}" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="{{ $score }} 100"/>
                                    </svg>
                                    <span class="absolute inset-0 flex items-center justify-center font-display text-xl font-bold text-ink-950">{{ $score }}</span>
                                </div>
                            @else
                                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-ink-400">Asesmen {{ $r->track->name }}</p>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $r->is_unlocked ? 'bg-forest-50 text-forest-700' : 'bg-amber-50 text-amber-700' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $r->is_unlocked ? 'bg-forest-500' : 'animate-pulse bg-amber-500' }}"></span>{{ $r->paymentLabel() }}
                                    </span>
                                </div>
                                <h3 class="mt-1 truncate font-display text-xl font-bold text-ink-950">{{ $r->organization ?: $r->name }}</h3>
                                <p class="mt-1 text-sm text-ink-500">{{ collect([$r->regency, $r->province])->filter()->implode(', ') }}{{ $r->regency ? ' · ' : '' }}{{ $r->created_at->format('d M Y') }}</p>
                                <p class="mt-2 text-sm text-ink-700">
                                    @if ($r->is_unlocked)
                                        Kategori <strong>{{ $r->band }}</strong>
                                    @else
                                        Terbuka setelah pembayaran <strong>Rp {{ number_format($r->latestOrder->amount ?? $r->track->price, 0, ',', '.') }}</strong>
                                    @endif
                                </p>
                            </div>

                            <span class="inline-flex shrink-0 items-center gap-1.5 self-start rounded-full px-4 py-2 text-sm font-semibold transition sm:self-center {{ $r->is_unlocked ? 'bg-ink-950 text-white group-hover:bg-ink-800' : 'bg-brand-600 text-white group-hover:bg-brand-700' }}">
                                {{ $r->is_unlocked ? 'Lihat hasil' : 'Bayar sekarang' }}
                                <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </a>
                    @empty
                        <div class="rounded-3xl border border-dashed border-ink-200 bg-white p-10 text-center">
                            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-cream-100 text-ink-400">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </span>
                            <p class="mt-4 font-display text-xl font-bold text-ink-950">Belum ada asesmen</p>
                            <p class="mt-1 text-sm text-ink-500">Tidak ditemukan asesmen dengan nomor ini. Pastikan nomor sama dengan yang diisi saat asesmen.</p>
                            <a href="{{ route('assessment.index') }}" class="btn-primary mt-6">Mulai Asesmen</a>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="mt-10 grid gap-4 text-sm sm:grid-cols-3">
                @foreach ([
                    ['Status pembayaran', 'Lihat apakah tagihan sudah lunas atau masih menunggu.'],
                    ['Buka hasil lagi', 'Akses skor & strategi kapan saja tanpa login.'],
                    ['Semua riwayat', 'Daftar seluruh asesmen yang pernah diisi.'],
                ] as [$t, $d])
                    <div class="rounded-3xl border border-ink-100 bg-white p-6">
                        <p class="font-bold text-ink-950">{{ $t }}</p>
                        <p class="mt-1 leading-relaxed text-ink-500">{{ $d }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
