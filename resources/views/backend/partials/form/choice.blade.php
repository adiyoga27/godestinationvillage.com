{{-- Pilihan ya/tidak bergaya tombol. Param: name, value (0/1), label (opsional), on/off (teks), onIcon/offIcon. --}}
<div class="gd-field">
    @if (! empty($label))<label class="gd-label">{{ $label }}</label>@endif
    <div class="gd-choice">
        <label class="gd-choice__opt">
            <input type="radio" name="{{ $name }}" value="1" @checked((int) $value === 1)>
            <span><i class="mdi mdi-{{ $onIcon ?? 'check-circle' }}"></i> {{ $on ?? 'Aktif' }}</span>
        </label>
        <label class="gd-choice__opt">
            <input type="radio" name="{{ $name }}" value="0" @checked((int) $value === 0)>
            <span><i class="mdi mdi-{{ $offIcon ?? 'minus-circle' }}"></i> {{ $off ?? 'Tidak Aktif' }}</span>
        </label>
    </div>
    {!! $errors->first($name, '<p class="gd-error">:message</p>') !!}
</div>
