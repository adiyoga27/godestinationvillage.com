@props([
    'title' => '',
    'subtitle' => '',
    'image' => 'assets/customer/frontdata/images/bg_2.jpg',
    'crumbs' => [],
])

<section class="relative overflow-hidden bg-ink-950">
    <img src="{{ asset($image) }}" alt="" aria-hidden="true"
        class="absolute inset-0 h-full w-full object-cover opacity-40" loading="eager">
    <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 to-ink-950/90"></div>
    <div class="container-gd relative z-10 py-20 text-center sm:py-24">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center justify-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-white/60">
                <li><a href="{{ url('/') }}" class="transition hover:text-white">Home</a></li>
                @foreach ($crumbs as $label => $link)
                    <li class="flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        @if (!empty($link))
                            <a href="{{ url($link) }}" class="transition hover:text-white">{{ $label }}</a>
                        @else
                            <span class="text-brand-400" aria-current="page">{{ $label }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
        <h1 class="mt-4 font-display text-4xl font-bold text-white sm:text-5xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mx-auto mt-4 max-w-2xl text-white/70">{{ $subtitle }}</p>
        @endif
    </div>
</section>