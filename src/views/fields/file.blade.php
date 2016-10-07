<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }} {{ $field->class }}">
	@if($field->label != '')
		{!! Form::rawLabel($field->id, $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}
	@endif

	@if($field->value == '')
		{!! Form::file($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : '')) !!}
	@else
		<div class="file-link">
			{{ $field->value }}
			{!! Form::submit('remove', array('name' => $field->name.($field->multi_key != '' ? '['.$field->multi_key.']' : ''))) !!}
		</div>
	@endif

	@include('form-maker::pieces.note')
	@include('form-maker::pieces.error')
</div>
