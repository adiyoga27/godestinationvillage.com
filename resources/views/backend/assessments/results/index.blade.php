@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <div>
            <h3 class="page-title mb-1">Hasil Asesmen</h3>
            <p class="gd-subtitle">Pantau pembayaran, laporan AI, dan tindak lanjut tim untuk setiap asesmen.</p>
        </div>
        <a href="{{ route('assessment-results.create') }}" class="gd-btn gd-btn--primary"><i class="mdi mdi-plus"></i> Input Manual</a>
    </div>
@endsection

@section('content')
@php
    $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    $tone = fn ($s) => $s >= 60 ? 'green' : ($s >= 40 ? 'amber' : 'red');
    $statusMeta = [
        'baru' => ['Baru', 'gd-pill--amber'],
        'dihubungi' => ['Dihubungi', 'gd-pill--blue'],
        'selesai' => ['Selesai', 'gd-pill--green'],
    ];
    $tabs = [
        'all' => ['Semua', 'format-list-bulleted'],
        'followup' => ['Perlu tindak lanjut', 'bell-ring'],
        'unpaid' => ['Menunggu bayar', 'timer-sand'],
        'paid' => ['Lunas / terbuka', 'check-circle'],
        'failed' => ['Laporan gagal', 'alert-circle'],
    ];
    $trackColors = ['pariwisata' => '#d81c25', 'ekonomi-desa' => '#15803d', 'daya-saing-destinasi' => '#1d4ed8', 'regeneratif' => '#b45309'];
    $tabUrl = fn ($t) => route('assessment-results.index', array_merge($filters, ['tab' => $t === 'all' ? null : $t]));
    $withoutFilter = fn ($key) => route('assessment-results.index', array_merge(\Illuminate\Support\Arr::except($filters, $key), ['tab' => $tab === 'all' ? null : $tab]));
    $activeChips = collect([
        'q' => ! empty($filters['q']) ? 'Cari: “'.$filters['q'].'”' : null,
        'track_id' => ! empty($filters['track_id']) ? optional($tracks->firstWhere('id', (int) $filters['track_id']))->name : null,
        'status' => ! empty($filters['status']) ? 'Status: '.($statusMeta[$filters['status']][0] ?? $filters['status']) : null,
        'source' => ! empty($filters['source']) ? ($filters['source'] === 'admin' ? 'Input admin' : 'Diisi guest') : null,
    ])->filter();
@endphp

{{-- ============ RINGKASAN (klik = filter) ============ --}}
<div class="gd-stats">
    <a href="{{ $tabUrl('all') }}" class="gd-stat gd-stat--link {{ $tab === 'all' ? 'is-active' : '' }}">
        <span class="gd-stat__icon"><i class="mdi mdi-clipboard-check"></i></span>
        <div><p class="gd-stat__label">Total hasil</p><p class="gd-stat__value">{{ number_format($stats['total']) }}</p></div>
    </a>
    <a href="{{ $tabUrl('followup') }}" class="gd-stat gd-stat--link {{ $tab === 'followup' ? 'is-active' : '' }}">
        <span class="gd-stat__icon gd-stat__icon--blue"><i class="mdi mdi-bell-ring"></i></span>
        <div><p class="gd-stat__label">Perlu tindak lanjut</p><p class="gd-stat__value">{{ number_format($stats['followup']) }}</p><p class="gd-stat__hint">Lunas, belum dihubungi</p></div>
    </a>
    <a href="{{ $tabUrl('unpaid') }}" class="gd-stat gd-stat--link {{ $tab === 'unpaid' ? 'is-active' : '' }}">
        <span class="gd-stat__icon gd-stat__icon--amber"><i class="mdi mdi-timer-sand"></i></span>
        <div><p class="gd-stat__label">Menunggu bayar</p><p class="gd-stat__value">{{ number_format($stats['unpaid']) }}</p></div>
    </a>
    <div class="gd-stat">
        <span class="gd-stat__icon gd-stat__icon--green"><i class="mdi mdi-cash"></i></span>
        <div><p class="gd-stat__label">Pendapatan Midtrans</p><p class="gd-stat__value">{{ $rupiah($stats['revenue']) }}</p></div>
    </div>
</div>

<section class="gd-card gd-list-card">
    {{-- ============ TAB ============ --}}
    <nav class="gd-tabs" aria-label="Status hasil">
        @foreach ($tabs as $key => [$label, $icon])
            <a href="{{ $tabUrl($key) }}" class="gd-tabs__item {{ $tab === $key ? 'is-active' : '' }}" @if ($tab === $key) aria-current="page" @endif>
                <i class="mdi mdi-{{ $icon }}"></i> {{ $label }}
                <span class="gd-tabs__count {{ $key === 'failed' && $tabCounts[$key] > 0 ? 'is-alert' : '' }}">{{ $tabCounts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- ============ FILTER (langsung terapkan) ============ --}}
    <form method="get" class="gd-toolbar" data-autosubmit>
        @if ($tab !== 'all')<input type="hidden" name="tab" value="{{ $tab }}">@endif
        <div class="gd-filters__search">
            <i class="mdi mdi-magnify"></i>
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control gd-input" placeholder="Cari nama, desa/usaha, WA, email, lokasi, atau no. invoice…" autocomplete="off">
        </div>
        <select name="track_id" class="form-control gd-input" aria-label="Jalur">
            <option value="">Semua jalur</option>
            @foreach ($tracks as $t)
                <option value="{{ $t->id }}" @selected(($filters['track_id'] ?? '') == $t->id)>{{ $t->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-control gd-input" aria-label="Tindak lanjut">
            <option value="">Semua tindak lanjut</option>
            @foreach ($statusMeta as $st => [$label])
                <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="source" class="form-control gd-input" aria-label="Sumber">
            <option value="">Semua sumber</option>
            <option value="guest" @selected(($filters['source'] ?? '') === 'guest')>Diisi guest</option>
            <option value="admin" @selected(($filters['source'] ?? '') === 'admin')>Input admin</option>
        </select>
        <noscript><button class="gd-btn gd-btn--primary">Terapkan</button></noscript>
    </form>

    @if ($activeChips->isNotEmpty())
        <div class="gd-chips">
            <span class="gd-chips__label">Filter aktif:</span>
            @foreach ($activeChips as $key => $label)
                <a href="{{ $withoutFilter($key) }}" class="gd-chip">{{ $label }} <i class="mdi mdi-close"></i></a>
            @endforeach
            <a href="{{ route('assessment-results.index', ['tab' => $tab === 'all' ? null : $tab]) }}" class="gd-chips__clear">Hapus semua</a>
        </div>
    @endif

    {{-- ============ DAFTAR ============ --}}
    <div class="gd-rows">
        <div class="gd-rows__head" aria-hidden="true">
            <span>Responden</span><span>Skor</span><span>Pembayaran</span><span>Tindak lanjut</span><span></span>
        </div>

        @forelse ($results as $r)
            @php
                $order = $r->latestOrder;
                $title = $r->organization ?: $r->name;
                $initials = collect(preg_split('/\s+/', trim($title)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                $color = $trackColors[$r->track->slug ?? ''] ?? '#4b4959';
                $score = (float) $r->total_score;
                $wa = $r->phone ? 'https://wa.me/62'.ltrim(preg_replace('/\D/', '', $r->phone), '0') : null;
                [$stLabel, $stClass] = $statusMeta[$r->status] ?? [ucfirst($r->status), 'gd-pill--ink'];
                $reportFailed = $r->is_unlocked && empty($r->ai_report) && $r->report_status === 'failed';
                $deleteNote = $r->orders->isNotEmpty() ? ' Invoice terkait ('.$r->orders->pluck('code')->implode(', ').') ikut terhapus.' : '';
            @endphp
            <article class="gd-row">
                {{-- Responden --}}
                <div class="gd-row__who">
                    <span class="gd-avatar" style="--c: {{ $color }}">{{ $initials ?: '?' }}</span>
                    <div class="min-w-0">
                        <a href="{{ route('assessment-results.show', $r->id) }}" class="gd-row__title">{{ $title }}</a>
                        <div class="gd-row__meta">
                            <span class="gd-dot" style="--c: {{ $color }}"></span>{{ optional($r->track)->name }}
                            @if ($r->regency) · {{ collect([$r->regency, $r->province])->filter()->implode(', ') }}@endif
                        </div>
                        <div class="gd-row__contact">
                            <span>{{ $r->name }}</span>
                            @if ($wa)<a href="{{ $wa }}" target="_blank" rel="noopener" title="WhatsApp {{ $r->phone }}"><i class="mdi mdi-whatsapp"></i>{{ $r->phone }}</a>@endif
                            @if ($r->email)<a href="mailto:{{ $r->email }}" title="{{ $r->email }}"><i class="mdi mdi-email-outline"></i><span class="gd-truncate">{{ $r->email }}</span></a>@endif
                        </div>
                        <div class="gd-row__time">
                            {{ $r->created_at?->translatedFormat('d M Y, H:i') }} · {{ $r->created_at?->diffForHumans() }}
                            @if ($r->source === 'admin')<span class="gd-pill gd-pill--ink ml-1">Input admin</span>@endif
                        </div>
                    </div>
                </div>

                {{-- Skor --}}
                <div class="gd-row__score">
                    <span class="gd-mobile-label">Skor</span>
                    <span class="gd-score-badge gd-score-badge--{{ $tone($score) }}">{{ number_format($score, 0) }}</span>
                    <span class="gd-row__meta">{{ $r->band }}</span>
                </div>

                {{-- Pembayaran --}}
                <div class="gd-row__pay">
                    <span class="gd-mobile-label">Pembayaran</span>
                    <span class="gd-pill {{ $r->is_unlocked ? 'gd-pill--green' : 'gd-pill--amber' }}">{{ $r->paymentLabel() }}</span>
                    @if ($order)
                        <button type="button" class="gd-copy" data-copy="{{ $order->code }}" title="Salin no. invoice"><span>{{ $order->code }}</span><i class="mdi mdi-content-copy"></i></button>
                        <span class="gd-row__meta">{{ $rupiah($order->amount) }}{{ $order->payment_type ? ' · '.str_replace('_', ' ', $order->payment_type) : '' }}</span>
                    @elseif (! $r->is_unlocked)
                        <span class="gd-row__meta">Belum checkout</span>
                    @endif
                    @if ($reportFailed)
                        <span class="gd-pill gd-pill--red mt-1"><i class="mdi mdi-alert"></i> Laporan AI gagal</span>
                    @endif
                </div>

                {{-- Tindak lanjut --}}
                <div class="gd-row__status">
                    <span class="gd-mobile-label">Tindak lanjut</span>
                    <span class="gd-pill {{ $stClass }}">{{ $stLabel }}</span>
                    <span class="gd-row__meta">PIC: {{ optional($r->pic)->name ?? '—' }}</span>
                </div>

                {{-- Aksi --}}
                <div class="gd-row__actions">
                    @if (! $r->is_unlocked)
                        <form action="{{ route('assessment-results.approve', $r->id) }}" method="post" data-confirm="Approve pembayaran {{ $title }} secara manual? Hasil akan terbuka dan laporan AI dibuat.">
                            @csrf
                            <button class="gd-btn gd-btn--sm gd-btn--success"><i class="mdi mdi-check"></i> Approve</button>
                        </form>
                    @endif
                    <a href="{{ route('assessment-results.show', $r->id) }}" class="gd-btn gd-btn--sm gd-btn--ghost">Detail</a>
                    <div class="dropdown">
                        <button type="button" class="gd-icon-btn gd-icon-btn--sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Aksi lain untuk {{ $title }}"><i class="mdi mdi-dots-vertical"></i></button>
                        <div class="dropdown-menu dropdown-menu-right gd-menu">
                            <a class="dropdown-item" href="{{ route('assessment.result', $r->uuid) }}" target="_blank" rel="noopener"><i class="mdi mdi-open-in-new"></i> Buka halaman hasil</a>
                            <button type="button" class="dropdown-item" data-copy="{{ route('assessment.result', $r->uuid) }}"><i class="mdi mdi-link-variant"></i> Salin link hasil</button>
                            @if ($wa)<a class="dropdown-item" href="{{ $wa }}" target="_blank" rel="noopener"><i class="mdi mdi-whatsapp"></i> Chat WhatsApp</a>@endif
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('assessment-results.destroy', $r->id) }}" method="post" data-confirm="Hapus hasil asesmen {{ $title }}?{{ $deleteNote }} Tindakan ini tidak bisa dibatalkan.">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect" value="{{ request()->fullUrl() }}">
                                <button class="dropdown-item gd-menu__danger"><i class="mdi mdi-delete"></i> Hapus hasil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="gd-empty-state">
                <span class="gd-empty-state__icon"><i class="mdi mdi-clipboard-text"></i></span>
                <p class="gd-empty-state__title">{{ $activeChips->isNotEmpty() || $tab !== 'all' ? 'Tidak ada hasil yang cocok' : 'Belum ada hasil asesmen' }}</p>
                <p class="gd-empty-state__desc">{{ $activeChips->isNotEmpty() || $tab !== 'all' ? 'Coba ubah tab atau hapus filter.' : 'Hasil dari guest atau input manual akan muncul di sini.' }}</p>
                @if ($activeChips->isNotEmpty() || $tab !== 'all')
                    <a href="{{ route('assessment-results.index') }}" class="gd-btn gd-btn--ghost">Lihat semua hasil</a>
                @endif
            </div>
        @endforelse
    </div>

    @if ($results->hasPages() || $results->total())
        <div class="gd-pager">
            <span class="gd-row__meta">Menampilkan {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} dari {{ $results->total() }} hasil</span>
            {{ $results->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    @endif
</section>

<div class="gd-toast" role="status" aria-live="polite" hidden></div>
@endsection

@section('js')
<script>
(function () {
    // Konfirmasi aman (teks dari atribut, bukan disisipkan ke string JS).
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) e.preventDefault();
        });
    });

    // Filter langsung diterapkan: dropdown saat berubah, pencarian setelah berhenti mengetik.
    var form = document.querySelector('form[data-autosubmit]');
    if (form) {
        form.querySelectorAll('select').forEach(function (s) { s.addEventListener('change', function () { form.submit(); }); });
        var q = form.querySelector('input[name=q]'), timer;
        q.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { form.submit(); }, 600);
        });
        q.addEventListener('search', function () { if (!q.value) form.submit(); });
        // Kembalikan kursor ke akhir kotak pencarian setelah reload.
        if (q.value) { q.focus(); q.setSelectionRange(q.value.length, q.value.length); }
    }

    // Salin teks (no. invoice / link hasil).
    var toast = document.querySelector('.gd-toast'), hide;
    var show = function (msg) {
        toast.textContent = msg; toast.hidden = false;
        clearTimeout(hide); hide = setTimeout(function () { toast.hidden = true; }, 1800);
    };
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-copy]');
        if (!btn) return;
        var text = btn.getAttribute('data-copy');
        var done = function () { show('Disalin: ' + (text.length > 48 ? text.slice(0, 48) + '…' : text)); };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(done);
        } else {
            var ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select();
            try { document.execCommand('copy'); done(); } catch (err) {} document.body.removeChild(ta);
        }
    });
})();
</script>
@endsection
