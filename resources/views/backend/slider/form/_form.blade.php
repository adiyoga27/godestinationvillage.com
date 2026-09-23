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

      <hr>
      <div class="alert alert-info">Tombol untuk slide ini (1 tombol). Kosongkan nama tombol jika tidak ingin menampilkan tombol di slide ini. Warna custom format hex (cth: #EA580C) — kosongkan untuk memakai gaya default.</div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tombol — Nama (EN)</label>
        <div class="col-sm-9">
          {!! Form::text('button_label', null, ['class'=>'form-control', 'maxlength'=>'191', 'placeholder'=>'cth: Explore Villages']) !!}
          {!! $errors->first('button_label', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tombol — Nama (ID)</label>
        <div class="col-sm-9">
          {!! Form::text('button_label_id', null, ['class'=>'form-control', 'maxlength'=>'191', 'placeholder'=>'cth: Jelajahi Desa']) !!}
          {!! $errors->first('button_label_id', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan English.</small>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tombol — Link Tujuan</label>
        <div class="col-sm-9">
          {!! Form::text('button_url', null, ['class'=>'form-control', 'maxlength'=>'500', 'placeholder'=>'cth: village / tour-packages / https://...']) !!}
          {!! $errors->first('button_url', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Bisa path internal (tanpa slash depan) atau URL penuh https://...</small>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tombol — Warna</label>
        <div class="col-sm-9">
          <div class="d-flex align-items-center" style="gap:10px;">
            <input type="color" id="button_color_picker" value="{{ old('button_color', isset($slider) && $slider->button_color ? $slider->button_color : '#EA580C') }}" style="width:48px;height:38px;padding:2px;border:1px solid #ddd;border-radius:6px;">
            {!! Form::text('button_color', null, ['class'=>'form-control', 'id'=>'button_color', 'maxlength'=>'20', 'placeholder'=>'#EA580C (kosongkan = gaya default)', 'style'=>'max-width:280px;']) !!}
            <button type="button" class="btn btn-sm btn-light" id="button_color_clear">Default</button>
          </div>
          {!! $errors->first('button_color', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
        </div>
      </div>

<script>
(function () {
    function bindColor(textId, pickerId, clearId) {
        var text = document.getElementById(textId);
        var picker = document.getElementById(pickerId);
        var clearBtn = document.getElementById(clearId);
        if (!text || !picker) return;
        picker.addEventListener('input', function () { text.value = picker.value.toUpperCase(); });
        text.addEventListener('input', function () {
            var v = text.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(v)) picker.value = v;
        });
        if (clearBtn) clearBtn.addEventListener('click', function () { text.value = ''; });
    }
    bindColor('button_color', 'button_color_picker', 'button_color_clear');
})();
</script>
  </div>
</div>