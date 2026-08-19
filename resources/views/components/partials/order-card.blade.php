@props([
    'order',
    'type' => 'package',
    'folder' => 'packages',
    'actions' => true,
    'label' => 'package_name',
])

<article class="card overflow-hidden">
    <div class="grid sm:grid-cols-3">
        <div class="relative h-48 bg-cream-100 sm:h-full">
            @if (optional($order->package)->default_img)
                <img src="{{ asset('storage/' . $folder . '/' . $order->package->default_img) }}" alt="{{ $order->$label ?? $order->code }}"
                    class="h-full w-full object-cover" loading="lazy">
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-50 to-cream-100">
                    <svg class="h-12 w-12 text-brand-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                </div>
            @endif
        </div>
        <div class="p-7 sm:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <span class="badge bg-brand-50 text-brand-700">{{ $order->code }}</span>
                    <h3 class="mt-3 font-display text-xl font-bold text-ink-950">{{ $order->$label ?? $order->code }}</h3>
                </div>
                @if (optional($order->package)->category)
                    <span class="badge bg-ink-50 text-ink-600">{{ $order->package->category->name }}</span>
                @endif
            </div>

            <div class="mt-5 grid gap-x-8 gap-y-3 text-sm sm:grid-cols-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-ink-400">Total Payment</span>
                    <p class="mt-0.5 font-bold text-brand-600">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-ink-400">Pax</span>
                    <p class="mt-0.5 text-ink-700">{{ $order->pax }}</p>
                </div>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-ink-400">Booking Date</span>
                    <p class="mt-0.5 text-ink-700">{{ date('d M Y H:i', strtotime($order->created_at)) }} WITA</p>
                </div>
                @if (!empty($order->payment_type))
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-ink-400">Payment Method</span>
                        <p class="mt-0.5 text-ink-700">{{ $order->payment_type }}</p>
                    </div>
                @endif
            </div>

            @if (!empty($order->special_note))
                <div class="mt-4 rounded-2xl bg-cream-100 px-4 py-3 text-sm text-ink-600">
                    <span class="font-bold text-ink-800">Special Note:</span> {{ $order->special_note }}
                </div>
            @endif

            @if ($actions)
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ url($paymentUrl) }}" class="btn btn-primary !px-6 !py-2.5">Pay Now</a>
                    <a href="{{ url($cancelUrl) }}" class="btn btn-secondary !px-6 !py-2.5">Cancel</a>
                </div>
            @endif
        </div>
    </div>
</article>