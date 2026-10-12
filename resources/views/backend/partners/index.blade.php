@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Our Partners
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Our Partners</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('partners.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah Partner
                </a>
                <a href="{{ url('/our-partner') }}" target="_blank" class="btn btn-lg btn-light">
                  <i class="mdi mdi-open-in-new"></i> Lihat Halaman
                </a>
                <br /><br />
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Logo</th>
                                <th>Nama (EN / ID)</th>
                                <th>Website</th>
                                <th>Status</th>
                                <th style="width: 220px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partners as $partner)
                                <tr>
                                    <td>{{ $partner->sort_order }}</td>
                                    <td>
                                        @if ($partner->logo_url)
                                            <img src="{{ $partner->logo_url }}" style="width:90px;height:90px;object-fit:contain;border-radius:8px;border:1px solid #eee;background:#fff;" onerror="this.style.display='none'">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $partner->name }}</strong><br><small class="text-muted">{{ $partner->name_id }}</small></td>
                                    <td>
                                        @if ($partner->url)
                                            <a href="{{ $partner->url }}" target="_blank" rel="noopener">{{ \Illuminate\Support\Str::limit($partner->url, 40) }}</a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($partner->is_active)
                                            <span class="badge badge-success">Tampil</span>
                                        @else
                                            <span class="badge badge-secondary">Sembunyi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('partners.edit', $partner->id) }}" class="btn btn-sm btn-gradient-info">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('partners.toggle', $partner->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $partner->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}" title="{{ $partner->is_active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                <i class="mdi {{ $partner->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('partners.destroy', $partner->id) }}" style="display:inline;"
                                            onsubmit="return confirm('Hapus partner &quot;{{ $partner->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">Belum ada partner.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
