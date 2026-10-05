@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Pertanyaan FAQ
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('faqs.index') }}">FAQ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
{!! Form::model($faq, ['url' => route('faqs.update', $faq->id), 'method' => 'put', 'class' => 'form-sample']) !!}
  @include('backend.faqs.form._form')
{!! Form::close() !!}
@endsection

@section('js')
  @include('backend.partials.form.rich-editor-js', ['height' => 260])
@endsection
