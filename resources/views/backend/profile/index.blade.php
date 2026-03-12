@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Profile
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <h3 align="center">Edit Profile</h3>
                <hr />
                {!! Form::model($user, ['url' => route('update_profile'),
                    'method'=>'post', 'class'=>'form-sample', 'files' => true]) !!}
                    @if(Auth::user()->role_id == 1)
                        @include('backend.profile.form._form_profile')
                    @else
                        @include('backend.village.form._form')
                    @endif
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@if(Auth::user()->role_id == 2)
@include('backend.village.script.edit_js')
@endif