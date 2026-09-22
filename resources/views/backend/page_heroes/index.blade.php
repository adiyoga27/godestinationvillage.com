@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Hero Halaman
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / Hero Halaman</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-description">
                    Atur judul, sub-judul, dan gambar latar banner atas tiap halaman website.
                    Kosongkan field agar memakai bawaan. Perubahan langsung tampil di website.
                </p>
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Halaman</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($heroes as $hero)
                                <tr>
                                    <td><strong>{{ $hero->name }}</strong><br><small class="text-muted">{{ $hero->key }}</small></td>
                                    <td>
                                        @php $img = \App\Helpers\PageHero::image($hero->key); @endphp
                                        @if ($img)
                                            <img src="{{ $img }}" style="width:140px;height:60px;object-fit:cover;border-radius:8px;" onerror="this.style.display='none'">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($hero->title, 60) }}</td>
                                    <td>
                                        <a href="{{ route('page-heroes.edit', $hero->id) }}" class="btn btn-sm btn-gradient-info">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
