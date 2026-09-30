@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Fitur Why GODEVI
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Homepage / Fitur Why GODEVI</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('homepage-about-features.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah Kartu
                </a>
                <br /><br />
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Ikon</th>
                                <th>Judul (EN / ID)</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th style="width: 260px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($features as $feature)
                                <tr>
                                    <td>{{ $feature->sort_order }}</td>
                                    <td>
                                        @if ($feature->image)
                                            <img src="{{ \App\Helpers\Homepage::aboutFeatureImage($feature->image) }}" style="width:60px;height:60px;object-fit:contain;border-radius:8px;border:1px solid #eee;" onerror="this.style.display='none'">
                                        @else
                                            <span class="badge badge-secondary">Ikon bawaan</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $feature->title }}</strong><br><small class="text-muted">{{ $feature->title_id }}</small></td>
                                    <td><small class="text-muted">{{ \Illuminate\Support\Str::limit($feature->desc_id ?: $feature->desc, 80) }}</small></td>
                                    <td>
                                        @if ($feature->is_active)
                                            <span class="badge badge-success">Tampil</span>
                                        @else
                                            <span class="badge badge-secondary">Sembunyi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('homepage-about-features.edit', $feature->id) }}" class="btn btn-sm btn-gradient-info">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('homepage-about-features.toggle', $feature->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $feature->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}">
                                                <i class="mdi {{ $feature->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('homepage-about-features.destroy', $feature->id) }}" style="display:inline;"
                                            onsubmit="return confirm('Hapus kartu &quot;{{ $feature->title }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger">
                                                <i class="mdi mdi-delete"></i>
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
