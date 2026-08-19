<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex,nofollow">
    <title>Secure Payment — GODEVI</title>
    <link rel="icon" href="{{ url('assets/customer/img/favicon.png') }}" type="image/png" />

    @vite(['resources/css/app.css'])

    <style>
        html,
        body {
            height: 100%;
        }

        #snap-container {
            min-height: 100vh;
            width: 100%;
        }

        .pay-loader {
            position: fixed;
            inset: 0;
            z-index: 60;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            background: #ffffff;
            transition: opacity .35s ease;
        }

        .pay-loader.is-hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-white antialiased">
    <div id="pay-loader" class="pay-loader">
        <img src="{{ url('assets/customer/img/logo.png') }}" alt="GODEVI" class="h-14 w-auto">
        <div class="flex items-center gap-2 text-sm font-semibold text-ink-500">
            <svg class="h-5 w-5 animate-spin text-brand-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            {{ __('Loading secure payment…') }}
        </div>
    </div>

    <div id="snap-container"></div>

    <script src="{{ config('midtrans.uri') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        (function() {
            var loader = document.getElementById('pay-loader');
            var started = false;
            var ready = function() {
                if (loader && !loader.classList.contains('is-hidden')) {
                    loader.classList.add('is-hidden');
                }
            };

            // Hide loader as soon as the Snap popup/embed is up, or after a safe timeout.
            setTimeout(ready, 6000);

            window.snap.embed('{{ $snapToken }}', {
                embedId: 'snap-container',
                onSuccess: function(result) {
                    console.log(result);
                    window.location.href = '{{ $redirectURISuccess }}';
                },
                onPending: function(result) {
                    console.log(result);
                    window.location.href = '{{ $redirectURIError }}';
                },
                onError: function(result) {
                    console.log(result);
                    window.location.href = '{{ $redirectURIError }}';
                },
                onClose: function() {
                    window.location.href = '{{ $redirectURIError }}';
                }
            });
        })();
    </script>
</body>

</html>