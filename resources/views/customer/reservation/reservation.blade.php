@extends('customer/layout')
@section('content')
    <style>
        .bor {
            border: 1px solid grey;
            padding: 20px
        }

    </style>
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Unpaid Reservation </h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Reservation </a>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Unpaid </a>

                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/privacy.jpg') }}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->
    
    
    <section class="booking-section ptb-100 bg-light">
        <div class="container">
           
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="row">
                        @foreach ($order as $orders)
                            <div class="col-md-12">
                                <div class="item-single mb-30">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="image">
                                                <img src="{{ url('storage/packages/' . $orders->package->default_img) }}"
                                                    alt="{{ $orders->event_name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="content">
                                                <h3>
                                                    <a class="active" style="color: red">{{ $orders->code }} </a>
                                                    <br>
                                                    <a href="destination-details.html">{{ $orders->event_name }}</a>
                                                </h3>
                                                <hr>
                                                <div class="row" style="font-size: 12pt">
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-map-alt'></i>&nbsp Category  </h6>
                                                        </strong>
                                                        <div style="margin-left: 20pt;">
                                                            {{ $orders->package->category->name }} </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bxs-bank'></i>&nbsp Package Name </h6>
                                                        </strong>
                                                        <div style="margin-left: 20pt;">{{ $orders->package_name }} </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="font-size: 12pt; margin-top:5pt">
                                                  
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-money'></i>&nbsp Price  </b></h6>
                                                        </strong>
                                                        <div style="margin-left: 20pt;">Rp {{ number_format($orders->total_payment,0) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-calendar-x'></i>&nbsp Date Purchase  </h6>
                                                        </strong>
                                                        <div style="margin-left: 20pt;">{{ date('d M Y H:i', strtotime($orders->created_at)) }} WITA</div>
                                                    </div>
                                                </div>
                                                @if($orders->special_note)
                                                <div class="row" style="font-size: 12pt; margin-top:5pt">
                                                    <div class="col-md-12">
                                                        <strong>
                                                            <h6><i class='bx bx-receipt'></i>&nbsp Special Note  </h6>
                                                        </strong>
                                                        <div style="margin-left: 20pt;">{{ $orders->special_note }} </div>
                                                    </div>
                                                 
                                                </div>
                                                @endif
                                                <hr>
                                                <div class="float-right">
                                                    <a href="{{url('payment/package/'.$orders->uuid)}}" class="btn btn-success btn-clear-cart" style="color">Pay Now</a>
                                                    <a href="{{ url('payment/package/do_cancel/'. $orders->uuid ) }}" class="btn btn-danger btn-update-cart">Cancel</a>
                                                </div>
                                                <br>
                                                {{-- <div class="cta-btn">
                                                    <a href="{{url('payment/event/'.$orders->id)}}" class="btn-primary">Pay Now</a>
                                                </div> --}}
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <aside>
                        <?php if (isset(Auth::user()->email)) {
                            $email = Auth::user()->email;
                        } else {
                            $email = $isiemail;
                        } ?>
                        <div class="info-content">
                            <h3 class="sub-title">Status Booking Events</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-map-alt'></i>
                                        <h6><span><a href="{{ url('reservation/' . $email) }}"
                                                    class="active" style="color: red"> Unpaid </a></span> </h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-book-reader'></i>
                                        <h6><span><a href="{{ url('reservation/paid/' . $email) }}">Paid</a></span> </h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-notepad'></i>
                                        <h6><span><a href="{{ url('reservation/cancel/' . $email) }}">Cancel</span> </a></h6>
                                    </div>
                                </div>
                            </div>
                    </aside>
                </div>
            </div>
            @if(count($order)>0)
            <div class="item col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="pagination text-center">


                            @for ($i = 1; $i <= $order->lastPage(); $i++)
                                <a href="{{ $order->url($i) }}" class="page-numbers @if ($order->currentPage() == $i) current @endif">

                                    {{ $i }}
                                </a>
                            @endfor
                            @if ($order->lastPage() > 0 && $order->currentPage() < $order->lastPage())
                                <a href="{{ $order->nextPageUrl() }}" class="page-numbers">Next</a>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

           
        </div>
        </div>
    </section>
    
@endsection()

<script
    src="https://www.paypal.com/sdk/js?client-id=AWqwc4AETYKPOwQioHYGRXZAFeAcSrSB6BhSEpSHzq2c3UdafPYHin9Zdy0SXRlo_EzmmYxevXBlv_xV">
</script>
<script>
</script>
