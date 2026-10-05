<div class="row">
  <div class="col-md-12">
      <div class="alert alert-info">Kartu kecil di section Why GODEVI (4 kartu: Socially Responsible, dst). Gambar ikon opsional — kosong = ikon centang bawaan.</div>

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
        <label class="col-sm-3 col-form-label">Deskripsi (English)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => '3']) !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc_id', null, ['class' => 'form-control', 'rows' => '3']) !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Gambar Ikon</label>
        <div class="col-sm-9">
          @if (isset($feature) && $feature->image)
            <div class="mb-2">
              <img src="{{ \App\Helpers\Homepage::aboutFeatureImage($feature->image) }}" style="width:90px;height:90px;object-fit:contain;border-radius:10px;border:1px solid #e3e3e3;" onerror="this.style.display='none'">
              <div class="form-check mt-1">
                <input type="checkbox" name="remove_image" value="1" class="form-check-input" id="remove_image">
                <label class="form-check-label" for="remove_image">Hapus gambar (kembali ke ikon bawaan)</label>
              </div>
            </div>
          @endif
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control">
          {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Opsional. PNG/JPG maks 10 MB.</small>
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
            {!! Form::checkbox('is_active', 1, isset($feature) ? null : true, ['class' => 'form-check-input', 'id' => 'is_active']) !!}
            <label class="form-check-label" for="is_active">Ya, tampilkan kartu ini</label>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
          <a href="{{ route('homepage-about-features.index') }}" class="btn btn-lg btn-light mb-2">Kembali</a>
        </div>
      </div>
  </div>
</div>
