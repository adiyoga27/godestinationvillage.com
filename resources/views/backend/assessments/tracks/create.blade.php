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
                <div class="form-group"><label>Nama Jalur *</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required></div>
                <div class="form-group"><label>Tagline</label><input type="text" name="tagline" value="{{ old('tagline') }}" class="form-control"></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea></div>
                <div class="form-group"><label>Target Peserta</label><input type="text" name="target_audience" value="{{ old('target_audience') }}" class="form-control"></div>
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
