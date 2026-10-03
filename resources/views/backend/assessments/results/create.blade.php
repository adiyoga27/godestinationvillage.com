@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <div>
            <a href="{{ route('assessment-results.index') }}" class="gd-back"><i class="mdi mdi-arrow-left"></i> Hasil Asesmen</a>
            <h3 class="page-title mb-0">Input Asesmen Manual</h3>
        </div>
    </div>
@endsection

@section('content')
<div class="gd-callout gd-callout--amber mt-0 mb-4">
    <strong>Cara kerja:</strong> pilih jalur, lalu Anda diarahkan ke <strong>form asesmen yang sama persis dengan guest</strong> (profil → soal) dalam <em>Mode Input Admin</em>.
    Saat disimpan, hasil langsung terbuka <strong>tanpa pembayaran</strong>, laporan AI dibuat otomatis, dan tercatat sebagai input atas nama Anda.
</div>

<div class="gd-track-grid">
    @foreach ($tracks as $t)
        <a href="{{ route('assessment-results.input', $t->slug) }}" class="gd-track">
            <span class="gd-track__icon"><i class="mdi mdi-clipboard-check"></i></span>
            <strong>{{ $t->name }}</strong>
            <span>{{ $t->tagline }}</span>
            <span class="gd-table__meta">{{ $t->active_questions_count }} dimensi · ± {{ $t->estimated_minutes }} menit</span>
            <span class="gd-track__go">Mulai isi <i class="mdi mdi-arrow-right"></i></span>
        </a>
    @endforeach
</div>
@endsection
