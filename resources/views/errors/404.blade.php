<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Author: HiBootstrap, Category: Tourism, Multipurpose, HTML, SASS, Bootstrap" />
        <!-- title -->
   

    <!-- title -->
    <title>{{ $title ?? 'GODEVI - Authentic Village Experiences' }}</title>
    <!-- bootstrap CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/bootstrap.min.css') }}" />
    <!-- font-awesome CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/fontawesome.css') }}" />
    <!-- box-icon CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/boxicons.min.css') }}">
    <!-- animate CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/animate.min.css') }}" />
    <!-- bootstrap date-picker CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/bootstrap-datepicker.min.css') }}">
    <!-- nice select CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/nice-select.css') }}">
    <!-- magnific popup CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/magnific-popup.min.css') }}" />
    <!-- owl-carousel CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/owl.carousel.min.css') }}" />
    <!-- mean-menu CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/meanmenu.min.css') }}" />
    <!-- main style CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/style.css') }}" />
    <!-- responsive CSS -->
    <link rel="stylesheet" href="{{ url('customer/css/responsive.css') }}" />
    <!-- favicon -->
    <link rel="icon" href="{{ url('customer/img/favicon.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ url('frontdata/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('frontdata/css/owl.theme.default.min.css') }}">
        <!-- favicon -->
        <link rel="icon" href="{{ url('customer/img/favicon.png')}}" type="image/png"/>
    </head>
    <body>

        <!-- start error area -->
        <section class="error-area ptb-100">
            <div class="container">
                <div class="error-content">
                    <img src="{{ url('customer/img/404.png')}}" alt="image" />
                    <h3>Ooops! Page Not Found</h3>
                    <p>
                        The page you are looking for might have been removed had its name changed or is temporarily unavailable.
                    </p>
                    <a href="{{url('/')}}" class="btn-primary">Back to Home</a>
                </div>
            </div>
        </section>
        <!-- end error area -->

    <!-- jquery JS -->
    <script src="{{ url('customer/js/jquery.min.js') }}"></script>
    <!-- popper JS -->
    <script src="{{ url('customer/js/popper.min.js') }}"></script>
    <!-- bootstrap JS -->
    <script src="{{ url('customer/js/bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker JS -->
    <script src="{{ url('customer/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- nice select JS -->
    <script src="{{ url('customer/js/jquery.nice-select.min.js') }}"></script>
    <!-- magnific popup JS -->
    <script src="{{ url('customer/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- filterizr JS -->
    <script src="{{ url('customer/js/jquery.filterizr.min.js') }}"></script>
    <!-- owl carousel JS -->
    <script src="{{ url('customer/js/owl.carousel.min.js') }}"></script>
    <!-- mean menu JS -->
    <script src="{{ url('customer/js/meanmenu.min.js') }}"></script>
    <!-- form validator -->
    <script src="{{ url('customer/js/form-validator.min.js') }}"></script>
    <!-- contact form JS -->
    <script src="{{ url('customer/js/contact-form-script.js') }}"></script>
    <!-- ajax chimp JS -->
    <script src="{{ url('customer/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ url('frontdata/js/owl.carousel.min.js') }}"></script>

    <!-- script JS -->
    <script src="{{ url('customer/js/script.js') }}"></script>
	</body>
</html>