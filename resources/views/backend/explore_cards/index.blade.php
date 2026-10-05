@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Kartu Explore Village
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / Kartu Explore Village</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info">
                    Kartu bergambar di section <strong>Explore Village</strong> beranda (maks. 6 kartu tampil, urut dari angka urutan terkecil).
                    Judul, subjudul & tombol "View All Villages" section ini diatur di
                    <a href="{{ route('homepage-sections.index') }}">Homepage Sections</a>.
                    Kartu ini juga menjadi pilihan <em>Tag Paket</em> di form Paket Wisata.
                </div>

                <a href="{{ route('explore-cards.create') }}" class="btn btn-lg btn-gradient-danger mb-4">
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah Kartu
                </a>

                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:80px;">Urutan</th>
                                <th style="width:140px;">Gambar</th>
                                <th>Judul & Deskripsi (EN / ID)</th>
                                <th>Link</th>
                                <th style="width:110px;">Beranda</th>
                                <th style="width:190px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cards as $card)
                                <tr>
                                    <td>{{ $card->sort_order }}</td>
                                    <td>
                                        <img src="{{ asset('storage/tag/'.$card->image) }}" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:10px;" onerror="this.style.visibility='hidden'">
                                    </td>
                                    <td style="white-space:normal;">
                                        <strong>{{ $card->name }}</strong> <small class="text-muted">/ {{ $card->name_id ?: '—' }}</small>
                                        <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($card->desc ?: '(tanpa deskripsi)', 90) }}</small>
                                        @if ($usage[$card->id] ?? 0)
                                            <br><span class="badge badge-light mt-1">Dipakai {{ $usage[$card->id] }} paket</span>
                                        @endif
                                    </td>
                                    <td style="white-space:normal;"><small>{{ $card->url ?: 'Daftar desa (default)' }}</small></td>
                                    <td>
                                        @if ($card->status)
                                            <span class="badge badge-success">Tampil</span>
                                        @else
                                            <span class="badge badge-secondary">Sembunyi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('explore-cards.edit', $card->id) }}" class="btn btn-sm btn-gradient-info"><i class="mdi mdi-pencil"></i> Edit</a>
                                        <form method="POST" action="{{ route('explore-cards.toggle', $card->id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $card->status ? 'btn-outline-secondary' : 'btn-gradient-success' }}" title="{{ $card->status ? 'Sembunyikan' : 'Tampilkan' }}">
                                                <i class="mdi {{ $card->status ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('explore-cards.destroy', $card->id) }}" style="display:inline;"
                                            onsubmit="return confirm('Hapus kartu &quot;{{ $card->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-gradient-danger" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-muted">Belum ada kartu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
