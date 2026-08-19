@props([
    'email',
    'base' => 'reservation',
    'active' => 'unpaid',
    'title' => null,
])

@php
    $title = $title ?? __('Status Booking');
    $links = [
        'unpaid' => ['label' => __('Unpaid'), 'url' => url($base . '/' . $email)],
        'paid' => ['label' => __('Paid'), 'url' => url($base . '/paid/' . $email)],
        'cancel' => ['label' => __('Cancel'), 'url' => url($base . '/cancel/' . $email)],
    ];
@endphp

<aside>
    <div class="card p-6">
        <h3 class="font-display text-lg font-bold text-ink-950">{{ $title }}</h3>
        <div class="mt-5 space-y-2">
            @foreach ($links as $key => $link)
                <a href="{{ $link['url'] }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition {{ $active === $key ? 'bg-brand-600 text-white shadow-glow' : 'text-ink-600 hover:bg-cream-100' }}">
                    <span class="h-2 w-2 rounded-full {{ $active === $key ? 'bg-white' : 'bg-ink-300' }}"></span>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</aside>