@php
   $public = env('PUBLIC_VARIABLE_URL', "");
@endphp

{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message] ) !!}
	<a href="{{ $show_url }}" title="@lang('common.title_show')">
        <img class="icon" src="{{ asset($public.'icons/show.svg') }}">
    </a>
    <a href="{{ $edit_url }}" title="@lang('common.title_edit')">
        <img class="icon" src="{{ asset($public.'icons/edit.svg') }}">
    </a>
    <button title="@lang('common.title_delete')" type="submit" class="btn-link">
        <img class="icon" src="{{ asset($public.'icons/delete.svg') }}">
    </button>
    <!-- {!! Form::submit('Delete', ['class'=>'btn btn-danger']) !!}  -->
{!! Form::close()!!}
