@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Edit Jalur: {{ $track->name }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <form action="{{ route('assessments.update', $track->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group"><label>Nama Jalur *</label><input type="text" name="name" value="{{ old('name', $track->name) }}" class="form-control" required></div>
                <div class="form-group"><label>Slug (tidak dapat diubah)</label><input type="text" value="{{ $track->slug }}" class="form-control" disabled></div>
                <div class="form-group"><label>Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $track->tagline) }}" class="form-control"></div>
                <div class="form-group"><label>Deskripsi</label><textarea name="description" rows="4" class="form-control">{{ old('description', $track->description) }}</textarea></div>
                <div class="form-group"><label>Target Peserta</label><input type="text" name="target_audience" value="{{ old('target_audience', $track->target_audience) }}" class="form-control"></div>
                <div class="form-row">
                    <div class="form-group col-md-4"><label>Estimasi (menit)</label><input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $track->estimated_minutes) }}" class="form-control" min="1"></div>
                    <div class="form-group col-md-4"><label>Urutan</label><input type="number" name="sort_order" value="{{ old('sort_order', $track->sort_order) }}" class="form-control" min="0"></div>
                    <div class="form-group col-md-4"><label>Aktif</label><select name="is_active" class="form-control"><option value="1" {{ $track->is_active ? 'selected' : '' }}>Ya</option><option value="0" {{ ! $track->is_active ? 'selected' : '' }}>Tidak</option></select></div>
                </div>
                <button class="btn btn-gradient-danger">Simpan</button>
                <a href="{{ route('assessments.questions.index', $track->id) }}" class="btn btn-info">Kelola Soal</a>
            </form>
            <hr>
            <form action="{{ route('assessments.destroy', $track->id) }}" method="post" onsubmit="return confirm('Hapus jalur beserta seluruh soal & hasilnya?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus Jalur</button>
            </form>
        </div></div>
    </div>
</div>
@endsection
