@extends('customer/layout')
@section('content')

    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Rule Delete Account</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="terms-of-service.html"><i class='bx bx-chevrons-right'></i>Rule Delete Account</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="customer/img/page-title-area/terms.jpg" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->

    <!-- start terms area -->
    <section class="terms-of-services ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        {{-- <div class="image">
                            <img src="assets/img/terms.jpg" alt="image">
                        </div> --}}
                        <div class="container">
    <h1>Penghapusan Akun</h1>


<p>
    Jika Anda ingin melakukan penghapusan akun (delete account), silakan mengirimkan email ke alamat berikut:
</p>

<p class="email">
    admin@godestinationvillage.com
</p>

<p>
    Mohon sertakan <strong>nama akun</strong> pada email tersebut.  
    Tim admin kami akan memproses permintaan penghapusan akun Anda dalam waktu maksimal <strong>24 jam</strong>.
</p>

<p>
    Terima kasih atas kepercayaan Anda menggunakan layanan GoDestinationVillage.
</p>

</div>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- end terms area -->

@endsection()
