@extends('customer/layout')

@section('content')

@php
    $order = $result->latestOrder;
    $amount = $pendingOrder->amount ?? $result->track->price;
    $closed = in_array($order?->status, ['expired', 'failed'], true);
    $formConfig = \App\Services\AssessmentService::formFor($result->track);
    $dimensionNames = array_keys($result->dimension_scores ?? []);
    $answered = collect($result->dimension_scores ?? [])->sum(fn ($d) => count($d['questions'] ?? []));
@endphp

@include('customer.assessment._hero', [
    'title' => 'Satu langkah lagi untuk membuka hasil',
    'subtitle' => 'Jawaban asesmen '.$result->organization.' sudah tersimpan dengan aman. Selesaikan pembayaran untuk membuka skor dan analisa lengkap.',
    'eyebrow' => 'Asesmen '.$result->track->name,
    'crumbs' => ['Asesmen' => route('assessment.index'), 'Pembayaran' => null],
    'image' => $formConfig['hero_image'] ?? null,
    'overlap' => true,
])

<section class="relative flow-root bg-cream-50 pb-20 sm:pb-28">
    <div class="container-gd max-w-6xl">
        <div class="relative z-10 -mt-20 grid gap-6 lg:grid-cols-12">
            {{-- Ringkasan & pratinjau --}}
            <div class="space-y-6 lg:col-span-7">
                @if (session('error'))
                    <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">{{ session('error') }}</div>
                @endif
                <div class="card p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-forest-50 text-forest-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <p class="font-bold text-ink-950">Jawaban tersimpan</p>
                                <p class="text-sm text-ink-500">{{ $answered }} penilaian · {{ $result->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-bold {{ $closed ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $closed ? 'bg-red-500' : 'animate-pulse bg-amber-500' }}"></span>{{ $result->paymentLabel() }}
                        </span>
                    </div>

                    <dl class="mt-6 grid gap-px overflow-hidden rounded-2xl bg-ink-100 text-sm sm:grid-cols-2">
                        @foreach ([
                            'Nama Desa / DTW' => $result->organization,
                            'Lokasi' => collect([$result->regency, $result->province])->filter()->implode(', ') ?: '-',
                            'Kontak' => $result->name,
                            'No. WhatsApp' => $result->phone,
                        ] as $label => $value)
                            <div class="bg-white p-4">
                                <dt class="text-xs font-semibold uppercase tracking-wider text-ink-400">{{ $label }}</dt>
                                <dd class="mt-1 font-semibold text-ink-900">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Pratinjau laporan terkunci --}}
                <div class="card relative p-6 sm:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-ink-400">Isi laporan Anda</p>
                    <div class="mt-5 space-y-4 select-none blur-[5px]" aria-hidden="true">
                        <div class="flex items-center gap-5">
                            <div class="flex h-24 w-24 items-center justify-center rounded-full border-[8px] border-brand-100 font-display text-3xl font-bold text-brand-700">??</div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 w-1/3 rounded-full bg-ink-200"></div>
                                <div class="h-3 w-3/4 rounded-full bg-ink-100"></div>
                                <div class="h-3 w-2/3 rounded-full bg-ink-100"></div>
                            </div>
                        </div>
                        @foreach (array_slice($dimensionNames, 0, 4) as $i => $name)
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-ink-600"><span>{{ $name }}</span><span>00</span></div>
                                <div class="mt-1.5 h-2 rounded-full bg-cream-100"><div class="h-full rounded-full {{ $i % 2 ? 'bg-amber-400' : 'bg-forest-500' }}" style="width: {{ [78, 52, 66, 44][$i] }}%"></div></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center rounded-3xl bg-white/40 p-6 text-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-ink-950 text-white shadow-xl">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        </span>
                        <p class="mt-4 font-display text-xl font-bold text-ink-950">Hasil terkunci</p>
                        <p class="mt-1 max-w-sm text-sm text-ink-600">Skor, analisis kekuatan & tantangan, serta draf strategi terbuka otomatis setelah pembayaran berhasil.</p>
                    </div>
                </div>
            </div>

            {{-- Kartu pembayaran --}}
            <aside class="lg:col-span-5">
                <div class="space-y-5 lg:sticky lg:top-28">
                    <div class="overflow-hidden rounded-3xl bg-ink-950 text-white shadow-[0_25px_50px_-12px_rgb(26_26_38/0.4)]">
                        <div class="relative p-7 sm:p-8">
                            <div aria-hidden="true" class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-brand-600/35 blur-3xl"></div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/50">Total Pembayaran</p>
                            <p class="mt-2 font-display text-4xl font-bold sm:text-5xl">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                            <p class="mt-2 text-sm text-white/60">Asesmen {{ $result->track->name }}{{ $order ? ' · '.$order->code : '' }}</p>

                            @php $cardIcon = '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>'; @endphp
                            @if ($pendingOrder)
                                <a href="{{ route('assessment.payment', $pendingOrder->code) }}" class="btn-primary mt-7 w-full !py-4 text-base">{!! $cardIcon !!} Lanjutkan Pembayaran</a>
                            @else
                                <form action="{{ route('assessment.checkout', $result->uuid) }}" method="post" class="mt-7">
                                    @csrf
                                    <button type="submit" class="btn-primary w-full !py-4 text-base">{!! $cardIcon !!} {{ $closed ? 'Bayar Ulang' : 'Bayar Sekarang' }}</button>
                                </form>
                            @endif
                            @if ($closed)
                                <p class="mt-3 text-center text-xs text-white/50">Tagihan sebelumnya {{ strtolower($result->paymentLabel()) }} — invoice baru akan dibuat.</p>
                            @endif
                        </div>
                        <ul class="space-y-3 border-t border-white/10 p-7 text-sm sm:p-8">
                            @foreach (['Skor kesiapan total & kategori', 'Skor per dimensi & catatan Anda', 'Analisis kekuatan & tantangan', 'Draf strategi prioritas', 'Bisa dibuka ulang kapan saja'] as $benefit)
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-forest-500/20 text-forest-300">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span class="text-white/85">{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-ink-100 bg-white p-6 text-sm">
                        <div class="flex items-center gap-3">
                            <svg class="h-6 w-6 shrink-0 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            <p class="font-semibold text-ink-900">Pembayaran aman via Midtrans</p>
                        </div>
                        <p class="mt-2 leading-relaxed text-ink-500">Transfer bank / VA, e-wallet, QRIS & kartu kredit.</p>
                        <div class="mt-4 rounded-2xl bg-cream-50 p-4 leading-relaxed text-ink-600">
                            Sudah bayar tapi hasil belum terbuka? <button type="button" onclick="window.location.reload()" class="font-semibold text-brand-600 underline">Muat ulang</button> dalam beberapa saat. Buka lagi kapan saja lewat
                            <a href="{{ route('assessment.status', ['phone' => $result->phone]) }}" class="font-semibold text-brand-600 underline">Cek Status Asesmen</a>.
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
