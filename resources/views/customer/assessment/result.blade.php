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
            <p class="mt-4 text-xs text-ink-400">Kode hasil: <code>{{ $result->uuid }}</code> · {{ $result->created_at }}</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <button type="button" onclick="window.print()" class="btn btn-secondary">Cetak / Simpan PDF</button>
                <a href="{{ route('assessment.index') }}" class="btn btn-primary">Coba Jalur Lain</a>
            </div>
        </div>

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
