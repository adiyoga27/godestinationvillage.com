<div class="row">
  <div class="col-md-12">
    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Nama (*)</label>
      <div class="col-sm-9">
      	{!! Form::text('name', null, ['class'=>'form-control', 'required'=>'required']) !!}
        {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Email (*)</label>
      <div class="col-sm-9">
        {!! Form::email('email', null, ['class'=>'form-control', 'required'=>'required']) !!}
    	{!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Password</label>
      <div class="col-sm-9">
        {!! Form::password('password', ['class'=>'form-control']) !!}
    	{!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-3 col-form-label">Konfrimasi Password</label>
      <div class="col-sm-9">
        {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
        <small>* Konfirmasi Password harus diisi ketika kolom password terisi</small>
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
  </div>
</div>
<div class="float-right">
    <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save Profile</button>
</div>