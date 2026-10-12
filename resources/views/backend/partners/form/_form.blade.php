<div class="row">
  <div class="col-md-12">
      <div class="alert alert-info">Partner tampil di halaman <a href="{{ url('/our-partner') }}" target="_blank">Our Partners</a>, diurutkan berdasarkan kolom Urutan.</div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama (English) (*)</label>
        <div class="col-sm-9">
          {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191']) !!}
          {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Nama (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::text('name_id', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan nama English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Logo {{ isset($partner) ? '' : '(*)' }}</label>
        <div class="col-sm-9">
          @if (isset($partner) && $partner->logo_url)
            <div class="mb-2">
              <img src="{{ $partner->logo_url }}" style="width:140px;height:140px;object-fit:contain;border-radius:10px;border:1px solid #e3e3e3;background:#fff;" onerror="this.style.display='none'">
              <br><small class="form-text text-muted">Logo saat ini (biarkan kosong untuk mempertahankan)</small>
            </div>
          @endif
          <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="form-control" {{ isset($partner) ? '' : 'required' }}>
          {!! $errors->first('logo', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">PNG/JPG/WEBP, maksimal 5 MB. Disarankan PNG latar transparan.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi (English)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => '3', 'maxlength' => '1000']) !!}
          {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc_id', null, ['class' => 'form-control', 'rows' => '3', 'maxlength' => '1000']) !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Website</label>
        <div class="col-sm-9">
          {!! Form::text('url', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: https://www.unud.ac.id']) !!}
          {!! $errors->first('url', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Opsional. Terisi = kartu partner bisa diklik (buka tab baru).</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Urutan Tampil</label>
        <div class="col-sm-9">
          {!! Form::number('sort_order', null, ['class' => 'form-control', 'style' => 'max-width:160px;']) !!}
          <small class="form-text text-muted">Kosongkan saat tambah = taruh paling akhir.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tampilkan?</label>
        <div class="col-sm-9">
          <div class="form-check mt-2">
            {!! Form::checkbox('is_active', 1, isset($partner) ? null : true, ['class' => 'form-check-input', 'id' => 'is_active']) !!}
            <label class="form-check-label" for="is_active">Ya, tampilkan partner ini</label>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
          <a href="{{ route('partners.index') }}" class="btn btn-lg btn-light mb-2">Kembali</a>
        </div>
      </div>
  </div>
</div>
