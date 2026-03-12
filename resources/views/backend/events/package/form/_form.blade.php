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
            <label class="col-sm-3 col-form-label">Kategori Event (*)</label>
            <div class="col-sm-9">
                {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>



        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Event (*)</label>
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
            <label class="col-sm-3 col-form-label">Location (*)</label>
            <div class="col-sm-9">
                {!! Form::text('location', null, ['class' => 'form-control', 'placeholder'=> 'Input lokasi event example: Badung, Mengwi Denpasar']) !!}
                {!! $errors->first('location', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tanggal (*)</label>
            <div class="col-sm-9">
                {!! Form::input('dateTime-local','date_event', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('date_event', '<p class="text-danger">:message</p>') !!}
                <p style="font-size: 8pt; color:grey">Tekan gambar/icon calendar untuk mempermudah memilih tanggal</p>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Durasi (*)</label>
            <div class="col-sm-9">
                {!! Form::text('duration', null, ['class' => 'form-control', 'placeholder'=> 'Input durasi event, example: 5 Hours']) !!}
                {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Itenaries</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('interary', null, ['class' => 'form-control']) !!}
                {!! $errors->first('interary', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('interary_id', $packageTranslate->itenaries ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('interary_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Inclusion</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('inclusion', null, ['class' => 'form-control']) !!}
                {!! $errors->first('inclusion', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('inclusion_id', $packageTranslate->inclusion ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('inclusion_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>




        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Additional</label>
            <div class="col-sm-9">
                <label>English : </label>

                {!! Form::textarea('additional', null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('additional_id', $packageTranslate->term ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('additional_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>


        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Default Image</label>
            <div class="col-sm-9">
                <input type="file" name="default_img" class="form-control">
                {!! $errors->first('default_img', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>



        @if (Auth::user()->role_id == 1) 
        {{-- <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status Pay Wish(*)</label>
            <div class="col-sm-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_paywish" value="1" @if (empty($package)) checked=""
                    @else
                        @if ($package->is_paywish == 1)
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
                        <input type="radio" class="form-check-input" name="is_paywish" value="0" @if (!empty($package))
                        @if ($package->is_paywish == 0)
                            checked="" @endif
                        @endif
                        >
                        Tidak Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
        </div> --}}
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status Free(*)</label>
            <div class="col-sm-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="is_free" value="1" @if (empty($package)) checked=""
                    @else
                        @if ($package->is_free == 1)
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
                        <input type="radio" class="form-check-input" name="is_free" value="0" @if (!empty($package))
                        @if ($package->is_free == 0)
                            checked="" @endif
                        @endif
                        >
                        Tidak Aktif
                        <i class="input-helper"></i>
                    </label>
                </div>
            </div>
        </div>
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
