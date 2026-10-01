@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Hasil Asesmen: {{ $result->track->name }}"
    subtitle="Skor kesiapan, analisis kekuatan & tantangan, dan draf strategi untuk {{ $result->organization ?? $result->name }}."
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="['Home' => '/', 'Asesmen' => '/asesmen', 'Hasil' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        <div class="card overflow-hidden p-8 text-center sm:p-10">
            <p class="eyebrow justify-center">{{ $result->track->tagline }}</p>
            <div class="mx-auto mt-6 flex h-44 w-44 items-center justify-center rounded-full border-[10px] border-brand-100 bg-white">
                <div>
                    <p class="font-display text-5xl font-bold text-brand-700">{{ number_format($computed['total'], 0) }}</p>
                    <p class="text-xs font-semibold text-ink-500">dari 100</p>
                </div>
            </div>
            <h2 class="mt-6 font-display text-3xl font-bold text-ink-950">{{ $computed['band'] }}</h2>
            <p class="mx-auto mt-3 max-w-xl text-ink-600">{{ $computed['band_desc'] }}</p>
            @if ($result->track->slug === 'daya-saing-destinasi')
                <p class="mx-auto mt-4 max-w-xl rounded-2xl border border-amber-200 bg-amber-50 p-4 text-left text-xs leading-relaxed text-amber-900">{{ \App\Services\AssessmentService::TTDI_DISCLAIMER }}</p>
            @endif
            <p class="mt-4 text-xs text-ink-400">Kode hasil: <code>{{ $result->uuid }}</code> · {{ $result->created_at }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak / Simpan PDF</button>
                <a href="{{ route('assessment.index') }}" class="btn btn-primary">Coba Jalur Lain</a>
            </div>
        </div>

        @if (session('status'))
            <div class="card mt-8 border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="card mt-8 border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        {{-- Paywall laporan lengkap (Brief §4–§5): skor gratis, laporan berbayar --}}
        @if (! $result->is_unlocked)
            <div class="card mt-8 overflow-hidden p-8 sm:p-10">
                <div class="flex items-start gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                    </span>
                    <div class="flex-1">
                        <h3 class="font-display text-xl font-bold text-ink-950">Laporan Strategi Lengkap Terkunci</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink-600">Skor di atas gratis. Laporan lengkap — ringkasan eksekutif, analisis kekuatan & tantangan, 5 langkah prioritas, segmen pasar, dan strategi pemasaran yang disusun khusus untuk {{ $result->organization ?? $result->name }} — terbuka setelah pembayaran <strong class="text-ink-900">Rp {{ number_format($result->track->price ?? 199000, 0, ',', '.') }}</strong> (QRIS, Virtual Account, e-wallet via Midtrans).</p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            @if (! empty($pendingOrder))
                                <a href="{{ route('assessment.payment', $pendingOrder->code) }}" class="btn btn-primary">Lanjutkan Pembayaran</a>
                            @else
                                <form action="{{ route('assessment.checkout', $result->uuid) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Buka Laporan — Bayar Rp {{ number_format($result->track->price ?? 199000, 0, ',', '.') }}</button>
                                </form>
                            @endif
                        </div>
                        <p class="mt-3 text-xs text-ink-400">Satu pembayaran = satu laporan. Status pembayaran diverifikasi otomatis.</p>
                    </div>
                </div>
            </div>
        @else
            <div id="laporan" class="card mt-8 p-8 sm:p-10">
                <h3 class="font-display text-xl font-bold text-ink-950">Laporan Strategi Lengkap</h3>
                @if (! empty($result->ai_report))
                    @php $rep = $result->ai_report; @endphp
                    <p class="mt-1 text-sm text-ink-500">Disusun khusus untuk {{ $result->organization ?? $result->name }} berdasarkan skor asesmen Anda.</p>
                    <div class="mt-5 rounded-2xl bg-cream-50 p-5 text-sm leading-relaxed text-ink-700">{{ $rep['ringkasan'] ?? '' }}</div>
                    <div class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <h4 class="font-bold text-green-800">Kekuatan Utama</h4>
                            <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">
                                @foreach ((array) ($rep['kekuatan'] ?? []) as $s)<li>{{ $s }}</li>@endforeach
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-700">Tantangan Utama</h4>
                            <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">
                                @foreach ((array) ($rep['tantangan'] ?? []) as $c)<li>{{ $c }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="font-bold text-ink-950">5 Langkah Prioritas</h4>
                        <ol class="mt-2 list-decimal space-y-2 pl-6 text-sm text-ink-700">
                            @foreach ((array) ($rep['langkah_prioritas'] ?? []) as $s)<li>{{ $s }}</li>@endforeach
                        </ol>
                    </div>
                    @if (! empty($rep['segmen_pasar']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Segmen Pasar</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['segmen_pasar'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['strategi_pemasaran']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Strategi Pemasaran</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['strategi_pemasaran'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['opsi_branding']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Opsi Branding</h4>
                        <div class="mt-2 grid gap-4 md:grid-cols-3">@foreach ((array) $rep['opsi_branding'] as $b)
                            <div class="rounded-2xl bg-cream-50 p-4 text-sm"><p class="font-bold text-ink-900">{{ is_array($b) ? ($b['nama'] ?? '') : $b }}</p>
                            @if (is_array($b))<p class="mt-1 italic text-ink-600">"{{ $b['tagline'] ?? '' }}"</p><p class="mt-1 text-ink-600">{{ $b['alasan'] ?? '' }}</p>@endif</div>
                        @endforeach</div></div>
                    @endif
                    @if (! empty($rep['produk_unggulan_potensial']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Produk Unggulan Potensial</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['produk_unggulan_potensial'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['model_bisnis_disarankan']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Model Bisnis Disarankan</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['model_bisnis_disarankan'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['catatan_kategori']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Catatan per Subindeks</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['catatan_kategori'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['prioritas_kebijakan']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Prioritas Kebijakan</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['prioritas_kebijakan'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['catatan_ttdi']))
                        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-relaxed text-amber-900">{{ $rep['catatan_ttdi'] }}</div>
                    @endif
                    @if (! empty($rep['posisi_spektrum']))
                        <div class="mt-6 rounded-2xl bg-forest-50 p-5 text-sm text-ink-700"><span class="font-bold text-ink-950">Posisi Spektrum: </span>{{ $rep['posisi_spektrum'] }}</div>
                    @endif
                    @if (! empty($rep['rekomendasi_regeneratif']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Rekomendasi Regeneratif</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['rekomendasi_regeneratif'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['sertifikasi_relevan']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Sertifikasi Relevan</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['sertifikasi_relevan'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                    @if (! empty($rep['layanan_godevi_disarankan']))
                        <div class="mt-6"><h4 class="font-bold text-ink-950">Layanan GODEVI yang Disarankan</h4>
                        <ul class="mt-2 list-disc space-y-2 pl-5 text-sm text-ink-700">@foreach ((array) $rep['layanan_godevi_disarankan'] as $s)<li>{{ $s }}</li>@endforeach</ul></div>
                    @endif
                @elseif (($result->report_status ?? '') === 'failed')
                    <p class="mt-2 rounded-2xl bg-red-50 p-4 text-sm text-red-700">Pembuatan laporan gagal ({{ $result->report_error }}). Tim kami akan menindaklanjuti — atau hubungi kami dengan menyebut kode hasil di atas.</p>
                @else
                    <p class="mt-2 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900">Laporan lengkap sedang disiapkan. Muat ulang halaman ini dalam beberapa saat.</p>
                @endif
            </div>
        @endif

        <div class="card mt-8 p-8 sm:p-10">
            <h3 class="font-display text-xl font-bold text-ink-950">Skor per Dimensi</h3>
            <div class="mt-6 space-y-5">
                @foreach ($computed['dimensions'] as $name => $dim)
                    <div>
                        <div class="flex items-center justify-between gap-4 text-sm">
                            <span class="font-semibold text-ink-900">{{ $name }}</span>
                            <span class="shrink-0 font-bold {{ $dim['score'] >= 60 ? 'text-green-700' : ($dim['score'] >= 40 ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($dim['score'], 0) }}/100</span>
                        </div>
                        <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-cream-100">
                            <div class="h-full rounded-full {{ $dim['score'] >= 60 ? 'bg-green-600' : ($dim['score'] >= 40 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $dim['score'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($result->track->slug === 'daya-saing-destinasi' && ! empty($computed['subindexes']))
            <div class="card mt-8 p-8 sm:p-10">
                <h3 class="font-display text-xl font-bold text-ink-950">Subskor per Subindeks</h3>
                <p class="mt-1 text-sm text-ink-500">Rata-rata pilar dalam tiap subindeks (0–100).</p>
                <div class="mt-6 space-y-5">
                    @foreach ($computed['subindexes'] as $sub => $score)
                        <div>
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="font-semibold text-ink-900">{{ $sub }}</span>
                                <span class="shrink-0 font-bold">{{ number_format($score, 0) }}/100</span>
                            </div>
                            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-cream-100">
                                <div class="h-full rounded-full bg-brand-600" style="width: {{ $score }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 grid gap-7 md:grid-cols-2">
            <div class="card border-green-200 p-8">
                <h3 class="font-display text-xl font-bold text-green-800">Kekuatan</h3>
                <p class="mt-1 text-sm text-ink-500">Dimensi dengan skor tertinggi — jadikan modal promosi & kemitraan.</p>
                <ul class="mt-4 space-y-3">
                    @foreach ($computed['strengths'] as $name)
                        <li class="rounded-xl bg-green-50 p-4">
                            <p class="font-bold text-ink-900">{{ $name }} — {{ number_format($computed['dimensions'][$name]['score'], 0) }}/100</p>
                            <p class="mt-1 text-sm text-ink-600">{{ $computed['dimensions'][$name]['advice'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card border-red-200 p-8">
                <h3 class="font-display text-xl font-bold text-red-700">Tantangan</h3>
                <p class="mt-1 text-sm text-ink-500">Dimensi dengan skor terendah — fokus perbaikan 3 bulan ke depan.</p>
                <ul class="mt-4 space-y-3">
                    @foreach ($computed['challenges'] as $name)
                        <li class="rounded-xl bg-red-50 p-4">
                            <p class="font-bold text-ink-900">{{ $name }} — {{ number_format($computed['dimensions'][$name]['score'], 0) }}/100</p>
                            <p class="mt-1 text-sm text-ink-600">{{ $computed['dimensions'][$name]['advice'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="card mt-8 p-8 sm:p-10">
            <h3 class="font-display text-xl font-bold text-ink-950">Draf Strategi Prioritas</h3>
            <p class="mt-1 text-sm text-ink-500">Disusun otomatis dari jawaban dengan skor terendah + saran per dimensi.</p>
            @if (count($computed['priority_actions']) > 0)
                <ol class="mt-5 list-decimal space-y-3 pl-6 text-ink-700">
                    @foreach ($computed['priority_actions'] as $item)
                        <li><strong>{{ $item['dimension'] }}:</strong> {{ $item['action'] }}</li>
                    @endforeach
                </ol>
            @else
                <p class="mt-5 rounded-xl bg-green-50 p-4 text-sm text-ink-700">Tidak ada pernyataan bernilai rendah. Pertahankan ritme dan naikkan target ke level berikutnya (sertifikasi, ekspansi pasar, kemitraan).</p>
            @endif
            <div class="mt-6 rounded-2xl bg-ink-950 p-6 text-white sm:p-8">
                <h4 class="font-display text-lg font-bold">Ingin didampingi menyusun strategi final?</h4>
                <p class="mt-2 text-sm text-white/70">Tim GODEVI mendampingi desa, UMKM/koperasi, dan pemda dari hasil asesmen hingga aksi. Hubungi kami dengan menyebut kode hasil di atas.</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ url('contact') }}" class="btn btn-white">Hubungi Tim GODEVI</a>
                    <a href="{{ url('services') }}" class="btn border border-white/40 text-white hover:bg-white/10">Lihat Layanan</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
