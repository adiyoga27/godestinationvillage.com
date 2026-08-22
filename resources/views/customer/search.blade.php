@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Search Results') }}"
    subtitle="{{ $keyword ? __('Packages matching :keyword', ['keyword' => $keyword]) : __('Enter a keyword to find the perfect village experience.') }}"
    image="assets/customer/img/page-title-area/explorer.jpg"
    :crumbs="[__('Home') => '/', __('Search') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <form action="{{ url('search') }}" method="GET" class="mx-auto mb-12 max-w-2xl">
            <div class="relative">
                <input type="text" name="key" value="{{ $keyword }}" placeholder="{{ __('Search tour packages...') }}"
                    class="input-gd !py-4 pl-12 pr-28 text-base">
                <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <button type="submit" class="btn btn-primary absolute right-2 top-1/2 -translate-y-1/2 !py-2.5 !text-sm">{{ __('Search') }}</button>
            </div>
        </form>

        @if (!$keyword)
            <p class="text-center text-ink-500">{{ __('Type a keyword above to search our tour packages.') }}</p>
        @else
            @if (count($packages) > 0)
                <p class="mb-8 text-sm text-ink-500"><strong class="text-ink-900">{{ $packages->total() }}</strong> {{ __('package(s) found for') }} "<strong class="text-brand-600">{{ $keyword }}</strong>"</p>
            @endif
            <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($packages as $pack)
                    @php
                        $tr = $pack->translate?->firstWhere('lang', App::getLocale());
                        $name = $tr?->name ?: $pack->name;
                        $desc = $tr?->desc ?: $pack->desc;
                    @endphp
                    <a href="{{ url('tour-packages/' . $pack->slug) }}" data-vue="Reveal" class="group card card-hover flex flex-col overflow-hidden">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $pack->default_img ? asset('storage/packages/' . $pack->default_img) : asset('assets/customer/frontdata/images/destination-' . (($loop->index % 6) + 1) . '.jpg') }}"
                                alt="{{ $name }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide text-ink-400">
                                <span class="text-brand-600">{{ $pack->cat_name }}</span>
                                <span class="flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>{{ $pack->vil_name }}</span>
                            </div>
                            <h2 class="mt-2 font-display text-lg font-semibold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $name }}</h2>
                            <p class="mt-2 line-clamp-2 flex-1 text-sm text-ink-500">{{ strip_tags($desc) }}</p>
                            <div class="mt-5 flex items-center justify-between border-t border-ink-50 pt-4">
                                <span class="text-lg font-bold text-brand-600">Rp {{ number_format($pack->price, 0, ',', '.') }}</span>
                                <span class="text-xs font-bold uppercase tracking-wide text-brand-600 transition group-hover:translate-x-1">{{ __('View') }} →</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="card col-span-full p-12 text-center">
                        <p class="font-display text-xl font-bold text-ink-950">{{ __('No results found') }}</p>
                        <p class="mt-2 text-sm text-ink-500">{{ __('We couldn\'t find any packages matching ":keyword". Try a different keyword.', ['keyword' => $keyword]) }}</p>
                        <a href="{{ url('tour-packages') }}" class="btn btn-primary mt-6">{{ __('Browse All Packages') }}</a>
                    </div>
                @endforelse
            </div>
        @endif

        @if ($keyword && $packages->hasPages())
            <div class="mt-10 flex items-center justify-center gap-2">
                @for ($i = 1; $i <= $packages->lastPage(); $i++)
                    <a href="{{ $packages->url($i) }}"
                        class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-bold transition {{ $packages->currentPage() == $i ? 'bg-brand-600 text-white' : 'bg-white text-ink-600 hover:bg-cream-100' }}">{{ $i }}</a>
                @endfor
                @if ($packages->lastPage() > 0 && $packages->currentPage() < $packages->lastPage())
                    <a href="{{ $packages->nextPageUrl() }}" class="btn btn-secondary !px-5 !py-2.5">{{ __('Next') }}</a>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection