@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          {{ $page->title_id ?: $page->title }}
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / {{ $page->title_id ?: $page->title }}</li>
          </ol>
        </nav>
    </div>
@endsection

@section('content')
{!! Form::model($page, ['url' => route('legal-pages.update', $page->key), 'method' => 'put', 'class' => 'form-sample']) !!}
  @include('backend.partials.form.errors')

  <div class="gd-form">
      <div class="gd-form__main">
          @include('backend.partials.form.card-open', ['icon' => 'file-document-outline', 'title' => 'Isi Halaman', 'desc' => 'Isi versi English dan Indonesia. Pindah bahasa lewat tombol EN / ID.'])
              @include('backend.partials.form.bilingual', ['title' => 'Judul', 'en' => 'title', 'id' => 'title_id', 'required' => true, 'type' => 'text', 'placeholder' => 'Terms & Conditions', 'placeholderId' => 'Syarat & Ketentuan'])
              @include('backend.partials.form.bilingual', ['title' => 'Isi', 'en' => 'content', 'id' => 'content_id', 'required' => true,
                  'hint' => 'Pakai format "Judul" untuk tiap bagian (1. ..., 2. ...). Versi Indonesia boleh dikosongkan — pengunjung ID akan melihat versi English.'])
          @include('backend.partials.form.card-close')
      </div>

      <aside class="gd-form__side">
          @include('backend.partials.form.card-open', ['icon' => 'calendar', 'title' => 'Terakhir Diperbarui', 'desc' => 'Tampil di bagian bawah halaman.'])
              <div class="gd-field">
                  {!! Form::date('last_updated', $page->last_updated?->format('Y-m-d'), ['class' => 'form-control gd-input']) !!}
                  {!! $errors->first('last_updated', '<p class="gd-error">:message</p>') !!}
                  <p class="gd-hint mb-0">Otomatis diisi tanggal hari ini bila isi diubah dan tanggal ini tidak disentuh.</p>
              </div>
          @include('backend.partials.form.card-close')

          @if ($publicUrl)
              @include('backend.partials.form.card-open', ['icon' => 'open-in-new', 'title' => 'Halaman Publik'])
                  <a href="{{ url($publicUrl) }}" target="_blank" rel="noopener">{{ url($publicUrl) }}</a>
                  @if ($page->key === 'terms')
                      <p class="gd-hint mt-2 mb-0">Checkbox persetujuan di form asesmen menautkan ke halaman ini. Bagian "Privacy Policy" punya anchor <code>#privacy-policy</code> — jangan dihapus selama belum ada halaman Kebijakan Privasi tersendiri.</p>
                  @endif
              @include('backend.partials.form.card-close')
          @endif
      </aside>
  </div>

  @include('backend.partials.form.actionbar', ['cancel' => route('home'), 'label' => 'Simpan Halaman'])
{!! Form::close() !!}
@endsection

@section('js')
  @include('backend.partials.form.rich-editor-js', ['height' => 560])
@endsection
