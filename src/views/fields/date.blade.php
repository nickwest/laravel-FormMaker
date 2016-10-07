<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }} {{ $field->class }}"{{ $field->max_length > 0 ? ' maxlength="'.$field->max_length.'"' : '' }}>
	@if($field->label != '')
		{!! Form::rawLabel($field->id, $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}
	@endif

	{!! Form::date($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : ''), ($field->value == '' ? $field->default_value : $field->value), $field->attributes) !!}

	@include('form-maker::pieces.note')
	@include('form-maker::pieces.error')
</div>
