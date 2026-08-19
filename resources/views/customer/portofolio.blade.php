@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Our Portfolio"
    subtitle="Village tourism projects, community empowerment programs and sustainable tourism initiatives across Bali."
    image="assets/customer/img/page-title-area/founding-timenile.jpg"
    :crumbs="['Home' => '/', 'Portfolio' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-7 md:grid-cols-2">
            @forelse ($portofolios as $f)
                <article data-vue="Reveal" class="group card card-hover overflow-hidden">
                    <div class="relative h-64 overflow-hidden bg-cream-100">
                        @if (!empty($f->attachment))
                            <img src="{{ asset('storage/portofolio/' . $f->attachment) }}" alt="{{ $f->title }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        @elseif (!empty($f->thumbnail))
                            <img src="{{ asset('storage/portofolio/' . $f->thumbnail) }}" alt="{{ $f->title }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        @endif
                        @if (!empty($f->dates))
                            <span class="badge absolute left-4 top-4 bg-brand-600 text-white shadow-lg">{{ date('M Y', strtotime($f->dates)) }}</span>
                        @endif
                    </div>
                    <div class="p-7">
                        <h3 class="font-display text-xl font-bold text-ink-950 transition group-hover:text-brand-600">{{ $f->title }}</h3>
                        @if (!empty($f->description))
                            <p class="mt-3 text-sm leading-relaxed text-ink-500">{!! $f->description !!}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="col-span-full text-center text-ink-400">No portfolio items yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection