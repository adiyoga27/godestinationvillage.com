@extends('layouts.backend')

@section('content-header')
    <div class="row">
        <div class="col-12">
          <span class="d-flex align-items-center purchase-popup">
            <p>Selamat Datang di Dashboard Godevi</p>
            <a href="{{ url('administrator/profile') }}" target="_blank" class="btn btn-gradient-danger ml-auto">Edit Profile</a>
            <i class="mdi mdi-close popup-dismiss"></i>
          </span>
        </div>
    </div>
    <div class="page-header">
        <h3 class="page-title">
          <span class="page-title-icon bg-gradient-danger text-white mr-2">
            <i class="mdi mdi-home"></i>                 
          </span>
          Dashboard
        </h3>
        <nav aria-label="breadcrumb">
          <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
              <span></span>Overview
              <i class="mdi mdi-alert-circle-outline icon-sm text-danger align-middle"></i>
            </li>
          </ul>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                Anda berhasil login ke halaman Dashboard Godevi!
            </div>
        </div>
    </div>
</div>

<br /><br />
@if(Auth::user()->role_id == 1)
<div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-success text-white mr-2">
        <i class="mdi mdi-account-box"></i>                 
      </span>
      Users
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Overview
          <i class="mdi mdi-alert-circle-outline icon-sm text-success align-middle"></i>
        </li>
      </ul>
    </nav>
</div>
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Admin
            <i class="mdi mdi-account mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($count_admin) }}</h2>
          <a href="{{ url('administrator/user-admin') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Desa Wisata
            <i class="mdi mdi-account mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($count_village) }}</h2>
          <a href="{{ url('administrator/user-village') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-success card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Member
            <i class="mdi mdi-account mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($count_member) }}</h2>
          <a href="{{ url('administrator/user-member') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
</div>
@endif
<div class="page-header">
    <h3 class="page-title">
      <span class="page-title-icon bg-gradient-danger text-white mr-2">
        <i class="mdi mdi-cart"></i>                 
      </span>
      Paket Wisata & Pemesanan
    </h3>
    <nav aria-label="breadcrumb">
      <ul class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
          <span></span>Overview
          <i class="mdi mdi-alert-circle-outline icon-sm text-danger align-middle"></i>
        </li>
      </ul>
    </nav>
</div>
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Paket Wisata
            <i class="mdi mdi-animation mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($count_package) }}</h2>
          <a href="{{ url('administrator/package') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Pemesanan
            <i class="mdi mdi-cart mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($count_order) }}</h2>
          <a href="{{ url('administrator/orders') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
      <div class="card bg-gradient-danger card-img-holder text-white">
        <div class="card-body" style="background: url('{{ asset('dist/images/dashboard/circle.svg')  }}') no-repeat right;">
          <h4 class="font-weight-normal mb-3">Pemasukan
            <i class="mdi mdi-cart mdi-24px float-right"></i>
          </h4>
          <h2 class="mb-5">{{ number_format($sum_order) }}</h2>
          <a href="{{ url('administrator/orders') }}" style="color:#fff"><h6 class="card-text">Menuju ke Halaman ></h6></a>
        </div>
      </div>
    </div>
</div>
@endsection
