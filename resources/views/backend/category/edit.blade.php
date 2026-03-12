@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Kategori Paket Wisata
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/category') }}">Kategori Paket Wisata</a></li>
            {{-- <li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Edit Kategori Paket Wisata</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($category, ['url' => route('category.update', $category->id),
                  'method'=>'put', 'files'=>true, 'class'=>'form-sample']) !!}
                  @include('backend.category.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
