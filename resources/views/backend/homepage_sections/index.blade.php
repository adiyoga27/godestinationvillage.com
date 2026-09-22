@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Section Homepage
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Homepage / Sections</li>
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
                    Atur tulisan (EN/ID), gambar, dan tampil/sembunyi setiap section di halaman utama.
                    Perubahan langsung tampil di website.
                </p>
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Section</th>
                                <th>Judul (EN)</th>
                                <th>Status</th>
                                <th style="width: 220px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $section)
                                <tr>
                                    <td>{{ $section->sort_order }}</td>
                                    <td><strong>{{ $section->name }}</strong><br><small class="text-muted">{{ $section->key }}</small></td>
                                    <td>{{ \Illuminate\Support\Str::limit($section->title, 60) }}</td>
                                    <td>
                                        @if ($section->is_active)
                                            <span class="badge badge-success">Tampil</span>
                                        @else
                                            <span class="badge badge-secondary">Sembunyi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('homepage-sections.edit', $section->id) }}" class="btn btn-sm btn-gradient-info">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('homepage-sections.toggle', $section->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}">
                                                <i class="mdi {{ $section->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                                {{ $section->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                            </button>
                                        </form>
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
