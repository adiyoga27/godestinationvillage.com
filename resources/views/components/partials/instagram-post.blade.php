@props(['url' => null])

@php
    $shortcode = '';
    if (preg_match('#/(p|reel|tv)/([A-Za-z0-9_-]{5,})#', $url ?? '', $m)) {
        $shortcode = $m[2];
    }
@endphp

@if (!empty($url) && !empty($shortcode))
    <div class="overflow-hidden rounded-2xl bg-white">
        <iframe
            src="https://www.instagram.com/p/{{ $shortcode }}/embed/captioned/"
            width="100%"
            height="560"
            frameborder="0"
            scrolling="no"
            allowtransparency="true"
            allowfullscreen
            loading="lazy"
            title="Instagram post"
            style="width:100%;height:560px;border:none;overflow:hidden;background:#fff;"></iframe>
        <p class="px-4 py-3 text-center text-sm">
            <a href="{{ $url }}" target="_blank" rel="noopener" class="font-semibold text-brand-600 hover:underline">
                {{ __('View this post on Instagram') }}
            </a>
        </p>
    </div>
@endif