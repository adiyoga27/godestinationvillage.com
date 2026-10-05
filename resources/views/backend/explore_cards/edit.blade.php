@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Kartu Explore
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('explore-cards.index') }}">Kartu Explore Village</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
{!! Form::model($card, ['url' => route('explore-cards.update', $card->id), 'method' => 'put', 'files' => true, 'class' => 'form-sample']) !!}
  @include('backend.explore_cards.form._form')
{!! Form::close() !!}
@endsection
