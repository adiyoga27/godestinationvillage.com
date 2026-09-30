@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Dashboard Tim — Inbox 4 Alur</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Tim</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
@include('backend.team_dashboard._inbox')
@endsection
