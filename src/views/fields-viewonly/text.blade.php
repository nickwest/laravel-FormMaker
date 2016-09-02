<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	{{ Form::rawLabel($field->id, $field->label.($field->is_required ? ' <em>*</em>' : '')) }}
	<span class="{{ $field->classes }}">{{ $field->value ? $field->value : 'N/A' }}</span>
</div>
