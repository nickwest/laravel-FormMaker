<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }}"{{ $field->max_length > 0 ? ' maxlength="'.$field->max_length.'"' : '' }}>
	@if($field->label != ''){!! Form::rawLabel($field->id, $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}@endif
	{!! Form::text($field->name.($field->multi_key != '' ? '['.$field->multi_key.']' : ''), ($field->value == '' ? $field->default_value : $field->value), $field->attributes) !!}
	@if($field->error_message)
		<div class="error_message">{{ $field->error_message }}</div>
	@endif
</div>
