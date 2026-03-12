<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
<div class="dropdown-menu">
    @if(!empty($url_blank))
        @foreach($url_blank as $key => $data)
            <li><a class="dropdown-item" href="{{ $data }}" target="_blank">{{ $key }}</a></li>
        @endforeach
    @endif
    @foreach($url as $key => $data)
        <a class="dropdown-item" href="{{ $data }}">{{ $key }}</a>
    @endforeach
</div>