<div id="field-{{{ $field->name }}}" class="field {{{ $field->type }}}">
	@if(is_array($field->options))
		@if($field->label != '')<div class="field_label"><strong>{!! $field->label.($field->is_required ? ' <em>*</em>' : '') !!}</strong></div> @endif
		@foreach($field->options as $key => $option)
			<div class="option">
				{!! Form::radio($field->id, 1, ($field->value == $key ? true : false), array('id' => $field->id.'_'.$key, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
				{!! Form::rawLabel($field->id.'_'.$key, $option) !!}
			</div>
		@endforeach
	@else
		{!! Form::radio($field->id, 1, ($field->value == 1 ? true : false), array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
		{!! Form::rawLabel($field->name, $field->label.($field->is_required ? ' <em>*</em>' : '')) !!}
	@endif
</div>