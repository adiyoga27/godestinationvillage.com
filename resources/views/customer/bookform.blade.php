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
                <h1>Book Form</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Book Form</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{url('customer/img/page-title-area/account.jpg')}}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->
    <section class="booking-section ptb-100 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="booking-form">
                        <form action="{{ url('booking/send') }}" method="post">
                            @csrf
                            <input type="hidden" name="idtour" class="form-control" id="exampleInputEmail1" placeholder=""
                                value="{{ $packages->id }}" readonly>
                            <input type="hidden" name="village_id" class="form-control" id="exampleInputEmail1"
                                placeholder="" value="{{ $packages->village_id }}" required readonly>


                            <div class="content">
                                <h3>Tour Packages Information</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tour Packages Name</label>
                                        <input type="text" name="tourname" class="form-control" placeholder=""
                                            value="{{ $packages->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Type</label>
                                        <input type="text" name="type" class="form-control" placeholder=""
                                            value="{{ $packages->category->name }}" required readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Village</label>
                                        <input type="text" name="village" class="form-control" placeholder=""
                                            value="{{ $packages->detailVillage->village_name }}" required readonly>
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
                                                            id="exampleInputEmail1" placeholder=""
                                                            value="@isset($user){{ $user->name }}@endisset" required>

                                                            <input type="hidden" name="customerid" class="form-control"
                                                                id="exampleInputEmail1" placeholder=""
                                                                value="@isset($user){{ $user->id }}@endisset" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Email</label>
                                                            <div class="form-group">

                                                                <input type="email" name="email" class="form-control"
                                                                    placeholder=""
                                                                    value="@isset($user){{ $user->email }}@endisset" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Address</label>
                                                                <div class="form-group">

                                                                    <input type="text" name="address" class="form-control"
                                                                        placeholder=""
                                                                        value="@isset($user){{ $user->address }}@endisset" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Phone</label>
                                                                    <div class="form-group">

                                                                        <input type="text" name="phone" class="form-control"
                                                                            placeholder=""
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
                                                        <h3>Book Information</h3>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Pax</label>
                                                                <div class="form-group">

                                                                    <input type="number" name="pax" min="2" value="2" class="form-control pax"
                                                                        id="pax" placeholder="" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Price / Pax <b>@if ($packages->disc > 0) ( Diskon )@endif</b></label>

                                                                <div class="input-group mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <span style="background-color: #fd5056; color:white"
                                                                            class="input-group-text" id="basic-addon1">Rp</span>
                                                                    </div>

                                                                    <input type="text" name="price" class="form-control price"
                                                                        id="exampleInputEmail1" placeholder=""
                                                                        value="{{ $packages->disc > 0 ? $packages->disc : $packages->price }}"
                                                                        readonly>

                                                                    <input type="hidden" name="totalprice" class="form-control totalprice"
                                                                        id="exampleInputEmail1" placeholder=""
                                                                        value="{{ $packages->disc > 0 ? $packages->disc : $packages->price }}"
                                                                        required>

                                                                </div>
                                                            </div>
                                                            @if ($packages->category->name != 'Virtual Tour')
                                                                <div class="col-md-12">
                                                                    <label>Date</label>
                                                                    <div class="form-group">

                                                                        <input type="datetime-local" name="checkin_date" class="form-control"
                                                                            placeholder="" required>
                                                                    </div>
                                                                </div>
                                                          
                                                                <div class="col-md-12">
                                                                <label for="chkPassport">
                                                                    <input type="checkbox" id="chkPassport" />
                                                                    Include Pickup Location ?
                                                                </label>
                                                                <hr />
                                                                 </div>
                                                                    <div class="col-md-6" id="dvPassport" style="display: none">
                                                                        <label>Pick up location</label>

                                                                            <input type="text" name="pickup" class="form-control" value = " "
                                                                                placeholder="Input your location pick up" required>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="col-md-6" id="dvPassport2" style="display: none">
                                                                        <label>Hotel/Villa/Guest House Name</label>

                                                                            <input type="text" name="pickupname" class="form-control" value = " "
                                                                                placeholder="Input your place name" placeholder=""
                                                                                required>
                                                                        <hr>
                                                                </div>



                                                            @endif

                                                            <div class="col-md-12">
                                                                <label>Special Note</label>
                                                                <div class="form-group">

                                                                    <textarea placeholder="Input your note transaction" style="height: 100pt"
                                                                        name="special_note" class="form-control" ></textarea>
                                                                </div>
                                                                <hr>
                                                            </div>
                                                            <div class="col-md-8">
                                                            </div>
                                                            <div class="col-md-4">

                                                                <h3 class="total">Total : Rp
                                                                    {{ $packages->disc > 0 ? number_format($packages->disc, 0, ',', '.') : number_format($packages->price, 0, ',', '.') }}
                                                                </h3>

                                                                <p>*Please check your form, because the order cannot be
                                                                    changed</p>
                                                                <button class="btn btn-lg btn-warning btn-block">BOOK
                                                                    NOW</button>
                                                            </div>




                                                        </div>
                                                    </div>
                        </section>
                        </form>
                        </section>
                    @endsection()

                    @section('js')
                        <script>

$(function () {
        $("#chkPassport").click(function () {
            if ($(this).is(":checked")) {
                $("#dvPassport").show();
                $("#dvPassport2").show();

            } else {
                $("#dvPassport").hide();
                $("#dvPassport2").hide();

            }
        });
    });

                            $(function() {
                                $("#datepicker").datepicker({
                                    dateFormat: 'yy-mm-dd'
                                });
                            });
                            //change
                            // $('.pax').keyup(function() {
                            //     v = parseInt($(this).val());
                            //     min = parseInt($(this).attr('min'));


                            //     if (v < min) {

                            //         $(this).val(min);
                            //     }

                            //     $('.totalprice').val($('.pax').val() * $('.price').val())
                            //     result = formatRupiah(($('.pax').val() * $('.price').val()).toString(), '')
                            //     $('.total').html("Total : Rp" + result)
                            // })
                            $('.pax').change(function() {
                                v = parseInt($(this).val());
                                min = parseInt($(this).attr('min'));


                                if (v < min) {

                                    $(this).val(min);
                                }
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
