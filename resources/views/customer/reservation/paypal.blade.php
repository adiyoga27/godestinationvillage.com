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
                <h1>Paypal Reservation </h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Reservation </a>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Paypal </a>

                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/privacy.jpg') }}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->
    <section class="privacy-policy ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12  ftco-animate">
                    <h4>Your Reservation List</h4>
                </div>
                <div class="col-lg-4">
                    <aside class="widget-area">
                        <section class="widget">
                            <ul>
                                <?php if (isset(Auth::user()->email)) {
                                $email = Auth::user()->email;
                                } else {
                                $email = $isiemail;
                                } ?>
                                <li><a href="{{ url('reservation/' . $email) }}"><i
                                            class='bx bx-chevron-right'></i>Unpaid</a></li>
                                <li><a href="{{ url('reservation/paid/' . $email) }}"><i
                                            class='bx bx-chevron-right'></i>Paid</a></li>
                                <li><a href="{{ url('reservation/paypal/' . $email) }}" class="active"><i
                                            class='bx bx-chevron-right'></i>Paypal</a></li>
                                <li><a href="{{ url('reservation/bank/' . $email) }}"><i
                                            class='bx bx-chevron-right'></i>Bank Transfer</a></li>
                                <li><a href="{{ url('reservation/cancel/' . $email) }}"><i
                                            class='bx bx-chevron-right'></i>Canceled</a></li>
                                </li>
                            </ul>
                        </section>
                    </aside>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="content">
                        <div class="col-md-12">
                            @if (count($order) == 0)
                                <h3>
                                    No Transactions </h3>
                            @endif
                            @foreach ($order as $orders)
                                <div class="row bor">
                                    <div class="col-md-3">
                                        <img src="{{ url('storage/packages/' . $orders->package->default_img) }}"
                                            width="100%" alt="">
                                    </div>
                                    <div class="col-md-9">
                                        <h3>{{ $orders->package_name }}</h3>
                                        <hr>

                                        <h6> {{ $orders->package->category->name }}</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <i class="fa fa-address-book"></i> {{ $orders->pax }} Peoples
                                            </div>
                                            <div class="col-md-6">
                                                <i class="fa fa-industry"></i> {{ $orders->checkin_date }}
                                            </div>
                                            <div class="col-md-12">
                                                <b style="padding-top:10px; display:block;">Special notes :</b>
                                                <p>{{ $orders->special_note }}</p>
                                            </div>
                                            <div class="col-md-12">
                                                <hr>
                                                <button class="btn btn-warning">$ {{ $orders->total_payment }}
                                                    PAID</button>
                                                <button class="btn btn-danger">With {{ $orders->payment_type }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if (count($order) > 0)

                                <div class="item col-md-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="pagination text-center">


                                                @for ($i = 1; $i <= $order->lastPage(); $i++)
                                                    <a href="{{ $order->url($i) }}" class="page-numbers @if ($order->currentPage() == $i) current @endif">

                                                        {{ $i }}
                                                    </a>
                                                @endfor
                                                @if ($order->lastPage() > 1)
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
                </div>

            </div>
        </div>

    </section>
@endsection()

<script
    src="https://www.paypal.com/sdk/js?client-id=AWqwc4AETYKPOwQioHYGRXZAFeAcSrSB6BhSEpSHzq2c3UdafPYHin9Zdy0SXRlo_EzmmYxevXBlv_xV">
</script>
<script>
</script>
