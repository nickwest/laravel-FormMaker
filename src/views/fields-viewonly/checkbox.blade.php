<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	@if(is_array($field->options) && sizeof($field->options) > 0)
		{{ Form::rawLabel( $field->label,$field->label.($field->is_required ? ' <em>*</em>' : '')) }}
		<?php $isEmpty = true; ?>
		@foreach($field->options as $key => $value)
			@if(is_array($field->value) && in_array($key, $field->value))
				<span id="cb-{{{ $key }}}">{{ $value }}</span>
				<?php $isEmpty = false; ?>
			@endif
		@endforeach
		@if($isEmpty)
			<span>{{{ 'N/A' }}}</span>
		@endif
	@else
		{{ Form::rawLabel($field->id, $field->label.($field->is_required ? ' <em>*</em>' : '')) }}
		<span class="{{ $field->classes }}">{{ $field->value ? 'True' : 'False' }}</span>
	@endif
</div>
