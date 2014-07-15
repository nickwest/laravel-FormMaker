<div id="field-{{ $field->name }}" class="field {{ $field->type }}">
	{{ Form::rawLabel($field->id, $field->name.($field->is_required ? ' <em>*</em>' : '')) }}
	{{ Form::file($field->name, $field->value, array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) }}
</div>