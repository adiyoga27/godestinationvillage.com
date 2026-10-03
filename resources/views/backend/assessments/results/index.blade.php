@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Hasil Asesmen Masuk</h3>
        <a href="{{ route('assessment-results.create') }}" class="gd-btn gd-btn--primary"><i class="mdi mdi-plus"></i> Input Manual</a>
    </div>
@endsection

@section('content')
@php
    $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    $statusTone = ['baru' => 'gd-pill--amber', 'dihubungi' => 'gd-pill--blue', 'selesai' => 'gd-pill--green'];
@endphp

<div class="gd-stats">
    <div class="gd-stat"><span class="gd-stat__icon"><i class="mdi mdi-clipboard-check"></i></span><div><p class="gd-stat__label">Total hasil</p><p class="gd-stat__value">{{ number_format($stats['total']) }}</p></div></div>
    <div class="gd-stat"><span class="gd-stat__icon gd-stat__icon--amber"><i class="mdi mdi-timer-sand"></i></span><div><p class="gd-stat__label">Menunggu bayar</p><p class="gd-stat__value">{{ number_format($stats['unpaid']) }}</p></div></div>
    <div class="gd-stat"><span class="gd-stat__icon gd-stat__icon--green"><i class="mdi mdi-check-circle"></i></span><div><p class="gd-stat__label">Lunas / terbuka</p><p class="gd-stat__value">{{ number_format($stats['paid']) }}</p></div></div>
    <div class="gd-stat"><span class="gd-stat__icon gd-stat__icon--blue"><i class="mdi mdi-cash"></i></span><div><p class="gd-stat__label">Pendapatan (Midtrans)</p><p class="gd-stat__value">{{ $rupiah($stats['revenue']) }}</p></div></div>
</div>

<section class="gd-card">
    <div class="gd-card__body">
        <form method="get" class="gd-filters">
            <div class="gd-filters__search">
                <i class="mdi mdi-magnify"></i>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control gd-input" placeholder="Cari nama, desa/usaha, WA, email, kab/kota, atau no. invoice">
            </div>
            <select name="track_id" class="form-control gd-input">
                <option value="">Semua jalur</option>
                @foreach ($tracks as $t)
                    <option value="{{ $t->id }}" @selected(($filters['track_id'] ?? '') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
            <select name="payment" class="form-control gd-input">
                <option value="">Semua pembayaran</option>
                <option value="belum" @selected(($filters['payment'] ?? '') === 'belum')>Belum bayar</option>
                <option value="lunas" @selected(($filters['payment'] ?? '') === 'lunas')>Lunas / terbuka</option>
            </select>
            <select name="status" class="form-control gd-input">
                <option value="">Semua tindak lanjut</option>
                @foreach (['baru', 'dihubungi', 'selesai'] as $st)
                    <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <select name="source" class="form-control gd-input">
                <option value="">Semua sumber</option>
                <option value="guest" @selected(($filters['source'] ?? '') === 'guest')>Diisi guest</option>
                <option value="admin" @selected(($filters['source'] ?? '') === 'admin')>Input admin</option>
            </select>
            <button class="gd-btn gd-btn--primary"><i class="mdi mdi-filter-variant"></i> Filter</button>
            @if (array_filter($filters))
                <a href="{{ route('assessment-results.index') }}" class="gd-btn gd-btn--ghost">Reset</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table gd-table">
                <thead>
                    <tr><th>Pengisi</th><th>Jalur</th><th>Skor</th><th>Invoice & Pembayaran</th><th>Tindak lanjut</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($results as $r)
                        @php $order = $r->latestOrder; @endphp
                        <tr>
                            <td>
                                <a href="{{ route('assessment-results.show', $r->id) }}" class="gd-table__title">{{ $r->organization ?: $r->name }}</a>
                                <div class="gd-table__meta">{{ collect([$r->regency, $r->province])->filter()->implode(', ') }}</div>
                                <div class="gd-table__meta">{{ $r->name }} · {{ $r->phone }}@if ($r->email) · {{ $r->email }}@endif</div>
                                <div class="gd-table__meta">{{ $r->created_at?->format('d M Y H:i') }}@if ($r->source === 'admin') · <span class="gd-pill gd-pill--ink">Input admin</span>@endif</div>
                            </td>
                            <td><span class="gd-table__meta-strong">{{ optional($r->track)->name }}</span></td>
                            <td><span class="gd-score">{{ number_format($r->total_score, 0) }}</span><div class="gd-table__meta">{{ $r->band }}</div></td>
                            <td>
                                <span class="gd-pill {{ $r->is_unlocked ? 'gd-pill--green' : 'gd-pill--amber' }}">{{ $r->paymentLabel() }}</span>
                                @if ($order)
                                    <div class="gd-table__meta mt-1"><strong>{{ $order->code }}</strong> · {{ $rupiah($order->amount) }}</div>
                                    <div class="gd-table__meta">{{ strtoupper($order->gateway) }}{{ $order->payment_type ? ' · '.str_replace('_', ' ', $order->payment_type) : '' }}{{ $order->paid_at ? ' · '.$order->paid_at->format('d M Y H:i') : '' }}</div>
                                @elseif (! $r->is_unlocked)
                                    <div class="gd-table__meta mt-1">Belum checkout</div>
                                @endif
                            </td>
                            <td>
                                <span class="gd-pill {{ $statusTone[$r->status] ?? 'gd-pill--amber' }}">{{ ucfirst($r->status) }}</span>
                                <div class="gd-table__meta mt-1">PIC: {{ optional($r->pic)->name ?? '—' }}</div>
                            </td>
                            <td class="text-right text-nowrap">
                                @if (! $r->is_unlocked)
                                    <form action="{{ route('assessment-results.approve', $r->id) }}" method="post" class="d-inline" onsubmit="return confirm('Approve pembayaran {{ $r->organization ?: $r->name }} secara manual? Hasil akan terbuka dan laporan AI dibuat.')">
                                        @csrf
                                        <button class="gd-btn gd-btn--sm gd-btn--success"><i class="mdi mdi-check"></i> Approve</button>
                                    </form>
                                @endif
                                <a href="{{ route('assessment-results.show', $r->id) }}" class="gd-btn gd-btn--sm gd-btn--ghost">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada hasil yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $results->links() }}</div>
    </div>
</section>
@endsection
