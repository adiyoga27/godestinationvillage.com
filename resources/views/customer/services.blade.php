@extends('customer/layout',array(
'title' => 'Our Services - GODEVI',
)
)
@section('content')
    <style>
        .resize {
            width: 130px !important;
            height: 130px !important;
            object-fit: contain;
        }
    </style>
    <!-- start page title area-->

    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>@lang('Our Services')</h1>
                <ul>
                    <li class="item"><a href="/">@lang('Home')</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>@lang('Our Services')</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('assets/customer/img/page-title-area/services.jpg') }}" alt="Services">
        </div>
    </div>
    <!-- end page title area -->

    <section class="features-section services pt-70 pb-20">
        <div class="container">
            <p style="text-align: center" class="consultancy">@lang('Subtitle Our Services')</p>
            <br>
            <br>

            <div class="row justify-content-center">
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="200"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#perencanaan" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{url('assets/customer/img/etc/perencanaan.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Tourism Planning, Strategy and Revitalization')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="300"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a href="{{url('v-portofolio')}}" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/portofolio.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Portofolio')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="400"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#projectmanagement" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/kajian.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Project Management')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="500"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#pengembangansdm" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/sdm.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Human Resources Development')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="600"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#branding" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/branding.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Destination Branding and Digital Marketing')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="700"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#tren" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/tren.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Consumer Trend and Tourism Insight')</h4>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="800"
                    class="col-lg-4 col-md-6 mb-4">
                    <div class="modern-card text-center h-100">
                        <div class="content">
                            <a data-toggle="modal" data-target="#internship" style="color: inherit; text-decoration: none;">
                                <div class="services-icon mb-4">
                                    <img src="{{ url('assets/customer/img/etc/internship.png')}}" class="resize hover-lift" alt="">
                                </div>
                                <h4 class="font-weight-bold" style="font-size: 18px;">@lang('Internship Program')</h4>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection()
