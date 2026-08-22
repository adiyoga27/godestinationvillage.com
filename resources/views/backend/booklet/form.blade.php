@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Manajemen Booklet
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Booklet</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Upload Booklet PDF</h4>
                <p class="card-description">File ini menggantikan <code>GODEVI-Booklet.pdf</code> yang tampil di halaman utama (section GODEVI Booklet).</p>

                @if (isset($file) && $file)
                    <div class="alert alert-success">
                        <strong>File aktif:</strong> {{ $file['name'] }} &mdash; {{ $file['size'] }} &mdash; diperbarui {{ $file['updated_at'] }}
                        <br>
                        <a href="{{ $file['url'] }}" target="_blank" rel="noopener">Lihat / Unduh PDF</a>
                    </div>
                @else
                    <div class="alert alert-warning">Belum ada file booklet. Silakan upload PDF di bawah.</div>
                @endif

                {!! Form::open(['url' => route('booklet.store'), 'method' => 'post', 'files' => true, 'class' => 'form-sample']) !!}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">File PDF Booklet (*)</label>
                        <div class="col-sm-9">
                            <input type="file" name="pdf" accept="application/pdf" class="form-control" required>
                            {!! $errors->first('pdf', '<p class="text-danger">:message</p>') !!}
                            <small class="form-text text-muted">Format PDF, maksimal 30 MB.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Upload & Ganti</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection