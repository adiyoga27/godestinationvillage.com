@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Tambah Review
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/review') }}">Review</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Review</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => route('review.store'),
                  'method' => 'post', 'files'=>true, 'class'=>'form-sample']) !!}
                    @include('backend.review.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
