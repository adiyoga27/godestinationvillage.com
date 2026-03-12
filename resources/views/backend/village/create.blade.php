@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Tambah User Desa Wisata
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/user-village') }}">User Data Wisata</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah User Desa Wisata</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => route('user_village.store'),
                  'method' => 'post', 'files'=>true, 'class'=>'form-sample']) !!}
                    @include('backend.village.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@include('backend.village.script.create_js')