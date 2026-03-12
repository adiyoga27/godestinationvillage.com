<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        
            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">
        
            <title>{{ config('app.name', 'Laravel') }}</title>
        
            <!-- Scripts -->
            <script src="{{ asset('js/app.js') }}" defer></script>
        
            <!-- Fonts -->
            <link rel="dns-prefetch" href="//fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        
            <!-- Styles -->
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        </head>
    <body>
        <button id="pay-button">Bayar</button>
        <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-GFgcArE9h79HUy4u"></script>
        <script type="text/javascript">
            document.getElementById('pay-button').onclick = function(){
                // SnapToken acquired from previous step
                snap.pay('{{$token}}', {
                    // Optional
                    onSuccess: function(result){
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    // Optional
                    onPending: function(result){
                        document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    },
                    // Optional
                    onError: function(result){
                      document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                    }
                });
            };
        </script>
    </body>
</html>