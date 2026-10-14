@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Saldo &amp; Pemakaian AI</h3>
        <a href="{{ route('ai-usage.index', ['refresh' => 1]) }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-refresh"></i> Cek ulang saldo</a>
    </div>
@endsection

@section('content')
@php $n = fn ($v) => number_format((int) $v, 0, ',', '.'); @endphp
<div class="row">
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Saldo DeepSeek</h4>
            @if ($balance['ok'])
                @forelse ($balance['infos'] as $info)
                    <p class="display-4 mb-1" style="font-size:2rem">{{ $info['currency'] }} {{ $info['total_balance'] }}</p>
                    <p class="text-muted small mb-2">Top up {{ $info['topped_up_balance'] ?? '-' }} · Bonus {{ $info['granted_balance'] ?? '-' }}</p>
                @empty
                    <p class="text-muted">Tidak ada data saldo.</p>
                @endforelse
                <span class="badge {{ $balance['available'] ? 'badge-success' : 'badge-danger' }}">{{ $balance['available'] ? 'Saldo cukup untuk dipakai' : 'Saldo habis — laporan AI akan gagal' }}</span>
            @else
                <p class="text-danger mb-0">Saldo tidak bisa dicek: {{ $balance['error'] }}</p>
            @endif
            <p class="text-muted small mt-3 mb-0">Dicek {{ $balance['checked_at'] }} (disimpan 5 menit). Driver aktif: <strong>{{ config('ai.driver') }}</strong> · model <strong>{{ config('ai.model') }}</strong></p>
        </div></div>
    </div>
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Pemakaian token</h4>
            <div class="table-responsive"><table class="table table-sm">
                <thead><tr><th></th><th>Panggilan</th><th>Gagal</th><th>Input</th><th>Cache hit</th><th>Output</th><th>Reasoning</th><th>Total</th></tr></thead>
                <tbody>
                @foreach ($periods as $label => $p)
                    <tr><th>{{ $label }}</th><td>{{ $n($p->calls) }}</td><td class="{{ $p->failed ? 'text-danger' : '' }}">{{ $n($p->failed) }}</td><td>{{ $n($p->prompt) }}</td><td>{{ $n($p->cache_hit) }}</td><td>{{ $n($p->completion) }}</td><td>{{ $n($p->reasoning) }}</td><td><strong>{{ $n($p->total) }}</strong></td></tr>
                @endforeach
                </tbody>
            </table></div>
            <h5 class="mt-4">Per kegunaan</h5>
            <ul class="mb-0">
                @forelse ($byPurpose as $row)
                    <li>{{ \App\Models\AiUsageLog::PURPOSES[$row->purpose] ?? $row->purpose }}: {{ $n($row->calls) }} panggilan ({{ $n($row->failed) }} gagal), {{ $n($row->total) }} token</li>
                @empty
                    <li class="text-muted">Belum ada pemakaian.</li>
                @endforelse
            </ul>
        </div></div>
    </div>
</div>
<div class="row">
    <div class="col-lg-5 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Harian (30 hari)</h4>
            <div class="table-responsive"><table class="table table-sm">
                <thead><tr><th>Tanggal</th><th>Panggilan</th><th>Gagal</th><th>Total token</th></tr></thead>
                <tbody>
                @forelse ($daily as $d)
                    <tr><td>{{ \Illuminate\Support\Carbon::parse($d->day)->format('d M Y') }}</td><td>{{ $n($d->calls) }}</td><td>{{ $n($d->failed) }}</td><td>{{ $n($d->total) }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-muted">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div></div>
    </div>
    <div class="col-lg-7 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">50 panggilan terakhir</h4>
            <div class="table-responsive"><table class="table table-sm">
                <thead><tr><th>Waktu</th><th>Kegunaan</th><th>Hasil</th><th>Token</th><th>Durasi</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($recent as $log)
                    <tr>
                        <td><small>{{ $log->created_at?->format('d M H:i') }}</small></td>
                        <td><small>{{ \App\Models\AiUsageLog::PURPOSES[$log->purpose] ?? $log->purpose }}</small></td>
                        <td><small>@if ($log->result)<a href="{{ route('assessment-results.show', $log->result->id) }}">{{ $log->result->organization ?: $log->result->name }}</a>@else - @endif</small></td>
                        <td>{{ $n($log->total_tokens) }}</td>
                        <td><small>{{ $log->duration_ms ? round($log->duration_ms / 1000, 1).' dtk' : '-' }}</small></td>
                        <td>@if ($log->ok)<span class="badge badge-success">OK</span>@else<span class="badge badge-danger" title="{{ $log->error }}">Gagal</span><br><small class="text-danger">{{ \Illuminate\Support\Str::limit($log->error, 60) }}</small>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">Belum ada panggilan.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div></div>
    </div>
</div>
@endsection
