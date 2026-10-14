@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Tambah Jalur Asesmen</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <form action="{{ route('assessments.store') }}" method="post">
                @csrf
                <p class="text-muted small">Isi versi Indonesia (wajib) dan English (opsional — kosong = pengunjung English melihat versi Indonesia). Pindah bahasa lewat tombol <strong>ID / EN</strong>.</p>
                @include('backend.assessments._bilingual', ['label' => 'Nama Jalur', 'field' => 'name', 'model' => null, 'required' => true])
                @include('backend.assessments._bilingual', ['label' => 'Tagline', 'field' => 'tagline', 'model' => null])
                @include('backend.assessments._bilingual', ['label' => 'Deskripsi', 'field' => 'description', 'model' => null, 'type' => 'textarea', 'rows' => 4])
                @include('backend.assessments._bilingual', ['label' => 'Target Peserta', 'field' => 'target_audience', 'model' => null])
                <div class="form-group"><label>Harga Asesmen (Rp) *</label><input type="number" name="price" value="{{ old('price', 199000) }}" class="form-control" min="0" required><small class="text-muted">Isi 0 bila gratis (hasil langsung terbuka tanpa pembayaran).</small></div>
                <div class="form-row">
                    <div class="form-group col-md-4"><label>Estimasi (menit)</label><input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', 10) }}" class="form-control" min="1"></div>
                    <div class="form-group col-md-4"><label>Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0"></div>
                    <div class="form-group col-md-4"><label>Aktif</label><select name="is_active" class="form-control"><option value="1" selected>Ya</option><option value="0">Tidak</option></select></div>
                </div>
                <button class="btn btn-gradient-danger">Simpan & Tambah Soal</button>
            </form>
        </div></div>
    </div>
</div>
@endsection
