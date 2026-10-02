{{--
    Hero ringkas halaman asesmen.
    Variabel: $title, $subtitle (opsional), $eyebrow (opsional), $crumbs [label => url|null],
    $image (opsional), $overlap (bool, beri ruang bawah untuk kartu yang menumpuk).
--}}
@php
    $image = $image ?? 'assets/customer/img/page-title-area/services.jpg';
    $overlap = $overlap ?? false;
@endphp
<section class="relative overflow-hidden bg-ink-950">
    <img src="{{ url($image) }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-40" loading="eager">
    <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/85 to-ink-950/50"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -right-24 -top-24 h-80 w-80 rounded-full bg-brand-600/25 blur-3xl"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -bottom-32 left-1/3 h-72 w-72 rounded-full bg-forest-500/15 blur-3xl"></div>

    <div class="container-gd relative z-10 pt-12 sm:pt-16 {{ $overlap ? 'pb-28 sm:pb-32' : 'pb-14 sm:pb-20' }}">
        <div class="animate-fade-up max-w-3xl">
            <nav aria-label="Breadcrumb" class="text-xs font-semibold uppercase tracking-wider text-white/50">
                <a href="{{ url('/') }}" class="transition hover:text-white">Home</a>
                @foreach ($crumbs ?? [] as $label => $link)
                    <span class="mx-1.5">/</span>
                    @if ($link)
                        <a href="{{ $link }}" class="transition hover:text-white">{{ $label }}</a>
                    @else
                        <span class="text-brand-400" aria-current="page">{{ $label }}</span>
                    @endif
                @endforeach
            </nav>
            @if (! empty($eyebrow))
                <p class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white/80 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-brand-500"></span>{{ $eyebrow }}
                </p>
            @endif
            <h1 class="mt-4 font-display text-3xl font-bold leading-tight text-white text-balance sm:text-5xl">{{ $title }}</h1>
            @if (! empty($subtitle))
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</section>
