

<div class="row">
    <div class="col-md-12">
        @if (Auth::user()->role_id == 1)
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Desa Wisata (*)</label>
                <div class="col-sm-9">
                    {!! Form::select('user_id', $villages, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                    {!! $errors->first('user_id', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
        @else
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
        @endif

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Kategori Paket (*)</label>
            <div class="col-sm-9">
                {!! Form::select('category_id', $categories, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Tag Paket (*)</label>
            <div class="col-sm-9">
                {!! Form::select('tag_id', $tags, null, ['class' => 'selectpicker', 'required' => 'required', 'data-live-search' => 'true']) !!}
                {!! $errors->first('tag_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nama Paket (*)</label>
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
            <label class="col-sm-3 col-form-label">Diskon Paket (*)</label>
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
            <label class="col-sm-3 col-form-label">Default Image</label>
            <div class="col-sm-9">
                <input type="file" name="default_img" class="form-control">
                {!! $errors->first('default_img', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        {{-- <div class="form-group row">
            <label class="col-sm-3 col-form-label">Other Images</label>
            <div class="col-sm-9">
                <input type="file" name="other_img[]" class="form-control" multiple="true">
                {!! $errors->first('other_img', '<p class="text-danger">:message</p>') !!}
            </div>
        </div> --}}

        {{-- <div class="form-group row">
            <label class="col-sm-3 col-form-label">Review</label>
            <div class="col-sm-9">
                <div class="starrating risingstar d-flex flex-row-reverse" style="float: left">
                    {!! Form::radio('review', 5, null, ['id' => 'star5']) !!}<label for="star5" title="5 star"></label>
                    {!! Form::radio('review', 4, null, ['id' => 'star4']) !!}<label for="star4" title="4 star"></label>
                    {!! Form::radio('review', 3, null, ['id' => 'star3']) !!}<label for="star3" title="3 star"></label>
                    {!! Form::radio('review', 2, null, ['id' => 'star2']) !!}<label for="star2" title="2 star"></label>
                    {!! Form::radio('review', 1, null, ['id' => 'star1']) !!}<label for="star1" title="1 star"></label>
                </div>
            </div>
        </div> --}}

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Deskripsi (*)</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('desc', null, ['class' => 'form-control']) !!}
                {!! $errors->first('desc', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('desc_id', $packageTranslate->desc ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('desc_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        {{-- <div class="form-group row">
          <label class="col-sm-3 col-form-label">Review</label>
          <div class="col-sm-9">
            {!! Form::textarea('review', null, ['class'=>'form-control']) !!}
            {!! $errors->first('review', '<p class="text-danger">:message</p>') !!}
          </div>
        </div> --}}

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Itenaries</label>
            <div class="col-sm-9">
                <label>English : </label>
                {!! Form::textarea('itenaries', null, ['class' => 'form-control']) !!}
                {!! $errors->first('itenaries', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>
                {!! Form::textarea('itenaries_id', $packageTranslate->itenaries ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('itenaries_id', '<p class="text-danger">:message</p>') !!}
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

        {{-- <div class="form-group row">
            <label class="col-sm-3 col-form-label">Exclusion</label>
            <div class="col-sm-9">
                <label>English : </label>

                {!! Form::textarea('exclusion', null, ['class' => 'form-control']) !!}
                {!! $errors->first('exclusion', '<p class="text-danger">:message</p>') !!}
            </div>
        </div> --}}

        {{-- <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('exclusion_id', $packageTranslate->exclusion ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('exclusion_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div> --}}

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Term & Condition</label>
            <div class="col-sm-9">
                <label>English : </label>

                {!! Form::textarea('term', null, ['class' => 'form-control']) !!}
                {!! $errors->first('term', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('term_id', $packageTranslate->term ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('term_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Durasi</label>
            <div class="col-sm-9">
                <label>English : </label>

                {!! Form::textarea('duration', null, ['class' => 'form-control']) !!}
                {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('duration_id', $packageTranslate->duration ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('duration_id', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Perisapan Yang Diperlukan</label>
            <div class="col-sm-9">
                <label>English : </label>

                {!! Form::textarea('preparation', null, ['class' => 'form-control']) !!}
                {!! $errors->first('preparation', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <label>Indonesia : </label>

                {!! Form::textarea('preparation_id', $packageTranslate->preparation ?? null, ['class' => 'form-control']) !!}
                {!! $errors->first('preparation_id', '<p class="text-danger">:message</p>') !!}
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
