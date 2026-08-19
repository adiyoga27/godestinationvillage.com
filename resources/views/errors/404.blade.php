@extends('customer/layout')

@section('content')

@php
    $seo = \App\Support\Seo::make()
        ->title($title ?? 'Page Not Found — GODEVI')
        ->description('The page you are looking for could not be found.')
        ->noindex()
        ->toArray();
@endphp

<section class="section-pad bg-ink-50/60">
    <div class="container-gd flex min-h-[65vh] items-center justify-center">
        <div class="w-full max-w-lg text-center">
            <p class="font-display text-[7rem] font-bold leading-none text-brand-600/15">404</p>
            <h1 class="mt-4 font-display text-3xl font-semibold text-ink-950 sm:text-4xl">Ooops! Page Not Found</h1>
            <p class="mx-auto mt-4 max-w-md text-ink-500">
                The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-8">Back to Home</a>
        </div>
    </div>
</section>

@endsection