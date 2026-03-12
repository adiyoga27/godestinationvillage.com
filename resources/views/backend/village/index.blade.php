@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Manajemen User Desa Wisata
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">User Desa Wisata</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('user_village.create') }}" class="btn btn-lg btn-gradient-danger mr-2"> 
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah User Desa Wisata
                </a>
                <br /><br />
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
