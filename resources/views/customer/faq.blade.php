@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="faq"
    title="{{ __('Frequently Asked Questions') }}"
    subtitle="{{ __('Everything you need to know about GODEVI, booking, payment and cancellations.') }}"
    image="assets/customer/img/page-title-area/faq.jpg"
    :crumbs="[__('Home') => '/', __('FAQ') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-4xl">
        @forelse ($categories as $category)
            <div class="mb-12">
                <h2 class="mb-6 font-display text-2xl font-bold text-ink-950">{{ $category->localTitle() }}</h2>
                <div class="space-y-4">
                    @foreach ($category->faqs as $faq)
                        <details class="group rounded-2xl border border-ink-100 bg-white shadow-soft transition open:border-brand-200">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 text-left font-semibold text-ink-900 marker:hidden">
                                <span>{{ $faq->localQuestion() }}</span>
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600 transition group-open:rotate-45">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </span>
                            </summary>
                            <div class="prose-gd border-t border-ink-100 px-6 py-5 text-sm leading-relaxed text-ink-600">
                                {!! $faq->localAnswer() !!}
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="mb-12 text-center text-ink-500">{{ __('No questions yet.') }}</p>
        @endforelse

        <div class="card flex flex-col items-center gap-4 p-8 text-center sm:flex-row sm:justify-between sm:text-left">
            <div>
                <h3 class="font-display text-lg font-bold text-ink-950">{{ __('Still have questions?') }}</h3>
                <p class="mt-1 text-sm text-ink-500">{{ __('Our team is happy to help you plan the perfect village experience.') }}</p>
            </div>
            <a href="{{ url('contact') }}" class="btn btn-primary shrink-0">{{ __('Contact Us') }}</a>
        </div>
    </div>
</section>
@endsection