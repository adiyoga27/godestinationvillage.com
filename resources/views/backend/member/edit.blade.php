@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit User Member
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/user-member') }}">User Member</a></li>
            {{-- <li class="breadcrumb-item" aria-current="page"><a href="{{ route('user.show', $user->id) }}">{{ $user->name }}</a></li> --}}
            <li class="breadcrumb-item active" aria-current="page">Edit User Member</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($member, ['url' => route('user_member.update', $member->id),
                  'method'=>'put', 'files'=>true, 'class'=>'form-sample']) !!}
                  @include('backend.member.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
