@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <div class="d-flex align-items-center" style="gap:.875rem">
            <span class="ai-page-icon"><i class="mdi mdi-creation"></i></span>
            <div>
                <h3 class="page-title mb-1">Pengaturan AI</h3>
                <p class="gd-subtitle">AI penyusun laporan strategi &amp; terjemahan asesmen — penyedia aktif, API key, model, saldo, dan pemakaian.</p>
            </div>
        </div>
        <a href="{{ route('ai-settings.edit', ['refresh' => 1]) }}" class="gd-btn gd-btn--ghost"><i class="mdi mdi-refresh"></i> Perbarui status &amp; model</a>
    </div>
@endsection

@section('content')
@php
    $providers = [
        'deepseek' => ['name' => 'DeepSeek', 'logo' => 'assets/admin/ai/deepseek.svg', 'tint' => '#4D6BFE'],
        'claude' => ['name' => 'Claude', 'vendor' => 'Anthropic', 'logo' => 'assets/admin/ai/claude.svg', 'tint' => '#D97757'],
    ];
    $driver = old('driver', config('ai.driver'));
    $tab = old('tab', session('ai_tab', $driver));
    $n = fn ($v) => number_format((int) $v, 0, ',', '.');
    // Angka token ringkas: 12.345 → "12,3 rb", 2.500.000 → "2,5 jt".
    $compact = fn ($v) => $v >= 1e6 ? number_format($v / 1e6, 1, ',', '.').' jt' : ($v >= 1e3 ? number_format($v / 1e3, 1, ',', '.').' rb' : $n($v));
    $source = fn ($key) => match ($sources[$key] ?? null) {
        'admin' => ['diatur di admin', 'gd-pill--green'],
        'env' => ['dari .env', 'gd-pill--blue'],
        default => ['belum diisi', 'gd-pill--ink'],
    };
    $usd = fn ($v) => 'US$ '.number_format((float) $v, 2, ',', '.');
    $deepseek = $accounts['deepseek'];
    $claude = $accounts['claude'];
    $keyOk = ['deepseek' => $deepseek['ok'], 'claude' => $claude['key_ok']];
    $maxDaily = max(1, (int) $daily->max('total'));
    $maxPurpose = max(1, (int) $byPurpose->max('total'));
@endphp

@if (session('status'))<div class="gd-callout gd-callout--green mt-0 mb-3"><i class="mdi mdi-check-circle"></i> {{ session('status') }}</div>@endif
@if (session('error'))<div class="gd-callout gd-callout--red mt-0 mb-3"><i class="mdi mdi-alert-circle"></i> {{ session('error') }}</div>@endif
@include('backend.partials.form.errors')

<form action="{{ route('ai-settings.update') }}" method="post" autocomplete="off">
    @csrf
    @method('PUT')
    <input type="hidden" name="tab" value="{{ $tab }}" data-ai-tab-input>

    {{-- ============ PILIH AI AKTIF ============ --}}
    <p class="gd-eyebrow mb-2">AI aktif untuk asesmen</p>
    <div class="ai-providers">
        @foreach ($providers as $key => $p)
            @php $month = $monthByDriver[$key] ?? null; @endphp
            <label class="ai-provider {{ $driver === $key ? 'is-active' : '' }}" style="--tint: {{ $p['tint'] }}">
                <input type="radio" name="driver" value="{{ $key }}" class="ai-provider__radio" {{ $driver === $key ? 'checked' : '' }}>
                <div class="ai-provider__head">
                    <span class="ai-provider__logo"><img src="{{ url($p['logo']) }}" alt="" width="30" height="30"></span>
                    <div class="flex-grow-1">
                        <p class="ai-provider__name">{{ $p['name'] }}</p>
                        <p class="ai-provider__model">{{ config('ai.'.$key.'_model') }}</p>
                    </div>
                    <span class="ai-provider__check"><i class="mdi mdi-check"></i></span>
                </div>

                <div class="ai-provider__money">
                    @php $credit = $credits[$key] ?? null; @endphp
                    <span class="ai-provider__label">Sisa kredit layanan</span>
                    <strong class="{{ ($credit->balance ?? 0) <= 0 ? 'is-muted' : '' }}">{{ $usd($credit->balance ?? 0) }}</strong>
                    <span class="ai-provider__sub">Terpakai bulan ini {{ $usd($month->charged ?? 0) }}</span>
                </div>

                <div class="ai-provider__foot">
                    @if ($keyOk[$key])
                        <span class="gd-pill gd-pill--green"><i class="mdi mdi-check-circle"></i> Terhubung</span>
                    @elseif (blank(config('ai.'.$key.'_key')))
                        <span class="gd-pill gd-pill--ink"><i class="mdi mdi-key-remove"></i> Belum ada API key</span>
                    @else
                        <span class="gd-pill gd-pill--red"><i class="mdi mdi-alert-circle"></i> Gagal terhubung</span>
                    @endif
                    @if (($credits[$key]->balance ?? 0) <= 0)
                        <span class="gd-pill gd-pill--red">Kredit habis</span>
                    @endif
                    <span class="ai-provider__usage">{{ $n($month->calls ?? 0) }} panggilan · {{ $compact($month->total ?? 0) }} token bulan ini</span>
                </div>
                <span class="ai-provider__badge">AKTIF</span>
            </label>
        @endforeach
    </div>

    {{-- ============ KREDENSIAL & MODEL ============ --}}
    <section class="gd-card mt-4">
        <header class="gd-card__head">
            <span class="gd-card__icon"><i class="mdi mdi-key-variant"></i></span>
            <div>
                <h2 class="gd-card__title">Kredensial &amp; model</h2>
                <p class="gd-card__desc">Disimpan terenkripsi dan menimpa <code>.env</code>. Kolom key yang dibiarkan kosong tidak mengubah key tersimpan.</p>
            </div>
        </header>
        <nav class="gd-tabs mt-3" role="tablist">
            @foreach ($providers as $key => $p)
                <button type="button" class="gd-tabs__item ai-tab {{ $tab === $key ? 'is-active' : '' }}" data-ai-tab="{{ $key }}" role="tab">
                    <img src="{{ url($p['logo']) }}" alt="" width="16" height="16"> {{ $p['name'] }}
                    @if ($driver === $key)<span class="gd-tabs__count">aktif</span>@endif
                </button>
            @endforeach
        </nav>
        <div class="gd-card__body">
            @foreach ($providers as $key => $p)
                @php
                    $list = $models[$key];
                    $current = old($key.'_model', config('ai.'.$key.'_model'));
                    $secretFields = $key === 'claude'
                        ? [['claude_key', 'API key', 'sk-ant-api…', null], ['claude_admin_key', 'Admin API key', 'sk-ant-admin… (opsional)', 'Untuk membaca biaya terpakai (Cost Report). Buat di Console › Settings › Admin keys. Tidak dipakai untuk menyusun laporan.']]
                        : [['deepseek_key', 'API key', 'sk-…', null]];
                @endphp
                <div class="ai-pane" data-ai-pane="{{ $key }}" @if ($tab !== $key) hidden @endif>
                    <div class="gd-grid-2">
                        @foreach ($secretFields as [$field, $label, $placeholder, $hint])
                            @php [$srcLabel, $srcClass] = $source($field); @endphp
                            <div class="gd-field">
                                <label class="gd-label">{{ $label }} <span class="gd-pill {{ $srcClass }}">{{ $srcLabel }}</span></label>
                                @if ($masked[$field])
                                    <div class="ai-saved {{ ($sources[$field] ?? null) === 'admin' ? '' : 'is-env' }}">
                                        <i class="mdi {{ ($sources[$field] ?? null) === 'admin' ? 'mdi-shield-check' : 'mdi-file-cog-outline' }}"></i>
                                        <div>
                                            <strong class="text-monospace">{{ $masked[$field] }}</strong>
                                            <span>{{ ($sources[$field] ?? null) === 'admin'
                                                ? 'Tersimpan di database (terenkripsi)'.(isset($savedAt[$field]) ? ' · '.\Illuminate\Support\Carbon::parse($savedAt[$field])->translatedFormat('d M Y H:i') : '')
                                                : 'Dari file .env server' }}</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="ai-secret">
                                    <i class="mdi mdi-lock-outline"></i>
                                    <input type="password" name="{{ $field }}" class="form-control gd-input" autocomplete="new-password"
                                        placeholder="{{ $masked[$field] ? 'Ketik key baru hanya bila ingin mengganti' : $placeholder }}">
                                </div>
                                @if ($hint)<p class="gd-hint">{{ $hint }}</p>@endif
                                @if (($sources[$field] ?? null) === 'admin')
                                    <label class="ai-check mt-2"><input type="checkbox" name="clear[]" value="{{ $field }}"> Hapus key ini (kembali ke .env)</label>
                                @endif
                            </div>
                        @endforeach

                        @php [$srcLabel, $srcClass] = $source($key.'_model'); @endphp
                        <div class="gd-field">
                            <label class="gd-label">Model <span class="gd-pill {{ $srcClass }}">{{ $srcLabel }}</span></label>
                            @if ($list['ok'] && $list['models'])
                                @php $inList = array_key_exists($current, $list['models']); @endphp
                                <select name="{{ $key }}_model" class="form-control gd-input" data-ai-model="{{ $key }}">
                                    @unless ($inList)
                                        <option value="{{ $current }}" selected>{{ $current }} (tersimpan — tidak ada di daftar)</option>
                                    @endunless
                                    @foreach ($list['models'] as $id => $label)
                                        <option value="{{ $id }}" {{ $current === $id ? 'selected' : '' }}>{{ $label }}{{ $key === 'claude' && $id === 'claude-opus-5-5' ? ' (disarankan)' : '' }}</option>
                                    @endforeach
                                    <option value="__custom">Ketik nama model lain…</option>
                                </select>
                                <input type="text" name="{{ $key }}_model_custom" class="form-control gd-input mt-2 text-monospace" placeholder="nama model" data-ai-custom="{{ $key }}" hidden disabled>
                                <p class="gd-hint">{{ count($list['models']) }} model dari API {{ $p['name'] }}.
                                    @if ($key === 'claude') Disarankan Claude Opus 5.5; Sonnet 5.5 / Haiku 5.5 lebih hemat.@endif</p>
                            @else
                                <input type="text" name="{{ $key }}_model" value="{{ $current }}" class="form-control gd-input text-monospace">
                                <p class="gd-hint">Daftar model belum bisa dimuat: {{ $list['error'] ?? '-' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center" style="gap:.5rem">
                        <button type="submit" name="test" value="{{ $key }}" class="gd-btn gd-btn--ghost gd-btn--sm"><i class="mdi mdi-lan-connect"></i> Simpan &amp; tes koneksi {{ $p['name'] }}</button>
                        <span class="gd-hint m-0">Menyimpan semua isian di halaman ini, lalu mengecek key. Tidak memakai token.</span>
                    </div>
                </div>
            @endforeach

            <details class="gd-details mt-4">
                <summary>Pengaturan lanjutan</summary>
                @php [$srcLabel, $srcClass] = $source('timeout'); @endphp
                <div class="gd-field mt-3 mb-0" style="max-width:260px">
                    <label class="gd-label">Batas waktu (detik) <span class="gd-pill {{ $srcClass }}">{{ $srcLabel }}</span></label>
                    <input type="number" name="timeout" value="{{ old('timeout', config('ai.timeout')) }}" min="30" max="600" class="form-control gd-input">
                    <p class="gd-hint">Claude memakai minimal 180 detik karena model berpikir dulu sebelum menjawab.</p>
                </div>
            </details>
        </div>
    </section>

    @include('backend.partials.form.actionbar', ['cancel' => route('ai-settings.edit'), 'label' => 'Simpan Pengaturan AI'])
</form>


{{-- ============ PEMAKAIAN ============ --}}
<h2 class="gd-section-title mt-5" id="pemakaian"><i class="mdi mdi-chart-box-outline"></i> Pemakaian AI</h2>
<p class="gd-subtitle mb-3">Dicatat dari setiap panggilan API (laporan, terjemahan laporan, terjemahan soal).</p>

<div class="gd-stats">
    @foreach ($periods as $label => $p)
        <div class="gd-stat">
            <span class="gd-stat__icon {{ $loop->first ? '' : ($loop->last ? 'gd-stat__icon--green' : 'gd-stat__icon--blue') }}"><i class="mdi mdi-{{ ['calendar-today', 'calendar-week', 'calendar-month', 'sigma'][$loop->index] }}"></i></span>
            <div>
                <p class="gd-stat__label">{{ $label }}</p>
                <p class="gd-stat__value">{{ $compact($p->total) }} <small class="ai-unit">token</small></p>
                <p class="gd-stat__hint">{{ $usd($p->charged) }} · {{ $n($p->calls) }} panggilan @if ($p->failed)· <span class="text-danger">{{ $n($p->failed) }} gagal</span>@endif</p>
            </div>
        </div>
    @endforeach
</div>

<div class="ai-usage-grid">
    <section class="gd-card">
        <header class="gd-card__head">
            <span class="gd-card__icon"><i class="mdi mdi-chart-bar"></i></span>
            <div><h2 class="gd-card__title">Token per hari</h2><p class="gd-card__desc">30 hari terakhir</p></div>
        </header>
        <div class="gd-card__body">
            <div class="ai-chart" role="img" aria-label="Grafik token per hari, 30 hari terakhir">
                @for ($i = 29; $i >= 0; $i--)
                    @php
                        $day = today()->subDays($i);
                        $row = $daily[$day->toDateString()] ?? null;
                        $h = $row ? max(4, round($row->total / $maxDaily * 100)) : 0;
                    @endphp
                    <div class="ai-chart__col" title="{{ $day->translatedFormat('d M') }}: {{ $n($row->total ?? 0) }} token · {{ $n($row->calls ?? 0) }} panggilan{{ ($row->failed ?? 0) ? ' · '.$row->failed.' gagal' : '' }}">
                        <span class="ai-chart__bar {{ ($row->failed ?? 0) ? 'has-failed' : '' }}" style="height: {{ $h }}%"></span>
                    </div>
                @endfor
            </div>
            <div class="ai-chart__axis"><span>{{ today()->subDays(29)->translatedFormat('d M') }}</span><span>Hari ini</span></div>
            <p class="gd-hint"><span class="ai-dot"></span> Token &nbsp; <span class="ai-dot ai-dot--red"></span> Ada panggilan gagal</p>
        </div>
    </section>

    <section class="gd-card">
        <header class="gd-card__head">
            <span class="gd-card__icon"><i class="mdi mdi-format-list-bulleted-type"></i></span>
            <div><h2 class="gd-card__title">Per kegunaan</h2><p class="gd-card__desc">Sejak pencatatan dimulai</p></div>
        </header>
        <div class="gd-card__body">
            @forelse ($byPurpose as $row)
                <div class="mb-3">
                    <div class="gd-dim__head">
                        <span class="gd-dim__name">{{ \App\Models\AiUsageLog::PURPOSES[$row->purpose] ?? $row->purpose }}</span>
                        <span class="gd-table__meta-strong">{{ $compact($row->total) }} token</span>
                    </div>
                    <div class="gd-bar"><span class="gd-bar__fill" style="width: {{ max(2, round($row->total / $maxPurpose * 100)) }}%; background: var(--gd-brand)"></span></div>
                    <p class="gd-dim__meta">{{ $n($row->calls) }} panggilan{{ $row->failed ? ' · '.$n($row->failed).' gagal' : '' }}</p>
                </div>
            @empty
                <div class="gd-empty"><i class="mdi mdi-chart-donut"></i><p>Belum ada pemakaian.</p></div>
            @endforelse
        </div>
    </section>
</div>

<section class="gd-card mt-4">
    <header class="gd-card__head">
        <span class="gd-card__icon"><i class="mdi mdi-history"></i></span>
        <div><h2 class="gd-card__title">Panggilan terakhir</h2><p class="gd-card__desc">30 panggilan API terbaru</p></div>
    </header>
    <div class="gd-card__body pt-2">
        <div class="table-responsive">
            <table class="table gd-table">
                <thead><tr><th>Waktu</th><th>AI</th><th>Kegunaan</th><th>Hasil asesmen</th><th class="text-right">Token</th><th class="text-right">Kredit</th><th class="text-right">Durasi</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($recent as $log)
                    @php $p = $providers[$log->driver] ?? null; @endphp
                    <tr>
                        <td class="text-nowrap"><span class="gd-table__meta-strong">{{ $log->created_at?->translatedFormat('d M') }}</span><br><span class="gd-table__meta">{{ $log->created_at?->format('H:i') }}</span></td>
                        <td class="text-nowrap">
                            @if ($p)<img src="{{ url($p['logo']) }}" alt="" class="ai-logo-sm">@endif
                            <span class="gd-table__meta">{{ $log->model ?: $log->driver }}</span>
                        </td>
                        <td><span class="gd-table__meta">{{ \App\Models\AiUsageLog::PURPOSES[$log->purpose] ?? $log->purpose }}</span></td>
                        <td>@if ($log->result)<a href="{{ route('assessment-results.show', $log->result->id) }}" class="gd-table__title">{{ $log->result->organization ?: $log->result->name }}</a>@else<span class="gd-table__meta">—</span>@endif</td>
                        <td class="text-right"><span class="gd-table__meta-strong">{{ $n($log->total_tokens) }}</span>@if ($log->reasoning_tokens)<br><span class="gd-table__meta">{{ $compact($log->reasoning_tokens) }} reasoning</span>@endif</td>
                        <td class="text-right"><span class="gd-table__meta-strong">{{ number_format($log->charged, 4, ',', '.') }}</span></td>
                        <td class="text-right"><span class="gd-table__meta">{{ $log->duration_ms ? number_format($log->duration_ms / 1000, 1, ',', '.').' dtk' : '—' }}</span></td>
                        <td>
                            @if ($log->ok)
                                <span class="gd-pill gd-pill--green">OK</span>
                            @else
                                <span class="gd-pill gd-pill--red">Gagal</span>
                                <div class="gd-table__meta text-danger" style="max-width:260px">{{ \Illuminate\Support\Str::limit($log->error, 90) }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="gd-empty"><i class="mdi mdi-robot-outline"></i><p>Belum ada panggilan AI.</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('js')
<style>
    .ai-page-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 15px; background: linear-gradient(135deg, #d81c25, #7c3aed); color: #fff; font-size: 1.5rem; box-shadow: 0 12px 24px -12px rgba(124, 58, 237, .7); flex-shrink: 0; }
    .ai-providers { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
    .ai-provider { position: relative; display: flex; flex-direction: column; gap: 1rem; margin: 0; padding: 1.25rem; background: #fff; border: 2px solid #ece9e2; border-radius: 20px; cursor: pointer; transition: border-color .15s, box-shadow .15s, transform .15s; }
    .ai-provider:hover { border-color: color-mix(in srgb, var(--tint) 45%, #ece9e2); transform: translateY(-1px); }
    .ai-provider.is-active { border-color: var(--tint); box-shadow: 0 18px 36px -22px var(--tint); }
    .ai-provider__radio { position: absolute; opacity: 0; pointer-events: none; }
    .ai-provider__radio:focus-visible + .ai-provider__head { outline: 2px solid var(--tint); outline-offset: 4px; border-radius: 12px; }
    .ai-provider__head { display: flex; align-items: center; gap: .875rem; }
    .ai-provider__logo { display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; border-radius: 16px; background: color-mix(in srgb, var(--tint) 12%, #fff); flex-shrink: 0; }
    .ai-provider__name { margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--gd-ink); }
    .ai-provider__model { margin: 0; font-family: SFMono-Regular, Menlo, monospace; font-size: .75rem; color: #8a8797; word-break: break-all; }
    .ai-provider__check { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; border: 2px solid #d9d5cc; color: transparent; flex-shrink: 0; }
    .ai-provider.is-active .ai-provider__check { background: var(--tint); border-color: var(--tint); color: #fff; }
    .ai-provider__money { display: flex; flex-direction: column; gap: .125rem; padding: .875rem 1rem; border-radius: 14px; background: #faf9f6; }
    .ai-provider__money strong { font-size: 1.6rem; font-weight: 800; line-height: 1.15; color: var(--gd-ink); }
    .ai-provider__money strong.is-muted { color: #c4c0b6; }
    .ai-provider__label { font-size: .7rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #8a8797; }
    .ai-provider__sub { font-size: .75rem; color: #8a8797; }
    .ai-provider__foot { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; }
    .ai-provider__usage { margin-left: auto; font-size: .75rem; color: #8a8797; }
    .ai-provider__badge { position: absolute; top: -11px; right: 18px; display: none; padding: .15rem .6rem; border-radius: 99px; background: var(--tint); color: #fff; font-size: .65rem; font-weight: 800; letter-spacing: .1em; }
    .ai-provider.is-active .ai-provider__badge { display: inline-block; }
    body.gd-admin .content-wrapper .gd-table td img.ai-logo-sm, .ai-logo-sm { width: 16px !important; height: 16px !important; border-radius: 0 !important; margin-right: .375rem; vertical-align: -3px; }
    .ai-tab { display: inline-flex; align-items: center; gap: .4rem; background: none; border: 0; }
    .ai-saved { display: flex; align-items: center; gap: .625rem; margin-bottom: .5rem; padding: .625rem .875rem; border-radius: 12px; background: #effaf3; color: #14532d; font-size: .8rem; }
    .ai-saved > i { font-size: 1.25rem; color: #15803d; }
    .ai-saved strong { display: block; font-size: .85rem; }
    .ai-saved span { color: #3f6b50; }
    .ai-saved.is-env { background: #e8f0fb; color: #1e3a8a; } .ai-saved.is-env > i { color: #1d4ed8; } .ai-saved.is-env span { color: #3b5b99; }
    .ai-secret { position: relative; }
    .ai-secret > i { position: absolute; left: .875rem; top: 50%; transform: translateY(-50%); color: #8a8797; pointer-events: none; }
    body.gd-admin .ai-secret .gd-input { padding-left: 2.4rem; font-family: SFMono-Regular, Menlo, monospace; }
    .ai-check { display: inline-flex; align-items: center; gap: .5rem; margin: 0; font-size: .8rem; color: #4b4959; cursor: pointer; }
    .ai-check input { width: 16px; height: 16px; margin: 0; position: static; opacity: 1; accent-color: var(--gd-brand); }
    .ai-unit { font-size: .7rem; font-weight: 600; color: #8a8797; }
    .ai-usage-grid { display: grid; gap: 1.25rem; grid-template-columns: minmax(0, 1fr); }
    @media (min-width: 992px) { .ai-usage-grid { grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr); } }
    .ai-chart { display: flex; align-items: flex-end; gap: 3px; height: 160px; padding-bottom: 2px; border-bottom: 1px solid #ece9e2; }
    .ai-chart__col { flex: 1; height: 100%; display: flex; align-items: flex-end; }
    .ai-chart__bar { width: 100%; border-radius: 4px 4px 0 0; background: linear-gradient(180deg, #e63f49, #d81c25); }
    .ai-chart__bar.has-failed { background: linear-gradient(180deg, #e63f49 70%, #f59e0b 70%); }
    .ai-chart__col:hover .ai-chart__bar { filter: brightness(1.15); }
    .ai-chart__axis { display: flex; justify-content: space-between; margin-top: .375rem; font-size: .7rem; color: #8a8797; }
    .ai-dot { display: inline-block; width: 8px; height: 8px; border-radius: 2px; background: #d81c25; vertical-align: middle; }
    .ai-dot--red { background: #f59e0b; }
</style>
<script>
(function () {
    // Kartu penyedia = radio "AI aktif".
    var cards = document.querySelectorAll('.ai-provider');
    cards.forEach(function (card) {
        card.querySelector('input').addEventListener('change', function () {
            cards.forEach(function (c) { c.classList.toggle('is-active', c.querySelector('input').checked); });
        });
    });

    // Tab kredensial per penyedia.
    var tabInput = document.querySelector('[data-ai-tab-input]');
    document.querySelectorAll('[data-ai-tab]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var key = btn.getAttribute('data-ai-tab');
            document.querySelectorAll('[data-ai-tab]').forEach(function (b) { b.classList.toggle('is-active', b === btn); });
            document.querySelectorAll('[data-ai-pane]').forEach(function (p) { p.hidden = p.getAttribute('data-ai-pane') !== key; });
            tabInput.value = key;
        });
    });

    // Model: pilihan "ketik manual" memunculkan isian teks.
    document.querySelectorAll('[data-ai-model]').forEach(function (select) {
        var custom = document.querySelector('[data-ai-custom="' + select.getAttribute('data-ai-model') + '"]');
        var sync = function () {
            var on = select.value === '__custom';
            custom.hidden = !on;
            custom.disabled = !on;
            custom.required = on;
            if (on) custom.focus();
        };
        select.addEventListener('change', sync);
    });
})();
</script>
@endsection
