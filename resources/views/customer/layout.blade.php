@if(!Auth::guest())
    @if(Auth::user()->role_id <> 3)
    <script type="text/javascript">
        window.location = "{{ url('/administrator/dashboard') }}";//here double curly bracket
    </script>
    @endif
@endif

<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> --}}
    <meta name="viewport"
        content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi">

    <meta property="og:site_name" content="Godevi">
    <meta property="og:title" content="{{ $title ?? 'GODEVI - Authentic Village Experiences' }}" />
    <meta property="og:description"
        content="{{ $content ?? 'GODEVI is a socially pro-active business dedicated to uplifting local communities in the developing village through efforts in tourism industry. Beside also support fair trade, we create a marketplace by empowering the village communities. GODEVI adheres to a strict policy of promoting Socially Responsible Village Tourism activities.' }}" />
    <meta property="og:image" itemprop="image" content="{{ $image ?? url('frontdata/images/bird.png') }}">
    <meta property="og:type" content="website" />
    <meta property="og:updated_time" content="1440432930" />


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <style>
        .coret {

            text-decoration: line-through !important;
            color: red;
            margin: 10px 0 0 0;
            padding: 0;
            font-size: 18px;
        }

        .bookfix {
            width: 100%;
            height: 60px;
            padding: 10px;
            text-align: center;
            background: #d81c25;
            color: #fff;
            bottom: 0;
            right: 0;
            position: fixed;
            z-index: 3000;
            font-size: 24px;
        }

        .bookfix:hover {
            color: #eee;
        }

        blink {
            animation: blink-animation 1s steps(5, start) infinite;
            -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        @-webkit-keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .dropdown-toggle,
        .dropdown-menu {
            border-radius: 0px !important;
        }

        .dropdown-item:hover {
            color: red;
            background-color: #dc3545;
        }

        .dropdown-menu-right .dropdown-item {
            opacity: 100%;
            /* color: white; */
            background-color: white;
        }


        .dropdown:hover>.dropdown-menu {
            display: block;
        }



        @media (max-width: 768px) {
            .carousel-inner .carousel-item>div {
                display: none;

            }

            .carousel-inner .carousel-item>div:first-child {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .carousel-inner .carousel-item>div {
                display: none;
            }

            .carousel-inner .carousel-item>div:first-child {
                display: block;
            }
        }

        .carousel-inner .carousel-item.active,
        .carousel-inner .carousel-item-next,
        .carousel-inner .carousel-item-prev {
            display: flex;
        }

        /* display 3 */
        @media (min-width: 768px) {

            .carousel-inner .carousel-item-right.active,
            .carousel-inner .carousel-item-next {
                transform: translateX(33.333%);
            }

            .carousel-inner .carousel-item-left.active,
            .carousel-inner .carousel-item-prev {
                transform: translateX(-33.333%);
            }
        }

        .carousel-inner .carousel-item-right,
        .carousel-inner .carousel-item-left {
            transform: translateX(0);
        }


        .roundedcar {
            border-radius: 55px 55px 55px 55px !important;
            overflow: hidden !important;

        }

        @media (max-width: 768px) {
            .btn-account {
                width: 95% !important;
            }

            .profile-nav {
                padding-top: 20px !important;
                width: 250px;
                margin: auto;
            }

            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            .header-area .top-header-area .side-option .item .language .menu {
                width: 150px !important;
            }

        }

        .cart-btn {
            display: none !important;
        }

        .profile-nav {
            width: 250px;
            margin: auto;
        }


        .profile-nav p {
            text-align: center;
            padding-top: 10px;
            font-weight: bold;

        }

        .profile-nav img {
            background-color: #e0e0e0;
            align-items: center;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .profile-rounded-img img {

            border-radius: 50% !important;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .dropdown-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            .dropdown-nav {
                display: none;
            }

            .dropdown-mobile {
                display: block;
            }
        }

    </style>
</head>

<body>
    <!-- start preloader area -->
    {{-- <div id="loading">
        <div class="loader"></div>
    </div> --}}
    
    <!-- end preloader area -->

    <!-- start header area -->
    <header class="header-area">
        <!-- start top header area -->
        <div class="top-header-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-7">
                        <div class="contact-info">
                            <div class="content">
                                <i class='bx bx-phone'></i>
                                <a href="tel:+6281997674778">+62 819-9767-4778</a>
                            </div>
                            <div class="content">
                                <i class='bx bx-envelope'></i>
                                <a href="mailto:hello@jaunt.com">hello@godestinationvillage.com</a>
                            </div>
                            <div class="content">
                                <a href="#"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-5">
                        <div class="side-option">
                            <div class="item">
                                <div class="language">
                                    <a href="#language" id="languageButton" class="btn-secondary">
                                        {{-- @lang('Language') --}}

                                        @if (app()->getLocale() == 'en')
                                            <img src="{{ url('customer/img/flag-uk.png') }}" alt="flag">&nbsp;&nbsp;
                                            English
                                        @else
                                            <img src="{{ url('customer/img/flag-indonesia.png') }}"
                                                alt="flag">&nbsp;&nbsp;
                                            Indonesia
                                        @endif


                                        <i class='bx bxs-chevron-down'></i>
                                    </a>
                                    <ul class="menu">
                                        <li class="menu-item">
                                            <a href="{{ url('locale/en') }}" class="menu-link"
                                                style="color:{{ app()->getLocale() == 'en' ? 'red' : 'black' }}">
                                                <img src="{{ url('customer/img/flag-uk.png') }}" alt="flag">
                                                English
                                            </a>
                                        </li>

                                        <li class="menu-item"><a href="{{ url('locale/id') }}" class="menu-link"
                                                style="color:{{ app()->getLocale() == 'id' ? 'red' : 'black' }}">
                                                <img src="{{ url('customer/img/flag-indonesia.png') }}" alt="flag">
                                                Indonesia</a>
                                        </li>


                                    </ul>
                                </div>
                            </div>


                            <div class="item">
                                <a href="#searchBox" id="searchButton" class="btn-search" data-effect="mfp-zoom-in">
                                    <i class='bx bx-search-alt'></i>
                                </a>
                                <div id="searchBox" class="search-box mfp-with-anim mfp-hide">
                                    <form class="search-form">
                                        <input class="search-input" name="search" placeholder="Search" type="text">
                                        <button class="btn-search">
                                            <i class='bx bx-search-alt'></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end top header area -->

        <!-- start navbar area -->
        <div class="main-navbar-area">
            <div class="main-responsive-nav">
                <div class="container">
                    <div class="main-responsive-menu">
                        <div class="logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ url('customer/img/logo1.png') }}" alt="logo">
                            </a>
                        </div>
                        <div class="cart responsive">
                            <a href="cart.html" class="cart-btn"><i class='bx bx-cart'></i>
                                <span class="badge">0</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-nav">
                <div class="container">
                    <nav class="navbar navbar-expand-md navbar-light">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ url('customer/img/logo.png') }}" width="200px" alt="Logo">
                        </a>
                        <div class="collapse navbar-collapse mean-menu">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a href="{{ url('/') }}" class="nav-link toggle">@lang('Home')</a>

                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('services') }}" class="nav-link toggle">@lang('Our Services')</a>

                                </li>

                                <li class="nav-item"><a href="{{ url('village') }}"
                                        class="nav-link toggle">@lang('Explore Village')</a>

                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('events') }}" class="nav-link toggle">Events</a>
                          
                                </li>
                                {{-- <li class="nav-item"><a href="{{ url('tour-packages') }}"
                                        class="nav-link toggle">@lang('homestay')</a>

                                </li> --}}
                                <li class="nav-item"><a href="{{ url('homestay') }}"
                                        class="nav-link toggle">@lang('Home Stay')</a>

                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('blog') }}" class="nav-link">@lang('Blog')</a>
                                </li>

                                <!--<li class="nav-item">-->
                                <!--    <a href="https://play.google.com/store/apps/details?id=com.godevi.id.webview"-->
                                <!--        class="nav-link">Apps</a>-->
                                <!--</li>-->
                                @auth
                                    <li class="dropdown-nav">


                                        <div class="dropdown">

                                            <button class="btn btn-danger btn-account " type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown">
                                                Account
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <div class="profile-nav">
                                                    <br>
                                                    @if(Auth::user()->avatar == null)
                                                    <center><img src="{{ url('customer/img/users.png') }}"
                                                            alt="Avatar">
                                                    </center>
                                                    @else
                                                    <center><img src="{{ url('storage/users/' . Auth::user()->avatar) }}"
                                                            alt="Avatar">
                                                    </center>
                                                    @endif
                                                    <p>{{ Auth::user()->name }}</p>
                                                </div>
                                                <hr>
                                                <div class="col-md-12">
                                                    <a class="dropdown-item" href="{{ url('account') }}"><i
                                                            class="bx bx-user"></i>&nbsp &nbsp Account</span></a>
                                                </div>
                                                <div class="col-md-12">

                                                    <a class="dropdown-item"
                                                        href="{{ url('reservation/' . Auth::user()->email) }}"><i
                                                            class="bx bx-calendar-edit"></i>&nbsp &nbsp Reservation</a>
                                                </div>
                                                <div class="col-md-12">

                                                    <a class="dropdown-item"
                                                        href="{{ url('reservation-events/' . Auth::user()->email) }}"><i
                                                            class="bx bx-star"></i>&nbsp &nbsp Events</a>
                                                </div>

                                                <div class="col-md-12">

                                                    <a class="dropdown-item"
                                                        href="{{ url('reservation-homestay/' . Auth::user()->email) }}"><i class='bx bx-building-house'></i>&nbsp &nbsp Homestay</a>
                                                </div>
                                                <hr>
                                                <div class="col-md-12">

                                                    <form method="POST" action="{{ url('logout') }}">
                                                        {{ csrf_field() }}
                                                        <button type="submit" class="dropdown-item"><i
                                                                class="bx bx-log-out-circle"></i>&nbsp&nbsp
                                                            Logout</button>


                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="dropdown-submenu dropdown-mobile">
                                        <a style="background-color: #d81c25;" href="#" class="dropdown-toggle"
                                            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            <font style="color: white">Account </font>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ url('account') }}"><i class="bx bx-user"></i>Account</a></li>
                                            <li><a href="{{ url('reservation/' . Auth::user()->email) }}">
                                                    Reservation</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>

                                            </li>
                                        </ul>
                                    </li>

                                    <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                    </form>




                                @endauth
                                @guest
                                    <a href="{{ url('login') }}" class="btn btn-danger" type="button">
                                        Login
                                    </a>
                                @endauth

                            </ul>


                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- end navbar area -->
    </header>
    <!-- end header area -->
    @yield('content')
    <!-- start home banner area -->

    <!-- start footer area -->
    <footer class="footer-area">
        <div class="container">
            <div class="footer-top pt-100 pb-70">
                <div class="row">
                    <div class="row" data-v-44716918="">
                        <div data-aos="fade-in" data-aos-duration="500" data-aos-offset="0"
                            class="col-lg-3 col-md-5 col-sm-6 col-12 aos-init aos-animate" data-v-44716918="">
                            <div class="footer-widget" data-v-44716918="">
                                <div class="navbar-brand" data-v-44716918=""><a href="/" data-v-44716918=""><img
                                            src="{{ url('customer/img/logo-white.png') }}" alt="logo"
                                            data-v-44716918=""></a></div>
                                <p>GODEVI is a company under of PT Banua Wisata Lestari. GODEVI stands for Go
                                    Destination Village. <span class="hides">The GODEVI logo is inspired by Bali
                                        Starling birds. Bali Starling is represented as one of the rare and unique
                                        natural potentials. The colors that appear in the GODEVI logo are cheerful
                                        colors that represent tourist activities full of joyful experiences. GODEVI
                                        believes that the village as a gathering place for all potentials, each has a
                                        different uniqueness and deserves to be introduced to the world community. Like
                                        this Bali Starling, GODEVI hopes to be able to become a distinctive brand
                                        without losing the identity of the island of Bali. In addition, the starling
                                        star is green, which means that GODEVI as a digital-based business is expected
                                        to be able to use its mind to see all the opportunities and phenomena that occur
                                        while being oriented to environmental sustainability and always prioritizing
                                        spirit (SEE) Sustainability, Empowerment and Entrepreneurship.</span>
                                </p>
                                <p><button href="#" class="reads btn btn-danger btn-sm">@lang('Read More')
                                    </button><button href="#" class="css btn btn-danger btn-sm">@lang('Close')</button>
                                </p>

                                <div class="contact-info social-media" data-v-44716918="">
                                    <ul class="content d-inline-flex" data-v-44716918="">
                                        <li data-v-44716918=""><a href="https://www.facebook.com/godestinationvillage/"
                                                data-v-44716918=""><i class="bx bxl-facebook"
                                                    data-v-44716918=""></i></a></li>
                                        <li data-v-44716918=""><a href="https://www.instagram.com/godestinationvillage/"
                                                data-v-44716918=""><i class="bx bxl-instagram"
                                                    data-v-44716918=""></i></a></li>
                                        <li data-v-44716918=""><a
                                                href="https://www.youtube.com/channel/UCule1cMKmK4RKh_n-Rrx81A"
                                                data-v-44716918=""><i class="bx bxl-youtube" data-v-44716918=""></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div data-aos="fade-in" data-aos-duration="500" data-aos-delay="400" data-aos-offset="0"
                            class="col-lg-2 col-md-5 col-sm-6 col-12 aos-init aos-animate" data-v-44716918="">
                            <div class="footer-widget" data-v-44716918="">
                                <h5 data-v-44716918="">@lang('Information')</h5>
                                <ul class="footer-links" data-v-44716918="">
                                    <li><a href="/faq" class="py-2 d-block">FAQ</a></li>
                                    <li><a href="/term" class="py-2 d-block">TERMS & SERVICE</a></li>
                                    <li><a href="/v-founding" class="py-2 d-block">THE FOUNDING</a></li>
                                    <li><a href="/our-team" class="py-2 d-block">OUR TEAM</a></li>
                                    <li><a href="/v-board" class="py-2 d-block">BOARD OF EXPERTS</a></li>
                                    <li><a href="/our-partner" class="py-2 d-block">OUR PARTNER</a></li>
                                </ul>
                            </div>
                        </div>
                        <div data-aos="fade-in" data-aos-duration="500" data-aos-delay="600" data-aos-offset="0"
                            class="col-lg-3 col-md-5 col-sm-6 col-12 aos-init aos-animate" data-v-44716918="">
                            <div class="footer-widget" data-v-44716918="">
                                <h5 data-v-44716918="">@lang('Payment')</h5> <img
                                    src="{{ asset('frontdata/images/payment.png') }}" width="80%">
                            </div>
                        </div>
                        <div data-aos="fade-in" data-aos-duration="500" data-aos-delay="800" data-aos-offset="0"
                            class="col-lg-4 col-md-5 col-sm-6 col-12 aos-init aos-animate" data-v-44716918="">
                            <div class="footer-widget" data-v-44716918="">
                                <h5 data-v-44716918="">@lang('Have A Questions')</h5>
                                <div class="contact-info" data-v-44716918="">
                                    <div class="content" data-v-44716918=""><a href="#" data-v-44716918=""><i
                                                class="bx bx-map" data-v-44716918=""></i>Jl Kroya No 1 Denpasar</a>
                                    </div>
                                    <div class="content" data-v-44716918=""><a href="tel:+6281997674778"
                                            data-v-44716918=""><i class="bx bx-phone" data-v-44716918=""></i>+62 819-9767-4778</a></div>
                                    <div class="content" data-v-44716918=""><a
                                            href="mailto:hello@godestinationvillage.com" data-v-44716918=""><i
                                                class="bx bx-envelope"
                                                data-v-44716918=""></i>hello@godestinationvillage.com</a></div>


                                    <div class="content" data-v-44716918="" id="histats_counter" style="display: none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="copy-right-area">
                <div class="container">
                    <div class="copy-right-content">
                        <p>
                            Copyright @2021 GoDestinationVillage
                            {{-- <a href="https://hibootstrap.com/" target="_blank">
                                HiBootstrap.com
                            </a> --}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end footer area -->
    @extends('customer/modal')

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
    <script id="pixel-chaty" async="true" src="https://cdn.chaty.app/pixel.js?id=stx7rWCJ"></script>

    @yield('js')
    <!-- WhatsHelp.io widget -->
    <script type="text/javascript">
$( "form" ).submit(function( event ) {
                                $(".spinner-border").show();
                                $("#text-book").hide();
                                $("#pay-button").prop('disabled', true);

                            });
        $(function($) {
            let url = window.location.href;
            $('.nav-link').each(function() {


                if (this.href === url) {
                    // if(this.href != 'http://localhost:8000/home'){

                    // }

                    $(this).addClass('active')

                } else {
                    $(this).removeClass('active')

                }
            });
        });

        // $(function() {
        //     $('#nav-item').find('a').click(function(e) {
        //         e.preventDefault();
        //         alert('ads');
        //         $(this.hash).show().siblings().hide();
        //         $('#nav-item').find('a').parent().removeClass('active')
        //         $(this).parent().addClass('active')
        //     }).filter(':first').click();
        // });
        // (function() {
        //     var options = {
        //         facebook: "259094734978868", // Facebook page ID
        //         whatsapp: "+6282236803301", // WhatsApp number
        //         call_to_action: "Message us", // Call to action
        //         button_color: "#FF6550", // Color of button
        //         position: "right", // Position may be 'right' or 'left'
        //         order: "facebook,whatsapp", // Order of buttons
        //     };
        //     var proto = document.location.protocol,
        //         host = "whatshelp.io",
        //         url = proto + "//static." + host;
        //     var s = document.createElement('script');
        //     s.type = 'text/javascript';
        //     s.async = true;
        //     s.src = url + '/widget-send-button/js/init.js';
        //     s.onload = function() {
        //         WhWidgetSendButton.init(host, proto, options);
        //     };
        //     var x = document.getElementsByTagName('script')[0];
        //     x.parentNode.insertBefore(s, x);
        // })();


        $('.hides').hide()
        $('.css').hide()
        $('.reads').click(function() {
            $('.hides').show()
            $('.css').show()
            $('.reads').hide()
        })

        $('.css').click(function() {
            $('.hides').hide()
            $('.reads').show()
            $('.css').hide()
        })
        $('#recipeCarousel').carousel({
            interval: 10000
        })

        $('.carousel .carousel-item').each(function() {
            var minPerSlide = 3;
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i = 0; i < minPerSlide; i++) {
                next = next.next();
                if (!next.length) {
                    next = $(this).siblings(':first');
                }

                next.children(':first-child').clone().appendTo($(this));
            }
        });
    </script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CD6TPM6T4N"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-CD6TPM6T4N');
</script>

<!-- Google tag (gtag.js) -->
<!--<script async src="https://www.googletagmanager.com/gtag/js?id=G-F0LL71YS66"></script>-->
<!--<script>-->
<!--  window.dataLayer = window.dataLayer || [];-->
<!--  function gtag(){dataLayer.push(arguments);}-->
<!--  gtag('js', new Date());-->

<!--  gtag('config', 'G-F0LL71YS66');-->
<!--</script>-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-210470879-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-210470879-1');
</script> -->



    <!-- Histats.com  (div with counter) -->
    <!-- Histats.com  START  (aync)-->
    <script type="text/javascript">
        var _Hasync = _Hasync || [];
        _Hasync.push(['Histats.start', '1,4554660,4,2041,130,60,00011001']);
        _Hasync.push(['Histats.fasi', '1']);
        _Hasync.push(['Histats.track_hits', '']);
        (function() {
            var hs = document.createElement('script');
            hs.type = 'text/javascript';
            hs.async = true;
            hs.src = ('//s10.histats.com/js15_as.js');
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
        })();
    </script>
    <noscript><a href="/" target="_blank"><img src="//sstatic1.histats.com/0.gif?4554660&101" alt="hidden hit counter"
                border="0"></a></noscript>
    <!-- Histats.com  END  -->

</body>

</html>
