@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Kategori FAQ
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('faqs.index') }}">FAQ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Kategori</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
{!! Form::model($category, ['url' => route('faq-categories.update', $category->id), 'method' => 'put', 'class' => 'form-sample']) !!}
  @include('backend.faq_categories.form._form')
{!! Form::close() !!}
@endsection
