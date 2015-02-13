<div id="field-{{ $field->name }}" class="field {{ $field->type }}">
	{!! Form::rawLabel($field->id, $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '')) !!}
	{!! Form::password($field->name, array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
</div>