@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit News
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ url('administrator/news') }}">News</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit News</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($blog, ['url' => route('news.update', $blog->id),
                  'method'=>'put', 'files'=>true, 'class'=>'form-sample']) !!}
                  @include('backend.blog.form._form')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
  <script src="{{ asset('assets/admin/tinymce/js/tinymce/tinymce.min.js') }}"></script>
  <script type="text/javascript">
    tinymce.init({
      selector: "textarea",
      height: 400,
      plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons paste textcolor code"
      ],
      toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink anchor | image media | forecolor backcolor | print preview code",
      image_advtab: true,
      image_uploadtab: true,
      images_upload_url: "{{ route('tinymce.upload_image') }}",
      images_upload_headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      automatic_uploads: true,
    });
  </script>
@endsection
