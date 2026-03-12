@extends('customer/layout',array(
'title' => $village->village_detail->village_name,
'content'=> strip_tags($village->village_detail->desc),
'image'=> url('storage/users/'.$village->avatar))
)

@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>{{ $village->village_detail->village_name }}</h1>
                <ul>
                    <li class="item"><a href="index.html">Home</a></li>
                    <li class="item"><a href="destination-details.html"><i class='bx bx-chevrons-right'></i>Explore
                            Village</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('storage/users/' . $village->avatar) }}" alt="Demo Image">
        </div>
    </div>
    <!-- end page title area -->

    <!-- start destination details section -->
    <section class="destinations-details-section pt-100 pb-70">
        <div class="container">
            <div class="section-title">
                <h2>{{ $village->village_detail->village_name }}</h2>
                <h6><i class='bx bx-current-location'> </i> {{ $village->village_detail->village_address }}</h6>

                <hr>

            </div>

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="destination-details-desc mb-30">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="image mb-30">
                                    <img src="{{ url('storage/users/' . $village->avatar) }}" alt="Demo Image" />
                                </div>
                            </div>
                            {{-- <div class="col-md-6 col-sm-12">
                                <div class="image mb-30">
                                    <img src="assets/img/destination14.jpg" alt="Demo Image" />
                                </div>
                            </div> --}}
                        </div>
                        <div class="content mb-20">

                            {!! $village->village_detail->desc !!}


                        </div>


                        <div id="map"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    <aside class="widget-area">

                        <div class="widget widget-video mb-30">
                            <div class="video-image">
                                <img src="https://img.youtube.com/vi/kIFVo2qgI-g/sddefault.jpg" width="900px" width="600px"
                                    alt="video" />
                            </div>
                            <a href="https://www.youtube.com/watch?v=kIFVo2qgI-g" class="youtube-popup video-btn">
                                <i class='bx bx-right-arrow'></i>
                            </a>
                        </div>
                        <div class="widget widget-article mb-30">
                            <h3 class="sub-title">Recent Tour Packages</h3>
                            <hr>
                            @foreach ($recent as $rec)
                                <article class="article-item">
                                    <div class="image">
                                        <img src="{{ url('storage/packages/' . $rec->default_img) }}" alt="Demo Image" wi/>
                                    </div>
                                    <div class="content">
                                        {{-- <span class="location"><i class='bx bx-map'></i>95 Fleet, London</span> --}}
                                        <h4>
                                            <a href="{{ url('tour-packages/' . $rec->slug) }}">{{ $rec->name }}.</a>
                                        </h4>
                                        <ul class="list">
                                            <li><i class='bx bx-time' style="color:red"></i>{{ $rec->cat_name }}</li>

                                        </ul>
                                    </div>
                                </article>
                            @endforeach


                        </div>


                    </aside>
                </div>
                <div class="col-lg-12 col-md-12">
                    <hr>

                    <!-- start destination section -->
                    <section id="destination" class="destination-section ptb-50 ">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 m-auto">
                                    <div class="filter-group">
                                        <!-- Control List -->
                                        <h2>Tour Package</h2>
                                        <p>Travel has helped us to understand the meaning of life and it has helped us
                                            become
                                            better
                                            people. Each time we travel, we see the world with new eyes.</p>
                                        </li>
                                    </div>
                                </div>
                            </div>
                            <div class="row filtr-container">
                                @foreach ($packages as $pack)
                                    <div class="col-lg-4 col-md-6 filtr-item" data-category="1" data-sort="value">
                                        <div class="item-single mb-30">
                                            <div class="image">
                                                <img src="{{ url('storage/packages/' . $pack->default_img) }}"
                                                    alt="{{ $pack->name }}">
                                            </div>
                                            <div class="content">

                                                <h3>
                                                    <a
                                                        href="{{ url('tour-packages/' . $pack->slug) }}">{{ $pack->name }}</a>
                                                </h3>
                                                <span class="location"><i
                                                        class='bx bx-time'></i>{{ $pack->category->name ?? '' }}&nbsp
                                                    <i class='bx bx-group'></i>1 Person
                                                </span>
                                                <br>
                        
                                                <p>
                                                    {{ strip_tags(substr($pack->desc, 0, 100)) }}.
                                                </p>
                                                
                                                <hr>
                                                <ul class="list">
                                                    <li>
                                                        Price :
                                                    </li>
                                           
                                                    <li> 
                                                        @if($pack->disc > 0)
                                                       
                                                        <font style="font-size: 13pt; color: red">&nbsp Rp {{ number_format($pack->disc,0,',','.') }}</font>
/

                                                        <a class="coret">
                                                            <font style="font-size: 10pt; color: rgb(0, 0, 0)">
                                                                {{ number_format($pack->price,0,',','.') }} 
                                                            </font>
                                                        </a>
                                                        @else 
                                                         <font style="font-size: 13pt;color: red">&nbsp Rp {{ number_format($pack->price,0,',','.') }}</font>
                                                        @endif
                                                    </li>
                                                </ul>
                                         
                                            </div>
                                            <div class="spacer"></div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="pagination text-center">
                                        @if ($packages->lastPage() != 1)
                                            @for ($i = 1; $i <= $packages->lastPage(); $i++)
                                                <a href="{{ $packages->url($i) }}" class="page-numbers @if ($packages->currentPage() == $i) current @endif">

                                                    {{ $i }}
                                                </a>
                                            @endfor

                                            <a href="{{ $packages->nextPageUrl() }}" class="page-numbers">Next</a>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- end destination section -->
            </div>
        </div>
    </section>
    <!-- end blog details section -->
@endsection()

@section('js')
    <script type="text/javascript">
        function initMap() {
            var latStr = "{{ $village->village_detail->lat }}";
            var lngStr = "{{ $village->village_detail->lng }}";
            var latCoor = parseFloat(latStr.replace(',', '.'));
            var lngCoor = parseFloat(lngStr.replace(',', '.'));
            var myLatLng = {
                lat: latCoor,
                lng: lngCoor
            }
            // var myLatLng = {lat: -8.614762, lng: 115.193850};
            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 10
            });

            var marker = new google.maps.Marker({
                map: map,
                title: 'Hello World!',
                position: new google.maps.LatLng(latCoor, lngCoor)
            });

        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgAQOKoaiYIXHi0UxM76u3B50dVJLBZKk&callback=initMap" async
        defer></script>
@endsection
