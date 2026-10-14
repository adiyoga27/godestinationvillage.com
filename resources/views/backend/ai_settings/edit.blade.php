@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Pengaturan AI</h3>
        <a href="{{ route('ai-usage.index') }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-chart-bar"></i> Saldo &amp; Pemakaian AI</a>
    </div>
@endsection

@section('content')
@php
    $badge = fn ($key) => match ($sources[$key] ?? null) {
        'admin' => '<span class="badge badge-success">diatur di admin</span>',
        'env' => '<span class="badge badge-info">dari .env</span>',
        default => '<span class="badge badge-secondary">kosong</span>',
    };
    $driver = old('driver', config('ai.driver'));
@endphp
<div class="row">
    <div class="col-lg-9 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
            @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

            <div class="alert alert-info">
                AI dipakai untuk menyusun laporan strategi asesmen dan terjemahan English. Pengaturan di sini menimpa nilai di <code>.env</code>;
                kolom yang dikosongkan kembali memakai nilai <code>.env</code>. API key disimpan terenkripsi dan tidak pernah ditampilkan utuh.
            </div>

            <form action="{{ route('ai-settings.update') }}" method="post" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="d-block"><strong>AI aktif untuk asesmen</strong> {!! $badge('driver') !!}</label>
                    @foreach (\App\Support\AiSettings::DRIVERS as $key => $label)
                        <label class="ai-radio mr-4">
                            <input type="radio" name="driver" value="{{ $key }}" {{ $driver === $key ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                    <small class="d-block text-muted">Laporan baru langsung memakai AI yang dipilih. Laporan yang sudah jadi tidak berubah.</small>
                </div>

                @foreach ([
                    'deepseek' => ['title' => 'DeepSeek', 'keys' => [['deepseek_key', 'API Key', 'sk-...']], 'model_hint' => 'mis. deepseek-flash, deepseek-chat'],
                    'claude' => ['title' => 'Claude (Anthropic)', 'keys' => [['claude_key', 'API Key', 'sk-ant-api...'], ['claude_admin_key', 'Admin API Key (opsional)', 'sk-ant-admin...']], 'model_hint' => 'Disarankan claude-opus-5-5. Lebih hemat: claude-sonnet-5-5 / claude-haiku-5-5.'],
                ] as $provider => $cfg)
                    <div class="ai-provider {{ $driver === $provider ? 'is-active' : '' }}" data-provider="{{ $provider }}">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3" style="gap:.5rem">
                            <h4 class="mb-0">{{ $cfg['title'] }} <span class="badge badge-danger ai-active-badge">AKTIF</span></h4>
                            <button type="submit" form="test-{{ $provider }}" class="btn btn-sm btn-outline-primary"><i class="mdi mdi-lan-connect"></i> Tes koneksi</button>
                        </div>
                        @foreach ($cfg['keys'] as [$key, $label, $placeholder])
                            <div class="form-group">
                                <label>{{ $label }} {!! $badge($key) !!}</label>
                                <input type="password" name="{{ $key }}" class="form-control text-monospace" placeholder="{{ $masked[$key] ? 'Tersimpan: '.$masked[$key].' — kosongkan bila tidak diubah' : $placeholder }}" autocomplete="new-password">
                                @if ($key === 'claude_admin_key')
                                    <small class="text-muted d-block">Anthropic tidak menyediakan API sisa saldo. Admin key (dibuat di Console › Settings › Admin keys) dipakai untuk membaca <strong>biaya terpakai</strong> bulan ini.</small>
                                @endif
                                @if ($sources[$key] === 'admin')
                                    <label class="ai-radio mt-1"><input type="checkbox" name="clear[]" value="{{ $key }}"> <small>Hapus key ini (kembali ke .env)</small></label>
                                @endif
                            </div>
                        @endforeach
                        <div class="form-group mb-0">
                            <label>Model {!! $badge($provider.'_model') !!}</label>
                            <input type="text" name="{{ $provider }}_model" value="{{ old($provider.'_model', config('ai.'.$provider.'_model')) }}" class="form-control text-monospace">
                            <small class="text-muted">{{ $cfg['model_hint'] }}</small>
                        </div>
                    </div>
                @endforeach

                <div class="form-group mt-4">
                    <label>Batas waktu permintaan (detik) {!! $badge('timeout') !!}</label>
                    <input type="number" name="timeout" value="{{ old('timeout', config('ai.timeout')) }}" min="30" max="600" class="form-control" style="max-width:160px">
                    <small class="text-muted">Claude memakai minimal 180 detik karena model berpikir dulu sebelum menjawab.</small>
                </div>

                <button class="btn btn-gradient-danger"><i class="mdi mdi-content-save"></i> Simpan Pengaturan</button>
            </form>

            @foreach (array_keys(\App\Support\AiSettings::DRIVERS) as $provider)
                <form id="test-{{ $provider }}" action="{{ route('ai-settings.test', $provider) }}" method="post" class="d-none">@csrf</form>
            @endforeach
        </div></div>
    </div>
</div>
@endsection

@section('js')
<style>
    .ai-radio { display: inline-flex; align-items: center; gap: .5rem; cursor: pointer; font-weight: 400; margin-bottom: 0; }
    .ai-radio input { width: 18px; height: 18px; margin: 0; position: static; opacity: 1; accent-color: #d81c25; cursor: pointer; }
    .ai-provider { border: 1px solid #ece9e2; border-radius: 14px; padding: 1.25rem; margin-top: 1.25rem; background: #fcfbf9; }
    .ai-provider.is-active { border-color: #d81c25; background: #fff; }
    .ai-provider .ai-active-badge { display: none; vertical-align: middle; }
    .ai-provider.is-active .ai-active-badge { display: inline-block; }
</style>
<script>
    document.querySelectorAll('input[name=driver]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('[data-provider]').forEach(function (box) {
                box.classList.toggle('is-active', box.getAttribute('data-provider') === radio.value);
            });
        });
    });
</script>
@endsection
