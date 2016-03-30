@if(isset($extends) && $extends != '')
	@extends($extends)
@endif

@if(isset($section) && $section != '')
	@section($section)
@endif

{!! Form::open(array('url' => $Form->url)) !!}
	<fieldset>
	@foreach($Form->getDisplayFields() as $field)
		@include('form-maker::fields.'.$field->type, array('field' => $field))
	@endforeach
	</fieldset>

	<fieldset class="submit_button">
		{!! Form::submit((isset($Form->submit_button) ? $Form->submit_button : 'Save'), array('name' => 'submit_button', 'class' => 'submit-green save')) !!}
		@if($Form->getAllowDelete())
			{!! Form::submit((isset($Form->delete_button) ? $Form->delete_button : 'Delete'), array('name' => 'submit_button', 'class' => 'submit-red delete')) !!}
		@endif
	</fieldset>

{!! Form::close() !!}

@if(isset($section) && $section != '')
	@stop
@endif
