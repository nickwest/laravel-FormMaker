<div class="field {{ $field->type }}" id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}">
	@if($field->label != '')<div class="label">{!! $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '') !!}</div>@endif
	<div class="value">@if(isset($field->options[$field->value])){{ $field->options[$field->value] }}@else{{ $field->value }}@endif</div>
</div>
