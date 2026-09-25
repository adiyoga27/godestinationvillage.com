@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="asesmen"
    title="Pilih Jenis Asesmen"
    subtitle="Setiap jalur menghasilkan skor kesiapan, analisis kekuatan/tantangan, dan draf strategi yang disesuaikan dengan kebutuhan spesifikmu."
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="['Home' => '/', 'Asesmen' => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="mx-auto mb-12 max-w-2xl text-center">
            <p class="eyebrow justify-center">Asesmen Kesiapan GODEVI</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">Mulai dari jalur yang paling sesuai</h2>
            <p class="mt-4 text-ink-600">Gratis, tanpa login, ±10–15 menit. Hasil langsung tampil beserta draf strategi.</p>
        </div>

        <div class="grid gap-7 md:grid-cols-2">
            @foreach ($tracks as $track)
                <article data-vue="Reveal" class="card card-hover flex flex-col p-8 sm:p-10">
                    <p class="eyebrow">{{ $track->tagline }}</p>
                    <h3 class="mt-3 font-display text-2xl font-bold text-ink-950">{{ $track->name }}</h3>
                    <p class="mt-3 flex-1 leading-relaxed text-ink-600">{{ $track->description }}</p>
                    <div class="mt-5 flex flex-wrap gap-2 text-xs font-semibold">
                        <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">Untuk: {{ $track->target_audience }}</span>
                        <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">±{{ $track->estimated_minutes }} menit</span>
                        <span class="rounded-full bg-cream-100 px-3 py-1.5 text-ink-700">{{ $track->questions_count }} pernyataan</span>
                    </div>
                    <div class="mt-7">
                        <a href="{{ route('assessment.intro', $track->slug) }}" class="btn btn-primary w-full !py-4">Mulai Asesmen {{ $track->name }}</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
