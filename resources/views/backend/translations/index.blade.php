@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Bahasa Website
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Kelola Website / Bahasa Website</li>
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
                    Teks tetap di website (menu, tombol, judul, meta SEO, dll.) dalam Bahasa Indonesia (<code>/id/…</code>) dan English (<code>/en/…</code>).
                    Ubah teksnya lalu klik <strong>Simpan</strong>. Kosongkan kolom atau klik <i class="mdi mdi-restore"></i> untuk kembali ke teks bawaan.
                    Teks yang berisi <code>:name</code> dan sejenisnya adalah isian otomatis — jangan dihapus.
                </div>

                <form method="GET" action="{{ route('translations.index') }}" class="d-flex flex-wrap align-items-center mb-3" style="gap:.5rem;">
                    <input type="search" name="q" value="{{ $search }}" class="form-control" style="max-width:340px;" placeholder="Cari teks (ID / EN)...">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <button type="submit" class="btn btn-gradient-info"><i class="mdi mdi-magnify"></i> Cari</button>
                    @if ($search !== '')
                        <a href="{{ route('translations.index', ['filter' => $filter]) }}" class="btn btn-light">Reset</a>
                    @endif
                </form>

                <div class="btn-group mb-4" role="group" aria-label="Filter">
                    @foreach (['all' => 'Semua', 'changed' => 'Diubah dari admin', 'untranslated' => 'ID belum diterjemahkan'] as $key => $label)
                        <a href="{{ route('translations.index', array_filter(['filter' => $key === 'all' ? null : $key, 'q' => $search ?: null])) }}"
                            class="btn btn-sm {{ $filter === $key || ($key === 'all' && ! in_array($filter, ['changed', 'untranslated'])) ? 'btn-gradient-danger' : 'btn-outline-secondary' }}">
                            {{ $label }} <span class="badge badge-light ml-1">{{ $counts[$key] }}</span>
                        </a>
                    @endforeach
                </div>

                @if ($translations->isEmpty())
                    <p class="text-muted">Tidak ada teks yang cocok.</p>
                @else
                    <form method="POST" action="{{ route('translations.update') }}" id="translations-form">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table class="table" style="width:100%; table-layout:fixed;">
                                <thead>
                                    <tr>
                                        @foreach ($locales as $code => $name)
                                            <th>{{ $name }} <small class="text-muted">/{{ $code }}</small></th>
                                        @endforeach
                                        <th style="width:56px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($translations as $row)
                                        <tr>
                                            <td colspan="{{ count($locales) + 1 }}" class="pb-0 border-bottom-0" style="white-space:normal;">
                                                <small class="text-muted"><i class="mdi mdi-key-variant"></i> {{ \Illuminate\Support\Str::limit($row['key'], 140) }}</small>
                                                @if ($row['changed'])<span class="badge badge-warning ml-1">Diubah</span>@endif
                                            </td>
                                        </tr>
                                        <tr>
                                            @foreach ($locales as $code => $name)
                                                @php $value = $row['values'][$code]; @endphp
                                                <td class="pt-2" style="vertical-align:top;">
                                                    <textarea name="translations[{{ $row['hash'] }}][{{ $code }}]" class="form-control" lang="{{ $code }}"
                                                        rows="{{ min(8, max(1, (int) ceil(mb_strlen($value) / 60))) }}"
                                                        style="font-size:.85rem; line-height:1.45;">{{ $value }}</textarea>
                                                    @if ($value !== $row['defaults'][$code])
                                                        <small class="text-muted d-block mt-1" title="{{ $row['defaults'][$code] }}">Bawaan: {{ \Illuminate\Support\Str::limit($row['defaults'][$code], 90) }}</small>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="pt-2" style="vertical-align:top;">
                                                @if ($row['changed'])
                                                    <button type="submit" form="reset-{{ $row['hash'] }}" class="btn btn-sm btn-outline-secondary" title="Kembalikan ke teks bawaan"
                                                        onclick="return confirm('Kembalikan teks ini ke bawaan?')">
                                                        <i class="mdi mdi-restore"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between mt-3" style="gap:1rem;">
                            <div>{{ $translations->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
                            <button type="submit" class="btn btn-lg btn-gradient-danger"><i class="mdi mdi-content-save"></i> Simpan halaman ini</button>
                        </div>
                    </form>

                    {{-- Form reset terpisah (form tidak boleh bersarang). --}}
                    @foreach ($translations as $row)
                        @if ($row['changed'])
                            <form id="reset-{{ $row['hash'] }}" method="POST" action="{{ route('translations.reset', $row['hash']) }}" hidden>
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
  // Ingatkan bila pindah halaman sebelum menyimpan perubahan.
  (function () {
    var form = document.getElementById('translations-form');
    if (!form) return;
    var dirty = false;
    form.addEventListener('input', function () { dirty = true; });
    form.addEventListener('submit', function () { dirty = false; });
    window.addEventListener('beforeunload', function (e) {
      if (dirty) { e.preventDefault(); e.returnValue = ''; }
    });
  })();
</script>
@endsection
