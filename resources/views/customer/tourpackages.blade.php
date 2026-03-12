@extends('customer/layout',array(
'title' => 'Best Offers - GODEVI',
)
)
@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div data-aos="fade-up" data-aos-offset="100" data-aos-duration="500" class="container aos-init aos-animate">
            <div class="page-title-content">
                <h1>@lang('Best Offers')</h1>
                <ul>
                    <li class="item"><a href="/">@lang('Home')</a></li>
                    <li class="item"><a href="#"><i class="bx bx-chevrons-right"></i>@lang('Best Offers')</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-image"><img src="{{ url('customer/img/page-title-area/bestoffer.jpg') }}" alt="Image"></div>
    </div>
    <!-- end page title area -->
    <!-- start top destination section -->
    <section id="top-destination" class="top-destination-section pt-100 pb-70 bg-light">
        <div class="container">
            {{-- <div class="section-title">
                <h2>Top Destinations</h2>
                <p>Travel has helped us to understand the meaning of life and it has helped us become better people. Each
                    time we travel, we see the world with new eyes.</p>
            </div> --}}
            <div class="row">
                @foreach ($packages as $pack)

                    <div class="col-lg-4 col-md-6">
                        <div class="item-single mb-50">
                            <div class="image">
                                <img src="{{ url('storage/packages/' . $pack->default_img) }}" alt="{{ $pack->name }}">
                            </div>
                            <div class="content">
                                <span class="location"><i class='bx bx-map'></i>{{ $pack->vil_name }}</span>
                                <h3>
                                    <a href="{{ url('tour-packages/' . $pack->slug) }}">{{ $pack->name }}</a>
                                </h3>

                                <p>
                                    {{ strip_tags(substr($pack->desc, 0, 100)) }}.
                                </p>
                                <hr>
                                <ul class="list">
                                    <li><i class='bx bx-time'></i>{{ $pack->cat_name }}</li>
                                    <li><i class='bx bx-group'></i>10+</li>
                                    <li>Rp {{ number_format($pack->price,0,',','.') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                @endforeach


                <div class="item col-md-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="pagination text-center">


                                @for ($i = 1; $i <= $packages->lastPage(); $i++)
                                    <a href="{{ $packages->url($i) }}" class="page-numbers @if ($packages->currentPage() == $i) current @endif">

                                        {{ $i }}
                                    </a>
                                @endfor
                                @if ($packages->lastPage() > 0 && $packages->currentPage() < $packages->lastPage())
                                    <a href="{{ $packages->nextPageUrl() }}" class="page-numbers">Next</a>
                                @endif

                                {{-- <span class="page-numbers current" aria-current="page">1</span>
                                <a href="#" class="page-numbers">2</a>
                                <a href="#" class="page-numbers">3</a>
                                <a href="#" class="page-numbers">Next</a> --}}
                            </div>
                        </div>
                    </div>
                    {{-- {{ $packages->links() }} --}}
                </div>
            </div>
        </div>
    </section>
    <!-- end top destination section -->
    <!-- end our tour section -->


@endsection()
