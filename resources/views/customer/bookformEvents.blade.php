@extends('customer/layout')
@section('content')
    <style>
        label {
            font-weight: 400
        }
    </style>
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Reservation Form</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Reservation Form</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/header-event.png') }}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->
    <section class="booking-section ptb-100 bg-light" style="background-color:ghostwhite !important">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="booking-form">
                        <form action="{{ $packages->is_free ? url('bookingEvents/sendEventFree') : url('bookingEvents/sendEvent') }}" id="formbook" method="post">
                            @csrf
                            <input type="hidden" name="idevent" class="form-control" value="{{ $packages->id }}"
                                readonly>
                            <input type="hidden" name="customerid" class="form-control"
                                value="@isset($user){{ $user->id }}@endisset" required>
                                <div class="content">
                                    <h3>Event Information</h3>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Event Name</label>
                                            <input type="text" name="eventname" class="form-control"
                                                value="{{ $packages->name }}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Category</label>
                                            <input type="text" name="type" class="form-control"
                                                value="{{ $packages->category->name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 30px"></div>
                                <div class="content">
                                    <h3>Customer Information</h3>
                                    <hr>
                                    <div class="payment-tabs">
                                        <div id="tab-credit-card" class="tab-pane active">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Customer Name</label>
                                                    <div class="form-group">
                                                        <input type="text" name="customername" class="form-control"
                                                            placeholder="Input your name"
                                                            value="@isset($user){{ $user->name }}@endisset" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Email</label>
                                                        <div class="form-group">
                                                            <input type="email" name="email" class="form-control"
                                                                placeholder="Input your email"
                                                                value="@isset($user){{ $user->email }}@endisset" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Address</label>
                                                            <div class="form-group">
                                                                <input type="text" name="address" class="form-control" placeholder="Input your address"
                                                                    value="@isset($user){{ $user->address }}@endisset" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Phone</label>
                                                                <div class="form-group">
                                                                    <input type="text" name="phone" class="form-control"
                                                                        placeholder="Input your phone"
                                                                        value="@isset($user){{ $user->phone }}@endisset" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Gender</label>
                                                                    <div class="select-box">
                                                                        <select name="gender" id="" class="form-control">
                                                                            <option value="">Male</option>
                                                                            <option value="">Female</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        </div>
                                        <div style="margin-top: 30px"></div>
                                        <div class="content">
                                            <h3>Reservation Information</h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Pax</label>
                                                    <input type="number" name="pax" min="1" value="1" class="form-control pax" placeholder="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Price / Pax <b></b></label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span style="background-color: #fd5056; color:white" class="input-group-text"
                                                                id="basic-addon1">Rp</span>
                                                        </div>
                                                        <input type="text" name="price" class="form-control price" placeholder=""
                                                            value="{{ $packages->price }}" readonly>
                                                    </div>
                                                    <input type="hidden" name="totalprice" class="form-control totalprice" placeholder=""
                                                        value="{{ $packages->price }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Special Note</label>
                                                    <textarea placeholder="Input your note transaction" style="height: 100pt" name="special_note" class="form-control"
                                                        placeholder=""></textarea>
                                                    <hr>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-top: 30px"></div>
                                        <div class="content">
                                            <div class="row">
                                                <div class="col-md-8"> </div>
                                                <div class="col-md-4">
                                                    @if($packages->is_free)
                                                    <h3 class="">Total : <font style="color:red">Gratis</font></h3>
                                                    @else
                                                        <h3 class="total">Total : Rp
                                                        {{ number_format($packages->price, 0, ',', '.') }}</h3>
                                                    @endif
                                                        <p>* Please check your form, because the order cannot be changed</p>

                                          
                                                    <button class="btn btn-lg btn-warning btn-block" id="pay-button"> <span style="display: none" class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span><span id="text-book">BOOK NOW</span></button>

                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </section>
                    @endsection()
                    @section('js')
                        <script>
                            var result = '';
                         
                            $(function() {
                                $("#datepicker").datepicker({
                                    dateFormat: 'yy-mm-dd'
                                });
                            });
                            //change
                            $('.pax').keyup(function() {
                                $('.totalprice').val($('.pax').val() * $('.price').val())
                                result = formatRupiah(($('.pax').val() * $('.price').val()).toString(), '')
                                $('.total').html("Total : Rp" + result)
                            })
                            $('.pax').change(function() {
                                $('.totalprice').val($('.pax').val() * $('.price').val())
                                result = formatRupiah(($('.pax').val() * $('.price').val()).toString(), '')
                                $('.total').html("Total : Rp " + result)
                            })
                            /* Fungsi formatRupiah */
                            function formatRupiah(angka, prefix) {
                                var number_string = angka.replace(/[^,\d]/g, '').toString(),
                                    split = number_string.split(','),
                                    sisa = split[0].length % 3,
                                    rupiah = split[0].substr(0, sisa),
                                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                                if (ribuan) {
                                    separator = sisa ? '.' : '';
                                    rupiah += separator + ribuan.join('.');
                                }
                                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
                            }
                        </script>
                    @endsection
