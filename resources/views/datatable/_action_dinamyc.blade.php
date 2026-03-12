{!! Form::model($model, ['url' => $delete, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message, 'onSubmit' => 'event.preventDefault(); return confirmDelete(this)'] ) !!}
<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-table-el="{{ empty($tableEl) ? '#dataTableBuilder' : $tableEl }}" data-padding="{{ empty($padding) ? '65px' : $padding }}" onclick="addPadding(this)">Action</button>
<div class="dropdown-menu">
    @foreach($url as $key => $data)
        <a class="dropdown-item" href="{{ $data }}">{{ $key }}</a>
    @endforeach
  <div role="separator" class="dropdown-divider"></div>
  {!! Form::submit('Delete', ['class' => 'dropdown-item']) !!}
</div>
{!! Form::close()!!}