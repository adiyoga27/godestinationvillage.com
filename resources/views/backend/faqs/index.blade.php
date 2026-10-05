@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          FAQ
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / FAQ</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    Isi halaman <a href="{{ url('faq') }}" target="_blank" rel="noopener">/faq</a>. Pertanyaan tampil sebagai akordeon per kategori, urut dari angka urutan terkecil.
                    Versi Indonesia opsional — bila kosong, pengunjung berbahasa Indonesia melihat versi English.
                </div>

                <a href="{{ route('faqs.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                  <i class="mdi mdi-plus-circle-outline"></i> Tambah Pertanyaan
                </a>
                <a href="{{ route('faq-categories.create') }}" class="btn btn-lg btn-outline-secondary">
                  <i class="mdi mdi-folder-plus-outline"></i> Tambah Kategori
                </a>

                @forelse ($categories as $category)
                    <div class="mt-5">
                        <div class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-2 mb-2" style="gap:.5rem;">
                            <div>
                                <h4 class="mb-0">
                                    <span class="text-muted" style="font-size:.8rem;">#{{ $category->sort_order }}</span>
                                    {{ $category->title }}
                                    @unless ($category->is_active)<span class="badge badge-secondary ml-1">Sembunyi</span>@endunless
                                </h4>
                                @if ($category->title_id)<small class="text-muted">{{ $category->title_id }}</small>@endif
                            </div>
                            <div>
                                <a href="{{ route('faqs.create', ['category' => $category->id]) }}" class="btn btn-sm btn-gradient-success">
                                    <i class="mdi mdi-plus"></i> Pertanyaan
                                </a>
                                <a href="{{ route('faq-categories.edit', $category->id) }}" class="btn btn-sm btn-gradient-info">
                                    <i class="mdi mdi-pencil"></i> Kategori
                                </a>
                                <form method="POST" action="{{ route('faq-categories.destroy', $category->id) }}" style="display:inline;"
                                    onsubmit="return confirm('Hapus kategori &quot;{{ $category->title }}&quot;?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-gradient-danger" title="Hapus kategori (harus kosong)">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">Urutan</th>
                                        <th>Pertanyaan (EN / ID)</th>
                                        <th style="width:110px;">Status</th>
                                        <th style="width:190px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($category->faqs as $faq)
                                        <tr>
                                            <td>{{ $faq->sort_order }}</td>
                                            <td style="white-space:normal;">
                                                <strong>{{ $faq->question }}</strong>
                                                <br><small class="text-muted">{{ $faq->question_id ?: '— belum ada versi Indonesia —' }}</small>
                                            </td>
                                            <td>
                                                @if ($faq->is_active)
                                                    <span class="badge badge-success">Tampil</span>
                                                @else
                                                    <span class="badge badge-secondary">Sembunyi</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('faqs.edit', $faq->id) }}" class="btn btn-sm btn-gradient-info">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('faqs.toggle', $faq->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $faq->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}" title="{{ $faq->is_active ? 'Sembunyikan' : 'Tampilkan' }}">
                                                        <i class="mdi {{ $faq->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('faqs.destroy', $faq->id) }}" style="display:inline;"
                                                    onsubmit="return confirm('Hapus pertanyaan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-gradient-danger" title="Hapus">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">Belum ada pertanyaan di kategori ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p class="mt-4 text-muted">Belum ada kategori FAQ. Mulai dengan <a href="{{ route('faq-categories.create') }}">menambah kategori</a>.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
