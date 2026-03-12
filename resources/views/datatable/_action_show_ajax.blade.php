@php
   $public = env('PUBLIC_VARIABLE_URL', "");
@endphp

{{-- {!! Form::model($model) !!} --}}
    {{-- <a href="" title="@lang('common.title_show')" data-source="{{ $id }}">
        <img class="icon" src="{{ asset($public.'icons/show.svg') }}">
    </a> --}}
    {{-- <button title="@lang('common.title_show')" type="button" class="btn-link action-show" data-source="{{ $id }}">
        <img class="icon" src="{{ asset($public.'icons/show.svg') }}">
    </button> --}}
{{-- {!! Form::close()!!} --}}
{!! Form::model($model , ['url' => isset($delete) ? $delete : '', 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => isset($confirm_message) ? $confirm_message : '']) !!}
    <div class="btn-group btn-group--dart">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @lang('datatable.dropdown.actions') <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            {{-- custom actions --}}
            @foreach($url as $key => $data)
            <li><a href="{{ $data['link'] }}" data-source="{{ $id }}" class="{{ $data['class'] }}">{{ $key }}</a></li>
            @endforeach
            @if (isset($delete))
                <li>
                    {!! Form::submit(trans('datatable.dropdown.delete'), ['class' => 'btn btn-danger']) !!}
                </li>
            @endif
        </ul>
        {{-- @lang('datatable.dropdown.delete') --}}
    </div>
{!! Form::close()!!}
