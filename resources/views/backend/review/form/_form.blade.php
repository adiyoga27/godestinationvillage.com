<div class="row">
  <div class="col-md-12">
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Invoice</label>
          <div class="col-sm-9">
            {!! Form::text('inv', null, ['class'=>'form-control']) !!}
            {!! $errors->first('inv', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Rating  (*)</label>
          <div class="col-sm-9">
              <div class="starrating risingstar d-flex flex-row-reverse" style="float: left">
                  {!! Form::radio('rating', 5, null, ['id' => 'star5']) !!}<label for="star5" title="5 star"></label>
                  {!! Form::radio('rating', 4, null, ['id' => 'star4']) !!}<label for="star4" title="4 star"></label>
                  {!! Form::radio('rating', 3, null, ['id' => 'star3']) !!}<label for="star3" title="3 star"></label>
                  {!! Form::radio('rating', 2, null, ['id' => 'star2']) !!}<label for="star2" title="2 star"></label>
                  {!! Form::radio('rating', 1, null, ['id' => 'star1']) !!}<label for="star1" title="1 star"></label>
              </div>
          </div>
      </div>
      

        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Pekerjaan</label>
          <div class="col-sm-9">
            {!! Form::text('job', null, ['class'=>'form-control']) !!}
            {!! $errors->first('job', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Nama</label>
          <div class="col-sm-9">
            {!! Form::text('name', null, ['class'=>'form-control', 'required'=>'required']) !!}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Comment</label>
          <div class="col-sm-9">
            {!! Form::textarea('comment', null, ['class'=>'form-control', 'required'=>'required']) !!}
            {!! $errors->first('comment', '<p class="text-danger">:message</p>') !!}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-3 col-form-label">Photo</label>
          <div class="col-sm-9">
              <input type="file" name="avatar" class="form-control">
              {!! $errors->first('avatar', '<p class="text-danger">:message</p>') !!}
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
