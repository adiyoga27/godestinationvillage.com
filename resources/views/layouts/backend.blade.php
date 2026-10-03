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
  <link rel="stylesheet" href="{{ url('assets/customer/dist/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/customer/dist/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ url('assets/customer/dist/css/style.css') }}">
  {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> --}}
  <link rel="stylesheet" href="{{ url('assets/customer/dist/css/rating.css') }}">
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="{{ url('assets/admin/admin-shell.css') }}?v={{ @filemtime(public_path('assets/admin/admin-shell.css')) }}">

  @yield('style')
</head>
<body class="gd-admin">
  @php
    $gdAvatar = empty(Auth::user()->avatar)
        ? url('assets/customer/dist/images/faces/face1.jpg')
        : asset('storage/users/'.Auth::user()->avatar);
    $gdRole = Auth::user()->role_id == 1 ? 'Super Admin' : (Auth::user()->role_id == 2 ? 'Admin Desa Wisata' : 'Administrator');
  @endphp
  <script>
    // Terapkan mode sidebar ciut sebelum render agar tidak berkedip.
    try { if (localStorage.getItem('gd-sb-mini') === '1') document.body.classList.add('gd-sb-mini'); } catch (e) {}
  </script>
  <div class="container-scroller">
    {{-- ============ TOPBAR ============ --}}
    <header class="gd-topbar">
      <button type="button" class="gd-icon-btn gd-drawer-toggle" data-gd-drawer="open" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
        <i class="mdi mdi-menu"></i>
      </button>
      <button type="button" class="gd-icon-btn gd-collapse-toggle" data-gd-collapse aria-label="Ciutkan/lebarkan sidebar">
        <i class="mdi mdi-chevron-double-left"></i><i class="mdi mdi-chevron-double-right"></i>
      </button>
      <a class="gd-topbar__brand-mobile" href="{{ url('administrator/dashboard') }}"><img src="{{ url('assets/godevi-black.png') }}" alt="GODEVI"></a>

      <div class="gd-topbar__spacer"></div>

      <a href="{{ url('/') }}" target="_blank" rel="noopener" class="gd-topbar__site" title="Lihat website">
        <i class="mdi mdi-open-in-new"></i><span>Lihat Website</span>
      </a>
      <button type="button" class="gd-icon-btn d-none d-lg-inline-flex" id="fullscreen-button" aria-label="Layar penuh"><i class="mdi mdi-fullscreen"></i></button>

      <div class="dropdown">
        <a class="gd-profile-btn dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="{{ $gdAvatar }}" alt="">
          <span class="gd-profile-btn__name">{{ Auth::user()->name }}</span>
          <i class="mdi mdi-chevron-down d-none d-sm-inline" style="color:#8a8797"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
          <div class="dropdown-header">{{ Auth::user()->email }}</div>
          <a class="dropdown-item" href="{{ url('administrator/profile') }}"><i class="mdi mdi-account-edit"></i> Edit Profil</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-logout"></i> Keluar</a>
        </div>
      </div>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </header>

    <div class="container-fluid page-body-wrapper">
      {{-- ============ SIDEBAR ============ --}}
      <div class="gd-backdrop" data-gd-drawer="close" aria-hidden="true"></div>
      <aside class="gd-sidebar" id="sidebar" aria-label="Navigasi admin">
        <div class="gd-sidebar__brand">
          <a href="{{ url('administrator/dashboard') }}">
            <img class="gd-sidebar__logo-mini" src="{{ url('assets/godevi-white.png') }}" alt="GODEVI">
            <img class="gd-sidebar__logo" src="{{ url('assets/godevi-white.png') }}" alt="GODEVI">
          </a>
          <button type="button" class="gd-sidebar__close" data-gd-drawer="close" aria-label="Tutup menu"><i class="mdi mdi-close"></i></button>
        </div>

        <div class="gd-sidebar__user">
          <img src="{{ $gdAvatar }}" alt="">
          <div class="gd-sidebar__user-meta">
            <div class="gd-sidebar__user-name">{{ Auth::user()->name }}</div>
            <div class="gd-sidebar__user-role">{{ $gdRole }}</div>
          </div>
        </div>

        <nav class="gd-sidebar__scroll">
          <ul class="gd-nav">
            <li class="gd-nav__section">Utama</li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" href="{{ url('administrator/dashboard') }}" title="Dashboard">
                <span class="gd-nav__icon"><i class="mdi mdi-view-dashboard"></i></span><span class="gd-nav__label">Dashboard</span>
              </a>
            </li>
            {{-- Pengajuan Desa disembunyikan dari menu (halaman tetap bisa dibuka via URL). --}}
            {{-- <li class="gd-nav__item">
              <a class="gd-nav__link" href="{{ route('village-submissions.index') }}" title="Pengajuan Desa">
                <span class="gd-nav__icon"><i class="mdi mdi-map-marker-plus"></i></span><span class="gd-nav__label">Pengajuan Desa</span>
              </a>
            </li> --}}
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-asesmen" role="button" aria-expanded="false" aria-controls="ui-asesmen" title="Asesmen">
                <span class="gd-nav__icon"><i class="mdi mdi-clipboard-check"></i></span><span class="gd-nav__label">Asesmen</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-asesmen">
                <ul class="gd-subnav">
                  <li><a class="gd-subnav__link" href="{{ route('assessments.index') }}">Jalur & Soal</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('assessment-results.index') }}">Hasil Masuk</a></li>
                </ul>
              </div>
            </li>

            <li class="gd-nav__section">Konten</li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" href="{{ url('administrator/news') }}" title="News">
                <span class="gd-nav__icon"><i class="mdi mdi-newspaper"></i></span><span class="gd-nav__label">News</span>
              </a>
            </li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" href="{{ url('administrator/surat') }}" title="Surat">
                <span class="gd-nav__icon"><i class="mdi mdi-file-document"></i></span><span class="gd-nav__label">Surat</span>
              </a>
            </li>

            <li class="gd-nav__section">Produk & Pemesanan</li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-wisata" role="button" aria-expanded="false" aria-controls="ui-wisata" title="Paket Wisata">
                <span class="gd-nav__icon"><i class="mdi mdi-wallet-travel"></i></span><span class="gd-nav__label">Paket Wisata</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-wisata">
                <ul class="gd-subnav">
                  @if(Auth::user()->role_id == 1)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/category') }}">Kategori</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/package') }}">Paket Wisata</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/orders') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/package') }}">Pengajuan</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/orders') }}">Laporan Desa Wisata</a></li>
                  @endif
                </ul>
              </div>
            </li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-events" role="button" aria-expanded="false" aria-controls="ui-events" title="Events">
                <span class="gd-nav__icon"><i class="mdi mdi-calendar"></i></span><span class="gd-nav__label">Events</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-events">
                <ul class="gd-subnav">
                  @if(Auth::user()->role_id == 1)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/category-events') }}">Kategori</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/events') }}">Paket Events</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/order-event') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/events') }}">Pengajuan Event</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/order-event') }}">Laporan Events</a></li>
                  @endif
                </ul>
              </div>
            </li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-homestay" role="button" aria-expanded="false" aria-controls="ui-homestay" title="Home Stay">
                <span class="gd-nav__icon"><i class="mdi mdi-home-modern"></i></span><span class="gd-nav__label">Home Stay</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-homestay">
                <ul class="gd-subnav">
                  @if(Auth::user()->role_id == 1)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/category-homestay') }}">Kategori</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/homestay') }}">Paket Home Stay</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/order-homestay') }}">Pemesanan</a></li>
                  @endif
                  @if(Auth::user()->role_id == 2)
                    <li><a class="gd-subnav__link" href="{{ url('administrator/homestay') }}">Pengajuan Homestay</a></li>
                    <li><a class="gd-subnav__link" href="{{ url('administrator/order-homestay') }}">Laporan Home Stay</a></li>
                  @endif
                </ul>
              </div>
            </li>

            {{-- ====== Khusus super admin ====== --}}
            @if(Auth::user()->role_id == 1)
            <li class="gd-nav__section">Administrasi</li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-pengguna" role="button" aria-expanded="false" aria-controls="ui-pengguna" title="Pengguna">
                <span class="gd-nav__icon"><i class="mdi mdi-account-multiple"></i></span><span class="gd-nav__label">Pengguna</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-pengguna">
                <ul class="gd-subnav">
                  <li><a class="gd-subnav__link" href="{{ url('administrator/user-admin') }}">Admin</a></li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/user-village') }}">Desa Wisata</a></li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/user-member') }}">Member</a></li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/review') }}">Review</a></li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/subscriber') }}">Subscriber</a></li>
                </ul>
              </div>
            </li>
            <li class="gd-nav__item">
              <a class="gd-nav__link" data-toggle="collapse" href="#ui-pengaturan" role="button" aria-expanded="false" aria-controls="ui-pengaturan" title="Pengaturan">
                <span class="gd-nav__icon"><i class="mdi mdi-settings"></i></span><span class="gd-nav__label">Pengaturan</span><i class="mdi mdi-chevron-down gd-nav__caret"></i>
              </a>
              <div class="collapse" id="ui-pengaturan">
                <ul class="gd-subnav">
                  <li>
                    <a class="gd-subnav__link" data-toggle="collapse" href="#ui-kelola-website" role="button" aria-expanded="false" aria-controls="ui-kelola-website">
                      Kelola Website <i class="mdi mdi-chevron-down gd-nav__caret"></i>
                    </a>
                    <div class="collapse" id="ui-kelola-website">
                      <ul class="gd-subnav">
                        <li><a class="gd-subnav__link" href="{{ route('site-settings.index') }}">Pengaturan Website</a></li>
                        <li><a class="gd-subnav__link" href="{{ route('page-heroes.index') }}">Hero Halaman</a></li>
                        <li><a class="gd-subnav__link" href="{{ url('administrator/slider') }}">Slider</a></li>
                        <li><a class="gd-subnav__link" href="{{ route('homepage-sections.index') }}">Homepage Sections</a></li>
                        <li><a class="gd-subnav__link" href="{{ route('homepage-services.index') }}">Our Services</a></li>
                        <li><a class="gd-subnav__link" href="{{ url('administrator/booklet') }}">Booklet</a></li>
                        <li><a class="gd-subnav__link" href="{{ url('administrator/instagram') }}">Instagram</a></li>
                      </ul>
                    </div>
                  </li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/bank-account') }}">Akun Bank</a></li>
                  <li><a class="gd-subnav__link" href="{{ url('administrator/discount-member') }}">Diskon Member</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('founding.index') }}">The Founding</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('ourteam.index') }}">Our Team</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('boardexpert.index') }}">Board Expert</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('portofolio.index') }}">Portofolio</a></li>
                  <li><a class="gd-subnav__link" href="{{ route('system-log.index') }}">Log Sistem</a></li>
                </ul>
              </div>
            </li>
            @endif
          </ul>
        </nav>

        <div class="gd-sidebar__foot">
          <a class="gd-nav__link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Keluar">
            <span class="gd-nav__icon"><i class="mdi mdi-logout"></i></span><span class="gd-nav__label">Keluar</span>
          </a>
        </div>
      </aside>
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
  <script src="{{ url('assets/customer/dist/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ url('assets/customer/dist/vendors/js/vendor.bundle.addons.js') }}"></script>
  <script src="{{ asset('js/select2.min.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ url('assets/customer/dist/js/off-canvas.js') }}"></script>
  <script src="{{ url('assets/customer/dist/js/misc.js') }}?v={{ @filemtime(public_path('assets/customer/dist/js/misc.js')) }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ url('assets/customer/dist/js/dashboard.js') }}"></script>
  <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  <!-- End custom js for this page-->
  <script>
  (function ($) {
    var body = document.body;
    var mq = window.matchMedia('(min-width: 992px)');
    var sidebar = $('#sidebar');

    // Drawer (tablet/HP).
    function setDrawer(open) {
      body.classList.toggle('gd-sb-open', open);
      $('[data-gd-drawer="open"]').attr('aria-expanded', open ? 'true' : 'false');
    }
    $(document).on('click', '[data-gd-drawer="open"]', function () { setDrawer(true); });
    $(document).on('click', '[data-gd-drawer="close"]', function () { setDrawer(false); });
    $(document).on('keydown', function (e) { if (e.key === 'Escape') setDrawer(false); });
    mq.addListener(function (e) { if (e.matches) setDrawer(false); });

    // Mode ikon (desktop), diingat per browser.
    $('[data-gd-collapse]').on('click', function () {
      var mini = body.classList.toggle('gd-sb-mini');
      try { localStorage.setItem('gd-sb-mini', mini ? '1' : '0'); } catch (e) {}
    });

    // Menu aktif: cocokkan URL terpanjang, buka submenu induknya.
    var path = location.pathname.replace(/\/+$/, '');
    var best = null, bestLen = -1;
    sidebar.find('a.gd-nav__link[href], a.gd-subnav__link[href]').each(function () {
      if (this.getAttribute('data-toggle') || this.getAttribute('href').charAt(0) === '#') return;
      var p = this.pathname.replace(/\/+$/, '');
      if (p && (path === p || path.indexOf(p + '/') === 0) && p.length > bestLen) { best = this; bestLen = p.length; }
    });
    if (best) {
      $(best).addClass('is-active').closest('.gd-nav__item').addClass('is-active');
      $(best).parents('.collapse').addClass('show').each(function () {
        sidebar.find('[href="#' + this.id + '"]').attr('aria-expanded', 'true');
      });
      if (best.scrollIntoView && best.classList.contains('gd-subnav__link')) best.scrollIntoView({ block: 'nearest' });
    }

    // Satu grup terbuka dalam satu waktu (kecuali induk dari submenu bertingkat).
    sidebar.on('show.bs.collapse', '.collapse', function (e) {
      if (e.target !== this) return;
      sidebar.find('.collapse.show').not($(this).parents('.collapse')).not(this).collapse('hide');
    });
  })(jQuery);

  // Komponen form admin: tab bahasa EN/ID, preview gambar, format Rupiah.
  (function () {
    document.querySelectorAll('[data-gd-lang-group]').forEach(function (group) {
      var buttons = group.querySelectorAll('[data-gd-lang]');
      var show = function (lang) {
        buttons.forEach(function (b) {
          var on = b.getAttribute('data-gd-lang') === lang;
          b.classList.toggle('is-active', on);
          b.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        group.querySelectorAll('[data-gd-pane]').forEach(function (p) { p.hidden = p.getAttribute('data-gd-pane') !== lang; });
      };
      buttons.forEach(function (b) {
        if (group.querySelector('[data-gd-pane="' + b.getAttribute('data-gd-lang') + '"] .gd-error')) b.classList.add('has-error');
        b.addEventListener('click', function () { show(b.getAttribute('data-gd-lang')); });
      });
      // Bila hanya versi ID yang error, langsung tampilkan tab ID.
      if (!group.querySelector('[data-gd-pane="en"] .gd-error') && group.querySelector('[data-gd-pane="id"] .gd-error')) show('id');
      group._gdShow = show;
    });
    // Kolom wajib di tab bahasa yang tersembunyi: buka tabnya agar browser bisa menunjukkan pesan.
    document.addEventListener('invalid', function (e) {
      var pane = e.target.closest && e.target.closest('[data-gd-pane][hidden]');
      var group = pane && pane.closest('[data-gd-lang-group]');
      if (group && group._gdShow) group._gdShow(pane.getAttribute('data-gd-pane'));
    }, true);

    document.querySelectorAll('[data-gd-upload]').forEach(function (box) {
      var input = box.querySelector('input[type=file]');
      var img = box.querySelector('[data-gd-upload-preview]');
      var empty = box.querySelector('.gd-upload__empty');
      var name = box.parentNode.querySelector('[data-gd-upload-name]');
      input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) return;
        img.src = URL.createObjectURL(file);
        img.hidden = false;
        if (empty) empty.hidden = true;
        box.classList.add('has-image');
        if (name) name.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
      });
    });

    var rupiah = new Intl.NumberFormat('id-ID');
    document.querySelectorAll('[data-gd-money]').forEach(function (input) {
      var out = document.querySelector('[data-gd-money-preview="' + input.getAttribute('data-gd-money') + '"]');
      if (!out) return;
      var render = function () { out.textContent = input.value !== '' ? '= Rp ' + rupiah.format(Number(input.value) || 0) : ''; };
      input.addEventListener('input', render);
      render();
    });
  })();
  </script>
  @include('components/_script_modal-delete')
  @yield('js')
</body>

</html>
