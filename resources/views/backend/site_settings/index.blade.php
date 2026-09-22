@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Pengaturan Website
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / Pengaturan</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-description">
                    Data umum website yang tampil di footer, topbar, dan halaman kontak.
                    Perubahan langsung tampil di website.
                </p>
                {!! Form::open(['url' => route('site-settings.update'), 'method' => 'put', 'class' => 'form-sample']) !!}
                    @foreach ($settings as $setting)
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ $setting->label }}</label>
                            <div class="col-sm-9">
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                    class="form-control" maxlength="500" placeholder="{{ $setting->key }}">
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
