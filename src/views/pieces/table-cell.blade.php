{{-- This view is rendered by Table::getLinkView() --}}
<td>
	@if($Table->getLink($field_name, $row))
		<a href="{!! $Table->getLink($field_name, $row) !!}">
	@endif

    @if(isset($Table->field_replacements[$field_name]))
        {!! $Table->field_replacements[$field_name] !!}
    @elseif(is_object($row))
	    {{ $row->$field_name }}
    @elseif(is_array($row))
        {{ $row[$field_name] }}
    @endif

	@if($Table->getLink($field_name, $row))
		</a>
	@endif
</td>
