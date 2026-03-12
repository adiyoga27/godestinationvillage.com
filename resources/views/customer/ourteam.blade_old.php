@extends('customer/layout')
@section('content')

    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Our Team</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="team.html"><i class='bx bx-chevrons-right'></i>Team</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="customer/img/page-title-area/team.jpg" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->

    <!-- start team section -->
    <section id="team" class="team-section ptb-100">
        <div class="container">
            <div class="section-title">
                <h2>Our Team & Guide</h2>
                <p>Travel has helped us to understand the meaning of life and it has helped us become better people. Each
                    time we travel, we see the world with new eyes.</p>
            </div>
            <div class="row">
                <div class=" col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/gian.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">I Gede Gian Saputra,
                                        <br> S.Par.,M.Par.</a>
                                </h3>
                                <span>Founder</span>
                                <p>Travel has helped us to understand the meaning of life and it has helped us become better people. Each
                                    time we travel, we see the world with new eyes. Travel has helped us to understand the meaning of life and it has helped us become better people. Each
                                    time we travel, we see the world with new eyes.</p>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/gian.movingstraight" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-twitter'></i></a> --}}
                                <a href="https://id.linkedin.com/in/i-gede-gian-saputra-5a6830100" target="_blank"><i
                                        class='bx bxl-linkedin'></i></a>
                                <a href="https://www.instagram.com/igedegiansaputra/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/gatot.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">I Putu Gatot Adiprana,<br>
                                        S.Par.,M.Par.</a>
                                </h3>
                                <span>Co-Founder</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/gatot.adiprana" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                <a href="https://twitter.com/gatotadiprana" target="_blank"><i
                                        class='bx bxl-twitter'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-linkedin'></i></a> --}}
                                <a href="https://www.instagram.com/gatotadiprana/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/pegdua.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">Ni Kadek Pande Aristiani,<br>
                                        S.Par.</a>
                                </h3>
                                <span>Executive Secretary</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/pande.aristiani" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                <a href="https://twitter.com/aristiani_pande" target="_blank"><i
                                        class='bx bxl-twitter'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-linkedin'></i></a> --}}
                                <a href="https://www.instagram.com/pandearistiani/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/pegsatu.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">Made Sera Septiani,<br>
                                        S.Par.</a>
                                </h3>
                                <span>Chief Operation Officer</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/sera.septiani.9" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-twitter'></i></a> --}}
                                {{-- <a href="#" target="_blank"><i class='bx bxl-linkedin'></i></a> --}}
                                <a href="https://www.instagram.com/sera_septiani/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/Sanjiwani.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">Ni Made Gandhi Sanjiwani,<br>
                                        S.Par,M.Sc.</a>
                                </h3>
                                <span>Chief Business Officer</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/gandhy.sanjiwani" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-twitter'></i></a> --}}
                                <a href="https://id.linkedin.com/in/gandhi-sanjiwani-7a7567198" target="_blank"><i
                                        class='bx bxl-linkedin'></i></a>
                                <a href="https://www.instagram.com/gandhisanjiwani" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-lg-4 col-sm-4 col-md-4">-->
                <!--    <div class="item-single mb-30">-->
                <!--        <div class="image">-->
                <!--            <img src="{{ url('frontdata/images/Sukariyanto.jpg') }}" alt="Demo Image">-->
                <!--        </div>-->
                <!--        <div class="content">-->
                <!--            <div class="title">-->
                <!--                <h3>-->
                <!--                    <a href="team.html">I Gede Made Sukariyanto,<br>-->
                <!--                        S.Par.,M.Par.</a>-->
                <!--                </h3>-->
                <!--                <span>Chief Marketing Officer</span>-->
                <!--            </div>-->
                <!--            <div class="social-link">-->
                <!--                <a href="https://www.facebook.com/Agoezt.Sukariyanto" target="_blank"><i-->
                <!--                        class='bx bxl-facebook'></i></a>-->
                <!--                <a href="https://twitter.com/gus_ariy" target="_blank"><i class='bx bxl-twitter'></i></a>-->
                <!--                <a href="https://id.linkedin.com/in/sukariyanto" target="_blank"><i-->
                <!--                        class='bx bxl-linkedin'></i></a>-->
                <!--                <a href="https://www.instagram.com/gus.ariy/" target="_blank"><i-->
                <!--                        class='bx bxl-instagram'></i></a>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/adiyoga.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">Ida Bagus Ketut Adiyoga<br>
                                    </a>
                                </h3>
                                <span>Chief Technology Officer</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/adiyoga27" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                <a href="https://twitter.com/adiyoga27" target="_blank"><i class='bx bxl-twitter'></i></a>
                                <a href="https://www.linkedin.com/in/ida-bagus-ketut-adiyoga-55a939133/" target="_blank"><i
                                        class='bx bxl-linkedin'></i></a>
                                <a href="https://www.instagram.com/adiyoga27/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4">
                    <div class="item-single mb-30">
                        <div class="image">
                            <img src="{{ url('frontdata/images/sukmaarida.jpg') }}" alt="Demo Image">
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3>
                                    <a href="team.html">Dr. I Nyoman Sukma Arida, <br>
                                        M.Si.</a>
                                </h3>
                                <span>Board Advisor</span>
                            </div>
                            <div class="social-link">
                                <a href="https://www.facebook.com/sukma.arida" target="_blank"><i
                                        class='bx bxl-facebook'></i></a>
                                {{-- <a href="#" target="_blank"><i class='bx bxl-twitter'></i></a>
                                <a href="#" target="_blank"><i class='bx bxl-linkedin'></i></a> --}}
                                <a href="https://www.instagram.com/sukma_arida/" target="_blank"><i
                                        class='bx bxl-instagram'></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- end team section -->
@endsection()
