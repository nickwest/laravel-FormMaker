<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	@if(is_array($field->options) && sizeof($field->options) > 0)
		<strong>{{ $field->label.($field->is_required ? ' <em>*</em>' : '') }}</strong>
		@foreach($field->options as $key => $value)
		<div>
			{{ Form::checkbox($field->name.($field->is_multi ? '[]' : ''), $key, (is_array($field->value) && in_array($key, $field->value) ? true : false), array('id' => 'cb-'.$key)) }}
			{{ Form::rawLabel('cb-'.$key, $value) }}
		</div>
		@endforeach
	@else
		{{ Form::checkbox($field->name.($field->is_multi ? '[]' : ''), 1, (is_array($field->value) && in_array(1, $field->value) ? true : false), array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) }}
		{{ Form::rawLabel($field->id, $field->label.($field->is_required ? ' <em>*</em>' : '')) }}
	@endif
</div>
