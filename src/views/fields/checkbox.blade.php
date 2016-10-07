<div id="field-{{ $field->name.($field->multi_key != '' ? '_'.$field->multi_key : '') }}" class="field {{ $field->type }}{{ ($field->error_message ? ' error' : '') }}  {{ $field->class }}">

	@if(is_array($field->options))
		@if($field->label != '')
			<div class="field_label"><strong>{!! $field->label.($field->label_postfix != '' ? $field->label_postfix : '').($field->is_required ? ' <em>*</em>' : '') !!}</strong></div>
		@endif

		@foreach($field->options as $key => $option)
			<div class="option">
				{!! Form::checkbox($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : ''), $key, (isset($field->value[$key]) != '' || $field->value == $key ? true : false), array('id' => $field->attributes['id'].'_'.$key, 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
				{!! Form::rawLabel($field->attributes['id'].'_'.$key, $option) !!}
			</div>
		@endforeach

	@else
		{!! Form::checkbox($field->name.($field->multi_key || $field->is_multi ? '['.$field->multi_key.']' : ''), $field->options, (isset($field->value[$field->options]) != '' ? true : false), array('id' => $field->attributes['id'], 'class' => (isset($field->classes) && $field->classes != '' ? ' '.$field->classes : '' ))) !!}
		@if($field->label != '')
			{!! Form::rawLabel($field->attributes['id'], $field->label.($field->is_required ? ' <em>*</em>' : '')) !!}
		@endif
	@endif

	@include('form-maker::pieces.note')
	@include('form-maker::pieces.error')
</div>
