{{--
  Kolom dua bahasa dengan tab EN / ID.
  Param: title, hint (opsional), en (nama field EN), id (nama field ID), idValue, required (bool),
         type: 'rich' (TinyMCE, default) | 'textarea' | 'text', placeholder (opsional).
--}}
@php
    $type = $type ?? 'rich';
    $attrs = fn ($extra = []) => array_merge(['class' => 'form-control '.($type === 'rich' ? 'gd-rich' : 'gd-input')], $type === 'textarea' ? ['rows' => 4] : [], $extra);
    $hasError = $errors->has($en) || $errors->has($id);
@endphp
<div class="gd-field gd-bilingual {{ $hasError ? 'has-error' : '' }}" data-gd-lang-group>
    <div class="gd-bilingual__head">
        <div>
            <label class="gd-label mb-0">{{ $title }} @if (! empty($required))<span class="gd-req">*</span>@endif</label>
            @if (! empty($hint))<p class="gd-hint">{{ $hint }}</p>@endif
        </div>
        <div class="gd-seg" role="tablist" aria-label="Bahasa {{ $title }}">
            <button type="button" class="gd-seg__btn is-active" data-gd-lang="en" role="tab" aria-selected="true">EN</button>
            <button type="button" class="gd-seg__btn" data-gd-lang="id" role="tab" aria-selected="false">ID</button>
        </div>
    </div>
    <div class="gd-bilingual__pane" data-gd-pane="en">
        @if ($type === 'text')
            {!! Form::text($en, null, $attrs(array_filter(['required' => ! empty($required) ? 'required' : null, 'placeholder' => $placeholder ?? null]))) !!}
        @else
            {!! Form::textarea($en, null, $attrs()) !!}
        @endif
        {!! $errors->first($en, '<p class="gd-error">EN: :message</p>') !!}
    </div>
    <div class="gd-bilingual__pane" data-gd-pane="id" hidden>
        @if ($type === 'text')
            {!! Form::text($id, $idValue ?? null, $attrs(array_filter(['required' => ! empty($required) ? 'required' : null, 'placeholder' => $placeholderId ?? $placeholder ?? null]))) !!}
        @else
            {!! Form::textarea($id, $idValue ?? null, $attrs()) !!}
        @endif
        {!! $errors->first($id, '<p class="gd-error">ID: :message</p>') !!}
    </div>
</div>
