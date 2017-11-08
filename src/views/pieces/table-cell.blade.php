{{-- This view is rendered by Table::getLinkView() --}}
<td>
	@if($Table->getLink($field_name, $row))
		<a href="{!! $Table->getLink($field_name, $row) !!}">
	@endif

	{{ $row->$field_name }}

	@if($Table->getLink($field_name, $row))
		</a>
	@endif
</td>
