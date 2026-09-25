@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Edit Soal — {{ $track->name }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.questions.index', $track->id) }}">Soal</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <form action="{{ route('assessments.questions.update', [$track->id, $question->id]) }}" method="post">
                @csrf
                @method('PUT')
                @include('backend.assessments.questions._form')
                <button class="btn btn-gradient-danger">Simpan</button>
            </form>
            <hr>
            <form action="{{ route('assessments.questions.destroy', [$track->id, $question->id]) }}" method="post" onsubmit="return confirm('Hapus soal ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div></div>
    </div>
</div>
@endsection
