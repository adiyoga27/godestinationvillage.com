@extends('layouts.backend')

@section('style')
<link rel="stylesheet" href="{{asset('css/bootstrap-datepicker.min.css')}}">
@endsection

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Laporan Event
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Laporan Event</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <br /><br />
                <div class="row">
                    @if(Auth::user()->role_id == 1)
                        <div class="col-md-3">
                            {!! Form::select('village_id', $villages, null, ['id'=>'village_id', 'class'=>'selectpicker', 'required'=>'required', 'data-live-search'=>'true']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::select('package_id', [], null, ['id'=>'package', 'class'=>'selectpicker', 'required'=>'required', 'data-live-search'=>'true']) !!}
                        </div>
                        <div class="col-md-2">
                            <div class="input-group"> 
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white"><i class="mdi mdi-calendar"></i></span>
                                </div>
                                {!! Form::text('start_date', null, ['id'=>'start_date', 'class'=>'form-control', 'placeholder'=>'Start Date']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white"><i class="mdi mdi-calendar"></i></span>
                                </div> 
                                {!! Form::text('end_date', null, ['id'=>'end_date', 'class'=>'form-control', 'placeholder'=>'End Date']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="search" class="btn btn-lg btn-gradient-danger">Search</button>
                        </div>
                    @else
                        <input type="hidden" name="village_id" id="village_id" value="{{ Auth::user()->id }}">
                        <div class="col-md-3">
                            {!! Form::select('package_id', $packages, null, ['id'=>'package', 'class'=>'selectpicker', 'required'=>'required', 'data-live-search'=>'true']) !!}
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white"><i class="mdi mdi-calendar"></i></span>
                                </div> 
                                {!! Form::text('start_date', null, ['id'=>'start_date', 'class'=>'form-control', 'placeholder'=>'Start Date']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-danger text-white"><i class="mdi mdi-calendar"></i></span>
                                </div> 
                                {!! Form::text('end_date', null, ['id'=>'end_date', 'class'=>'form-control', 'placeholder'=>'End Date']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="search" class="btn btn-lg btn-gradient-danger">Search</button>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <br /><br /><br />
                        <a id="excel_link" href="{{route('report_village.export_xls')}}" class="btn btn-primary"><i class="mdi mdi-file-excel"></i> Export Excel</a>
                        <br /><br />
                        <div class="table-responsive"> 
                        <table id="order_report" class="table table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Order</th>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Tanggal Kedatangan</th>
                                    <th>Nama Desa</th>
                                    <th>Nama Customer</th>
                                    <th>Email</th>
                                    <th>HP</th>

                                    <th>Nama Paket</th>
                                    <th>Harga Paket</th>
                                    <th>Pax</th>
                                    <th>Total</th>
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
    </div>
</div>

@endsection

@section('js')
    {{-- {!! $html->scripts() !!} --}}
    <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>

    <script type="text/javascript">
        var start_date = $('#start_date').datepicker();
        var end_date = $('#end_date').datepicker();
        var data =[];
        var order_report = $('#order_report').dataTable({
            data: data,
            "bDestroy": true,
            "scrollX": true,
        });

        $('#village_id').change(function(){
            $("#package").empty();
            $("#package")
               .append('<option value="All">Semua Paket</option>')
               .selectpicker('refresh');
            $.get("{{ route('report_village.get_package') }}?id=" + $('#village_id').val(), function(data, status){
                data.data.forEach(e => {
                    $("#package")
                       .append('<option value="'+ e.id +'">'+ e.name +'</option>')
                       .selectpicker('refresh');
                });
            });
        });

        $('#search').on('click', function(){
            village = $('#village_id').val();
            package = $('#package').val();
            if(package == null)
                package = 'All'
            start_date = $('#start_date').val();
            if(start_date == '')
                start_date = 0
            end_date = $('#end_date').val();
            if(end_date == '')
                end_date = 0

            {{-- alert('{!! route('report_village.get_order') !!}?village_id='+village+'&package_id='+package+'&start_date='+start_date+'&end_date='+end_date); --}}

            order_report = $('#order_report').DataTable({
                destroy: true,
                stateSave: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{!! route('report_village.get_order') !!}?village_id='+village+'&package_id='+package+'&start_date='+start_date+'&end_date='+end_date,
                columns: [
                    // { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'rownum', name: 'rownum', searchable: false },
                    { data: 'code', name: 'code' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'checkin_date', name: 'checkin_date' },
                    { data: 'village_name', name: 'village_name' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'customer_email', name: 'customer_email' },
                    { data: 'customer_phone', name: 'customer_phone' },

                    { data: 'package_name', name: 'package_name' },
                    { data: 'package_price', name: 'package_price' },
                    { data: 'pax', name: 'pax' },
                    { data: 'total_payment', name: 'total_payment' },
                    { data: 'payment_type', name: 'payment_type' },
                    { data: 'payment_status', name: 'payment_status' }
                ],
            });

            var excel_href = $('#excel_link').attr('href');
            link_excel = excel_href + '?village_id='+village+'&package_id='+package+'&start_date='+start_date+'&end_date='+end_date;
                $('#excel_link').attr('href', link_excel);

            // order_report.ajax.reload();
        });
    </script>
    @include('components/_script_adjust-table')
@endsection
