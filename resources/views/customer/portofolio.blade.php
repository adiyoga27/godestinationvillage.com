@extends('customer/layout')
@section('content')
    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>Portofolio</h1>
                <ul>
                    <li class="item"><a href="/">Home</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Portofolio</a></li>
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

                        @foreach ($portofolios as $f)
                            <!-- column  -->
                            <div class="col-md-6 mb-4">
                                <!-- Row -->
                                <div class="item-single ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img src="{{ url('storage') }}/portofolio/{{ $f->attachment }}" alt="wrapkit"
                                                class="img-fluid" />
                                        </div>
                                        <div class="col-md-12">
                                            <center>
                                                <h5 class="mt-4 font-weight-medium mb-0">{{ $f->title }}</h5>
                                                <h6 class="subtitle">( {{ $f->dates }} )</h6>
                                            </center>
                                            <div class="ml-4 mr-4 description">
                                                {!! $f->description !!}
                                            </div>
                                            <br>
                                            <center>
                                                <div class="social-link">
                                                    @if (!empty($f->attachment))
                                                        <a href="tel:{{ $f->attachment }}" target="_blank">
                                                            <i class='bx bxs-download'></i> Download</a>
                                                    @endif
                                                   


                                                </div>
                                            </center>
                                            <br>
                                        </div>
                                    </div>

                                </div>
                            </div>
                                <!-- Row -->
                        @endforeach
                        <!-- column  -->
                        <!-- column  -->

                        <!-- column  -->

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
