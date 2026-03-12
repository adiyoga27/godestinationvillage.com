@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Akun Bank
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/bank-account') }}">Akun Bank</a></li>
            {{-- <li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Edit Akun Bank</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($bank_account, ['url' => route('bank_account.update', $bank_account->id),
                  'method'=>'put', 'files'=>true, 'class'=>'form-sample']) !!}
                  @include('backend.bank_account.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
