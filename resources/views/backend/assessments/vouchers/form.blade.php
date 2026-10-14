@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">{{ $voucher->exists ? 'Edit Voucher '.$voucher->code : 'Buat Voucher' }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessment-vouchers.index') }}">Voucher Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $voucher->exists ? 'Edit' : 'Buat' }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
@php
    $type = old('type', $voucher->type);
    $selectedTracks = array_map('intval', old('track_ids', $voucher->track_ids ?? []));
    $mode = $voucher->exists ? 'single' : old('mode', 'single');
@endphp
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @if ($voucher->exists && $voucher->usedCount() > 0)
                <div class="alert alert-warning">Voucher ini sudah dipakai {{ $voucher->usedCount() }}×. Perubahan hanya berlaku untuk pemakaian berikutnya.</div>
            @endif

            <form action="{{ $voucher->exists ? route('assessment-vouchers.update', $voucher->id) : route('assessment-vouchers.store') }}" method="post">
                @csrf
                @if ($voucher->exists) @method('PUT') @endif

                @unless ($voucher->exists)
                    <div class="form-group">
                        <label class="d-block">Mode</label>
                        <div class="btn-group btn-group-toggle" role="group">
                            <label class="btn btn-outline-secondary voucher-mode-btn {{ $mode === 'single' ? 'active' : '' }}">
                                <input type="radio" name="mode" value="single" class="d-none" {{ $mode === 'single' ? 'checked' : '' }}> Satu kode
                            </label>
                            <label class="btn btn-outline-secondary voucher-mode-btn {{ $mode === 'bulk' ? 'active' : '' }}">
                                <input type="radio" name="mode" value="bulk" class="d-none" {{ $mode === 'bulk' ? 'checked' : '' }}> Banyak kode (bulk)
                            </label>
                        </div>
                    </div>
                @endunless

                <div class="form-row" data-mode="single">
                    <div class="form-group col-md-6">
                        <label>Kode Voucher *</label>
                        <input type="text" name="code" value="{{ old('code', $voucher->code) }}" class="form-control text-monospace text-uppercase" maxlength="40">
                        <small class="text-muted">Huruf, angka, - dan _. Tidak membedakan huruf besar/kecil saat dipakai.</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Keterangan (internal)</label>
                        <input type="text" name="description" value="{{ old('description', $voucher->description) }}" class="form-control" maxlength="191" placeholder="mis. Peserta pelatihan Dinas Pariwisata Bangli">
                    </div>
                </div>

                @unless ($voucher->exists)
                    <div data-mode="bulk">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Jumlah Kode *</label>
                                <input type="number" name="quantity" value="{{ old('quantity', 50) }}" class="form-control" min="2" max="1000">
                                <small class="text-muted">2–1000 kode sekaligus.</small>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Awalan Kode</label>
                                <input type="text" name="prefix" value="{{ old('prefix') }}" class="form-control text-monospace text-uppercase" maxlength="20" placeholder="mis. BANGLI">
                                <small class="text-muted">Hasil: <span class="text-monospace" id="prefix-preview">BANGLI-7KQ2MX</span></small>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Keterangan (internal)</label>
                                <input type="text" name="description" value="{{ old('description') }}" class="form-control" maxlength="191" placeholder="mis. Pelatihan Bangli Okt 2026" disabled>
                            </div>
                        </div>
                        <div class="alert alert-light border small">
                            Setiap kode dibuat acak dan unik, dengan pengaturan diskon, jalur &amp; masa berlaku yang sama.
                            Kuota di bawah berlaku <strong>per kode</strong> — isi <strong>1</strong> agar tiap kode hanya sekali pakai.
                            Setelah dibuat, unduh daftar kodenya lewat tombol <strong>Unduh CSV</strong>.
                        </div>
                    </div>
                @endunless

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Jenis Diskon *</label>
                        <select name="type" class="form-control" id="voucher-type">
                            @foreach (\App\Models\AssessmentVoucher::TYPES as $key => $label)
                                <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6" id="voucher-value-group">
                        <label id="voucher-value-label">Besar Diskon *</label>
                        <input type="number" name="value" value="{{ old('value', $voucher->type === 'free' ? '' : $voucher->value) }}" class="form-control" min="1">
                        <small class="text-muted" id="voucher-value-hint"></small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Berlaku untuk Jalur</label>
                    <div class="voucher-checks">
                        @foreach ($tracks as $t)
                            <label class="voucher-check">
                                <input type="checkbox" name="track_ids[]" value="{{ $t->id }}" {{ in_array($t->id, $selectedTracks, true) ? 'checked' : '' }}>
                                <span>{{ $t->name }} <small class="text-muted">(Rp {{ number_format($t->price, 0, ',', '.') }})</small></span>
                            </label>
                        @endforeach
                    </div>
                    <small class="text-muted">Kosongkan semua = berlaku untuk semua jalur.</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Kuota Pemakaian <span data-mode="bulk">per Kode</span></label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', $voucher->max_uses) }}" class="form-control" min="1" placeholder="Tanpa batas">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Mulai Berlaku</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Berakhir</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $voucher->expires_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="voucher-check">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                        <span>Aktif — bisa dipakai guest</span>
                    </label>
                </div>

                <button class="btn btn-gradient-danger" id="voucher-submit">Simpan Voucher</button>
                <a href="{{ route('assessment-vouchers.index') }}" class="btn btn-light">Batal</a>
            </form>
        </div></div>
    </div>
</div>
@endsection

@section('js')
<script>
(function () {
    var type = document.getElementById('voucher-type');
    var group = document.getElementById('voucher-value-group');
    var label = document.getElementById('voucher-value-label');
    var hint = document.getElementById('voucher-value-hint');
    var input = group.querySelector('input');
    function update() {
        var free = type.value === 'free';
        group.style.display = free ? 'none' : '';
        input.required = !free;
        input.max = type.value === 'percent' ? 100 : '';
        label.textContent = type.value === 'percent' ? 'Diskon (%) *' : 'Potongan (Rp) *';
        hint.textContent = type.value === 'percent' ? '1–100. Contoh 50 = setengah harga.' : 'Bila potongan ≥ harga jalur, asesmen menjadi gratis.';
    }
    type.addEventListener('change', update);
    update();

    // Mode satu kode / bulk: tampilkan kolom yang relevan; kolom tersembunyi dinonaktifkan agar tidak ikut terkirim.
    var modes = document.querySelectorAll('input[name=mode]');
    var submit = document.getElementById('voucher-submit');
    function setMode() {
        var checked = document.querySelector('input[name=mode]:checked');
        var mode = checked ? checked.value : 'single';
        document.querySelectorAll('[data-mode]').forEach(function (el) {
            var on = el.getAttribute('data-mode') === mode;
            el.style.display = on ? '' : 'none';
            el.querySelectorAll('input').forEach(function (i) { i.disabled = !on; });
        });
        document.querySelectorAll('.voucher-mode-btn').forEach(function (b) { b.classList.toggle('active', b.querySelector('input').checked); });
        var code = document.querySelector('input[name=code]');
        if (code) code.required = mode === 'single';
        var qty = document.querySelector('input[name=quantity]');
        if (qty) qty.required = mode === 'bulk';
        submit.textContent = mode === 'bulk' ? 'Generate Kode Voucher' : 'Simpan Voucher';
    }
    modes.forEach(function (m) { m.addEventListener('change', setMode); });
    setMode();

    var prefix = document.querySelector('input[name=prefix]');
    var preview = document.getElementById('prefix-preview');
    if (prefix) {
        var showPrefix = function () {
            var p = prefix.value.replace(/\s+/g, '').toUpperCase();
            preview.textContent = (p ? p + '-' : '') + '7KQ2MX';
        };
        prefix.addEventListener('input', showPrefix);
        showPrefix();
    }
})();
</script>
<style>
    .voucher-checks { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; }
    .voucher-check { display: inline-flex; align-items: center; gap: .5rem; margin: 0; cursor: pointer; font-weight: 400; }
    .voucher-check input { width: 18px; height: 18px; margin: 0; flex-shrink: 0; cursor: pointer; accent-color: #d81c25; position: static; opacity: 1; }
    .voucher-mode-btn { cursor: pointer; }
    .voucher-mode-btn.active { background: #d81c25; border-color: #d81c25; color: #fff; }
</style>
@endsection
