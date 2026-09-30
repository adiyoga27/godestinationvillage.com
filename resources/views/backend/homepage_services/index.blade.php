@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Item Our Services
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Homepage / Services</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('homepage-services.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah Service
                </a>
                <br /><br />
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Gambar</th>
                                <th>Judul (EN / ID)</th>
                                <th>Klik</th>
                                <th>Status</th>
                                <th style="width: 260px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->sort_order }}</td>
                                    <td>
                                        @if ($service->image)
                                            <img src="{{ \App\Helpers\Homepage::serviceImage($service->image) }}" style="width:90px;height:90px;object-fit:contain;border-radius:8px;border:1px solid #eee;" onerror="this.style.display='none'">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $service->title }}</strong><br><small class="text-muted">{{ $service->title_id }}</small></td>
                                    <td>
                                        @if ($service->desc || $service->desc_id)
                                            <span class="badge badge-info">Modal</span>
                                        @else
                                            <span class="badge badge-secondary">Link</span>
                                        @endif
                                        @if ($service->file)
                                            <span class="badge badge-success" title="Ada file brosur">File</span>
                                        @endif
                                        @if (is_array($service->buttons) && count($service->buttons))
                                            <span class="badge badge-warning" title="{{ count($service->buttons) }} tombol custom">+{{ count($service->buttons) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($service->is_active)
                                            <span class="badge badge-success">Tampil</span>
                                        @else
                                            <span class="badge badge-secondary">Sembunyi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('homepage-services.edit', $service->id) }}" class="btn btn-sm btn-gradient-info">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('homepage-services.toggle', $service->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $service->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}">
                                                <i class="mdi {{ $service->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('homepage-services.destroy', $service->id) }}" style="display:inline;"
                                            onsubmit="return confirm('Hapus item &quot;{{ $service->title }}&quot;?')">
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
