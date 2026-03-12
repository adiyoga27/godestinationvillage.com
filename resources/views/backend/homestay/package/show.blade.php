@extends('layouts.backend')

@section('style')
<link rel="stylesheet" href="{{asset('css/magnific-popup.css')}}">
@endsection

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Detail Paket Homestay
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/package') }}">Paket Homestay</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $package->name }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                @if(empty($package->default_img))
                    <img class="card-img-top" src="{{ asset('dist/images/no-image.png') }}" alt="image">
                @else
                    <img class="card-img-top" src="{{ asset('storage/packages/'.$package->default_img) }}">
                @endif
                <div class="card-body">
                    <h3 align="center">Informasi Paket Homestay</h3><br />
                    <table class="table nowrap table-hover dataTables no-footer" style="white-space:nowrap; width: 100%">
                        <tr>
                            <td>Nama Paket Homestay</td>
                            <td>{{ $package->name }}</td>
                        </tr>
                        <tr>
                            <td>Kategori</td>
                            <td>{{ $package->category->name }}</td>
                        </tr>
                        <tr>
                            <td>Desa</td>
                            <td>{{ $package->user->village_detail->village_name }}</td>
                        </tr>
                        <tr>
                            <td>Harga Paket</td>
                            <td>IDR {{ number_format($package->price) }}</td>
                        </tr>
                    </table>
                    <br />
                    <center><a href="{{ route('package.edit', $package->id) }}" class="btn btn-danger">Edit Data</a></center>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-order-tab" data-toggle="tab" href="#nav-order" role="tab" aria-controls="nav-order" aria-selected="false">Pemesanan ({{ count($package->orders) }})</a>
                    <a class="nav-item nav-link" id="nav-desc-tab" data-toggle="tab" href="#nav-desc" role="tab" aria-controls="nav-desc" aria-selected="true">Deskripsi</a>
                    <a class="nav-item nav-link" id="nav-review-tab" data-toggle="tab" href="#nav-review" role="tab" aria-controls="nav-review" aria-selected="true">Review</a>
                    <a class="nav-item nav-link" id="nav-itenaries-tab" data-toggle="tab" href="#nav-itenaries" role="tab" aria-controls="nav-itenaries" aria-selected="true">Itenary</a>
                    <a class="nav-item nav-link" id="nav-inclusion-tab" data-toggle="tab" href="#nav-inclusion" role="tab" aria-controls="nav-inclusion" aria-selected="true">Inclusion</a>
                    <a class="nav-item nav-link" id="nav-exclusion-tab" data-toggle="tab" href="#nav-exclusion" role="tab" aria-controls="nav-exclusion" aria-selected="true">Exclusion</a>
                    <a class="nav-item nav-link" id="nav-term-tab" data-toggle="tab" href="#nav-term" role="tab" aria-controls="nav-term" aria-selected="true">Term</a>
                    <a class="nav-item nav-link" id="nav-image-tab" data-toggle="tab" href="#nav-image" role="tab" aria-controls="nav-image" aria-selected="true">Image</a>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-order" role="tabpanel" aria-labelledby="nav-order-tab">
                            <br /><br />
                            <div class="table-responsive"> 
                                {!! $html->table(['class'=>'table table-hover', 'style'=>'width:100%']) !!}
                            </div>
                        </div>

                        <div class="tab-pane fade show" id="nav-desc" role="tabpanel" aria-labelledby="nav-desc-tab">
                            {!! $package->desc !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">
                            {!! $package->review !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-itenaries" role="tabpanel" aria-labelledby="nav-itenaries-tab">
                            {!! $package->itenaries !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-inclusion" role="tabpanel" aria-labelledby="nav-inclusion-tab">
                            {!! $package->inclusion !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-exclusion" role="tabpanel" aria-labelledby="nav-exclusion-tab">
                            {!! $package->exclusion !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-term" role="tabpanel" aria-labelledby="nav-term-tab">
                            {!! $package->term !!}
                        </div>

                        <div class="tab-pane fade show" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab">
                            <br /><br />

                            @if(sizeof($images) < 1)
                                <center> <h3>No Image Available</h3> </center>
                            @else

                                <div class="row">
                                    @foreach($images as $photo)
                                        <div class="col-md-4">
                                            <div class="img-cc img-gallery gallery-item" data-title="{{ $package->name }}" data-mfp-src="{{ url('/storage') }}/{{$photo}}" style="background-image: url( {{ url('/storage') }}/{{$photo}} ); height: 200px; background-size: cover; background-repeat: no-repeat; background-position: center; cursor: pointer;"></div>
                                            {!! Form::open(['url' => route('package.delete_image'), 'method' => 'post']) !!}
                                                <input type="hidden" name="file" value="{{ $photo }}">
                                                <button type="submit" class="btn btn-danger btn-block" data-confirm="Delete Image?" onclick="return confirm('Are you sure to delete this image?');"><i class="mdi mdi-delete"></i> Delete</button>
                                            {!! Form::close() !!}
                                        </div>
                                    @endforeach
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
{!! $html->scripts() !!}
@include('components/_script_adjust-table')
<script src="{{asset('js/jquery.magnific-popup.min.js')}}"></script>
<script type="text/javascript">
    $('.gallery-item').magnificPopup({
        type: 'image',
        gallery:{
            enabled:true,
            preload: [0,2], // read about this option in next Lazy-loading section
            navigateByImgClick: true,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
            tPrev: 'Previous (Left arrow key)', // title for left button
            tNext: 'Next (Right arrow key)', // title for right button
            tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
        },
        image: {
            titleSrc: function(item) {
                return item.el.attr('data-title');
            }
        },
    });
</script>
@endsection
