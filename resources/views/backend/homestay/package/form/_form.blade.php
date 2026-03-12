<div class="row">
    <div class="col-md-12">

        @if (Auth::user()->role_id == 1) 
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Village (*)</label>
            <div class="col-sm-9">
                {!! Form::select('village_id', $villages, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                {!! $errors->first('village_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
    @endif

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kategori Homestay (*)</label>
            <div class="col-sm-9">
                {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>



        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Homestay (*)</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>

        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::text('name_id', $packageTranslate->name ?? null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('name_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>


        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Deskripsi (*)</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('description_id', $packageTranslate->description ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('description_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Location (*)</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::text('location', null, ['class' => 'form-control']) !!}
                {!! $errors->first('location', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::text('location_id', $packageTranslate->location ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('location_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Harga Paket (*)</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-danger text-white">IDR</span>
                    </div>
                    {!! Form::number('price', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('price', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Harga Diskon (*)</label>
            <div class="col-sm-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-danger text-white">IDR</span>
                    </div>
                    {!! Form::number('disc', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('disc', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Facilities</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('facilities', null, ['class' => 'form-control']) !!}
                {!! $errors->first('facilities', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('facilities_id', $packageTranslate->facilities ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('facilities_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Additional Activities</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('additional_activities', null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional_activities', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Additional Activities</label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('additional_activities_id', $packageTranslate->additional_activities ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional_activities_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Owner Name (*)</label>
            <div class="col-sm-9">
                {!! Form::text('owner_name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('owner_name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Check In Time (*)</label>
            <div class="col-sm-9">
                {!! Form::text('check_in_time', null, ['class' => 'form-control']) !!}
                {!! $errors->first('check_in_time', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Check Out Time (*)</label>
            <div class="col-sm-9">
                {!! Form::text('check_out_time', null, ['class' => 'form-control']) !!}
                {!! $errors->first('check_out_time', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Additional Notes</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('additional_notes',  null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional_notes', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('additional_notes_id', $packageTranslate->additional_notes ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional_notes_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Default Image</label>
            <div class="col-sm-9">
                <input type="file" name="default_img" class="form-control">
                {!! $errors->first('default_img', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>


        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Breakfast(*)</label>
            <div class="col-sm-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_breakfast" value="1" @if (empty($package)) checked=""
                    @else
                        @if ($package->is_breakfast == 1)
                            checked="" @endif
                        @endif
                        >
                        Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_breakfast" value="0" @if (!empty($package))
                        @if ($package->is_breakfast == 0)
                            checked="" @endif
                        @endif
                        >
                        Tidak Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
        </div>
        @if (Auth::user()->role_id == 1) 
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status (*)</label>
            <div class="col-sm-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_active" value="1" @if (empty($package)) checked=""
                    @else
                        @if ($package->is_active == 1)
                            checked="" @endif
                        @endif
                        >
                        Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_active" value="0" @if (!empty($package))
                        @if ($package->is_active == 0)
                            checked="" @endif
                        @endif
                        >
                        Tidak Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
        </div>
        @endif
      
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
            </div>
        </div>
    </div>
</div>
