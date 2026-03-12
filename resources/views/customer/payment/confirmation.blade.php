<html>

<head>
    <title>PAYMENT GODEVI</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">



</head>

<body>
    <div class="container-mini">
{{-- {{dd($order)}} --}}
        <form action="{{url('payment/pay/confirm-payment')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="idtrx" class="form-control idtrx" id="exampleInputEmail1" value="{{$order->id}}">
            <div class="row">
                <div class="col-md-12">
                 
                    <center>
                    <img src="{{url('frontdata/images/bird.png') }}"><br>
                    <h4> Konfirmasi Pembayaran</h4>
                    <br>

                    </center>
                </div>
           
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Your Bank Account</label>
                        <select name="bank" id="" class="form-control">
                            @foreach($bank as $banks)
                            <option value="{{$banks->bank_name}}">{{$banks->bank_name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name Account</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Masukkan nama akun bank">
                    </div>
                </div>
              
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Date of transfer</label>
                        <input type="text" name="date" class="form-control" id="date" placeholder="22/04/2019">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Message</label>
                        <input type="text" name="message" class="form-control" id="exampleInputEmail1" placeholder="Message">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Evidence of transfer</label>
                        <input name="bukti" type="file" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-lg btn-primary" style="width:100%;">CONFIRM NOW</button>

                </div>
                {{-- <div class="col-md-12">
                    <br>
                    <center>
                        <h5>Or Pay With</h5>
                    </center>
                    <br>
                </div>
                <div class="col-md-12">
                    <div id="paypal-button-container"></div>
                </div> --}}
            </div>

        </form>
    </div>

</body>

</html>
<style>
    body {
        padding: 50px 10px;
        ;
        background: #eee;
    }

    .container-mini {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #ccc;
        padding: 20px;
    }
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ url('frontdata/js/jquery.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AZzcGXiUsp84fPHm_PpWICNmWiz6iOi1jkzmofR0q2oQn-6dtl4uhb4HBRzT4IrRq1J3dk6sFIEdK53v"></script>

<script>
    $(document).ready(function() {
        var nilai = $('.totpay').html()
        var id = $('.idtrx').val()
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: nilai
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                // Capture the funds from the transaction
                return actions.order.capture().then(function(details) {
                    // Show a success message to your buyer
                    $.ajax({
                        url: 'pay/paypal-payment',
                        type: 'get',
                        data: {
                            _token: CSRF_TOKEN,
                            editid: id
                        },
                        success: function(response) {
                            window.location = '/reservation/paid/'+response;
                        }
                    });
                });
            }
        }).render('#paypal-button-container');
    })
</script>
<script>
  $( function() {
    $( "#date").datepicker({ dateFormat: 'yy-mm-dd' });
  } );
  </script>