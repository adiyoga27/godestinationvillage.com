@extends('layouts.backend')

@section('style')
<link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
@endsection

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Detail Desa Wisata
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/user-village') }}">User Desa Wisata</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $village->name }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                @if(empty($village->avatar))
                    <img class="card-img-top" src="{{ asset('dist/images/faces/faces1.png') }}" alt="image">
                @else
                    <img class="card-img-top" src="{{ asset('storage/users/'.$village->avatar) }}">
                @endif
                <div class="card-body">
                    <h4 class="card-title">{{ $village->name }}</h4>
                    <p class="card-text">
                        {{ $village->email }} <br />
                        {{ $village->phone }} <br />
                        {{ strip_tags($village->address) }}
                    </p>
                    <a href="{{ route('user_village.edit', $village->id) }}" class="btn btn-danger">Edit Data</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card" style="padding: 30px;">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        {{-- <a class="nav-item nav-link active" id="nav-app-tab" data-toggle="tab" href="#nav-app" role="tab" aria-controls="nav-app" aria-selected="false">Manage App Account</a> --}}
                        <a class="nav-item nav-link active" id="nav-detail-tab" data-toggle="tab" href="#nav-detail" role="tab" aria-controls="nav-detail" aria-selected="false">Detail Desa Wisata</a>
                        <a class="nav-item nav-link" id="nav-package-tab" data-toggle="tab" href="#nav-package" role="tab" aria-controls="nav-package" aria-selected="true" onclick="return reload_table_package()">Paket Wisata ({{ count($village->packages) }})</a>
                        <a class="nav-item nav-link" id="nav-order-tab" data-toggle="tab" href="#nav-order" role="tab" aria-controls="nav-order" aria-selected="false" onclick="return reload_table_order()">Pemesanan ({{ count($village->village_orders) }})</a>
                    </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-detail" role="tabpanel" aria-labelledby="nav-detail-tab">
                        <br />
                        <h3 align="center">Informasi Desa Wisata</h3><br />
                        <table class="table nowrap table-hover dataTables no-footer" style="white-space:nowrap; width: 100%">
                            <tr>
                                <td>Nama Desa Wisata</td>
                                <td>{{ $village->village_detail->village_name }}</td>
                            </tr>
                            <tr>
                                <td>Alamat Desa Wisata</td>
                                <td>{{ $village->village_detail->village_address }}</td>
                            </tr>
                            <tr>
                                <td>Contact Person</td>
                                <td>{!! $village->village_detail->contact_person !!}</td>
                            </tr>
                            <tr>
                                <td>Deskripsi</td>
                                <td>{!! $village->village_detail->desc !!}</td>
                            </tr>
                            <tr>
                                <td>Akun Bank</td>
                                <td>
                                    {{ $village->village_detail->bank_acc_no }} <br /><br />
                                    a/n {{ $village->village_detail->bank_acc_name }} <br /><br />
                                    {{ $village->village_detail->bank_name }}
                                </td>
                            </tr>
                        </table>
                        <!-- <div id="map" style="width: 100%; height: 300px;"></div> -->
                    </div>

                    <div class="tab-pane fade show" id="nav-package" role="tabpanel" aria-labelledby="nav-package-tab">
                        <br /><br />
                        <table id="package-table" class="table nowrap table-hover dataTables no-footer" style="white-space:nowrap; width: 100%">
                            <thead>
                                <tr>
                                    {{-- <th></th> --}}
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Nama Paket</th>
                                    <th>Harga Paket</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="tab-pane fade show" id="nav-order" role="tabpanel" aria-labelledby="nav-order-tab">
                        <br /><br />
                        <table id="order-table" class="table nowrap table-hover dataTables no-footer" style="white-space:nowrap; width: 100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>No. Order</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Nama Paket</th>
                                    <th>Total Pembayaran</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status Pembayaran</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
{{-- @include('components/_script_adjust-table') --}}
@include('backend/village/script/show_js')
@endsection
