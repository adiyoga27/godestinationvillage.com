@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Kartu Fitur
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('homepage-about-features.index') }}">Fitur Why GODEVI</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($feature, ['url' => route('homepage-about-features.update', $feature->id),
                  'method' => 'put', 'files' => true, 'class' => 'form-sample']) !!}
                    @include('backend.homepage_about_features.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
