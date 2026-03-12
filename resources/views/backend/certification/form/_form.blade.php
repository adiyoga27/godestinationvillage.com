<div class="row">
  <div class="col-md-12">
    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Kategori (*)</label>
      <div class="col-sm-9">
        {!! Form::text('category', null, ['class'=>'form-control', ]) !!}
      {!! $errors->first('category', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">No Surat (*)</label>
          <div class="col-sm-9">
            {!! Form::text('reference_number', null, ['class'=>'form-control', ]) !!}
          {!! $errors->first('reference_number', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Tanggal (*)</label>
          <div class="col-sm-9">
            {!! Form::date('date_at', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('date_at', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
    
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Perihal (*)</label>
          <div class="col-sm-9">
            {!! Form::text('regarding', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('regarding', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Ditunjukan Kepada (*)</label>
          <div class="col-sm-9">
            {!! Form::text('addressed_to', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('addressed_to', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Penanda Tangan (*)</label>
          <div class="col-sm-9">
            {!! Form::text('signer', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('signer', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Jabatan (*)</label>
          <div class="col-sm-9">
            {!! Form::text('departemen', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('departemen', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Dokumen (*)</label>
          <div class="col-sm-9">
            {!! Form::file('file', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('file', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        
        
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Status (*)</label>
          <div class="col-sm-4">
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="isActive" value="1" 
                    @if(empty($certificate))
                        checked=""
                    @else
                        @if($certificate->isActive == 1)
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
                <input type="radio" class="form-check-input" name="isActive" value="0"
                    @if(!empty($certificate))
                        @if($certificate->isActive == 0)
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
