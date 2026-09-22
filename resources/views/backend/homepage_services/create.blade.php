@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Tambah Service
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('homepage-services.index') }}">Homepage / Services</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Service</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => route('homepage-services.store'),
                  'method' => 'post', 'files' => true, 'class' => 'form-sample']) !!}
                    @include('backend.homepage_services.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
