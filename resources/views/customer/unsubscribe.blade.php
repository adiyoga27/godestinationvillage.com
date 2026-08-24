@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('Unsubscribe Newsletter') }}"
    subtitle="{{ __('Manage your newsletter subscription on GODEVI.') }}"
    image="assets/customer/img/page-title-area/news.jpg"
    :crumbs="[__('Home') => '/', __('Unsubscribe') => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="mx-auto max-w-xl">
            <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-8 text-center shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)] sm:p-10">
                @if (session('status'))
                    <div class="mb-6 rounded-xl bg-forest-50 px-5 py-4 text-sm font-semibold text-forest-700">{{ session('status') }}</div>
                @endif

                @if ($subscriber->is_active)
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-cream-100">
                        <svg class="h-8 w-8 text-ink-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    </div>
                    <h1 class="mt-6 font-display text-2xl font-bold text-ink-950">{{ __('Unsubscribe from newsletter') }}</h1>
                    <p class="mt-3 text-sm text-ink-600">
                        {{ __('Your email') }} <strong>{{ $subscriber->email }}</strong> {{ __('is currently subscribed to the GODEVI newsletter. You will stop receiving village stories, new experiences and travel inspiration.') }}
                    </p>
                    <form action="{{ route('unsubscribe.confirm', $subscriber->unsubscribe_token) }}" method="POST" class="mt-8">
                        @csrf
                        <button type="submit" class="btn btn-primary w-full !py-4 !text-base">{{ __('Yes, unsubscribe me') }}</button>
                    </form>
                    <a href="{{ url('/') }}" class="mt-4 inline-block text-sm font-bold text-brand-600 hover:underline">{{ __('No, keep my subscription') }}</a>
                @else
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-forest-50">
                        <svg class="h-8 w-8 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h1 class="mt-6 font-display text-2xl font-bold text-ink-950">{{ __('You have been unsubscribed') }}</h1>
                    <p class="mt-3 text-sm text-ink-600">{{ __('You will no longer receive emails from the GODEVI newsletter. Changed your mind? You can subscribe again from the footer of any page.') }}</p>
                    <a href="{{ url('/') }}" class="btn btn-secondary mt-8">{{ __('Back to Home') }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection