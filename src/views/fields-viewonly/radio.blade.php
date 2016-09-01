<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	{{ Form::radio($field->id, 1, ($field->value == 1 ? true : false), array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) }}
	{{ Form::rawLabel($field->name, $field->label.($field->is_required ? ' <em>*</em>' : '')) }}
</div>