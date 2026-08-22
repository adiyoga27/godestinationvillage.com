<div class="row">
  <div class="col-md-12">
      <div class="alert alert-info">Konten hero slider di halaman utama. Isi judul & deskripsi dalam bahasa Inggris dan Indonesia.</div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Judul (English) (*)</label>
        <div class="col-sm-9">
          {!! Form::text('title', null, ['class'=>'form-control', 'required'=>'required', 'maxlength'=>'50']) !!}
          {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi (English)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc', null, ['class'=>'form-control', 'rows'=>'3']) !!}
          {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Judul (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::text('title_id', null, ['class'=>'form-control', 'maxlength'=>'50']) !!}
          {!! $errors->first('title_id', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan judul English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc_id', null, ['class'=>'form-control', 'rows'=>'3']) !!}
          {!! $errors->first('desc_id', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Gambar Slide (*)</label>
        <div class="col-sm-9">
          @if(isset($slider) && $slider->img)
            <div class="mb-2">
              <img src="{{ asset('storage/sliders/' . $slider->img) }}" style="width:280px;height:140px;object-fit:cover;border-radius:10px;border:1px solid #e3e3e3;" onerror="this.style.display='none'">
              <br><small class="form-text text-muted">Gambar saat ini: {{ $slider->img }} (biarkan kosong untuk mempertahankan)</small>
            </div>
          @endif
          <input type="file" name="img" accept="image/*" class="form-control" {{ isset($slider) ? '' : 'required' }}>
          {!! $errors->first('img', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Rekomendasi rasio lebar:tinggi 16:9 atau 3:2, maksimal 10 MB.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
        </div>
      </div>
  </div>
</div>