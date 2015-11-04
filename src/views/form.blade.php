@if(isset($extends) && $extends != '')
	@extends($extends)
@endif

@if(isset($section) && $section != '')
	@section($section)
@endif

@yield('above_form')

{{ Form::open(array('url' => $Form->getProperty('url'), 'id' => $Form->getProperty('form_id'))) }}
	@yield('form_top')
	<fieldset>
	@foreach($Form->getDisplayFields() as $field)
		@if($field->type != 'subform')
			@include('form-maker::fields.'.$field->type, array('field' => $field))
		@else
			{{ $field->subform->makeSubformView($field->subform_data) }}
		@endif
	@endforeach
	</fieldset>

	<fieldset class="submit_button">
		{{ Form::submit((isset($Form->submit_button) ? $Form->submit_button : 'Save'), array('name' => 'submit_button', 'class' => 'submit-green save')) }}
		@if($Form->getAllowDelete())
			{{ Form::submit((isset($Form->delete_button) ? $Form->delete_button : 'Delete'), array('name' => 'submit_button', 'class' => 'submit-red delete')) }}
		@endif
	</fieldset>
	@yield('form_bottom')
{{ Form::close() }}

@yield('below_form')

@if(isset($section) && $section != '')
	@stop
@endif
