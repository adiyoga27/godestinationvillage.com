@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Slider
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/slider') }}">Slider</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Slider</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($slider, ['url' => route('slider.update', $slider->id),
                  'method' => 'put', 'files'=>true, 'class'=>'form-sample']) !!}
                    @include('backend.slider.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection