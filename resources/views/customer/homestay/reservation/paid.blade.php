@extends('customer/layout')
@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Paid Transaction</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Booking </a>

                    <li class="item"><a href="blog-style-3.html"><i class='bx bx-chevrons-right'></i>Paid</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/privacy.jpg') }}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->
    <!-- start blog details section -->
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
                                                <img src="{{ url('storage/homestay/' . $orders->package->default_img) }}"
                                                    alt="Demo Image">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="content">
                                                <h3>
                                                    <a class="active" style="color: red">{{ $orders->code }} </a>
                                                    <br>
                                                    <a href="destination-details.html">{{ $orders->homestay_name }}</a>
                                                </h3>
                                                <hr>
                                                <div class="row" style="font-size: 12pt">
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-map-alt'></i>&nbsp Category  </h6>
                                                        </strong>
                                                        <div style="margin-left: 23pt;"> </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-money'></i>&nbsp Price  </b></h6>
                                                        </strong>
                                                        <div style="margin-left: 23pt;">Rp {{ $orders->total_payment }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="font-size: 12pt; margin-top:5pt">
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bxs-bank'></i>&nbsp Metode Payment  </h6>
                                                        </strong>
                                                        <div style="margin-left: 23pt;">{{ $orders->payment_type }} </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            <h6><i class='bx bx-calendar-x'></i>&nbsp Date Payment  </h6>
                                                        </strong>
                                                        <div style="margin-left: 23pt;">{{ date('d M Y H:i', strtotime($orders->payment_date)) }} WITA </div>
                                                    </div>
                                                </div>
                                                @if($orders->special_note)
                                                <div class="row" style="font-size: 12pt; margin-top:5pt">
                                                    <div class="col-md-12">
                                                        <strong>
                                                            <h6><i class='bx bx-receipt'></i>&nbsp Special Note  </h6>
                                                        </strong>
                                                        <div style="margin-left: 23pt;">{{ $orders->special_note }} </div>
                                                    </div>
                                                 
                                                </div>
                                                @endif
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
                            <h3 class="sub-title">Status Booking Homestay</h3>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-map-alt'></i>
                                        <h6><span><a href="{{ url('reservation-homestay/' . $email) }}"
                                                > Unpaid </a></span> </h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-book-reader'></i>
                                        <h6><span><a     class="active" style="color: red" href="{{ url('reservation-homestay/paid/' . $email) }}">Paid</a></span> </h6>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="content-list">
                                        <i class='bx bx-notepad'></i>
                                        <h6><span><a href="{{ url('reservation-homestay/cancel/' . $email) }}">Cancel</span> </a></h6>
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

                            {{-- <span class="page-numbers current" aria-current="page">1</span>
                            <a href="#" class="page-numbers">2</a>
                            <a href="#" class="page-numbers">3</a>
                            <a href="#" class="page-numbers">Next</a> --}}
                        </div>
                    </div>
                </div>
                {{-- {{ $packages->links() }} --}}
            </div>
            @endif

           
        </div>
        </div>
    </section>
    <!-- end blog details section -->
@endsection()
