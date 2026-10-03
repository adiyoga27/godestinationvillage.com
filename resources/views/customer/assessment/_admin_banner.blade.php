{{-- Penanda Mode Input Admin (admin mengisi atas nama responden, tanpa pembayaran). --}}
@if (! empty($adminMode))
    <div class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        <svg class="h-5 w-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
        <p class="flex-1"><strong>Mode Input Admin</strong> — Anda mengisi atas nama responden. Hasil langsung terbuka tanpa pembayaran.</p>
        <a href="{{ route('assessment-results.create') }}" class="font-bold text-amber-800 underline">Keluar mode admin</a>
    </div>
@endif
