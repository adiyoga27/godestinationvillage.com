@if ($errors->any())
    <div class="gd-form-alert">
        <i class="mdi mdi-alert-circle"></i>
        <div><strong>Periksa kembali isian Anda.</strong> {{ $errors->count() }} kolom perlu diperbaiki.</div>
    </div>
@endif
