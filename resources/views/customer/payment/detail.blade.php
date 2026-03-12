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
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <img src="{{url('frontdata/images/bird.png') }}"><br><br>
                        <h5>Mohon untuk dapat melakukan pembayaran sebesar : </h5>
                        <br>
                        
                        <h2>Rp <b class="totpay">{{ number_format($order->total_payment,0,',','.')}}</b></h2>
                        <br>
                      <h5>Melalui Akun Berikut </h5>
                    </center>
                    <br>
                    <center>
                        <p> {{ $order->bank_account->bank_name}}</p>
                        <h5> {{ $order->bank_account->bank_acc_no}}</h5>
                        <p> {{ $order->bank_account->bank_acc_name}}</p>

                    </center>
                    <br>
                    <br>

                </div>
                
                <div class="col-md-12">
                    <a href="{{url('payment-confirm/'.$order->id)}}" class="btn btn-lg btn-primary" style="width:100%;">CONFIRM NOW</a>

                </div>
             
            </div>

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