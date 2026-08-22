@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Manajemen Instagram
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Instagram</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                    <div>
                        <a href="{{ route('instagram.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                          <i class="mdi mdi-plus-circle-outline"></i> Tambah Instagram
                        </a>
                        <form action="{{ route('instagram.sync') }}" method="POST" style="display:inline-block;">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-lg btn-gradient-info mr-2">
                              <i class="mdi mdi-sync"></i> Sync Postingan Terbaru
                            </button>
                        </form>
                    </div>
                    <div class="text-muted" style="max-width:420px">
                        <small>Akun: <strong>{{ config('instagram.username') }}</strong> &mdash; sinkronisasi otomatis tiap 1 jam (cron <code>instagram:sync</code>).</small>
                    </div>
                </div>
                @if (config('instagram.access_token'))
                    <div class="alert alert-info mt-3 mb-0"><small>Mode: <strong>Instagram Graph API</strong> &mdash; token aktif, postingan terbaru diambil otomatis.</small></div>
                @else
                    <div class="alert alert-warning mt-3 mb-0"><small>Mode: <strong>scrape publik (best-effort)</strong>. Untuk sinkronisasi andal, isi <code>INSTAGRAM_ACCESS_TOKEN</code> &amp; <code>INSTAGRAM_USER_ID</code> di <code>.env</code>.</small></div>
                @endif
                <br>
                <div class="table-responsive">
                    {!! $html->table(['class'=>'table table-hover', 'style'=>'width:100%']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    {!! $html->scripts() !!}
    @include('components/_script_adjust-table')
@endsection
