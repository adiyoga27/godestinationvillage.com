@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Edit Section: {{ $section->name }}
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('homepage-sections.index') }}">Homepage / Sections</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $section->name }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                {!! Form::model($section, ['url' => route('homepage-sections.update', $section->id),
                  'method' => 'put', 'files' => true, 'class' => 'form-sample']) !!}

                <div class="alert alert-info">
                    Kosongkan field Indonesia jika sama dengan English. Section yang disembunyikan tidak tampil di homepage.
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Eyebrow / Label Kecil (EN)</label>
                    <div class="col-sm-9">
                        {!! Form::text('eyebrow', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: Our Services']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Eyebrow (ID)</label>
                    <div class="col-sm-9">
                        {!! Form::text('eyebrow_id', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: Layanan Kami']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Judul (EN)</label>
                    <div class="col-sm-9">
                        {!! Form::text('title', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Judul (ID)</label>
                    <div class="col-sm-9">
                        {!! Form::text('title_id', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Sub-judul / Deskripsi (EN)</label>
                    <div class="col-sm-9">
                        {!! Form::textarea('subtitle', null, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Sub-judul / Deskripsi (ID)</label>
                    <div class="col-sm-9">
                        {!! Form::textarea('subtitle_id', null, ['class' => 'form-control', 'rows' => '3']) !!}
                    </div>
                </div>

                @if ($section->key === 'about')
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Badge Kecil (selalu English)</label>
                    <div class="col-sm-4">
                        {!! Form::text('badge_title', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: SEE']) !!}
                        <small class="form-text text-muted">Judul badge, cth: SEE. Tidak di-translate.</small>
                    </div>
                    <div class="col-sm-5">
                        {!! Form::text('badge_subtitle', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: Sustainability · Empowerment · Entrepreneurship']) !!}
                        <small class="form-text text-muted">Sub badge, selalu tampil English.</small>
                    </div>
                </div>
                @endif

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Gambar Section</label>
                    <div class="col-sm-9">
                        @if ($section->image)
                            <div class="mb-2">
                                <img src="{{ \App\Helpers\Homepage::image($section->key) }}" style="max-width:280px;max-height:160px;object-fit:cover;border-radius:10px;border:1px solid #e3e3e3;" onerror="this.style.display='none'">
                                <br><small class="form-text text-muted">Gambar saat ini (biarkan kosong untuk mempertahankan)</small>
                            </div>
                        @endif
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control">
                        {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
                        <small class="form-text text-muted">Hanya untuk section yang punya gambar (Tentang, Virtual Reality). Maks 10 MB.</small>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tombol 1 (EN / ID / URL)</label>
                    <div class="col-sm-3">
                        {!! Form::text('button_label', null, ['class' => 'form-control', 'placeholder' => 'Label EN']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::text('button_label_id', null, ['class' => 'form-control', 'placeholder' => 'Label ID']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::text('button_url', null, ['class' => 'form-control', 'placeholder' => 'cth: tour-packages / https://...']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tombol 2 (EN / ID / URL)</label>
                    <div class="col-sm-3">
                        {!! Form::text('button2_label', null, ['class' => 'form-control', 'placeholder' => 'Label EN']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::text('button2_label_id', null, ['class' => 'form-control', 'placeholder' => 'Label ID']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::text('button2_url', null, ['class' => 'form-control', 'placeholder' => 'cth: contact']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Urutan Tampil</label>
                    <div class="col-sm-9">
                        {!! Form::number('sort_order', null, ['class' => 'form-control', 'style' => 'max-width:160px;']) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Tampilkan di Homepage?</label>
                    <div class="col-sm-9">
                        <div class="form-check mt-2">
                            {!! Form::checkbox('is_active', 1, null, ['class' => 'form-check-input', 'id' => 'is_active']) !!}
                            <label class="form-check-label" for="is_active">Ya, tampilkan section ini</label>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-3 col-form-label"></label>
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
                        <a href="{{ route('homepage-sections.index') }}" class="btn btn-lg btn-light mb-2">Kembali</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@if ($section->key === 'about')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Kartu Fitur Why GODEVI</h4>
                <p class="card-description">4 kartu kecil di bawah deskripsi (Socially Responsible, dst). Tambah, edit, tampil/sembunyikan, hapus dari sini.</p>
                <a href="{{ route('homepage-about-features.create') }}" class="btn btn-gradient-danger mb-3">
                    <i class="mdi mdi-plus-circle-outline"></i> Tambah Kartu
                </a>
                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Urutan</th>
                                <th>Judul (EN / ID)</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th style="width: 260px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($features as $feature)
                                <tr>
                                    <td>{{ $feature->sort_order }}</td>
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
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada kartu. Klik Tambah Kartu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
