<div class="col-md-6">
  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Nama Desa Wisata (*)</label>
    <div class="col-sm-9">
      {!! Form::text('village_name', $village->village_detail->village_name, ['class'=>'form-control', 'required'=>'required']) !!}
      {!! $errors->first('village_name', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Alamat Desa Wisata (*)</label>
    <div class="col-sm-9">
      {!! Form::text('village_address', $village->village_detail->village_address, ['class'=>'form-control', 'id'=>'village_address', 'required'=>'required']) !!}
      {!! $errors->first('village_address', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  {{-- <div id="map" style="width: 100%; height: 300px;">
  </div> --}}
  {!! Form::hidden('lat', $village->village_detail->lat, ['id' => 'lat']) !!}
  {!! Form::hidden('lng', $village->village_detail->lng, ['id' => 'lng']) !!}
  {{-- <br /><br /> --}}

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Contact Person (*)</label>
    <div class="col-sm-9">
      {!! Form::textarea('contact_person', $village->village_detail->contact_person, ['class'=>'form-control']) !!}
      {!! $errors->first('contact_person', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>

  <div class="form-group row">
    <label class="col-sm-3 col-form-label">Deskripsi (*)</label>
    <div class="col-sm-9">
      {!! Form::textarea('desc', $village->village_detail->desc, ['class'=>'form-control']) !!}
      {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
    </div>
  </div>
</div>