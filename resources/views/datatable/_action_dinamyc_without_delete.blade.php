<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-table-el="{{ empty($tableEl) ? '#dataTableBuilder' : $tableEl }}" data-padding="{{ empty($padding) ? '65px' : $padding }}" onclick="addPadding(this)">Action</button>
<div class="dropdown-menu">
    @foreach($url as $key => $data)
        <a class="dropdown-item" href="{{ $data }}">{{ $key }}</a>
    @endforeach
</div>