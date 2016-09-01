<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	@if(is_array($field->options) && sizeof($field->options) > 0)
		<strong>{{ $field->label.($field->is_required ? ' <em>*</em>' : '') }}</strong>
		@foreach($field->options as $key => $value)
		<div>
			@if(is_array($field->value) && in_array($key, $field->value))
				{{ Form::rawLabel('cb-'.$key, $value) }}
			@endif
		</div>
		@endforeach
	@else
		{{ Form::rawLabel($field->id, $field->label.($field->is_required ? ' <em>*</em>' : '')) }}
		<span>{{ $field->value ? 'True' : 'False' }}</span>
	@endif
</div>
