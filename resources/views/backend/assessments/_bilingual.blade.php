{{--
  Kolom dua bahasa asesmen dengan tab ID / EN (kolom utama Bahasa Indonesia, English di {field}_en).
  Param: label, field (nama kolom ID), model (opsional), required (bool, hanya versi ID),
         type: 'text' (default) | 'textarea', rows (opsional), hint (opsional), placeholder (opsional).
--}}
@php
    $type = $type ?? 'text';
    $enField = $field.'_en';
    $idValue = old($field, $model->{$field} ?? '');
    $enValue = old($enField, $model->{$enField} ?? '');
@endphp
<div class="form-group gd-bilingual" data-gd-lang-group>
    <div class="gd-bilingual__head">
        <div>
            <label class="mb-0">{{ $label }} @if (! empty($required))*@endif</label>
            @if (! empty($hint))<small class="d-block text-muted">{{ $hint }}</small>@endif
        </div>
        <div class="gd-seg" role="tablist" aria-label="Bahasa {{ $label }}">
            <button type="button" class="gd-seg__btn is-active" data-gd-lang="id" role="tab" aria-selected="true">ID</button>
            <button type="button" class="gd-seg__btn {{ blank($enValue) ? 'text-warning' : '' }}" data-gd-lang="en" role="tab" aria-selected="false" title="{{ blank($enValue) ? 'Versi English belum diisi' : 'English' }}">EN</button>
        </div>
    </div>
    <div class="gd-bilingual__pane" data-gd-pane="id">
        @if ($type === 'textarea')
            <textarea name="{{ $field }}" rows="{{ $rows ?? 3 }}" class="form-control" @if (! empty($required)) required @endif placeholder="{{ $placeholder ?? '' }}">{{ $idValue }}</textarea>
        @else
            <input type="text" name="{{ $field }}" value="{{ $idValue }}" class="form-control" @if (! empty($required)) required @endif placeholder="{{ $placeholder ?? '' }}">
        @endif
        {!! $errors->first($field, '<p class="gd-error text-danger small mb-0">ID: :message</p>') !!}
    </div>
    <div class="gd-bilingual__pane" data-gd-pane="en" hidden>
        @if ($type === 'textarea')
            <textarea name="{{ $enField }}" rows="{{ $rows ?? 3 }}" class="form-control" placeholder="English — kosong = tampil versi Indonesia">{{ $enValue }}</textarea>
        @else
            <input type="text" name="{{ $enField }}" value="{{ $enValue }}" class="form-control" placeholder="English — kosong = tampil versi Indonesia">
        @endif
        {!! $errors->first($enField, '<p class="gd-error text-danger small mb-0">EN: :message</p>') !!}
    </div>
</div>
