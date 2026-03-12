<div class="row">
    <div class="col-md-12">
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
            <label class="col-sm-3 col-form-label">Tahun</label>
            <div class="col-sm-9">
                {!! Form::text('dates', null, ['class' => 'form-control', ]) !!}
                {!! $errors->first('dates', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Attachment</label>
            <div class="col-sm-9">
                <input type="file" name="attachment" class="form-control">
                {!! $errors->first('attachment', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label">Thumbnail</label>
            <div class="col-sm-9">
                <input type="file" name="portofolio" class="form-control">
                {!! $errors->first('portofolio', '<p class="text-danger">:message</p>') !!}
                <p>Upload gambar thumbnail dengan screenshot filenya atau photo yang identik dengan portofolio tersebut ..</p>
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
