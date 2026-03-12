@extends('customer/layout',array(
'title' => 'Our Services - GODEVI',
)
)
@section('content')
    <style>
        .resize {
            width: 100px !important;
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
            <img src="{{ url('customer/img/page-title-area/services.jpg') }}" alt="Services">
        </div>
    </div>
    <!-- end page title area -->

    <section class="features-section services pt-70 pb-20">
        <div class="container">
            <p style="text-align: center" class="consultancy">@lang('Subtitle Our Services')</p>
            <br>
            <br>

            <div class="row">
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="400"
                    class="col-lg-4 col-md-6 aos-init aos-animate">
                    <div class="item-single mb-30">
                        <div class="services-icon mb-3">
                            <a data-toggle="modal" data-target="#perencanaan">
                                <img src="{{url('customer/img/etc/perencanaan.svg')}}" class="resize" alt="">
                        </div>
                        <p>@lang('Tourism Planning, Strategy and Revitalization')</p></a>
                    </div>
                </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="800"
                class="col-lg-4 col-md-6 aos-init aos-animate">
                <div class="item-single mb-30">
                    <a href="{{url('v-portofolio')}}">
                        <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/portofolio.svg')}}" class="resize" alt="">
                        </div>
                        <p class="px-3">@lang('Portofolio')</p>
                    </a>
                </div>
            </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="800"
                    class="col-lg-4 col-md-6 aos-init aos-animate">
                    <div class="item-single mb-30">
                        <a data-toggle="modal" data-target="#projectmanagement">
                            <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/kajian.svg')}}" class="resize" alt="">
                            </div>
                            <p class="px-3">@lang('Project Management')</p>
                        </a>
                    </div>
                </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="1200"
                    class="col-lg-4 col-md-6 m-auto aos-init aos-animate">
                    <div class="item-single mb-30">
                        <a data-toggle="modal" data-target="#pengembangansdm">
                            <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/sdm.svg')}}" class="resize" alt=""></div>
                            <p>@lang('Human Resources Development')</p>
                        </a>
                    </div>
                </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="400"
                    class="col-lg-4 col-md-6 m-auto aos-init aos-animate">
                    <div class="item-single mb-30">
                        <a data-toggle="modal" data-target="#branding">
                            <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/branding.svg')}}" class="resize" alt="">
                            </div>
                            <p>@lang('Destination Branding and Digital Marketing')</p>
                        </a>
                    </div>
                </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="800"
                    class="col-lg-4 col-md-6 m-auto aos-init aos-animate">
                    <div class="item-single mb-30">
                        <a data-toggle="modal" data-target="#tren">
                            <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/tren.svg')}}" class="resize" alt="">
                            </div>
                            <p>@lang('Consumer Trend and Tourism Insight')</p>
                        </a>
                    </div>
                </div>
                <div data-aos="fade-in" data-aos-offset="100" data-aos-duration="500" data-aos-delay="1200"
                    class="col-lg-4 col-md-6 m-auto aos-init aos-animate">
                    <div class="item-single mb-30">
                        <a data-toggle="modal" data-target="#internship">
                            <div class="services-icon mb-3"><img src="{{ url('customer/img/etc/internship.png')}}" class="resize"
                                    alt=""></div>
                            <p>@lang('Internship Program')</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>




@endsection()
