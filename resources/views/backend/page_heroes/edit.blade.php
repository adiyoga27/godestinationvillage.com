@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Hero: {{ $hero->name }}
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('page-heroes.index') }}">Kelola Website / Hero Halaman</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $hero->name }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($hero, ['url' => route('page-heroes.update', $hero->id),
                  'method' => 'put', 'files' => true, 'class' => 'form-sample']) !!}

                <div class="alert alert-info">
                    Kosongkan field untuk memakai teks/gambar bawaan halaman. Isi versi Indonesia bila berbeda dengan English.
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Judul (EN)</label>
                    <div class="col-sm-9">
                        {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Judul (ID)</label>
                    <div class="col-sm-9">
                        {!! Form::text('title_id', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Sub-judul (EN)</label>
                    <div class="col-sm-9">
                        {!! Form::textarea('subtitle', null, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Sub-judul (ID)</label>
                    <div class="col-sm-9">
                        {!! Form::textarea('subtitle_id', null, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Gambar Latar</label>
                    <div class="col-sm-9">
                        @php $current = \App\Helpers\PageHero::image($hero->key); @endphp
                        @if ($current)
                            <div class="mb-2">
                                <img src="{{ $current }}" style="max-width:320px;max-height:140px;object-fit:cover;border-radius:10px;border:1px solid #e3e3e3;" onerror="this.style.display='none'">
                                <br><small class="form-text text-muted">Gambar saat ini (biarkan kosong untuk mempertahankan)</small>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*" class="form-control">
                        {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
                        <small class="form-text text-muted">Gambar landscape (mis. 1920x600), maksimal 10 MB.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
                        <a href="{{ route('page-heroes.index') }}" class="btn btn-lg btn-light mb-2">Kembali</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
