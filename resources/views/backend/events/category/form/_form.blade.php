<div class="row">
  <div class="col-md-12">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Nama Kategori (*)</label>
          <div class="col-sm-9">
            {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'required']) !!}
          {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Deskripsi</label>
          <div class="col-sm-9">
            {!! Form::textarea('description', null, ['class'=>'form-control']) !!}
            {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Status (*)</label>
          <div class="col-sm-4">
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="is_active" value="1" 
                    @if(empty($category))
                        checked=""
                    @else
                        @if($category->is_active == 1)
                            checked=""
                        @endif
                    @endif
                >
                Aktif
              <i class="input-helper"></i></label>
            </div>
          </div>
          <div class="col-sm-5">
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="is_active" value="0"
                    @if(!empty($category))
                        @if($category->is_active == 0)
                            checked=""
                        @endif
                    @endif
                >
                Tidak Aktif
              <i class="input-helper"></i></label>
            </div>
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
