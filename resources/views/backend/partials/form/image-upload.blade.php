{{-- Upload gambar dengan preview. Param: name, current (URL gambar saat ini / null), required (bool), hint (opsional). --}}
<label class="gd-upload {{ ! empty($current) ? 'has-image' : '' }}" data-gd-upload>
    <img src="{{ $current ?? '' }}" alt="" data-gd-upload-preview @if (empty($current)) hidden @endif onerror="this.hidden=true;this.parentNode.classList.remove('has-image');this.parentNode.querySelector('.gd-upload__empty').hidden=false">
    <span class="gd-upload__empty" @if (! empty($current)) hidden @endif>
        <i class="mdi mdi-cloud-upload"></i>
        <strong>Pilih gambar</strong>
        <small>{{ $hint ?? 'JPG / PNG / WEBP, maks. 5 MB, rasio lanskap disarankan' }}</small>
    </span>
    <span class="gd-upload__change"><i class="mdi mdi-camera"></i> Ganti gambar</span>
    <input type="file" name="{{ $name }}" accept="{{ $accept ?? '.jpg,.jpeg,.png,.webp' }}" class="gd-upload__input" @if (! empty($required) && empty($current)) required @endif>
</label>
<p class="gd-hint mb-0" data-gd-upload-name>{{ ! empty($current) ? 'Biarkan kosong untuk tetap memakai gambar saat ini.' : '' }}</p>
{!! $errors->first($name, '<p class="gd-error">:message</p>') !!}
