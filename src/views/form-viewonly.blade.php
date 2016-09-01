@if(isset($extends) && $extends != '')
	@extends($extends)
@endif

@if(isset($section) && $section != '')
	@section($section)
@endif

@yield('above_form')

<div class="read-only-form">
	@yield('form_top')
	<fieldset>
	@foreach($Form->getDisplayFields() as $field)
		@if($field->type != 'subform')
			@include('form-maker::fields-viewonly.'.$field->type, array('field' => $field))
		@else
			{{ $field->subform->makeSubformView($field->subform_data, true)->render() }}
		@endif
	@endforeach
	</fieldset>

	@yield('form_bottom')
</div>

@yield('below_form')

@if(isset($section) && $section != '')
	@stop
@endif
