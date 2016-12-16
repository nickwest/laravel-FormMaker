@if(isset($extends) && $extends != '')
	@extends($extends)
@endif

@if(isset($section) && $section != '')
	@section($section)
@endif

@yield('above_form')

{!! Form::open(array('url' => $Form->url, 'id' => $Form->form_id, 'files' => true)) !!}
	<fieldset>
	@foreach($Form->getDisplayFields() as $field)
		@if($field->type != 'subform')
			@include('form-maker::fields.'.$field->type, array('field' => $field))
		@else
			{!! $field->subform->makeSubformView($field->subform_data)->render() !!}
		@endif
	@endforeach
	</fieldset>

	<fieldset class="submit_button">
		{!! Form::submit((isset($Form->submit_button) ? $Form->submit_button : 'Save'), array('name' => 'submit_button', 'class' => 'submit-green save')) !!}

		@if($Form->getSubmit())
			{!! Form::submit((isset($Form->submit_button) ? $Form->submit_button : 'Submit'), array('name' => 'submit_button', 'class' => 'submit-green submit')) !!}
		@endif

		@if($Form->getAllowDelete())
			{!! Form::submit((isset($Form->delete_button) ? $Form->delete_button : 'Delete'), array('name' => 'submit_button', 'class' => 'submit-red delete')) !!}
		@endif
	</fieldset>

{!! Form::close() !!}

@yield('below_form')

@if(isset($section) && $section != '')
	@stop
@endif
