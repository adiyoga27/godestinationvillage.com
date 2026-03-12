<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu">
	@php $file_name = ''; @endphp
	@foreach($images as $image)
		@php $file_name = $image; @endphp
	@endforeach
	<a class="dropdown-item" href="#" onclick="return put_id({{ $model->id }})" data-toggle="modal" data-target="#uploadImage">Upload Image</a>
    @foreach($url as $key => $data)
        <a class="dropdown-item" href="{{ $data }}/{{ $file_name }}" target="_blank">{{ $key }}</a>
    @endforeach
</div>

<!-- The Modal -->
<div class="modal" id="uploadImage">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Upload Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      {!! Form::open(['url' => $form_upload, 'method' => 'post', 'files'=>true]) !!}
	      <!-- Modal body -->
	      <div class="modal-body">
	        <div class="row">
  				<div class="col-md-12">
  					<input type="hidden" name="id" id="id">
  					{{-- <div class="row"> --}}
			          <input type="file" name="image" class="form-control">
			        {{-- </div> --}}
			    </div>
			</div>
	      </div>

	      <!-- Modal footer -->
	      <div class="modal-footer">
	      	<button type="submit" class="btn btn-gradient-danger">Upload</button>
	      </div>
      {!! Form::close() !!}

    </div>
  </div>
</div>

<script type="text/javascript">
function put_id(id)
{
	$('#id').val(id);
}
</script>