@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Saldo &amp; Pemakaian AI</h3>
        <span>
            <a href="{{ route('ai-settings.edit') }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-cog"></i> Pengaturan AI</a>
            <a href="{{ route('ai-usage.index', ['refresh' => 1]) }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-refresh"></i> Cek ulang saldo</a>
        </span>
    </div>
@endsection

@section('content')
@php $n = fn ($v) => number_format((int) $v, 0, ',', '.'); @endphp
<div class="row">
    <div class="col-lg-4 grid-margin">
        @php $active = config('ai.driver'); @endphp
        <div class="card mb-3 {{ $active === 'deepseek' ? 'border-danger' : '' }}"><div class="card-body">
            <h4 class="card-title mb-2">DeepSeek @if ($active === 'deepseek')<span class="badge badge-danger">AKTIF</span>@endif</h4>
            @if ($deepseek['ok'])
                @forelse ($deepseek['infos'] as $info)
                    <p class="mb-1" style="font-size:2rem;font-weight:700">{{ $info['currency'] }} {{ $info['total_balance'] }}</p>
                    <p class="text-muted small mb-2">Top up {{ $info['topped_up_balance'] ?? '-' }} · Bonus {{ $info['granted_balance'] ?? '-' }}</p>
                @empty
                    <p class="text-muted">Tidak ada data saldo.</p>
                @endforelse
                <span class="badge {{ $deepseek['available'] ? 'badge-success' : 'badge-danger' }}">{{ $deepseek['available'] ? 'Saldo cukup untuk dipakai' : 'Saldo habis — laporan AI akan gagal' }}</span>
            @else
                <p class="text-danger mb-0">Saldo tidak bisa dicek: {{ $deepseek['error'] }}</p>
            @endif
            <p class="text-muted small mt-2 mb-0">Model {{ config('ai.deepseek_model') }} · {{ $n($byDriver['deepseek']->calls ?? 0) }} panggilan bulan ini</p>
        </div></div>

        <div class="card {{ $active === 'claude' ? 'border-danger' : '' }}"><div class="card-body">
            <h4 class="card-title mb-2">Claude @if ($active === 'claude')<span class="badge badge-danger">AKTIF</span>@endif</h4>
            @if ($claude['key_ok'])
                <span class="badge badge-success">API key valid</span>
            @else
                <p class="text-danger mb-1">{{ $claude['key_error'] ?? 'API key tidak valid.' }}</p>
            @endif
            @if (isset($claude['cost']))
                @if ($claude['cost']['ok'])
                    <p class="mb-0 mt-2" style="font-size:2rem;font-weight:700">US$ {{ number_format($claude['cost']['month_usd'], 2) }}</p>
                    <p class="text-muted small mb-0">Biaya bulan ini (hari ini US$ {{ number_format($claude['cost']['today_usd'], 2) }}). Cost Report bisa tertunda beberapa jam.</p>
                @else
                    <p class="text-danger small mt-2 mb-0">Biaya tidak bisa dibaca: {{ $claude['cost']['error'] }}</p>
                @endif
            @else
                <p class="text-muted small mt-2 mb-0">Anthropic tidak menyediakan API sisa saldo. Isi <strong>Admin API key</strong> di <a href="{{ route('ai-settings.edit') }}">Pengaturan AI</a> untuk melihat biaya terpakai; sisa kredit dicek di <a href="https://console.anthropic.com/settings/billing" target="_blank" rel="noopener">Console › Billing</a>.</p>
            @endif
            <p class="text-muted small mt-2 mb-0">Model {{ config('ai.claude_model') }} · {{ $n($byDriver['claude']->calls ?? 0) }} panggilan bulan ini</p>
        </div></div>
        <p class="text-muted small mt-2">Dicek {{ $deepseek['checked_at'] }} (disimpan 5 menit). <a href="{{ route('ai-settings.edit') }}">Ganti AI aktif →</a></p>
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
                <thead><tr><th>Waktu</th><th>Kegunaan</th><th>AI</th><th>Hasil</th><th>Token</th><th>Durasi</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($recent as $log)
                    <tr>
                        <td><small>{{ $log->created_at?->format('d M H:i') }}</small></td>
                        <td><small>{{ \App\Models\AiUsageLog::PURPOSES[$log->purpose] ?? $log->purpose }}</small></td>
                        <td><small>{{ $log->model ?: $log->driver }}</small></td>
                        <td><small>@if ($log->result)<a href="{{ route('assessment-results.show', $log->result->id) }}">{{ $log->result->organization ?: $log->result->name }}</a>@else - @endif</small></td>
                        <td>{{ $n($log->total_tokens) }}</td>
                        <td><small>{{ $log->duration_ms ? round($log->duration_ms / 1000, 1).' dtk' : '-' }}</small></td>
                        <td>@if ($log->ok)<span class="badge badge-success">OK</span>@else<span class="badge badge-danger" title="{{ $log->error }}">Gagal</span><br><small class="text-danger">{{ \Illuminate\Support\Str::limit($log->error, 60) }}</small>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">Belum ada panggilan.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div></div>
    </div>
</div>
@endsection
