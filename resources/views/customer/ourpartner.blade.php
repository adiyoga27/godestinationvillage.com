@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Our Partners"
    subtitle="The partners and collaborators supporting GODEVI in building sustainable village tourism communities across Bali."
    image="assets/customer/img/page-title-area/partner.jpg"
    :crumbs="['Home' => '/', 'Our Partners' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        @php
            $partners = [
                ['image' => 'inbistohpati.png', 'title' => 'Tohpati Business Incubator', 'link' => null, 'desc' => 'Business incubation partner supporting tourism village entrepreneurship.'],
                ['image' => 'dewibali.png', 'title' => 'Bali Tourism Village Communication Forum', 'link' => null, 'desc' => 'Collaboration forum connecting tourism villages across Bali.'],
                ['image' => 'HMPIndonesia.png', 'title' => 'Himpunan Mahasiswa Pariwisata Indonesia', 'link' => 'https://hmpiofficial.org', 'desc' => 'National tourism student association (HMPI).'],
                ['image' => 'ezeego.png', 'title' => 'EZZEGO.APP', 'link' => 'https://EzeeGo.app', 'desc' => 'Digital travel technology partner.'],
                ['image' => 'IPBI.png', 'title' => 'Institut Pariwisata dan Bisnis Internasional', 'link' => 'https://www.ipb-intl.ac.id', 'desc' => 'International tourism and business institute (IPBI).'],
                ['image' => 'unud.png', 'title' => 'Universitas Udayana', 'link' => 'https://www.unud.ac.id', 'desc' => 'Academic partner for tourism research and development.'],
                ['image' => 'BUHSA.png', 'title' => 'Buleleng Homestay Association', 'link' => 'https://www.instagram.com/buleleng_homestay', 'desc' => 'Homestay association of Buleleng regency.'],
            ];
        @endphp

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($partners as $i => $p)
                @if ($p['link'])
                    <a href="{{ $p['link'] }}" target="_blank" rel="noopener" data-vue="Reveal" class="group card card-hover flex flex-col items-center justify-center p-10 text-center">
                        <div class="flex h-32 items-center justify-center">
                            <img src="{{ asset('assets/customer/frontdata/images/' . $p['image']) }}" alt="{{ $p['title'] }}"
                                class="max-h-28 w-auto object-contain opacity-90 grayscale transition duration-500 group-hover:grayscale-0" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-bold text-ink-950 transition group-hover:text-brand-600">{{ $p['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-500">{{ $p['desc'] }}</p>
                    </a>
                @else
                    <div data-vue="Reveal" class="card flex flex-col items-center justify-center p-10 text-center">
                        <div class="flex h-32 items-center justify-center">
                            <img src="{{ asset('assets/customer/frontdata/images/' . $p['image']) }}" alt="{{ $p['title'] }}"
                                class="max-h-28 w-auto object-contain opacity-90 grayscale transition duration-500 group-hover:grayscale-0" loading="lazy">
                        </div>
                        <h3 class="mt-6 font-display text-lg font-bold text-ink-950">{{ $p['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-500">{{ $p['desc'] }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endsection