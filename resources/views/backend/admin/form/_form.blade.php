@php
  $isEdit   = !empty($admin);
  $isActive = old('is_active', $isEdit ? $admin->is_active : 1);
  $val      = fn ($key, $default = '') => old($key, $isEdit ? ($admin->{$key} ?? $default) : $default);
@endphp

<input type="hidden" name="role_id" value="1">

@if ($errors->any())
  <div class="alert alert-danger">
    <strong>Data belum bisa disimpan.</strong> Periksa isian yang ditandai merah di bawah.
  </div>
@endif

<div class="row">
  {{-- ===== Kolom kiri: data akun ===== --}}
  <div class="col-lg-7">
    <h5 class="mb-3">Informasi Akun</h5>

    <div class="form-group">
      <label for="name">Nama <span class="text-danger">*</span></label>
      <input type="text" id="name" name="name" value="{{ $val('name') }}"
             class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
      @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="form-row">
      <div class="form-group col-md-7">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="email" id="email" name="email" value="{{ $val('email') }}" autocomplete="off"
               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        <small class="form-text text-muted">Dipakai untuk login. Harus berbeda dari akun member/village.</small>
      </div>
      <div class="form-group col-md-5">
        <label for="phone">Telepon <span class="text-danger">*</span></label>
        <input type="text" id="phone" name="phone" value="{{ $val('phone') }}" autocomplete="off"
               class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" required>
        @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-5">
        <label for="country">Negara</label>
        <input type="text" id="country" name="country" value="{{ $val('country') }}" class="form-control">
      </div>
      <div class="form-group col-md-7">
        <label for="address">Alamat</label>
        <textarea id="address" name="address" rows="2" class="form-control">{{ $val('address') }}</textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="d-block">Status Akun</label>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-outline-success {{ $isActive == 1 ? 'active' : '' }}">
          <input type="radio" name="is_active" value="1" {{ $isActive == 1 ? 'checked' : '' }}> Aktif
        </label>
        <label class="btn btn-outline-secondary {{ $isActive == 1 ? '' : 'active' }}">
          <input type="radio" name="is_active" value="0" {{ $isActive == 1 ? '' : 'checked' }}> Tidak Aktif
        </label>
      </div>
      <small class="form-text text-muted">Akun tidak aktif tidak bisa login ke halaman administrator.</small>
    </div>
  </div>

  {{-- ===== Kolom kanan: password & foto ===== --}}
  <div class="col-lg-5">
    <h5 class="mb-3">Password</h5>

    @if ($isEdit)
      <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" class="custom-control-input" id="change_password"
               {{ $errors->has('password') ? 'checked' : '' }}>
        <label class="custom-control-label" for="change_password">Ganti password akun ini</label>
      </div>
      <p class="text-muted small" id="password_hint">Password tidak diubah. Centang di atas hanya jika ingin menggantinya.</p>
    @endif

    <div id="password_fields" style="{{ $isEdit && !$errors->has('password') ? 'display:none' : '' }}">
      <div class="form-group">
        <label for="password">{{ $isEdit ? 'Password Baru' : 'Password' }} @unless($isEdit)<span class="text-danger">*</span>@endunless</label>
        <div class="input-group">
          <input type="password" id="password" name="password" autocomplete="new-password" minlength="6"
                 class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                 {{ $isEdit ? 'disabled' : 'required' }}>
          <div class="input-group-append">
            <button type="button" class="btn btn-outline-secondary js-toggle-pass" tabindex="-1">Lihat</button>
          </div>
        </div>
        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        <small class="form-text text-muted">Minimal 6 karakter.</small>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Ulangi Password {!! $isEdit ? '' : '<span class="text-danger">*</span>' !!}</label>
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
               class="form-control" {{ $isEdit ? 'disabled' : 'required' }}>
      </div>
    </div>

    <h5 class="mt-4 mb-3">Foto</h5>
    @if ($isEdit && !empty($admin->avatar))
      <p class="small text-muted mb-1">Foto saat ini: {{ $admin->avatar }}</p>
    @endif
    <div class="form-group">
      <input type="file" name="avatar" accept="image/*" class="form-control-file">
      @error('avatar')<div class="text-danger small">{{ $message }}</div>@enderror
      <small class="form-text text-muted">Opsional. Kosongkan jika tidak ingin mengubah foto.</small>
    </div>
  </div>
</div>

<hr>
<div class="d-flex justify-content-end">
  <a href="{{ url('administrator/user-admin') }}" class="btn btn-light mr-2">Batal</a>
  <button type="submit" class="btn btn-gradient-danger">Simpan</button>
</div>

<script>
  (function () {
    var check = document.getElementById('change_password');
    var pass  = document.getElementById('password');
    var conf  = document.getElementById('password_confirmation');

    function sync() {
      var on = check.checked;
      document.getElementById('password_fields').style.display = on ? '' : 'none';
      // field disabled tidak dikirim dan tidak diisi autofill browser
      pass.disabled = conf.disabled = !on;
      pass.required = conf.required = on;
      document.getElementById('password_hint').style.display = on ? 'none' : '';
      if (on) pass.focus();
    }
    if (check) {
      check.addEventListener('change', sync);
      if (check.checked) { pass.disabled = conf.disabled = false; pass.required = conf.required = true; }
    }

    document.querySelector('.js-toggle-pass').addEventListener('click', function () {
      var show = pass.type === 'password';
      pass.type = conf.type = show ? 'text' : 'password';
      this.textContent = show ? 'Sembunyikan' : 'Lihat';
    });
  })();
</script>
