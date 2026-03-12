@extends('customer/layout',array(
'title' => 'Explorer Village - GODEVI',
)
)
@section('content')

    <style>
        .section-title p {
            max-width: 100% !important;
        }

    </style>
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div data-aos="fade-up" data-aos-offset="100" data-aos-duration="500" class="container aos-init aos-animate">
            <div class="page-title-content">
                <h1>@lang('Explore Village')</h1>
                <ul>
                    <li class="item"><a href="/">@lang('Home')</a></li>
                    <li class="item"><a href="#"><i class="bx bx-chevrons-right"></i>@lang('Explore Village')</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bg-image"><img src="{{ url('customer/img/page-title-area/explorer.jpg') }}" alt="Image"></div>
    </div>
    <!-- end page title area -->
    <!-- start our tours section -->
    <section id="tours" class="tours-section ptb-100 ">
        <div class="container">
            <div class="section-title">
                <h2>@lang('Village Tour')</h2>
                <p>@lang('Subtitle Explore Village')</p>
                <p>
            </div>
            <div class="row">
                @foreach ($village as $val)
                    <div class="col-lg-4 col-md-6" onclick="location.href =''">
                        <div class="item-single mb-30">
                            <div class="image">
                                <img src="{{ url('storage/users/' . $val->avatar) }}"
                                    style="height:500px; object-fit: cover; !important
                                                                                                                                                                                                                                                                                                                                                                                                            object-fit: cover;"
                                    alt="Demo Image" />
                            </div>
                            <div class="content">

                                <div class="title">
                                    <h3>
                                        <a
                                            href="{{ url('village/' . ($val->village_detail->slug ?? '')) }}">{{ $val->village_detail->village_name ?? ''}}</a>
                                    </h3>
                                    <p>{{ $val->village_detail->village_address ?? '' }} </p>
                                </div>

                                {{-- <div class="review">
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <i class='bx bxs-star'></i>
                                    <span>39 Review</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                @endforeach


                {{-- <div class="col-lg-12 col-md-12">
                    <div class="pagination text-center">
                        <span class="page-numbers current" aria-current="page">1</span>
                        <a href="#" class="page-numbers">2</a>
                        <a href="#" class="page-numbers">3</a>
                        <a href="#" class="page-numbers">Next</a>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="item col-md-12">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="pagination text-center">
    
    
                        @for ($i = 1; $i <= $village->lastPage(); $i++)
                            <a href="{{ $village->url($i) }}" class="page-numbers @if ($village->currentPage() == $i) current @endif">
    
                                {{ $i }}
                            </a>
                        @endfor
                        @if ($village->lastPage() > 0 && $village->currentPage() < $village->lastPage())
                            <a href="{{ $village->nextPageUrl() }}" class="page-numbers">Next</a>
    
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
    </section>
    

    <!-- end our tour section -->


@endsection()
