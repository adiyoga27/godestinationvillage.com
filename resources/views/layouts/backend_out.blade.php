<!DOCTYPE html>
<html lang="en">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- Required meta tags -->
  
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Godestinationvillage - Administrator') }}</title>
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
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      {{-- <nav class="sidebar sidebar-offcanvas" id="sidebar">
        
      </nav> --}}
      <!-- partial -->
      {{-- <div class="main-panel"> --}}
        <div class="content-wrapper">
          @yield('content-header')
          @include('components.alert')
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        {{-- <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Godestinationvillage © {{ date("Y") }} All rights reserved.
                Crafted by <a style="color: #1fb4cc!important;" href="https://www.digitalartisans.id/" target="_blank">Digital Artisans</a>.
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Godestinationvillage</span>
          </div>
        </footer> --}}
        <!-- partial -->
      {{-- </div> --}}
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
