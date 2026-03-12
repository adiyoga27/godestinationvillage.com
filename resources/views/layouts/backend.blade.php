@if(Auth::user()->role_id == 3)
  <script type="text/javascript">
    window.location = "{{ url('/') }}";//here double curly bracket
  </script>
@endif
<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- Required meta tags -->
  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Godevi - Administrator') }}</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('dist/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('dist/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('dist/css/style.css') }}">
  {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> --}}
  <link rel="stylesheet" href="{{ asset('dist/css/rating.css') }}">
  <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
  <!-- endinject -->
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('favicons/site.webmanifest') }}">
  <link rel="mask-icon" href="{{ asset('favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">

  {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  @yield('style')
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('administrator/dashboard') }}"><img style="width: 115px; height: auto" src="{{ asset('dist/images/logo.png') }}" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('administrator/dashboard') }}"><img style="height: 65px !important" src="{{ asset('dist/images/icon.png') }}" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <div class="nav-profile-img">
                @if(empty(Auth::user()->avatar))
                    <img src="{{ asset('dist/images/faces/face1.jpg') }}" alt="image">
                @else
                    <img src="{{ asset('storage/users/'.Auth::user()->avatar) }}" alt="image">
                @endif
                <span class="availability-status online"></span>
              </div>
              <div class="nav-profile-text">
                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
              </div>
            </a>
            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="{{ url('administrator/profile') }}">
                <i class="mdi mdi-pen mr-2 text-danger"></i>
                Edit Profile
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout mr-2 text-danger"></i>
                Signout
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div>
          </li>
          <li class="nav-item d-none d-lg-block full-screen-link">
            <a href="javascript:;" class="nav-link">
              <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          <li class="nav-item nav-logout d-none d-lg-block">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
              <i class="mdi mdi-power"></i>
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
              <div class="nav-profile-image">
                <center>
                @if(empty(Auth::user()->avatar))
                    <img style=" border-radius: 50%;  object-fit: cover;
                    object-position: center center;
                    width: 100px;
                    height: 100px;"  src="{{ asset('dist/images/faces/face1.jpg') }}" alt="profile">
                @else
                    <img style=" border-radius: 50%;  object-fit: cover;
                    object-position: center center;
                    width: 100px;
                    height: 100px;"src="{{ asset('storage/users/'.Auth::user()->avatar) }}" alt="profile">
                @endif
              <div style="margin-top: 10px"></div>
                <b>Administrator</b>
                <br> {{Auth::user()->email}}

                </center>
              </div>
          </li>
          <li class="nav-item">
            <hr>

            <a class="nav-link" href="{{ url('administrator/dashboard') }}">
              <span class="menu-title">Dashboard</span>
              <i class="mdi mdi-home menu-icon"></i>
            </a>
          </li>
          @if(Auth::user()->role_id == 1)
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/bank-account') }}">
              <span class="menu-title">Akun Bank</span>
              <i class="mdi mdi mdi-account-card-details menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/instagram') }}">
              <span class="menu-title">Instagram</span>
              <i class="mdi mdi mdi-instagram menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/discount-member') }}">
              <span class="menu-title">Diskon Member</span>
              <i class="mdi mdi-percent menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-title">User</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-account-box menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/user-admin') }}">Admin</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/user-village') }}">Desa Wisata</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/user-member') }}">Member</a></li>
                </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/review') }}">
              <span class="menu-title">Review</span>
              <i class="mdi mdi-percent menu-icon"></i>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-wisata" aria-expanded="false" aria-controls="ui-wisata">
              <span class="menu-title">Paket Wisata</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-wallet-travel menu-icon"></i>
            </a>
            <div class="collapse" id="ui-wisata">
                <ul class="nav flex-column sub-menu">
                  @if(Auth::user()->role_id == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/category') }}">Kategori </a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/package') }}">Paket Wisata</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/orders') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/package') }}">Pengajuan</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/orders') }}">Laporan Desa Wisata</a></li>
                  @endif

                </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-events" aria-expanded="false" aria-controls="ui-events">
              <span class="menu-title">Events</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-monitor menu-icon"></i>
            </a>
            <div class="collapse" id="ui-events">
                <ul class="nav flex-column sub-menu">
                  @if(Auth::user()->role_id == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/category-events') }}">Kategori </a></li>
                    <li class="nav-item">  <a class="nav-link" href="{{ url('administrator/events') }}">Paket Events </a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/order-event') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/events') }}">Pengajuan Event</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/order-event') }}">Laporan Events</a></li>
                  @endif


                </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-homestay" aria-expanded="false" aria-controls="ui-homestay">
              <span class="menu-title">Home Stay</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-home-modern menu-icon"></i>
            </a>
            <div class="collapse" id="ui-homestay">
                <ul class="nav flex-column sub-menu">
                  @if(Auth::user()->role_id == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/category-homestay') }}">Kategori </a></li>
                    <li class="nav-item">  <a class="nav-link" href="{{ url('administrator/homestay') }}">Paket Home Stay </a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/order-homestay') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/homestay') }}">Pengajuan Homestay</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('administrator/order-homestay') }}">Laporan Home Stay</a></li>
                  @endif

                </ul>
            </div>
          </li>

       



          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/category') }}">
              <span class="menu-title">Kategori Paket Wisata</span>
              <i class="mdi mdi-tag menu-icon"></i>
            </a>
          </li> --}}

          {{-- <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/package') }}">
              <span class="menu-title">Paket Wisata</span>
              <i class="mdi mdi-animation menu-icon"></i>
            </a>
          </li> --}}
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/blog') }}">
              <span class="menu-title">Blog</span>
              <i class="mdi mdi-chart-areaspline menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('administrator/surat') }}">
              <span class="menu-title">Surat</span>
              <i class="mdi mdi-chart-bar menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-master" aria-expanded="false" aria-controls="ui-master">
              <span class="menu-title">Data Master</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-home-modern menu-icon"></i>
            </a>
            <div class="collapse" id="ui-master">
                <ul class="nav flex-column sub-menu">
                  @if(Auth::user()->role_id == 1)
                    <li class="nav-item"> <a class="nav-link" href="{{ route('founding.index') }}">The Founding </a></li>
                    <li class="nav-item">  <a class="nav-link" href="{{ route('ourteam.index') }}">Our Team</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('boardexpert.index') }}">Board Expert</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('portofolio.index') }}">Portofolio</a></li>
                  @endif
              

                </ul>
            </div>
          </li>

        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content-header')
          @include('components.alert')
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Godestinationvillage © {{ date("Y") }} All rights reserved.
                {{-- Crafted by <a style="color: #1fb4cc!important;" href="https://www.digitalartisans.id/" target="_blank">Digital Artisans</a>. --}}
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Godevi</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirm-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirm-label"></h5>
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        </div>
        <div class="modal-body">
          <span class="message"></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary dismiss" data-dismiss="modal"></button>
          <button type="button" class="btn btn-danger confirm" data-dismiss="modal"></button>
        </div>
      </div>
    </div>
  </div>


  <!-- plugins:js -->
  <script src="{{ asset('dist/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('dist/vendors/js/vendor.bundle.addons.js') }}"></script>
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('dist/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dist/js/misc.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('dist/js/dashboard.js') }}"></script>
  <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  <!-- End custom js for this page-->
  @include('components/_script_modal-delete')
  @yield('js')
</body>

</html>
