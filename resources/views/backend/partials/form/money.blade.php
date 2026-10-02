{{-- Input Rupiah. Param: name, label, hint (opsional), required (bool), last (bool, tanpa margin bawah). --}}
<div class="gd-field {{ ! empty($last) ? 'mb-0' : '' }}">
    <label class="gd-label">{{ $label }} @if (! empty($required))<span class="gd-req">*</span>@endif</label>
    <div class="gd-affix">
        <span class="gd-affix__pre">Rp</span>
        {!! Form::number($name, null, array_filter(['class' => 'form-control gd-input', 'required' => ! empty($required) ? 'required' : null, 'min' => 0, 'inputmode' => 'numeric', 'placeholder' => '0', 'data-gd-money' => $name])) !!}
    </div>
    @if (! empty($hint))<p class="gd-hint">{{ $hint }}</p>@endif
    <p class="gd-money-preview" data-gd-money-preview="{{ $name }}"></p>
    {!! $errors->first($name, '<p class="gd-error">:message</p>') !!}
</div>
