{{--
  Chip pilihan (radio/checkbox) untuk panel filter.
  Param: name, value, label, checked (bool), type ('radio' default | 'checkbox'), tone ('brand' | 'forest'), icon (SVG, opsional).
--}}
@php
    $tone = ($tone ?? 'brand') === 'forest'
        ? 'hover:border-forest-300 peer-checked:border-forest-600 peer-checked:bg-forest-600 peer-focus-visible:ring-forest-500/30'
        : 'hover:border-brand-300 peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-focus-visible:ring-brand-500/30';
@endphp
<label class="shrink-0 cursor-pointer">
    <input type="{{ $type ?? 'radio' }}" name="{{ $name }}" value="{{ $value }}" class="peer sr-only" data-autosubmit @checked($checked)>
    <span class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-600 transition peer-checked:text-white peer-focus-visible:ring-2 {{ $tone }}">
        {!! $icon ?? '' !!}{{ $label }}
    </span>
</label>
