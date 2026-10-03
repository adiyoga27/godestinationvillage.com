@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <div>
            <a href="{{ route('assessment-results.index') }}" class="gd-back"><i class="mdi mdi-arrow-left"></i> Hasil Asesmen</a>
            <h3 class="page-title mb-0">Input Asesmen Manual{{ $track ? ' — '.$track->name : '' }}</h3>
        </div>
    </div>
@endsection

@section('content')
@if (! $track)
    {{-- ============ LANGKAH 1: PILIH JALUR ============ --}}
    <p class="gd-hint mb-3" style="font-size:.875rem">Admin mengisi asesmen atas nama responden. Hasil langsung terbuka <strong>tanpa pembayaran</strong> dan laporan AI dibuat otomatis.</p>
    <div class="gd-track-grid">
        @foreach ($tracks as $t)
            <a href="{{ route('assessment-results.create', ['track' => $t->slug]) }}" class="gd-track">
                <span class="gd-track__icon"><i class="mdi mdi-clipboard-check"></i></span>
                <strong>{{ $t->name }}</strong>
                <span>{{ $t->tagline }}</span>
                <span class="gd-track__go">Pilih <i class="mdi mdi-arrow-right"></i></span>
            </a>
        @endforeach
    </div>
@else
    @php
        $showWeight = $weights->map(fn ($w) => round($w, 1))->unique()->count() > 1 || ! empty($formConfig['show_weight']);
        $totalWeight = max(1, $weights->sum());
        $sectionStarts = collect($formConfig['sections'] ?? [])->mapWithKeys(fn ($dims, $title) => [$dims[0] => $title]);
    @endphp

    <form action="{{ route('assessment-results.store') }}" method="post">
        @csrf
        <input type="hidden" name="track_id" value="{{ $track->id }}">
        @include('backend.partials.form.errors')
        @if (session('error'))<div class="gd-form-alert"><i class="mdi mdi-alert-circle"></i><div>{{ session('error') }}</div></div>@endif

        <div class="gd-form">
            <div class="gd-form__main">
                {{-- ============ PROFIL ============ --}}
                @include('backend.partials.form.card-open', ['icon' => 'account-card-details', 'title' => $profile['title'], 'desc' => 'Data responden yang diwakili admin.'])
                    <div class="gd-field">
                        <label class="gd-label">{{ $profile['organization_label'] }} <span class="gd-req">*</span></label>
                        <input type="text" name="organization" value="{{ old('organization') }}" required class="form-control gd-input" placeholder="{{ $profile['organization_placeholder'] }}">
                        {!! $errors->first('organization', '<p class="gd-error">:message</p>') !!}
                    </div>

                    @if ($profile['destination_fields'])
                        <div class="gd-field">
                            <label class="gd-label">Instansi/OPD Pengusul <span class="gd-req">*</span></label>
                            <input type="text" name="institution" value="{{ old('institution') }}" required class="form-control gd-input" placeholder="mis. Dinas Pariwisata Kabupaten Bangli">
                            {!! $errors->first('institution', '<p class="gd-error">:message</p>') !!}
                        </div>
                    @endif

                    @if ($profile['business_fields'] || $profile['entity_field'])
                        <div class="gd-grid-2">
                            <div class="gd-field">
                                <label class="gd-label">{{ $profile['entity_field'] ? 'Jenis Entitas' : 'Jenis Badan Usaha' }} <span class="gd-req">*</span></label>
                                <select name="business_type" required class="form-control gd-input">
                                    <option value="">Pilih...</option>
                                    @foreach ($profile['entity_field'] ? \App\Services\AssessmentService::ENTITY_TYPES : \App\Services\AssessmentService::BUSINESS_TYPES as $opt)
                                        <option value="{{ $opt }}" @selected(old('business_type') === $opt)>{{ $opt }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->first('business_type', '<p class="gd-error">:message</p>') !!}
                            </div>
                            @if ($profile['business_fields'])
                                <div class="gd-field">
                                    <label class="gd-label">Sektor Usaha <span class="gd-req">*</span></label>
                                    <select name="business_sector" required class="form-control gd-input">
                                        <option value="">Pilih...</option>
                                        @foreach (\App\Services\AssessmentService::BUSINESS_SECTORS as $opt)
                                            <option value="{{ $opt }}" @selected(old('business_sector') === $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('business_sector', '<p class="gd-error">:message</p>') !!}
                                </div>
                            @endif
                        </div>
                        @if ($profile['business_fields'])
                            <div class="gd-field" style="max-width:260px">
                                <label class="gd-label">Jumlah Anggota/Pelaku Usaha</label>
                                <input type="number" name="member_count" value="{{ old('member_count') }}" min="1" class="form-control gd-input">
                            </div>
                        @endif
                    @endif

                    {{-- Lokasi: cari untuk mengisi otomatis, tetap bisa diketik manual. --}}
                    <div class="gd-field">
                        <label class="gd-label">Cari lokasi</label>
                        <div class="gd-loc" data-gd-loc data-endpoint="{{ route('location.search') }}">
                            <div class="gd-filters__search"><i class="mdi mdi-magnify"></i><input type="text" class="form-control gd-input" placeholder="Ketik min. 3 huruf nama kelurahan / kecamatan / kab/kota…" autocomplete="off" data-gd-loc-input></div>
                            <ul class="gd-loc__list" data-gd-loc-list hidden></ul>
                        </div>
                    </div>
                    <div class="gd-grid-2">
                        @foreach (['subdistrict' => 'Kelurahan', 'district' => 'Kecamatan', 'regency' => 'Kota/Kab', 'province' => 'Provinsi'] as $f => $label)
                            <div class="gd-field">
                                <label class="gd-label">{{ $label }} @if (in_array($f, ['regency', 'province']))<span class="gd-req">*</span>@endif</label>
                                <input type="text" name="{{ $f }}" value="{{ old($f) }}" class="form-control gd-input" data-gd-loc-field="{{ $f }}" @if (in_array($f, ['regency', 'province'])) required @endif>
                                {!! $errors->first($f, '<p class="gd-error">:message</p>') !!}
                            </div>
                        @endforeach
                    </div>
                    <div class="gd-field" style="max-width:200px">
                        <label class="gd-label">Kode Pos</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" maxlength="10" class="form-control gd-input" data-gd-loc-field="postal_code">
                    </div>

                    <div class="gd-grid-2">
                        <div class="gd-field">
                            <label class="gd-label">{{ $profile['contact_label'] }} <span class="gd-req">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="form-control gd-input" placeholder="{{ $profile['contact_placeholder'] }}">
                            {!! $errors->first('name', '<p class="gd-error">:message</p>') !!}
                        </div>
                        <div class="gd-field">
                            <label class="gd-label">{{ $profile['phone_label'] }} <span class="gd-req">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="form-control gd-input" placeholder="08xx-xxxx-xxxx">
                            {!! $errors->first('phone', '<p class="gd-error">:message</p>') !!}
                        </div>
                    </div>
                    <div class="gd-field">
                        <label class="gd-label">Email responden</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control gd-input" placeholder="nama@email.com">
                        <p class="gd-hint">Opsional. Bila diisi, laporan hasil & strategi dikirim otomatis ke email ini.</p>
                        {!! $errors->first('email', '<p class="gd-error">:message</p>') !!}
                    </div>
                    <div class="gd-field mb-0">
                        <label class="gd-label">{{ $profile['description_label'] }}</label>
                        <textarea name="profile_description" rows="4" maxlength="3000" class="form-control gd-input" style="height:auto" placeholder="{{ $profile['description_placeholder'] }}">{{ old('profile_description') }}</textarea>
                    </div>
                @include('backend.partials.form.card-close')

                {{-- ============ PENILAIAN ============ --}}
                @include('backend.partials.form.card-open', ['icon' => 'chart-bar', 'title' => $formConfig['title'] ?? 'Penilaian', 'desc' => 'Skala 1–5 sesuai kondisi saat ini. Catatan ikut dikirim ke AI sebagai konteks.'])
                    @foreach ($grouped as $dimension => $questions)
                        @if ($sectionStarts->has($dimension))
                            <h4 class="gd-section-title mt-0">{{ $sectionStarts[$dimension] }}</h4>
                        @endif
                        <div class="gd-score-item">
                            <div class="gd-score-item__head">
                                <strong>{{ $dimension }}</strong>
                                @if ($showWeight)<span class="gd-pill gd-pill--ink">Bobot {{ round($weights[$dimension] / $totalWeight * 100) }}%</span>@endif
                            </div>
                            @foreach ($questions as $q)
                                @if ($q->question !== $dimension)<p class="gd-score-item__q">{{ $q->question }}</p>@endif
                                @if ($q->help_text)<p class="gd-hint mt-0">{{ $q->help_text }}</p>@endif
                                <div class="gd-scale" role="radiogroup" aria-label="{{ $q->question }}">
                                    @foreach ($labels as $val => $label)
                                        <label class="gd-scale__opt">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $val }}" required @checked((string) old('answers.'.$q->id) === (string) $val)>
                                            <span><strong>{{ $val }}</strong><small>{{ $label }}</small></span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                            <input type="text" name="notes[{{ $questions->first()->id }}]" value="{{ old('notes.'.$questions->first()->id) }}" maxlength="1000" class="form-control gd-input mt-2" placeholder="Catatan singkat (opsional)">
                        </div>
                    @endforeach
                    {!! $errors->first('answers', '<p class="gd-error">:message</p>') !!}
                @include('backend.partials.form.card-close')
            </div>

            <aside class="gd-form__side">
                @include('backend.partials.form.card-open', ['icon' => 'information-outline', 'title' => 'Tentang input manual'])
                    <ul class="gd-list mb-0">
                        <li>Hasil tercatat sebagai <strong>Input admin</strong> atas nama Anda.</li>
                        <li>Tanpa invoice & pembayaran — hasil langsung terbuka.</li>
                        <li>Laporan AI dibuat otomatis setelah disimpan.</li>
                        <li>Link hasil bisa dibagikan ke responden dari halaman detail.</li>
                    </ul>
                @include('backend.partials.form.card-close')
                @include('backend.partials.form.card-open', ['icon' => 'note-text', 'title' => 'Catatan internal'])
                    <textarea name="internal_note" rows="4" class="form-control gd-input" style="height:auto" placeholder="mis. Diisi saat kunjungan lapangan 3 Okt">{{ old('internal_note') }}</textarea>
                @include('backend.partials.form.card-close')
            </aside>
        </div>

        @include('backend.partials.form.actionbar', ['cancel' => route('assessment-results.create'), 'label' => 'Simpan & Buat Laporan'])
    </form>
@endif
@endsection

@section('js')
<script>
(function () {
    var box = document.querySelector('[data-gd-loc]');
    if (!box) return;
    var input = box.querySelector('[data-gd-loc-input]');
    var list = box.querySelector('[data-gd-loc-list]');
    var timer;
    var tc = function (s) { return (s || '').toLowerCase().replace(/\b\w/g, function (c) { return c.toUpperCase(); }); };
    var set = function (f, v) { var el = document.querySelector('[data-gd-loc-field="' + f + '"]'); if (el) el.value = v || ''; };
    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = input.value.trim();
        if (q.length < 3) { list.hidden = true; return; }
        timer = setTimeout(function () {
            fetch(box.dataset.endpoint + '?q=' + encodeURIComponent(q), { headers: { Accept: 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    list.innerHTML = '';
                    (res.data || []).slice(0, 30).forEach(function (it) {
                        var li = document.createElement('li');
                        li.innerHTML = '<strong></strong><small></small>';
                        li.querySelector('strong').textContent = ['Kel. ' + tc(it.subdistrict), 'Kec. ' + tc(it.district), tc(it.city), 'Prov. ' + tc(it.province)].join(', ');
                        li.querySelector('small').textContent = it.postal_code ? 'Kode Pos ' + it.postal_code : '';
                        li.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            set('subdistrict', tc(it.subdistrict)); set('district', tc(it.district));
                            set('regency', tc(it.city)); set('province', tc(it.province)); set('postal_code', it.postal_code);
                            input.value = li.querySelector('strong').textContent;
                            list.hidden = true;
                        });
                        list.appendChild(li);
                    });
                    if (!list.children.length) list.innerHTML = '<li><small>Lokasi tidak ditemukan — isi manual di bawah.</small></li>';
                    list.hidden = false;
                });
        }, 350);
    });
    input.addEventListener('blur', function () { setTimeout(function () { list.hidden = true; }, 150); });
    input.addEventListener('keydown', function (e) { if (e.key === 'Enter') e.preventDefault(); });
})();
</script>
@endsection
