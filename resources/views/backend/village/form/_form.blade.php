<div class="row">
  <div class="col-md-6">
    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nama Pengguna (*)</label>
      <div class="col-sm-9">
      	{!! Form::text('name', null, ['class'=>'form-control', 'required'=>'required']) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <input type="hidden" name="role_id" value="2">

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Email (*)</label>
      <div class="col-sm-9">
        {!! Form::email('email', null, ['class'=>'form-control', 'required'=>'required']) !!}
    	{!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Password @if(empty($village))(*)@endif</label>
      <div class="col-sm-9">
        {!! Form::password('password', ['class'=>'form-control']) !!}
    	{!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Konfrimasi Password @if(empty($village))(*)@endif</label>
      <div class="col-sm-9">
        {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
        @if(!empty($village))<small>* Konfirmasi Password harus diisi ketika kolom password terisi</small>@endif
     	{!! $errors->first('password_confirmation', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Telepon (*)</label>
      <div class="col-sm-9">
        {!! Form::text('phone', null, ['class'=>'form-control', 'required'=>'required']) !!}
        {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    @if(empty($village))
        @include('backend.village.form._form_create_bank')
    @else
        @include('backend.village.form._form_edit_bank')
    @endif

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Foto</label>
      <div class="col-sm-9">
        {!! Form::file('avatar', null, ['class'=>'form-control']) !!}
        {!! $errors->first('avatar', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Negara</label>
      <div class="col-sm-9">
        {!! Form::text('country', null, ['class'=>'form-control']) !!}
        {!! $errors->first('country', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>
    
    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Alamat</label>
      <div class="col-sm-9">
        {!! Form::textarea('address', null, ['class'=>'form-control']) !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Status (*)</label>
      <div class="col-sm-4">
        <div class="form-check">
          <label class="form-check-label">
            <input type="radio" class="form-check-input" name="is_active" value="1" 
                @if(empty($village))
                    checked=""
                @else
                    @if($village->is_active == 1)
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
                @if(!empty($village))
                    @if($village->is_active == 0)
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

  @if(empty($village))
        @include('backend.village.form._form_create_village')
    @else
        @include('backend.village.form._form_edit_village')
    @endif
</div>
