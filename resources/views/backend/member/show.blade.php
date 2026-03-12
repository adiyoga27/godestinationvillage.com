@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Detail Member
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/user-member') }}">User Member</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $member->name }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                @if(empty($member->avatar))
                    <img class="card-img-top" src="{{ asset('dist/images/faces/faces1.png') }}" alt="image">
                @else
                    <img class="card-img-top" src="{{ asset('storage/users/'.$member->avatar) }}">
                @endif
                <div class="card-body">
                    <h4 class="card-title">{{ $member->name }}</h4>
                    <p class="card-text">
                        {{ $member->email }} <br />
                        {{ $member->phone }} <br />
                        {{ $member->address }}
                    </p>
                    <a href="{{ route('user_member.edit', $member->id) }}" class="btn btn-danger">Edit Data</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Histori Order</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive"> 
                        {!! $html->table(['class'=>'table table-hover', 'style'=>'width:100%']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
{!! $html->scripts() !!}
@include('components/_script_adjust-table')
@endsection
