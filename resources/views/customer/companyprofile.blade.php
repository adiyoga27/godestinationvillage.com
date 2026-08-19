@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Company Profile"
    subtitle="GODEVI (PT Banua Wisata Lestari) — dedicated to socially responsible and sustainable village tourism in Bali."
    image="assets/customer/img/page-title-area/explorer.jpg"
    :crumbs="['Home' => '/', 'Company Profile' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="overflow-hidden rounded-3xl border border-ink-100 shadow-lift">
            <iframe
                src="https://online.fliphtml5.com/zaznz/zyys/"
                class="aspect-[3/4] w-full sm:aspect-video"
                frameborder="0"
                allowfullscreen
                loading="lazy"
                title="GODEVI Company Profile"></iframe>
        </div>
        <div class="mx-auto mt-10 max-w-3xl text-center">
            <h2 class="font-display text-2xl font-bold text-ink-950 sm:text-3xl">GODEVI — Go Destination Village</h2>
            <p class="mt-4 leading-relaxed text-ink-600">GODEVI is a socially pro-active business dedicated to uplifting local communities in developing villages through the tourism industry. Besides supporting fair trade, we create a marketplace by empowering village communities. GODEVI adheres to a strict policy of promoting Socially Responsible Village Tourism activities.</p>
            <a href="{{ url('v-founding') }}" class="btn btn-secondary mt-8">Read Our Founding Story</a>
        </div>
    </div>
</section>
@endsection