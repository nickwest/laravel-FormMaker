<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }}">
	@if(is_array($field->options))
		@if($field->label != '')<div class="field_label"><strong>{!! $field->label.($field->is_required ? ' <em>*</em>' : '') !!}</strong></div>@endif
		@foreach($field->options as $key => $option)
			<div class="option">
				{!! Form::radio($field->name.($field->multi_key != '' ? '['.$field->multi_key.']' : ''), $key, ($field->value == $key ? true : false), array('id' => $field->id.'_'.$key, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
				{!! Form::rawLabel($field->id.'_'.$key, $option) !!}
			</div>
		@endforeach
	@else
		{!! Form::radio($field->name.($field->multi_key != '' ? '['.$field->multi_key.']' : ''), 1, ($field->value == 1 ? true : false), array('id' => $field->id, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
		@if($field->label != ''){!! Form::rawLabel($field->id, $field->label.($field->is_required ? ' <em>*</em>' : '')) !!}@endif
	@endif
</div>