@extends('layouts.backend')

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
        {{-- <div class="pull-right"> --}}
        <button class="btn btn-lg btn-gradient-danger mb-2 float-right" onclick="printDiv('print');"><i class="mdi mdi-printer"></i> Print Invoice</button>
        {{-- </div>  --}}
        <h1>Invoice</h1>
        @if(Auth::user()->role_id == 1 && $order->payment_status == 'pending')
            <br />
            <a href="{{ route('order-event.change_status', ['id'=>$order->id, 'status'=>'cancel']) }}" onclick="return confirm('Anda Yakin?')" class="btn btn-lg btn-danger mb-2 float-right"><i class="mdi mdi-close"></i> Cancel</a> 
            <a href="{{ route('order-event.change_status', ['id'=>$order->id, 'status'=>'success']) }}" onclick="return confirm('Anda Yakin?')" class="btn btn-lg btn-success mb-2 float-right"><i class="mdi mdi-check"></i> Verifikasi</a> 
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
                <strong>{{env('APP_NAME')}}</strong>
            </div>
            <div>Jln Wr Supratman No. 302 Denpasar Timur, Bali</div>
            <div>Website: {{env('APP_URL')}} </div>
            <div>Email : {{env('APP_EMAIL')}} </div>
            <div>Phone : {{env('APP_PHONE')}}</div>
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
            {{-- @if($order->payment_type == 'bank_transfer')
            <div><strong>{{$order->bank_account->bank_name}} {{$order->bank_account->bank_acc_no}}</strong> a/n {{ $order->bank_account->bank_acc_name }}</div>
            @endif --}}
        </div>

        <div class="col-sm-12">
            <br />
            <strong>Special Note</strong> : {!! $order->special_note !!}
        </div>
    </div>
    
    <div class="table-responsive-sm">
        <table class="table table-striped">
    
            <tr>
                <td>Name of Event</td>
                <td>{{ $order->event_name }}</td>
            </tr>
            <tr>
                <td>People(s)</td>
                <td>{{ $order->pax }} Pax</td>
            </tr>
            <tr>
                <td>Price of Event</td>
                <td>Rp {{ number_format($order->event_price, 2,'.',',') }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>Rp {{ number_format($order->event_discount, 2,'.',',') }}</td>
            </tr>
            <tr>
                <td><strong>Payment Total</strong></td>
                <td><strong>Rp {{ number_format($order->total_payment, 2,'.',',') }}</strong></td>
            </tr>
        </table>
    </div>

    </div>
</div>
<br />
@if($order->payment_type == 'bank_transfer')
    <div class="card">
        <div class="card-header">
            <strong>Evidence of transfer</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <strong>Payment Date :</strong> {{ date('d/m/Y h:i:s', strtotime($order->payment_date))}}<br /><br />
                </div>

                {{-- <div class="col-sm-6">
                    <strong>Sender:</strong>
                    <strong>{{$order->bank_name}} {{$order->bank_acc_no}}</strong> a/n {{ $order->bank_acc_name }}
                </div>

                <div class="col-sm-6">
                    <strong>Receiver:</strong>
                    <strong>{{$order->bank_account->bank_name}} {{$order->bank_account->bank_acc_no}}</strong> a/n {{ $order->bank_account->bank_acc_name }}
                </div> --}}

                {{-- <div class="col-sm-12">
                    <br /><br />
                    <strong>Proof of Payment:</strong> <br /><br />
                    <img class="img-responsive" src="{{ asset('storage/orders/'.$order->payment_img) }}">
                </div> --}}
            </div>
        </div>
    </div>
@endif

</div>

@endsection

