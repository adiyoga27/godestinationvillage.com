<div class="col-md-6">
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Nama Desa Wisata (*)</label>
    <div class="col-sm-9">
      {!! Form::text('village_name', null, ['class'=>'form-control', 'required'=>'required']) !!}
      {!! $errors->first('village_name', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Alamat Desa Wisata (*)</label>
    <div class="col-sm-9">
      {!! Form::text('village_address', null, ['class'=>'form-control', 'id'=>'village_address', 'required'=>'required']) !!}
      {!! $errors->first('village_address', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  <div id="map" style="width: 100%; height: 300px;">
  </div>
  {!! Form::hidden('lat', null, ['id' => 'lat']) !!}
  {!! Form::hidden('lng', null, ['id' => 'lng']) !!}
  <br /><br />

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Contact Person (*)</label>
    <div class="col-sm-9">
      {!! Form::textarea('contact_person', null, ['class'=>'form-control']) !!}
      {!! $errors->first('contact_person', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Deskripsi (*)</label>
    <div class="col-sm-9">
      {!! Form::textarea('desc', null, ['class'=>'form-control']) !!}
      {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>
</div>