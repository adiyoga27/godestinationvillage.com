@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Our Services"
    subtitle="From tourism planning to destination branding — we help villages thrive through responsible tourism."
    image="assets/customer/img/page-title-area/services.jpg"
    :crumbs="['Home' => '/', 'Our Services' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="mx-auto mb-14 max-w-2xl text-center">
            <p class="eyebrow justify-center">What We Do</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">Services that empower villages</h2>
            <p class="mt-4 text-ink-600">GODEVI supports tourism villages with end-to-end solutions — from planning and development to branding and research.</p>
        </div>

        <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-4">
            @php
                $services = [
                    ['icon' => 'perencanaan.png', 'title' => 'Tourism Planning, Strategy & Revitalization', 'link' => null],
                    ['icon' => 'portofolio.png', 'title' => 'Portfolio', 'link' => url('v-portofolio')],
                    ['icon' => 'kajian.png', 'title' => 'Project Management', 'link' => null],
                    ['icon' => 'sdm.png', 'title' => 'Human Resources Development', 'link' => null],
                    ['icon' => 'branding.png', 'title' => 'Destination Branding & Digital Marketing', 'link' => null],
                    ['icon' => 'tren.png', 'title' => 'Consumer Trend & Tourism Insight', 'link' => null],
                    ['icon' => 'internship.png', 'title' => 'Internship Program', 'link' => null],
                    ['icon' => 'research.jpg', 'title' => 'Research Analytics & Scientific Consulting', 'link' => null],
                ];
            @endphp

            @foreach ($services as $i => $service)
                @if ($service['link'])
                    <a href="{{ $service['link'] }}" data-vue="Reveal" class="group card card-hover flex flex-col items-center p-8 text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full bg-cream-100 p-4 transition group-hover:bg-brand-50">
                            <img src="{{ asset('assets/customer/img/etc/' . $service['icon']) }}" alt="{{ $service['title'] }}" class="h-full w-full object-contain" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-semibold text-ink-950 transition group-hover:text-brand-600">{{ $service['title'] }}</h3>
                    </a>
                @else
                    <div data-vue="Reveal" class="card flex flex-col items-center p-8 text-center">
                        <div class="flex h-28 w-28 items-center justify-center rounded-full bg-cream-100 p-4 transition group-hover:bg-brand-50">
                            <img src="{{ asset('assets/customer/img/etc/' . $service['icon']) }}" alt="{{ $service['title'] }}" class="h-full w-full object-contain" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-semibold text-ink-950">{{ $service['title'] }}</h3>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<section class="bg-ink-950 py-16">
    <div class="container-gd flex flex-col items-center justify-between gap-6 text-center md:flex-row md:text-left">
        <div>
            <h2 class="font-display text-2xl font-bold text-white sm:text-3xl">Ready to build something together?</h2>
            <p class="mt-2 text-white/60">Partner with GODEVI to grow your tourism village sustainably.</p>
        </div>
        <a href="{{ url('contact') }}" class="btn btn-white shrink-0">Get in Touch</a>
    </div>
</section>
@endsection