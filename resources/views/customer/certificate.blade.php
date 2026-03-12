@extends('customer/layout')
@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>{{$certificate->category}}</h1>
                <ul>
                    <li class="item"><a href="index.html">Surat</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Details</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/surat-sertif-header.png') }}" alt=" Demo Image">
        </div>
    </div>
    <!-- start blog details section -->
    <section id="about" class="about-section about-style-three ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-10 m-auto">
                    <div class="about-content">
                    <div class="col-md-8 m-auto">

                        <table  style="text-align: left;" align="left">
                        <tr height="40px">

                                <td width='35%'><b>No Surat</b></td>
                                <td width='10%'>:</td>
                                <td width='60%'>{{$certificate->reference_number}}</td>

                            </tr>
                            <tr height="40px">

                                <td width='30%'><b>Tanggal</b></td>
                                <td width='5%'>:</td>
                                <td width='60%'>{{date('d M y', strtotime($certificate->date_at))}}</td>

                            </tr>
                            <tr height="40px">

                                <td width='30%'><b>Perihal</b></td>
                                <td width='10%'>:</td>
                                <td width='60%'>{{$certificate->regarding}}</td>

                            </tr>
                            <tr height="40px">

                                <td width='30%'><b>Ditujukan kepada</b></td>
                                <td width='10%'>:</td>
                                <td width='60%'>{{$certificate->addressed_to}}</td>

                            </tr>
                            <tr height="40px">

                                <td width='30%'><b>Penandatangan</b></td>
                                <td width='10%'>:</td>
                                <td width='60%'>{{$certificate->signer}}</td>

                            </tr>
                            <tr height="40px">
                                <td width='30%'><b>Jabatan</b></td>
                                <td width='10%'>:</td>
                                <td width='60%'>{{$certificate->departemen}}</td>

                            </tr>
                            <tr height="150px">
                                <td colspan="3">
                                <center><a href="{{url('storage/certification/'.$certificate->file)}}" class="btn-primary">Download</a>
                                </center>

                                </td>

                            </tr>
                        </table>
                    </div>
                              
                        <br>
                        <div class="about-btn">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape-1"><img src="{{url('customer/img/shape1.png')}}" alt="Background Shape"></div>
        <div class="shape shape-2"><img src="{{url('customer/img/shape2.png')}}" alt="Background Shape"></div>
        <div class="shape shape-3"><img src="{{url('customer/img/shape3.png')}}" alt="Background Shape"></div>
        <div class="shape shape-4"><img src="{{url('customer/img/shape4.png')}}" alt="Background Shape"></div>
    </section>
    <script>
        /**
         *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
         *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
        /*
        var disqus_config = function () {
        this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
        this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };
        */
        (function() { // DON'T EDIT BELOW THIS LINE
            var d = document,
                s = d.createElement('script');
            s.src = 'https://godestinationvillage.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <!-- end blog details section -->
@endsection
