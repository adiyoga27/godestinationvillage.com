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
          <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control" {{ isset($service) ? '' : 'required' }}>
          {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Format gambar (PNG/JPG), maksimal 10 MB. Tampil proporsional.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi Modal (English)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc', null, ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Isi popup saat kartu diklik. Boleh HTML sederhana. Kosongkan = kartu jadi link.']) !!}
          <small class="form-text text-muted">Terisi = klik kartu membuka modal. Kosong = klik kartu link ke tujuan.</small>
          {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Deskripsi Modal (Indonesia)</label>
        <div class="col-sm-9">
          {!! Form::textarea('desc_id', null, ['class' => 'form-control', 'rows' => '4']) !!}
          <small class="form-text text-muted">Kosongkan jika sama dengan English.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Link Tujuan</label>
        <div class="col-sm-9">
          {!! Form::text('url', null, ['class' => 'form-control', 'maxlength' => '191', 'placeholder' => 'cth: services']) !!}
          <small class="form-text text-muted">Path internal (cth: services) atau URL penuh. Dipakai saat kartu tidak punya deskripsi modal.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Telp / WA Modal</label>
        <div class="col-sm-4">
          {!! Form::text('phone', null, ['class' => 'form-control', 'maxlength' => '50', 'placeholder' => 'cth: 081997674778']) !!}
          <small class="form-text text-muted">Tombol Call (kosong = default).</small>
        </div>
        <div class="col-sm-5">
          {!! Form::text('whatsapp', null, ['class' => 'form-control', 'maxlength' => '50', 'placeholder' => 'cth: 6281997674778']) !!}
          <small class="form-text text-muted">Tombol WhatsApp (kosong = default).</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">File Brosur</label>
        <div class="col-sm-9">
          @if (isset($service) && $service->file)
            <div class="mb-2">
              <a href="{{ \App\Helpers\Homepage::serviceFile($service->file) }}" target="_blank">{{ $service->file }}</a>
              <div class="form-check mt-1">
                <input type="checkbox" name="remove_file" value="1" class="form-check-input" id="remove_file">
                <label class="form-check-label" for="remove_file">Hapus file ini</label>
              </div>
            </div>
          @endif
          <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.webp">
          {!! $errors->first('file', '<p class="text-danger">:message</p>') !!}
          <small class="form-text text-muted">Opsional. Muncul sebagai tombol Download di modal. Maks 10 MB.</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label">Tombol Custom</label>
        <div class="col-sm-9">
          <div id="customButtons">
            @php $btnRows = isset($service) && is_array($service->buttons) ? $service->buttons : (old('buttons', [['label' => '', 'label_id' => '', 'url' => '']])) ; @endphp
            @foreach ($btnRows as $i => $b)
              <div class="row mb-2" data-btn-row>
                <div class="col-4"><input type="text" name="buttons[{{ $i }}][label]" value="{{ $b['label'] ?? '' }}" class="form-control" maxlength="191" placeholder="Label EN"></div>
                <div class="col-4"><input type="text" name="buttons[{{ $i }}][label_id]" value="{{ $b['label_id'] ?? '' }}" class="form-control" maxlength="191" placeholder="Label ID"></div>
                <div class="col-3"><input type="text" name="buttons[{{ $i }}][url]" value="{{ $b['url'] ?? '' }}" class="form-control" maxlength="500" placeholder="URL / path"></div>
                <div class="col-1"><button type="button" class="btn btn-sm btn-light" data-btn-remove title="Hapus">×</button></div>
              </div>
            @endforeach
          </div>
          <button type="button" class="btn btn-sm btn-secondary" id="btnAddRow">+ Tambah tombol</button>
          <small class="form-text text-muted d-block">Tombol bebas di modal (cth: Back, Download, Lihat Halaman). Baris kosong diabaikan.</small>
        </div>
      </div>
      <script>
        (function () {
          var wrap = document.getElementById('customButtons');
          var add = document.getElementById('btnAddRow');
          if (!wrap || !add) return;
          add.addEventListener('click', function () {
            var i = wrap.querySelectorAll('[data-btn-row]').length;
            var div = document.createElement('div');
            div.className = 'row mb-2';
            div.setAttribute('data-btn-row', '');
            div.innerHTML = '<div class="col-4"><input type="text" name="buttons[' + i + '][label]" class="form-control" maxlength="191" placeholder="Label EN"></div>'
              + '<div class="col-4"><input type="text" name="buttons[' + i + '][label_id]" class="form-control" maxlength="191" placeholder="Label ID"></div>'
              + '<div class="col-3"><input type="text" name="buttons[' + i + '][url]" class="form-control" maxlength="500" placeholder="URL / path"></div>'
              + '<div class="col-1"><button type="button" class="btn btn-sm btn-light" data-btn-remove title="Hapus">×</button></div>';
            wrap.appendChild(div);
          });
          wrap.addEventListener('click', function (e) {
            if (e.target && e.target.hasAttribute('data-btn-remove')) {
              e.target.closest('[data-btn-row]').remove();
            }
          });
        })();
      </script>

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
