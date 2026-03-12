<div class="row">
  <div class="col-md-12">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Judul Artikel (*)</label>
          <div class="col-sm-9">
            {!! Form::text('post_title', null, ['class'=>'form-control', ]) !!}
          {!! $errors->first('post_title', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Isi Artikel (*)</label>
          <div class="col-sm-9">
            {!! Form::textarea('post_content', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('post_content', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
    
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Thumbnail (*)</label>
          <div class="col-sm-9">
            {!! Form::file('post_thumbnail', null, ['class'=>'form-control', ]) !!}
        	{!! $errors->first('post_thumbnail', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Status (*)</label>
          <div class="col-sm-4">
            <div class="form-check">
              <label class="form-check-label">
                <input type="radio" class="form-check-input" name="isPublished" value="1" 
                    @if(empty($blog))
                        checked=""
                    @else
                        @if($blog->isPublished == 1)
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
                <input type="radio" class="form-check-input" name="isPublished" value="0"
                    @if(!empty($blog))
                        @if($blog->isPublished == 0)
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
