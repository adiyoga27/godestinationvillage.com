<div class="row">
    <div class="col-md-12">



        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Name (*)</label>
            <div class="col-sm-9">
                {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Title (*)</label>
            <div class="col-sm-9">
                {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
                {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
     


        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Deskripsi (*)</label>
            <div class="col-sm-9">
                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Phone</label>
            <div class="col-sm-9">
                {!! Form::text('phone', null, ['class' => 'form-control', ]) !!}
                {!! $errors->first('phone', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Whatsapp</label>
            <div class="col-sm-9">
                {!! Form::text('whatsapp', null, ['class' => 'form-control']) !!}
                {!! $errors->first('whatsapp', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Instagram</label>
            <div class="col-sm-9">
                {!! Form::text('instagram', null, ['class' => 'form-control']) !!}
                {!! $errors->first('instagram', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Facebook</label>
            <div class="col-sm-9">
                {!! Form::text('facebook', null, ['class' => 'form-control']) !!}
                {!! $errors->first('facebook', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Twitter</label>
            <div class="col-sm-9">
                {!! Form::text('twitter', null, ['class' => 'form-control']) !!}
                {!! $errors->first('twitter', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Linkedin</label>
            <div class="col-sm-9">
                {!! Form::text('linkedin', null, ['class' => 'form-control']) !!}
                {!! $errors->first('linkedin', '<p class="text-danger">:message</p>') !!}
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
            <label class="col-sm-3 col-form-label"></label>
            <div class="col-sm-9">
                <button type="submit" class="btn btn-lg btn-gradient-danger mb-2">Save</button>
            </div>
        </div>
    </div>
</div>
