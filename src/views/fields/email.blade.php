<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }}">
	@if($field->label != ''){!! Form::rawLabel($field->id, $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}@endif
	{!! Form::email($field->name.($field->multi_key != '' ? '['.$field->multi_key.']' : ''), $field->value, array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
</div>