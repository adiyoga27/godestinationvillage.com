@props([
    'members' => [],
    'folder' => 'ourteam',
])

<div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($members as $m)
        <div data-vue="Reveal" class="group card card-hover overflow-hidden">
            <div class="relative aspect-[4/3] overflow-hidden bg-cream-100">
                @if (!empty($m->avatar))
                    <img src="{{ asset('storage/' . $folder . '/' . $m->avatar) }}" alt="{{ $m->name }}"
                        class="h-full w-full object-cover object-top transition-transform duration-700 group-hover:scale-105" loading="lazy">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-50 to-cream-100">
                        <span class="font-display text-5xl font-bold text-brand-600">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($m->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div class="p-6 text-center">
                <h3 class="font-display text-lg font-bold text-ink-950">{{ $m->name }}</h3>
                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-brand-600">{{ $m->title }}</p>
                @if (!empty($m->description))
                    <p class="mt-3 line-clamp-3 text-sm text-ink-500">{{ strip_tags($m->description) }}</p>
                @endif
                <div class="mt-5 flex items-center justify-center gap-2">
                    @if (!empty($m->phone))
                        <a href="tel:{{ $m->phone }}" target="_blank" rel="noopener" aria-label="Phone" class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-600 transition hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        </a>
                    @endif
                    @if (!empty($m->whatsapp))
                        <a href="https://wa.me/{{ $m->whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-600 transition hover:bg-forest-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.9 4.42-9.9 9.87 0 1.74.46 3.44 1.33 4.94L2 22l5.36-1.4a9.87 9.87 0 004.68 1.19h.01c5.46 0 9.9-4.42 9.9-9.88 0-2.64-1.03-5.12-2.9-6.99A9.83 9.83 0 0012.04 2zm0 18.03h-.01a8.2 8.2 0 01-4.17-1.14l-.3-.18-3.18.83.85-3.1-.2-.31a8.18 8.18 0 01-1.26-4.36c0-4.54 3.7-8.23 8.27-8.23 2.2 0 4.28.86 5.84 2.42a8.21 8.21 0 012.42 5.84c0 4.54-3.7 8.23-8.26 8.23z" /></svg>
                        </a>
                    @endif
                    @if (!empty($m->facebook))
                        <a href="{{ $m->facebook }}" target="_blank" rel="noopener" aria-label="Facebook" class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-600 transition hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.7 4.53-4.7 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.09 24 18.1 24 12.07z" /></svg>
                        </a>
                    @endif
                    @if (!empty($m->instagram))
                        <a href="{{ $m->instagram }}" target="_blank" rel="noopener" aria-label="Instagram" class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-600 transition hover:bg-brand-600 hover:text-white">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.72 3.72 0 01-1.38-.9 3.72 3.72 0 01-.9-1.38c-.16-.42-.36-1.06-.41-2.23-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41 1.27-.06 1.65-.07 4.85-.07M12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63a5.88 5.88 0 00-2.13 1.38A5.88 5.88 0 00.63 4.14C.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.27.26 2.15.56 2.91.31.8.72 1.47 1.38 2.13a5.88 5.88 0 002.13 1.38c.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.88 5.88 0 002.13-1.38 5.88 5.88 0 001.38-2.13c.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.88 5.88 0 00-1.38-2.13A5.88 5.88 0 0019.86.63c-.76-.3-1.64-.5-2.91-.56C15.67.01 15.26 0 12 0zm0 5.84A6.16 6.16 0 100 12a6.16 6.16 0 000-6.16zm0 10.15A3.99 3.99 0 1112 8a3.99 3.99 0 010 8zm6.4-11.85a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z" /></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full text-center text-ink-400">{{ __('No members yet.') }}</p>
    @endforelse
</div>