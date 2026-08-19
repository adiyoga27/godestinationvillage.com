@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="Contact Us"
    subtitle="Planning a village escape or a homestay stay? Our team is here to help — reach out any time."
    image="assets/customer/img/page-title-area/explorer.jpg"
    :crumbs="['Home' => '/', 'Contact Us' => '']"
/>

<section class="section-pad">
    <div class="container-gd">
        <div class="grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <p class="eyebrow">Get in Touch</p>
                <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">We'd love to hear from you</h2>
                <p class="mt-4 text-ink-600">Whether you have a question about our experiences, homestays or events — drop us a message and the GODEVI team will get back to you.</p>

                <div class="mt-8 space-y-5">
                    <a href="mailto:hello@godestinationvillage.com" class="group flex items-start gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-brand-50 text-brand-600 transition group-hover:bg-brand-600 group-hover:text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-ink-400">Email</span>
                            <span class="mt-1 block font-semibold text-ink-900 group-hover:text-brand-600">hello@godestinationvillage.com</span>
                        </span>
                    </a>
                    <a href="https://wa.me/6281997674778" target="_blank" rel="noopener" class="group flex items-start gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-forest-50 text-forest-600 transition group-hover:bg-forest-600 group-hover:text-white">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.9 4.42-9.9 9.87 0 1.74.46 3.44 1.33 4.94L2 22l5.36-1.4a9.87 9.87 0 004.68 1.19h.01c5.46 0 9.9-4.42 9.9-9.88 0-2.64-1.03-5.12-2.9-6.99A9.83 9.83 0 0012.04 2zm0 18.03h-.01a8.2 8.2 0 01-4.17-1.14l-.3-.18-3.18.83.85-3.1-.2-.31a8.18 8.18 0 01-1.26-4.36c0-4.54 3.7-8.23 8.27-8.23 2.2 0 4.28.86 5.84 2.42a8.21 8.21 0 012.42 5.84c0 4.54-3.7 8.23-8.26 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.8-.78.97-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.11-.22-.17-.47-.29z" /></svg>
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-ink-400">WhatsApp</span>
                            <span class="mt-1 block font-semibold text-ink-900 group-hover:text-forest-600">+62 819-9767-4778</span>
                        </span>
                    </a>
                    <div class="flex items-start gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-ink-50 text-ink-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-ink-400">Address</span>
                            <span class="mt-1 block font-semibold text-ink-900">Bali, Indonesia</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="card p-8 sm:p-10">
                    <h3 class="font-display text-xl font-bold text-ink-950">Send us a message</h3>
                    <p class="mt-1 text-sm text-ink-500">We usually reply within 24 hours.</p>
                    <form action="mailto:hello@godestinationvillage.com" method="GET" class="mt-7 space-y-5">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="block">
                                <span class="label-gd">Full name</span>
                                <input type="text" name="subject" placeholder="Your name" class="input-gd" required>
                            </label>
                            <label class="block">
                                <span class="label-gd">Email address</span>
                                <input type="email" name="body" placeholder="you@example.com" class="input-gd" required>
                            </label>
                        </div>
                        <label class="block">
                            <span class="label-gd">Subject</span>
                            <input type="text" name="subject" placeholder="How can we help?" class="input-gd" required>
                        </label>
                        <label class="block">
                            <span class="label-gd">Message</span>
                            <textarea name="body" rows="5" placeholder="Tell us about your trip..." class="input-gd resize-none" required></textarea>
                        </label>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                    <p class="mt-6 text-xs text-ink-400">Prefer email? Reach us directly at <a href="mailto:hello@godestinationvillage.com" class="font-semibold text-brand-600 hover:underline">hello@godestinationvillage.com</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection