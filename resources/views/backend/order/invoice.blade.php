@extends('layouts.backend_out')

@section('style')
<style>
    @media print {
        footer {
            display: none;
        }

        nav {
            display: none;
        }

        .btn {
            display: none;
        }

        .navbar {
            display: none !important;
        }

        label {
            display: none !important;
        }
    }
</style>
@endsection

@section('js')
<script>
    function printDiv(divName) {
        window.print();
    }
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-right">
                @if($order->payment_status == 'pending')
                
                <button class="btn btn-lg btn-gradient-danger mb-2" onclick="printDiv('print');"><i class="mdi mdi-printer"></i> Print Invoice</button>
                @elseif($order->payment_status == 'success')
                
                <button class="btn btn-lg btn-gradient-danger mb-2" onclick="printDiv('print');"><i class="mdi mdi-printer"></i> Print Voucher</button>
                @elseif($order->payment_status == 'cancel')
                
                <button class="btn btn-lg btn-gradient-danger mb-2" onclick="printDiv('print');"><i class="mdi mdi-printer"></i> Print Invoice</button>
                @endif
            </div>
            @if($order->payment_status == 'pending')
            <h1>Invoice</h1>
            @elseif($order->payment_status == 'success')
            <h1>Voucher</h1>
            @elseif($order->payment_status == 'cancel')
            <h1>Invoice</h1>
            @endif

        </div>
    </div>
    <br />
    <div class="card" id="print">
        <div class="card-header">
            Order No. :
            <strong>{{ $order->code }}</strong>
            <span class="float-right"> <strong>Order Date :</strong> {{ date('d/m/Y', strtotime($order->created_at))}}</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <span><strong>Arrival Date :</strong> {{ date('d/m/Y', strtotime($order->checkin_date))}}</span><br /><br /><br />
                </div>
                <div class="col-sm-6">
                    <h6 class="mb-3">Customer:</h6>
                    <div>
                        <strong>{{$order->customer_name}}</strong>
                    </div>
                    <div>{{$order->customer_address}}</div>
                    <div>Email: {{$order->customer_email}}</div>
                    <div>Phone : {{$order->customer_phone}}</div>
                </div>

                <div class="col-sm-6">
                    <div>
                        <strong>Godestiantionvillage</strong>
                    </div>
                    <div>Jln Wr Supratman No. 302 Denpasar Timur, Bali</div>
                    <div>Website: {{ env('APP_URL') }}  | Email : {{ env('APP_EMAIL') }} </div>
                    <div>Phone : 081938834675</div>
                    <br />
                    <h6 class="mb-3">Payment:</h6>
                    <div>
                        <strong>{{ str_replace('_', ' ', strtoupper($order->payment_type)) }}</strong> &nbsp;&nbsp;
                        @if($order->payment_status == 'pending')
                        <label class='badge badge-gradient-warning'>Pending</label>
                        @elseif($order->payment_status == 'success')
                        <label class='badge badge-gradient-success'>Success</label>
                        @elseif($order->payment_status == 'cancel')
                        <label class='badge badge-gradient-danger'>Declined</label>
                        @endif
                    </div>
                    @if($order->payment_type == 'bank_transfer')
                    <div><strong>{{$order->bank_account->bank_name}} {{$order->bank_account->bank_acc_no}}</strong> a/n {{ $order->bank_account->bank_acc_name }}</div>
                    @endif
                </div>

                <div class="col-sm-12">
                    <br />
                    <strong>Special Note</strong> : {!! $order->special_note !!}
                </div>
            </div>

            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <tr>
                        <td>Name of Tourism Village</td>
                        <td>{{ $order->village_name }}</td>
                    </tr>
                    <tr>
                        <td>Name of Tour Package</td>
                        <td>{{ $order->package_name }}</td>
                    </tr>
                    <tr>
                        <td>People(s)</td>
                        <td>{{ $order->pax }} Pax</td>
                    </tr>
                    
                    <tr>
                        <td>Discount</td>
                        <td>@if($order->package_disc>0){{ number_format(($order->package_price - $order->package_disc), 2,'.',',')}} @else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>Price of Tour Package</td>
                        <td>{{ number_format($order->package_price, 2,'.',',') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Total</strong></td>
                        <td><strong>{{ number_format($order->total_payment, 2,'.',',') }}</strong></td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
    <br />
    {{-- @if($order->payment_type == 'bank_transfer')
    <div class="card">
        <div class="card-header">
            <strong>Evidence of transfer</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <strong>Payment Date :</strong> {{ date('d/m/Y h:i:s', strtotime($order->payment_date))}}<br /><br />
                </div>

                <div class="col-sm-6">
                    <strong>Sender:</strong>
                    <strong>{{$order->bank_name}} {{$order->bank_acc_no}}</strong> a/n {{ $order->bank_acc_name }}
                </div>

                <div class="col-sm-6">
                    <strong>Receiver:</strong>
                    <strong>{{$order->bank_account->bank_name}} {{$order->bank_account->bank_acc_no}}</strong> a/n {{ $order->bank_account->bank_acc_name }}
                </div>

                <div class="col-sm-12">
                    <br /><br />
                    <strong>Proof of Payment:</strong> <br /><br />
                    <img class="img-responsive" src="{{ asset('storage/orders/'.$order->payment_img) }}">
                </div>
            </div>
        </div>
    </div>
    @endif --}}

</div>

@endsection