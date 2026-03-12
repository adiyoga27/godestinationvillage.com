@php
   $public = env('PUBLIC_VARIABLE_URL', "");
@endphp

{!! Form::model($model, ['url' => $delete, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message, 'onSubmit' => 'event.preventDefault(); return confirmDelete(this)'] ) !!}
    <div class="btn-group btn-group--dart">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-table-el="{{ empty($tableEl) ? '#dataTableBuilder' : $tableEl }}" data-padding="{{ empty($padding) ? '65px' : $padding }}" onclick="addPadding(this)">
            Action <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            {{-- custom actions --}}
            @foreach($url as $key => $data)
                <li><a href="{{ $data }}">{{ $key }}</a></li>
            @endforeach

            @foreach($additional as $key => $data)
                <li><a href="#" onclick="return confirmStatus('Apakah Anda Yakin?', '{{ $data }}')">{{ $key }}</a></li>
            @endforeach

            @foreach($post_link as $key => $data)
                <li><a href="#" onclick="return notValidate('Apakah Anda Yakin?', '{{ $data }}')">{{ $key }}</a></li>
            @endforeach

            {{-- delete --}}
            <li>
                {!! Form::submit('Hapus', ['class' => 'btn btn-danger']) !!}
            </li>
        </ul>
        {{-- @lang('datatable.dropdown.delete') --}}
    </div>
{!! Form::close()!!}