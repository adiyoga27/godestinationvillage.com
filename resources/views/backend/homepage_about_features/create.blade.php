@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Tambah Kartu Fitur
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('homepage-about-features.index') }}">Fitur Why GODEVI</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => route('homepage-about-features.store'),
                  'method' => 'post', 'files' => true, 'class' => 'form-sample']) !!}
                    @include('backend.homepage_about_features.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
