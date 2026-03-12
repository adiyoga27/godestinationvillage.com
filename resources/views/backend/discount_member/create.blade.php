@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Manajemen Diskon Member
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/discount-member') }}">Diskon Member</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Diskon Member</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::open(['url' => route('discount_member.store'),
                  'method' => 'post', 'files'=>true, 'class'=>'form-sample']) !!}
                    @include('backend.discount_member.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
