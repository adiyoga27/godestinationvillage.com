@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Asesmen: {{ $track->name }}"
    subtitle="{{ $track->tagline }}"
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="['Home' => '/', 'Asesmen' => '/asesmen', $track->name => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-3xl">
        @if (session('error'))
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="card mb-6 border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card p-8 sm:p-10">
            <p class="eyebrow">{{ $track->tagline }}</p>
            <h2 class="mt-3 font-display text-2xl font-bold text-ink-950">{{ $track->name }}</h2>
            <p class="mt-3 leading-relaxed text-ink-600">{{ $track->description }}</p>
            <div class="mt-5 flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">Untuk: {{ $track->target_audience }}</span>
                <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">±{{ $track->estimated_minutes }} menit</span>
                <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">{{ $track->questions_count }} pernyataan</span>
            </div>
            <div class="mt-6 rounded-2xl bg-cream-50 p-5 text-sm leading-relaxed text-ink-600">
                <p class="font-bold text-ink-900">Cara mengisi</p>
                <p class="mt-1">Nilai setiap pernyataan dengan skala 1 (sangat belum siap) sampai 5 (sangat siap) sesuai kondisi nyata saat ini. Hasil berupa <strong>skor kesiapan</strong>, <strong>analisis kekuatan & tantangan</strong>, dan <strong>draf strategi</strong>.</p>
            </div>
        </div>

        <form action="{{ route('assessment.start', $track->slug) }}" method="post" class="card mt-8 space-y-5 p-8 sm:p-10">
            @csrf
            <h3 class="font-display text-xl font-bold text-ink-950">Identitas Pengisi</h3>
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block"><span class="label-gd">Nama Lengkap *</span><input type="text" name="name" value="{{ old('name') }}" required class="input-gd"></label>
                <label class="block"><span class="label-gd">Email *</span><input type="email" name="email" value="{{ old('email') }}" required class="input-gd"></label>
                <label class="block"><span class="label-gd">No. Telepon / WA</span><input type="text" name="phone" value="{{ old('phone') }}" class="input-gd"></label>
                <label class="block"><span class="label-gd">Organisasi / Desa / Usaha</span><input type="text" name="organization" value="{{ old('organization') }}" class="input-gd" placeholder="cth: Desa Penglipuran / Kopdes Merah Putih"></label>
            </div>
            <button type="submit" class="btn btn-primary w-full !py-4">Lanjut ke Pernyataan →</button>
        </form>
    </div>
</section>
@endsection
