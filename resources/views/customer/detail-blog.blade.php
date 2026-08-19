@extends('customer/layout')

@section('content')

@php
    $blogImg = url('storage/blogs/' . $blog->post_thumbnail);
@endphp

<x-partials.page-hero
    :title="$blog->post_title"
    :image="$blogImg"
    :crumbs="['Home' => '/', 'News' => 'news', 'Article' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-[1fr_320px]">
            <article class="space-y-8">
                <div data-vue="Reveal" class="overflow-hidden rounded-3xl border border-ink-100 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
                    <img src="{{ $blogImg }}" alt="{{ $blog->post_title }}" class="aspect-[16/9] w-full object-cover" loading="eager">
                </div>

                <header data-vue="Reveal" class="flex flex-wrap items-center gap-5 text-sm font-semibold text-ink-400">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        {{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}
                    </span>
                    @if ($blog->post_tags)
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-forest-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                            {{ $blog->post_tags }}
                        </span>
                    @endif
                </header>

                <div data-vue="Reveal" class="prose-gd" style="font-size: 1.05rem">
                    {!! $blog->post_content !!}
                </div>

                {{-- Share --}}
                <div data-vue="Reveal" class="flex flex-wrap items-center justify-between gap-4 rounded-3xl bg-cream-50 p-6">
                    <span class="font-bold text-ink-800">Share this article</span>
                    <div class="flex items-center gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('news/' . $blog->slug)) }}" target="_blank" rel="noopener" aria-label="Share on Facebook"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-ink-600 shadow-sm transition hover:bg-[#1877f2] hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.92 3.78-3.92 1.09 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.45 2.9h-2.33V22c4.78-.79 8.43-4.94 8.43-9.94z"/></svg>
                        </a>
                        <a href="https://twitter.com/share?url={{ urlencode(url('news/' . $blog->slug)) }}" target="_blank" rel="noopener" aria-label="Share on Twitter"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-ink-600 shadow-sm transition hover:bg-ink-950 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.41l-5.8-7.58-6.64 7.58H.47l8.6-9.83L0 1.15h7.6l5.24 6.93L18.9 1.15zm-1.29 19.5h2.04L6.48 3.24H4.3l13.31 17.41z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('news/' . $blog->slug)) }}" target="_blank" rel="noopener" aria-label="Share on LinkedIn"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-ink-600 shadow-sm transition hover:bg-[#0a66c2] hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 110-4.12 2.06 2.06 0 010 4.12zM7.12 20.45H3.56V9h3.56v11.45zM22.23 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.46c.98 0 1.77-.77 1.77-1.72V1.72C24 .77 23.21 0 22.23 0z"/></svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($blog->post_title . ' ' . url('news/' . $blog->slug)) }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-ink-600 shadow-sm transition hover:bg-[#25D366] hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.4-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.13.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.21-.24-.58-.49-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.11 3.22 5.1 4.51.71.31 1.27.49 1.7.63.72.23 1.37.2 1.88.12.57-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.07-.13-.27-.2-.57-.35zm-5.42 7.4h-.004a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.9-9.88a9.83 9.83 0 019.88 9.89c0 5.45-4.43 9.88-9.88 9.88zM12.05 21.5c2.6 0 5.03-1.01 6.86-2.84a9.66 9.66 0 002.84-6.87 9.66 9.66 0 00-2.84-6.87c-1.83-1.82-4.26-2.83-6.86-2.83a9.68 9.68 0 00-9.69 9.69c0 1.7.45 3.37 1.3 4.83l-1.4 5.12 5.24-1.37a9.67 9.67 0 004.55 1.14z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Comments --}}
                <section data-vue="Reveal" class="border-t border-ink-100 pt-10">
                    <h2 class="font-display text-2xl font-bold">{{ count($comments) }} Comments</h2>
                    <ul class="mt-6 space-y-6">
                        @forelse ($comments as $comment)
                            <li class="flex gap-4">
                                <span class="flex h-11 w-11 flex-shrink-0 overflow-hidden rounded-full bg-cream-100 ring-2 ring-white">
                                    @if ($comment->users && $comment->users->avatar)
                                        <img src="{{ asset('storage/users/' . $comment->users->avatar) }}" alt="{{ $comment->users->name }}" class="h-full w-full object-cover">
                                    @else
                                        <svg class="m-auto h-5 w-5 text-ink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2.5c-4.14 0-7.5 2.16-7.5 4.82V21h15v-1.68c0-2.66-3.36-4.82-7.5-4.82z"/></svg>
                                    @endif
                                </span>
                                <div class="flex-1 rounded-2xl bg-cream-50 p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <span class="font-bold text-ink-900">{{ $comment->users ? $comment->users->name : 'Anonymous' }}</span>
                                        <time class="text-xs font-semibold text-ink-400">{{ \Carbon\Carbon::parse($comment->created_at)->format('F d, Y h:i a') }}</time>
                                    </div>
                                    <p class="mt-2 text-sm leading-relaxed text-ink-700">{{ $comment->comment }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-ink-400">Be the first to comment.</li>
                        @endforelse
                    </ul>

                    <div class="mt-10 rounded-3xl border border-ink-100 bg-white p-7">
                        @if (session('success'))
                            <div class="mb-4 rounded-xl bg-forest-50 px-4 py-3 text-sm font-semibold text-forest-700">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 rounded-xl bg-brand-50 px-4 py-3 text-sm font-semibold text-brand-700">{{ session('error') }}</div>
                        @endif

                        @auth
                            <h3 class="font-display text-xl font-bold">Leave a Reply</h3>
                            <form action="{{ url('news/comment/' . $blog->slug) }}" method="POST" class="mt-4 space-y-4">
                                @csrf
                                <label class="block">
                                    <span class="label-gd">Comment</span>
                                    <textarea name="comment" required rows="4" class="input-gd" placeholder="Share your thoughts..."></textarea>
                                </label>
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </form>
                        @else
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-cream-50 px-5 py-4">
                                <p class="text-sm font-semibold text-ink-600">Please login to leave a comment.</p>
                                <a href="{{ url('user/login') }}" class="btn btn-primary !py-2.5 text-sm">Login</a>
                            </div>
                        @endauth
                    </div>
                </section>
            </article>

            <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                <div data-vue="Reveal" class="rounded-3xl border border-ink-100 bg-white p-6 shadow-[0_10px_30px_-15px_rgb(26_26_38/0.2)]">
                    <h2 class="font-display text-lg font-semibold">Recent Articles</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($recent as $rec)
                            <li>
                                <a href="{{ url('news/' . $rec->slug) }}" class="group flex items-center gap-4">
                                    <img src="{{ asset('storage/blogs/' . $rec->post_thumbnail) }}" alt="{{ $rec->post_title }}"
                                        class="h-16 w-20 flex-shrink-0 rounded-xl object-cover" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/customer/img/etc/slider/blog%201%205x2.png') }}';">
                                    <span class="min-w-0">
                                        <span class="block line-clamp-2 text-sm font-semibold leading-snug text-ink-800 transition group-hover:text-brand-600">{{ $rec->post_title }}</span>
                                        <span class="mt-0.5 block text-xs font-semibold uppercase tracking-wide text-ink-400">{{ \Carbon\Carbon::parse($rec->created_at)->format('M d, Y') }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection