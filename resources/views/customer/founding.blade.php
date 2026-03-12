@extends('customer/layout')
@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Founding</h1>
                <ul>
                    <li class="item"><a href="/">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Founding</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="customer/img/page-title-area/founding-timenile.jpg" alt="Demo Image" style="background-repeat: no-repeat;
            background-size: auto;">
        </div>
    </div>
    <!-- end page title area -->

    <!-- start team section -->
    <section id="team" class="team-section ptb-100">
        <div class="container">
            {{-- <div class="section-title">
                <h2>Founding</h2>
                <p>Travel has helped us to understand the meaning of life and it has helped us become better people. Each
                    time we travel, we see the world with new eyes.</p>
            </div> --}}
            <div class="row">
                <div class="team3">

                    <div class="row">
                        @foreach ($foundings as $f)
                            <!-- column  -->
                            <div class="col-lg-4 mb-4">
                                <!-- Row -->
                                <div class="item-single ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="{{ url('storage') }}/founding/{{ $f->avatar }}" alt="wrapkit"
                                                class="img-fluid" />
                                        </div>
                                        <div class="col-md-12">
                                            <center>
                                                <h5 class="mt-4 font-weight-medium mb-0">{{ $f->name }}</h5>
                                                <h6 class="subtitle">( {{ $f->title }} )</h6>
                                            </center>
                                            <div class="ml-4 mr-4 description">
                                                {!! $f->description !!}
                                            </div>
                                            <br>
                                            <center>
                                                <div class="social-link">
                                                    @if (!empty($f->phone))
                                                        <a href="tel:{{ $f->phone }}" target="_blank"><i
                                                                class='bx bxs-phone-call'></i></a>
                                                    @endif
                                                    @if (!empty($f->whatsapp))
                                                        <a href="https://wa.me/{{ $f->whatsapp }}" target="_blank"><i
                                                                class='bx bxl-whatsapp'></i></a>
                                                    @endif
                                                    @if (!empty($f->facebook))
                                                        <a href="{{ $f->facebook }}" target="_blank"><i
                                                                class='bx bxl-facebook'></i></a>
                                                    @endif
                                                    @if (!empty($f->instagram))
                                                        <a href="{{ $f->instagram }}" target="_blank"><i
                                                                class='bx bxl-instagram'></i></a>
                                                    @endif
                                                    @if (!empty($f->twitter))
                                                        <a href="{{ $f->twitter }}" target="_blank"><i
                                                                class='bx bxl-twitter'></i></a>
                                                    @endif
                                                    @if (!empty($f->linkedin))
                                                        <a href="{{ $f->linkedin }}" target="_blank"><i
                                                                class='bx bxl-linkedin'></i></a>
                                                    @endif


                                                </div>
                                            </center>
                                            <br>
                                        </div>
                                    </div>

                                </div>
                                <!-- Row -->
                            </div>
                        @endforeach
                        <!-- column  -->
                        <!-- column  -->

                        <!-- column  -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- end team section -->
@endsection()
<script>
  document.addEventListener("DOMContentLoaded", function() {
        var paragraphs = document.querySelectorAll(".description");

        paragraphs.forEach(function(paragraph) {
            var text = paragraph.textContent;
            var truncatedText = text.slice(0, 200);
            var lastSpaceIndex = truncatedText.lastIndexOf(" ");
            var lastWordTruncated = truncatedText.slice(lastSpaceIndex);
            var isFullText = false;

            if (text.length > 200) {
                var truncatedContent = truncatedText.slice(0, lastSpaceIndex) + "...";
                paragraph.textContent = truncatedContent;

                var readMoreLink = document.createElement("a");
                readMoreLink.textContent = "Read More";
                readMoreLink.setAttribute("href", "#");
                readMoreLink.style.color = "red"; // Set color to red

                readMoreLink.addEventListener("click", function(e) {
                    e.preventDefault();
                    if (isFullText) {
                        paragraph.textContent = truncatedContent + " ";
                        readMoreLink.textContent = "Read More";
                        isFullText = false;
                    } else {
                        paragraph.textContent = text;
                        readMoreLink.textContent = "Hide More";
                        isFullText = true;
                    }
                });

                paragraph.appendChild(readMoreLink);
            }
        });
    });
</script>
