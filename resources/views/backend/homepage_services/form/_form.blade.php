<div class="row">
  <div class="col-md-12">
      <div class="alert alert-info">Item layanan di section Our Services. Gambar bisa diganti lewat upload.</div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Judul (English) (*)</label>
        <div class="col-sm-9">
          {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '191']) !!}
          {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Judul (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::text('title_id', null, ['class' => 'form-control', 'maxlength' => '191']) !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan judul English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Gambar {{ isset($service) ? '' : '(*)' }}</label>
        <div class="col-sm-9">
          @if (isset($service) && $service->image)
            <div class="mb-2">
              <img src="{{ \App\Helpers\Homepage::serviceImage($service->image) }}" style="width:140px;height:140px;object-fit:contain;border-radius:10px;border:1px solid #e3e3e3;" onerror="this.style.display='none'">
              <br><small class="form-text text-muted">Gambar saat ini (biarkan kosong untuk mempertahankan)</small>
            </div>
          @endif
          <input type="file" name="image" accept="image/*" class="form-control" {{ isset($service) ? '' : 'required' }}>
          {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Format gambar (PNG/JPG), maksimal 10 MB. Tampil proporsional.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Tujuan</label>
        <div class="col-sm-9">
          {!! Form::text('url', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: services']) !!}
          <small class="form-text text-muted">Path internal (cth: services) atau URL penuh.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Urutan Tampil</label>
        <div class="col-sm-9">
          {!! Form::number('sort_order', null, ['class' => 'form-control', 'style' => 'max-width:160px;']) !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tampilkan?</label>
        <div class="col-sm-9">
          <div class="form-check mt-2">
            {!! Form::checkbox('is_active', 1, isset($service) ? null : true, ['class' => 'form-check-input', 'id' => 'is_active']) !!}
            <label class="form-check-label" for="is_active">Ya, tampilkan item ini</label>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
          <a href="{{ route('homepage-services.index') }}" class="btn btn-lg btn-light mb-2">Kembali</a>
        </div>
      </div>
  </div>
</div>
